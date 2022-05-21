<?php
    include_once( './auxes/validation.php' );

    /**
     * Getting system's date from ASZ22 and ASZ24
     * $props : ARRAY 
     * [
     *  'url' => 'http://request-tst.sibgenco.local/corpsystems/',
     *  'path' => '/sap_devform/',
     *  'id' => 21,
     * ]
     * ! the props' set can be different !
     */
    function getSystemData( $props ) {
        $asz24_id = isset( $props['asz24_id'] ) ? cleanData( $props['asz24_id'], "i" ) : null;
        $instanceUrl = isset( $props['url'] ) ? cleanData( $props['url'] ) : null;
        $instancePath = isset( $props['path'] ) ? cleanData( $props['path'] ) : null;
        $instanceUrl = preg_replace( '/\s/', '', $instanceUrl );
        $instanceUrl = preg_replace( '/[^a-z0-9-_\/.:]/', '', $instanceUrl );
        $instancePath = preg_replace( '/\s/', '', $instancePath );
        $instancePath = preg_replace( '/[^a-z0-9-_\/.:]/', '', $instancePath );
        if ( !function_exists( 'addAnd' )) {
            function addAnd( $whereStr ) {
                return ( $whereStr !== "" ) ? "AND " : "";
            }
        }    
        $where = "";
        if ( !empty( $asz24_id )) $where .= addAnd( $where ) . "ASZ24.ID = $asz24_id ";
        if ( !empty( $instanceUrl )) $where .= addAnd( $where ) . "ASZ24.INSTANCE_URL = '$instanceUrl' ";
        if ( !empty( $instancePath )) $where .= addAnd( $where ) . "ASZ24.INSTANCE_PATH = '$instancePath' ";
        if ( $where == "" ) return array();
        $props['query'] = "
            SELECT
                ASZ22.ID DN__ASZ22_ID,
                ASZ22.NAME DN__ASZ22_NAME,
                ASZ22.FULL_NAME DN__ASZ22_FULL_NAME,
                ASZ22.APP12_ID_RESP DN__ASZ22_APP12_ID_RESP,
                ASZ22.ASZ31_SEQ_NAME DN__ASZ22_ASZ31_SEQ_NAME,
                ASZ22.SYSTEM_PREFIX DN__ASZ22_SYSTEM_PREFIX,
                ASZ22.STATUS DN__ASZ22_STATUS,
                ASZ22.REQUEST_TYPE DN__ASZ22_REQUEST_TYPE,       
                ASZ24.ID DN__ASZ24_ID,
                ASZ24.INSTANCE_TYPE DN__ASZ24_INSTANCE_TYPE,
                ASZ24.INSTANCE_URL DN__ASZ24_INSTANCE_URL,
                ASZ24.INSTANCE_PATH DN__ASZ24_INSTANCE_PATH,
                ASZ24.INSTANCE_NAME DN__ASZ24_INSTANCE_NAME,
                ASZ24.STATUS DN__ASZ24_STATUS,
                ASZ241.INSTANCE_URL DN__ASZ24_INSTANCE_URL_PDF,
                ASZ241.INSTANCE_PATH DN__ASZ24_INSTANCE_PATH,
                ASZ241.id DN__ASZ24_ID_PDF
            FROM 
                ASZ22_CORP_SYSTEMS ASZ22,
                ASZ24_SYSTEM_INSTANCES ASZ24,
                ASZ24_SYSTEM_INSTANCES ASZ241
            WHERE
                $where
                AND ASZ24.ASZ22_ID = ASZ22.ID
                AND ASZ24.ASZ24_ID = ASZ241.ID(+)
        ";       
        $result = select( $props );
        if ( count( $result ) == 1 ) return $result[0];
        return $result;
    }

    /**
     * Getting list of SAP systems
     */
    function getSapSystems( $props ) {
        if ( !checkDataSet( $props, ['asz22_id'] )) return array();
        $asz22_id = isset($props['asz22_id']) ? cleanData( $props['asz22_id'], 'i' ) : null;
        $props['query'] = "
            SELECT 
                ASZ00.FULL_NAME DN__FULL_NAME,
                ASZ00.NAME DN__NAME,
                ASZ00.ID DN__ASZ00_ID
            FROM ASZ00_SAP_SYSTEMS ASZ00
            WHERE sysdate between ASZ00.date_start AND ASZ00.date_end
            AND ASZ00.ASZ22_ID = $asz22_id
            ORDER BY DECODE(name, 'ERP_SUEK',' 0000000000',DECODE(p_asz_util.get_param_value($asz22_id,'SYSTEM_SORT_ORDER'),'NAME',name,'ID',TO_CHAR(id,'0000000000'),'ID_DESC',TO_CHAR(9999999999-id,'0000000000'),TO_CHAR(id,'0000000000')))
        ";
        return select( $props );
    }

    /**
     * Getting a list of locations for branch
     */
    function getLocations( $props ) {
        if ( !checkDataSet( $props, ['hrs05_id'] )) return array();
        $hrs05_id = isset( $props['hrs05_id'] ) ? cleanData( $props['hrs05_id'], 'i' ) : null;
        $props['query'] = "						
            SELECT
            addr_all.id DN__IDA,
            addr_all.address_text DN__ADR
            FROM
            (
            SELECT
            acdr.acdr_id id,
            acdr.acdr_city || DECODE(acdr.acdr_address, NULL, NULL, ', ' || acdr.acdr_address) address_text
            FROM
            asz_company_addresses acdr,
            asz60_hrs05_companies asz60
            WHERE
            acdr.acdr_acom_id = asz60.acom_id
            AND acdr.acdr_status = 'Enabled'
            AND asz60.hrs05_id = $hrs05_id
            UNION ALL
            SELECT 
            asz55a.id id,
            asz55a.address_text address_text
            FROM 
            asz55a_hrs05_address_text asz55a
            WHERE
            asz55a.hrs05_id = $hrs05_id
            ) addr_all
            ORDER BY
            NLSSORT(addr_all.address_text, 'NLS_SORT=RUSSIAN')
        ";
        return select( $props );        
    }

    /**
     * Getting a list of companies
     */
    function getCompanies( $props ) {
        $props['query'] = "
            SELECT
            hrs01_a.name DN__NAME,
            hrs01_a.id DN__ID
            FROM
            (
            SELECT 
            hrs01.name name, 
            hrs01.id id,
            0 order_seq
            FROM 
            hrs01_branches hrs01
            WHERE 
            hrs01.id IN 
            (
            SELECT 
            hrs05.hrs01_id 
            FROM 
            hrs05_branch_divisions hrs05 
            WHERE 
            hrs05.id IN (SELECT hrs05_id FROM its28_avail_hrs05)
            )                 
            UNION ALL
            SELECT 
            hrs01.name name,
            hrs01.id id,
            1 order_seq
            FROM
            hrs01_branches hrs01,
            (
            SELECT 
            app22_sq.id id,
            app22_sq.subdivision_id subdivision_id,
            app22_sq.div_name div_name
            FROM
            app22_org_structure app22_sq
            WHERE
            SYSDATE BETWEEN NVL(app22_sq.div_date_start, TO_DATE('01011900', 'DDMMYYYY')) AND NVL(app22_sq.div_date_end, TO_DATE('01013000', 'DDMMYYYY'))
            CONNECT BY
            PRIOR app22_sq.id = app22_sq.app22_id
            START WITH 
            app22_sq.subdivision_id = 700000001
            ) app22,
            app22a_app22_hrs01 app22a
            WHERE
            app22a.app22_id = app22.id
            AND app22a.hrs01_id = hrs01.id
            AND app22a.app22_base_flag = 1
            ) hrs01_a
            ORDER BY 
            hrs01_a.order_seq,
            hrs01_a.name
        ";
        return select( $props );
    }

    /**
     * Geting of list of branches for a company
     */
    function getBranches( $props ) {
        if ( !checkDataSet( $props, ['hrs01_id'] )) return array();
        $hrs01_id = isset( $props['hrs01_id'] ) ? cleanData( $props['hrs01_id'], 'i' ) : null;        
        $props['query'] = "
            SELECT 
            hrs05.name DN__NAME,
            hrs05.id DN__ID
            FROM 
            hrs05_branch_divisions hrs05
            WHERE  
            hrs05.id IN 
            (
            SELECT hrs05_id FROM its28_avail_hrs05 WHERE hrs05_id IS NOT NULL      
            UNION ALL           
            SELECT
            app22a.hrs05_id
            FROM
            (
            SELECT 
            app22_sq.id id
            FROM
            app22_org_structure app22_sq
            WHERE
            SYSDATE BETWEEN NVL(app22_sq.div_date_start, TO_DATE('01011900', 'DDMMYYYY')) AND NVL(app22_sq.div_date_end, TO_DATE('01013000', 'DDMMYYYY'))
            CONNECT BY
            PRIOR app22_sq.id = app22_sq.app22_id
            START WITH 
            app22_sq.subdivision_id IN (2000060, 1700000, 2009995)
            ) app22,
            app22a_app22_hrs01 app22a
            WHERE
            app22a.app22_id = app22.id
            AND app22a.hrs05_id IS NOT NULL
            )
            AND hrs05.hrs01_id = '$hrs01_id'
            ORDER BY
            DECODE(hrs05.name,
                'АО \"СУЭК-Кузбасс\"', 1,
                'Московский офис СТК', 1,
                'ГО СГК г.Москва', 1,
                'Энергоцентр ЧОУ ДПО', 1,
                'Исполнительный аппарат', 1,
                'Управление', 1,
                2),
            hrs05.name
        ";
        return select( $props );
    }

    /**
     * Geting of list of divisions of a branch
     */
    function getDivisions( $props ) {
        if ( !checkDataSet( $props, ['hrs05_id'] )) return array();
        $hrs05_id = isset( $props['hrs05_id'] ) ? cleanData( $props['hrs05_id'], 'i' ) : null;  
        $props['query'] = "
            SELECT
            *
            FROM
            (
            SELECT
            ID DN__IDPATH,
            REPLACE(division_path,
                    DECODE((
                            SELECT
                            COUNT(1) FROM app22_org_structure app22 WHERE app22_id IN
                            (
                            SELECT
                            app22a.APP22_ID
                            FROM
                            APP22A_APP22_HRS01 app22a
                            WHERE
                            app22a.HRS05_ID = $hrs05_id
                            )
                            AND SYSDATE BETWEEN app22.div_date_start AND NVL(app22.div_date_end, TO_DATE('01.01.3000 00:00:00', 'DD.MM.YYYY HH24:MI:SS'))
                            ),
                            0,(
                                SELECT
                                SUBSTR(app22.division_path,1,INSTR(app22.division_path,'/',-1)+1
                                )
                                FROM
                                APP22A_APP22_HRS01 app22a,
                                app22_org_structure app22
                                WHERE
                                app22a.HRS05_ID  = $hrs05_id
                                AND app22a.app22_id = app22.id
                                AND SYSDATE BETWEEN app22.div_date_start AND NVL(app22.div_date_end
                                , to_date('01013000','DDMMYYYY'))
                                AND ROWNUM <=1
                                ),
                            (
                            SELECT
                            app22.division_path
                            FROM
                            APP22A_APP22_HRS01 app22a,
                            app22_org_structure app22
                            WHERE
                            app22a.HRS05_ID  = $hrs05_id
                            AND app22a.app22_id = app22.id
                            AND SYSDATE BETWEEN app22.div_date_start AND NVL(app22.div_date_end
                            , to_date('01013000','DDMMYYYY'))
                            AND ROWNUM <=1
                            ) || ' / '
                            )
                    ,
                    ''
            ) DN__DIVISION_PATH
            FROM
            app22_org_structure
            WHERE
            SYSDATE BETWEEN div_date_start AND NVL(div_date_end, TO_DATE('01.01.3000 00:00:00', 'DD.MM.YYYY HH24:MI:SS'))
            CONNECT BY PRIOR id = app22_id
            START WITH
                (
                ID IN
                    (
                    SELECT
                    APP22_ID
                    FROM
                    APP22A_APP22_HRS01
                    WHERE
                    HRS05_ID = $hrs05_id
                    ) -- Вместо 331 подставить требуемый hrs05_id
                )
            )
            WHERE
            (
            DN__IDPATH NOT IN
            (
            SELECT
            APP22_ID
            FROM
            APP22A_APP22_HRS01
            WHERE
            HRS05_ID = $hrs05_id
            )
            OR NOT EXISTS
            (
            SELECT
            'x'
            FROM
            app22_org_structure app22
            WHERE
            app22_id IN
            (
            SELECT
            app22a.APP22_ID
            FROM
            APP22A_APP22_HRS01 app22a
            WHERE
            app22a.HRS05_ID = $hrs05_id
            AND SYSDATE BETWEEN app22.div_date_start AND NVL(
            app22.div_date_end, TO_DATE('01.01.3000 00:00:00',
            'DD.MM.YYYY HH24:MI:SS'))
            )
            )
            )
            AND DN__IDPATH NOT IN
            (
            SELECT
            id
            FROM
            (
            SELECT
            id,
            division_path,
            row_number() OVER (PARTITION BY division_path ORDER BY id) rn
            FROM
            app22_org_structure
            WHERE
            SYSDATE BETWEEN div_date_start AND div_date_end
            AND division_path IN
            (
            SELECT
            division_path
            FROM
            app22_org_structure
            WHERE
            SYSDATE BETWEEN div_date_start AND div_date_end
            GROUP BY
            division_path
            HAVING
            COUNT(1)>1
            )
            ORDER BY
            2,1
            )
            WHERE
            rn > 1
            )
            -- Исключение для псевдо-филиалов
            -- например мы выбрали АО СУЭК-Красноярск под АО СУЭК-Красноярск
            -- в этом случае не должны выбираться подразделения,
            -- явным образом связанные с настоящими филиалами АО СУЭК-Красноярск
            -- например Филиал АО СУЭК-Красноярск Бородинское ПТУ
            AND DN__IDPATH NOT IN
            (
            SELECT
            app22_sq.id
            FROM
            app22_org_structure app22_sq
            CONNECT BY
            PRIOR app22_sq.id = app22_sq.app22_id
            START WITH
            app22_sq.id IN
                (
                SELECT
                app22a_sq2.app22_id
                FROM
                hrs05_branch_divisions hrs05_sq2,
                app22a_app22_hrs01 app22a_sq2
                WHERE
                app22a_sq2.hrs05_id = hrs05_sq2.id
                -- все hrs05, подчиненные тому же hrs01
                AND hrs05_sq2.hrs01_id = (SELECT hrs05_sq3.hrs01_id FROM hrs05_branch_divisions hrs05_sq3 WHERE hrs05_sq3.id = $hrs05_id)
                AND hrs05_sq2.id <> $hrs05_id
                AND app22a_sq2.app22_id IN
                    (
                    SELECT
                    app22_sq3.id
                    FROM
                    app22_org_structure app22_sq3
                    CONNECT BY
                    PRIOR app22_sq3.id = app22_sq3.app22_id
                    START WITH
                    app22_sq3.id IN (SELECT app22a_sq4.app22_id FROM app22a_app22_hrs01 app22a_sq4 WHERE app22a_sq4.hrs05_id = $hrs05_id)
                    )
                )
            )
            ORDER BY
            NLSSORT(DN__DIVISION_PATH,'NLS_SORT=RUSSIAN')
        ";
        return select( $props );
    }

    /**
     * Getting language phrase
     */
    function getPhrase( $props ) {
        if ( !checkDataSet( $props, ['form_name', 'phrase', 'lang'] )) return '';
        $form_name = isset( $props['form_name'] ) ? cleanData( $props['form_name'] ) : null;
        $phrase = isset( $props['phrase'] ) ? cleanData( $props['phrase'] ) : null;
        $lang = isset( $props['lang'] ) ? cleanData( $props['lang'] ) : null;
        $props['query'] = 'FUNCTION p_asz_lang.get_phrase(form_name_in IN VARCHAR2, phrase_in IN VARCHAR2, lang_in IN VARCHAR2) RETURN VARCHAR2';
        $props['props'] = [
            'form_name_in' => $form_name,
            'phrase_in' => $phrase,
            'lang_in' => $lang
          ];
        return execute( $props );
    }
  
    /**
     * Setting Current Language
     */
    function setCurrentLanguage( $props ) {
        if ( !checkDataSet( $props, ['lang'] )) return false;
        $lang = isset( $props['lang'] ) ? cleanData( $props['lang'] ) : null;
        $props['query'] = 'PROCEDURE p_asz_lang.set_lang(lang_in IN VARCHAR2)';
        $props['props'] = ['lang_in' => $lang];
        return execute( $props );
    }
   
    /**
     * Getting list of main page sections
     */
    function getMainPageSections( $props ) {
        $props['query'] = "
            SELECT 
            asz68.id DN__ID,
            DECODE(p_asz_lang.get_lang,
                'RU', asz68.name,
                'EN', asz68.name_en
                ) DN__NAME
            FROM asz68_portal_sections asz68
            ORDER BY asz68.order_seq
        ";
        return select( $props );
    } 
   
    /**
     * Getting systems list of sections
     */
    function getMainPageSystemsSections( $props ) {
        if ( !checkDataSet( $props, ['asz68_id'] )) return array(); 
        $asz68_id = isset( $props['asz68_id'] ) ? cleanData( $props['asz68_id'] ) : null;
        $props['query'] = "
            SELECT 
                system_prefix DN__SYSTEM_PREFIX,
                request_name DN__REQUEST_NAME,
                add_systems_info DN__ADD_SYSTEMS_INFO,
                icon_filename DN__ICON_FILENAME,
                request_url DN__REQUEST_URL
            FROM asz_mainpage_v
            WHERE asz68_id = $asz68_id
            ORDER BY request_order_seq
        ";
        return select( $props );
    }
      
    /**
     * Getting systems name
     */
    function getSystemName( $props ) {
        if ( !checkDataSet( $props, ['asz22_id'] )) return '';
        $asz22_id = isset( $props['asz22_id'] ) ? cleanData( $props['asz22_id'] ) : null;
        $props['query'] = "
            SELECT request_name DN__REQUEST_NAME
            FROM asz_mainpage_v
            WHERE asz22_id = $asz22_id
        ";
        return select( $props )[0]['request_name'];
    }
    
    /**
     * Getting values of a range of parameters
     */
    function getParamValue ( $props ) {
        if ( !checkDataSet( $props, ['asz22_id', 'param'] )) return null;
        $asz22_id = isset( $props['asz22_id'] ) ? cleanData( $props['asz22_id'], 'i' ) : null;
        $param = isset( $props['param'] ) ? cleanData( $props['param'] ) : null;
        $props['query'] = 'FUNCTION p_asz_util.get_param_value(asz22_id_in IN NUMBER, param_in IN VARCHAR2) RETURN VARCHAR2';
        $props['props'] = [ 'asz22_id_in' => $asz22_id, 'param_in' => $param ];
        return execute( $props );
    }
