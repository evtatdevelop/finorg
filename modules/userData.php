<?php
    include_once( './auxes/validation.php' );

    /**
     * Getteng array of similar names
     * $props :array
     * [
     *  'search' :string,
     *  'system' :string,   
     * ] 
     */
    function getUserNames( $props ) {
        if ( !checkDataSet( $props, ['search']) ) return array();
        $system = isset( $props['system'] ) ? cleanData( $props['system'] ) : null;
        $where = getWhereName ( $props );
        $systemWhere = '';
        if ($system) {
            $systemWhere = " 
                AND (('$system' = 'SEDDEPUTY' AND app12.id IN (SELECT s.app12_id FROM app12_sed_users_v s)) OR '$system' <> 'SEDDEPUTY')                                      
                AND (('$system' = 'SAPERDEP' AND UPPER(app12.domain || '\' || app12.login) IN (SELECT UPPER(s.login) FROM int_saperion_asz_signers s)) OR '$system' <> 'SAPERDEP') 
                AND app12.domain IN (SELECT app60.domain FROM app60_domains app60, asz50_asz22_app60 asz50, asz22_corp_systems asz22 WHERE asz50.app60_id = app60.id AND asz50.asz22_id = asz22.id AND asz22.system_prefix = '$system')
                AND app12.company_group IN (SELECT app61.name FROM app61_company_groups app61, asz59_asz22_app61 asz59, asz22_corp_systems asz22 WHERE asz59.app61_id = app61.id AND asz59.asz22_id = asz22.id AND asz22.system_prefix = '$system') 
            ";
        }
        $props['query'] = "
            SELECT 
                APP12.ID            DN__ID,   
                APP12.LAST_NAME     DN__LAST_NAME,
                APP12.FIRST_NAME    DN__FIRST_NAME,
                APP12.MIDDLE_NAME   DN__MIDDLE_NAME,
                APP12.LOGIN         DN__LOGIN,
                APP12.EMAIL         DN__EMAIL
            FROM 
            APP12_ADDRESSBOOK APP12
            , APP22_ORG_STRUCTURE app22
            , APP60_DOMAINS app60
            WHERE
            $where  
            AND APP12.email IS NOT NULL
            AND APP12.login IS NOT NULL
            AND APP12.LAST_NAME IS NOT NULL
            AND app12.app60_id = app60.id
            AND app12.subdivision_id = app22.subdivision_id (+)
            AND app12.state =  'Enabled'
            $systemWhere
            ORDER BY APP12.LAST_NAME, APP12.FIRST_NAME, APP12.MIDDLE_NAME
        ";
        return select( $props );
    }
    
 
    /**
     * Generating of where clause from name
     */
    function getWhereName ( $props ) {
        $search = isset( $props['search'] ) ? cleanData( $props['search'] ) : '';
        $search = preg_replace( '/\s+/', ' ', $search );
        $search = preg_replace( '/[^a-zа-яё-\s]/ui', '', $search );
        $searchArr = explode( ' ', $search );
        $lastName = $searchArr[0];
        $firstName = ( isset( $searchArr[1] ) ? $searchArr[1] : null );
        $middleName = ( isset($searchArr[2] ) ? $searchArr[2] : null );  
        $where = "UPPER(APP12.LAST_NAME) LIKE UPPER('$lastName%')";
        if ( $firstName ) $where .= " AND upper(APP12.FIRST_NAME) like upper('$firstName%')";
        if ( $middleName ) $where .= " AND upper(APP12.MIDDLE_NAME) like upper('$middleName%')";
        return $where;
    }
 
 
    /**
     * Getting of additional users name list
    */
    function getAddUserNames( $props ) {
        if ( !checkDataSet( $props, ['search', 'asz01_id']) ) return array();
        $system = isset( $props['system'] ) ? cleanData( $props['system'] ) : ''; 
        $ids = isset( $props['ids'] ) ? cleanData( $props['ids'] ) : ''; 
        $asz01_id = isset( $props['asz01_id'] ) ? cleanData( $props['asz01_id'], 'i' ) : null; 
        
        $where = getWhereName ( $props );
        
        if ( trim( $ids ) == "" ) $exceptId = "1=1";
        else $exceptId = "APP12.ID not in ($ids)";

        $props['query'] = "
            SELECT DISTINCT
                APP12.ID            DN__ID,   
                APP12.LAST_NAME     DN__LAST_NAME,
                APP12.FIRST_NAME    DN__FIRST_NAME,
                APP12.MIDDLE_NAME   DN__MIDDLE_NAME,
                APP12.LOGIN         DN__LOGIN,
                APP12.EMAIL         DN__EMAIL
            FROM
                APP12_ADDRESSBOOK APP12,
                APP22_ORG_STRUCTURE app22
            WHERE
                $where AND
                $exceptId AND
                APP12.email IS NOT NULL  AND
                APP12.login IS NOT NULL  AND
                APP12.LAST_NAME IS NOT NULL
                AND app12.subdivision_id = app22.subdivision_id (+)
                AND app12.state = 'Enabled'
                AND (('$system' = 'SEDDEPUTY' AND app12.id IN (SELECT s.app12_id FROM app12_sed_users_v s)) OR '$system' <> 'SEDDEPUTY')
                AND (
                    p_asz11.get_app22_asz01_id(app22.id) IN (SELECT asz01.id
                    FROM (SELECT *
                        FROM asz01_sap_branches
                        WHERE SYSDATE BETWEEN date_start AND date_end) asz01
                        START WITH asz01.id = $asz01_id
                        CONNECT BY PRIOR asz01.id = asz01.asz01_id
                    )
                    AND app12.subdivision_id IS NOT NULL
                    AND p_app22a.get_app12_hrs01_id(app12.id) IS NOT NULL
                    AND p_app22a.get_app12_hrs05_id_asz(app12.id) IS NOT NULL
                )
                AND app12.domain IN (SELECT app60.domain FROM app60_domains app60, asz50_asz22_app60 asz50, asz22_corp_systems asz22 WHERE asz50.app60_id = app60.id AND asz50.asz22_id = asz22.id AND asz22.system_prefix = '$system')
                AND app12.company_group IN (SELECT app61.name FROM app61_company_groups app61, asz59_asz22_app61 asz59, asz22_corp_systems asz22 WHERE asz59.app61_id = app61.id AND asz59.asz22_id = asz22.id AND asz22.system_prefix = '$system')
            ORDER BY APP12.LAST_NAME, APP12.FIRST_NAME, APP12.MIDDLE_NAME
        ";
        return select( $props );
    }


    /**
     * Getting user data from app12
     * $props :ARRAY
     * [
     *  'id' :string | number,  
     * ] 
     */
    function getUserDataApp12( $props ) {
        if ( !checkDataSet( $props, ['id'] )) return array();
        $id = isset( $props['id'] ) ? cleanData( $props['id'], 'i' ) : null;
        $props['query'] = "
            SELECT
                APP12.ID            DN__ID,    
                APP12.LAST_NAME     DN__LAST_NAME,
                APP12.FIRST_NAME    DN__FIRST_NAME,
                APP12.MIDDLE_NAME   DN__MIDDLE_NAME,
                APP12.LOGIN         DN__LOGIN,
                APP12.PHONE1        DN__PHONE1,
                APP12.PHONE2        DN__PHONE2,
                APP12.POSITION_NAME DN__POSITION_NAME,
                APP12.EMAIL         DN__EMAIL,
                LTRIM( RTRIM( REPLACE( its01.city
                    || ', '
                    || its01.address
                    || ', '
                    || its02.location_number
                    , ', , ',NULL), ', '), ', ') DN__LOCATION,
                app22.DIV_NAME       DN__DIV_NAME,
                app22.ID             DN__APP22_ID,
                app12.SUBDIVISION_ID DN__SUBDIVISION_ID,
                app12.DOMAIN DN__DOMAIN,
                DECODE(INSTR(app12.login,'@'), 0, app12.domain || '\' || app12.login, app12.login || '.' || app60.domain_net) DN__AD_USER,
                APP12.MAIN_SUBDIVISION_NAME DN__MAIN_SUBDIVISION_NAME,
                APP12.ADDRESS DN__ADDRESS,
                APP12.MTS_PERSON_ID DN__MTS_PERSON_ID,
                APP12.APP03_ID DN__APP03_ID,
                APP12.AD_ID DN__AD_ID,
                APP12.OFFICE_NUMBER DN__OFFICE_NUMBER,
                APP12.CITY DN__CITY,
                APP12.REGION DN__REGION,
                APP12.GIVEN_NAME DN__GIVEN_NAME,
                APP12.state DN__STATE              
            FROM 
                APP12_ADDRESSBOOK APP12,
                its01_office_buildings its01,
                ITS02_LOCATIONS ITS02,
                its03_workplaces its03,
                APP22_ORG_STRUCTURE app22,
                APP60_DOMAINS app60
            WHERE 
                APP12.ID = $id AND 
                APP12.email IS NOT NULL  AND
                APP12.last_name IS NOT NULL AND
                its03.app12_id       (+) =  app12.id 
                AND its03.workplace_type (+) =  'PERSONAL'
                AND its03.status         (+) <> 'CLOSED'
                AND its02.id             (+) = its03.its02_id 
                AND its01.id             (+) = its02.its01_id 
                AND app12.subdivision_id = app22.subdivision_id (+)
                AND (app12.subdivision_id IS NOT NULL OR  app12.date_created BETWEEN SYSDATE-30 AND SYSDATE OR app12.domain <> 'SIBGENCO')
                AND (NVL(APP12.state,'~~~') <> 'Disabled' AND NVL(APP12.employee_state,'~~~') <> 'Dismissed')
                AND app12.app60_id = app60.id	
        ";
        $result = select( $props );
        return isset( $result[0] ) ? $result[0] : array();
    }


    /**
     * Getting APP12.ID by user login
     * $props : ARRAY
     * [
     *  $login :STRING - 'suekcorp\tatarenkoeg'
     * ]
     */
    function get_app12_id_by_login( $props ) {
        if ( !checkDataSet( $props, ['login'] )) return null;
        $login = isset( $props['login'] ) ? cleanData( $props['login'] ) : null;
        $props['query'] = 'FUNCTION p_asz_util.get_app12_id_by_login(login_in IN VARCHAR2) RETURN NUMBER';
        $props['props'] = ['login_in' => $login];
        return execute( $props );
    }
    

    /**
     * Getting SAP user login
     * $props : ARRAY
     * [
     *  $login :STRING - 'TatarenkoEG'
     *  $domain :STRING - 'SUEKCORP'
     *  $asz00_id :NUMBER - 1121
     * ]
     */
    function get_remote_login( $props ) {
        if ( !checkDataSet( $props, ['login', 'domain', 'asz00_id'] )) return null;
        $login = isset( $props['login'] ) ? cleanData( $props['login'] ) : null;
        $domain = isset( $props['domain'] ) ? cleanData( $props['domain'] ) : null;
        $asz00_id = isset( $props['asz00_id'] ) ? cleanData( $props['asz00_id'], 'i' ) : null;
        $props['query'] = 'FUNCTION p_asz_web.get_remote_login(login_in IN VARCHAR2, domain_in IN VARCHAR2, asz00_id_in IN NUMBER) RETURN VARCHAR2';
        $props['props'] = ['login_in' => $login, 'domain_in' => $domain, 'asz00_id_in' => $asz00_id];
        return execute( $props );
    }
    

    /**
     * Getting hrs01_ID by app12_ID
     * $props: ['app12_id': STRING|NUMBER => 1833]
     */
    function get_app12_hrs01_id( $props ) {
        if ( !checkDataSet( $props, ['app12_id'] )) return null;
        $app12_id = cleanData( $props['app12_id'], 'i' );
        $props['query'] = 'FUNCTION p_app22a.get_app12_hrs01_id(app12_id_in IN NUMBER) RETURN NUMBER';
        $props['props'] = ['app12_id_in' => $app12_id];
        return execute( $props );
    }

    /**
     * Getting company by app12_ID
     * $props: ['app12_id': STRING|NUMBER => 1833]
     */
    function getCompanyByUserId( $props ) {
        $hrs01_id = get_app12_hrs01_id( $props );
        if ( !$hrs01_id ) return [];
        $props['query'] = "
            SELECT 
                HRS01.id DN__HRS01_ID,
                HRS01.name DN__NAME
            FROM HRS01_BRANCHES HRS01 
            WHERE HRS01.id = '$hrs01_id'
        ";
        return select( $props )[0];
    }


    /**
     * Getting hrs05_ID by app12_ID
     * $props: ['app12_id': STRING|NUMBER => 1833]
     */
    function get_app12_hrs05_id_asz( $props ) {
        if ( !checkDataSet( $props, ['app12_id'] )) return null;
        $app12_id = cleanData( $props['app12_id'], 'i' );
        $props['query'] = 'FUNCTION p_app22a.get_app12_hrs05_id_asz(app12_id_in IN NUMBER) RETURN NUMBER';
        $props['props'] = ['app12_id_in' => $app12_id];
        return execute( $props );
    }

    /**
     * Getting branch by app12_ID
     * $props: ['app12_id': STRING|NUMBER => 1833]
     */
    function getBranchByUserId( $props ) {
        $hrs05_id = get_app12_hrs05_id_asz( $props );
        if ( !$hrs05_id ) return [];
        $props['query'] = "
            SELECT 
                HRS05.id DN__HRS05_ID,
                HRS05.name DN__NAME
            FROM HRS05_BRANCH_DIVISIONS HRS05
            WHERE HRS05.id = '$hrs05_id'
        ";
        return select( $props )[0];
    }

    /**
     * Getting app22_idby app12_id 
     */
    function get_app12_app22_id( $props ) {
        if ( !checkDataSet( $props, ['app12_id'] )) return null;
        $app12_id = cleanData( $props['app12_id'], 'i' );
        $props['query'] = 'FUNCTION p_app22a.get_app12_app22_id(app12_id_in IN NUMBER) RETURN NUMBER';
        $props['props'] = ['app12_id_in' => $app12_id];
        return execute( $props );
    }

    /**
     * Getting asz01_id by app22_id 
     */
    function get_app22_asz01_id( $props ) {
        if ( !checkDataSet( $props, ['app22_id'] )) return null;
        $app22_id = cleanData( $props['app22_id'], 'i' );
        $props['query'] = 'FUNCTION p_asz11.get_app22_asz01_id(app22_id_in IN NUMBER) RETURN NUMBER';
        $props['props'] = ['app22_id_in' => $app22_id];
        return execute( $props );
    }

    /**
     * Getting asz01_id by hrs05_id 
     */
    function get_hrs05_asz01_id( $props ) {
        if ( !checkDataSet( $props, ['hrs05_id'] )) return null;
        $hrs05_id = cleanData( $props['hrs05_id'], 'i' );
        $props['query'] = 'FUNCTION p_asz11.get_hrs05_asz01_id(hrs05_id_in IN NUMBER) RETURN NUMBER';
        $props['props'] = ['hrs05_id_in' => $hrs05_id];
        return execute( $props );
    }


    /**
     * Getting SAP user branch
     */
    function getSapBranchByUserId( $props ) {
        if ( count( $props ) == 0) return [];
        $app12_id = isset( $props['app12_id'] ) ? cleanData( $props['app12_id'], "i" ) : null;
        $app22_id = isset( $props['app22_id'] ) ? cleanData( $props['app22_id'], "i" ) : null;
        $hrs05_id = isset( $props['hrs05_id'] ) ? cleanData( $props['hrs05_id'], "i" ) : null;

        if ( $app12_id ) {
            $props['app22_id'] = get_app12_app22_id( $props );
            $asz01_id = get_app22_asz01_id( $props );
        } 
        elseif ( $hrs05_id ) $asz01_id = get_hrs05_asz01_id( $props);
        elseif ( $app22_id ) $asz01_id = get_app22_asz01_id( $props );
        else return [];

        if ( $asz01_id ) {
            $props['query'] = "
                SELECT
                    ASZ01.id DN__ASZ01_ID, 
                    ASZ01.name DN__NAME
                FROM ASZ01_SAP_BRANCHES ASZ01
                WHERE ASZ01.id = '$asz01_id'
            ";
            return select( $props )[0];
        } else return [];
    }

    /**
     * Getting user language
     */
    function getUserLang( $props ) {
        if ( !checkDataSet( $props, ['app12_id'] )) return null;
        $app12_id = isset( $props['app12_id'] ) ? cleanData( $props['app12_id'], "i" ) : null;
        $props['query'] = 'FUNCTION p_asz_lang.get_app12_lang(app12_id_in IN NUMBER) RETURN VARCHAR2';
        $props['props'] = ['app12_id_in' => $app12_id];
        return execute( $props );
    }

    /**
     * Set user language
     */
    function setUserLang( $props ) {
        if ( !checkDataSet( $props, ['app12_id', 'lang'] )) return array();
        $app12_id = isset( $props['app12_id'] ) ? cleanData( $props['app12_id'], "i" ) : null;
        $lang = isset( $props['lang'] ) ? cleanData( $props['lang'] ) : '';
        $props['query'] = 'PROCEDURE p_asz_lang.set_app12_lang(	
            app12_id_in IN NUMBER,
            lang_in IN VARCHAR2,
            ret_code_out OUT NUMBER,
            ret_mess_out OUT VARCHAR2
        )';
        $props['props'] = [
            'app12_id_in' => $app12_id, 
            'lang_in' => $lang
        ];
        return execute( $props );
    }