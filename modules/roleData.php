<?php
    include_once( './auxes/validation.php' );

    function getGroupList( $props ) {

        $asz00_id = isset( $props['asz00_id'] ) ? cleanData( $props['asz00_id'], 'i' ) : null;    
        $asz01_id = isset( $props['asz01_id'] ) ? cleanData( $props['asz01_id'], 'i' ) : null;    
        $app12_id = isset( $props['app12_id'] ) ? cleanData( $props['app12_id'], 'i' ) : null;    
        $app12_id_author = isset( $props['app12_id_author'] ) ? cleanData( $props['app12_id_author'], 'i' ) : null;    
        $order_type = isset( $props['order_type'] ) ? cleanData( $props['order_type']) : null;    
        $instance_type = isset( $props['instance_type'] ) ? cleanData( $props['instance_type']) : null;
        
        if ( $order_type !== 'ADD_PRIVS' and $order_type !== 'REMOVE_PRIVS') $order_type = null;
        if ( $instance_type !== 'PROD' and $instance_type !== 'DEV' and $instance_type !== 'TEST') $instance_type = null;

        $props['query'] = "
            SELECT ASZ02.name DN__NAME,
                    ASZ02.id DN__ID,
                    DECODE(asz02.name,'ОТСУТСТВУЕТ В СПИСКЕ (УКАЗАТЬ ВРУЧНУЮ)','ЯЯЯЯЯЯ',asz02.name) ORDSEQ
                FROM ASZ02_SAP_PROCESS_GROUPS ASZ02
            WHERE ASZ02.ASZ00_ID = $asz00_id AND
                    sysdate BETWEEN ASZ02.date_start AND ASZ02.date_end AND
                    (asz02.id IN (
                                SELECT asz03.asz02_id
                                FROM ASZ03_SAP_ROLES asz03,
                                    ASZ02_SAP_PROCESS_GROUPS asz02,
                                    ASZ00_SAP_SYSTEMS asz00,
                                    ASZ22_CORP_SYSTEMS asz22
                                WHERE ASZ03.ASZ00_ID = ASZ00.ID AND
                                    ASZ03.ASZ02_ID = ASZ02.ID AND
                                    ASZ00.ASZ22_ID = ASZ22.ID AND
                                    sysdate BETWEEN ASZ03.date_start AND ASZ03.date_end AND
                                    ((
                                    (asz03.id IN(
                                        SELECT asz04.asz03_id
                                        FROM ASZ04_ASZ03_ASZ01 asz04
                                        WHERE asz04.asz01_id IN ( SELECT asz01.id FROM asz01_sap_branches asz01
                                                                    WHERE SYSDATE BETWEEN asz01.date_start AND asz01.date_end
                                                                    CONNECT BY asz01.id = PRIOR asz01.asz01_id
                                                                    START WITH asz01.id = $asz01_id ) AND
                                                SYSDATE BETWEEN asz04.date_start AND asz04.date_end
                                        ) OR NOT EXISTS
                                        (SELECT ASZ04.ASZ03_ID FROM ASZ04_ASZ03_ASZ01 asz04 WHERE ASZ04.ASZ03_id = ASZ03.ID AND
                                        SYSDATE BETWEEN asz04.date_start AND asz04.date_end)
                                        ) AND
                                        (asz03.id NOT IN (
                                        SELECT asz51.asz03_id
                                        FROM asz51_asz03_asz01_deny asz51
                                        WHERE asz51.asz01_id IN ( SELECT asz01.id FROM asz01_sap_branches asz01
                                                                    WHERE SYSDATE BETWEEN asz01.date_start AND asz01.date_end
                                                                    CONNECT BY asz01.id = PRIOR asz01.asz01_id
                                                                    START WITH asz01.id = $asz01_id
                                                                ) AND
                                                SYSDATE BETWEEN asz51.date_start AND asz51.date_end
                                        )
                                        )) OR ASZ03.ID IN (SELECT asz18.asz03_id_force FROM asz18_authors_role_force asz18 WHERE asz18.app12_id_author = $app12_id_author)
                                        ) AND
                            ((asz03.code <> 'FR00000000' AND asz22.system_prefix = 'FILERES' AND '$instance_type' = 'PROD') OR '$instance_type' <> 'PROD' OR asz22.system_prefix <> 'FILERES')
                            -- AND ((NVL(asz02.code,'~~~') <> 'CERT_EP_GISTEK' AND asz22.system_prefix = 'CERT_EP' AND '$instance_type' = 'PROD') OR '$instance_type' <> 'PROD' OR asz22.system_prefix <> 'CERT_EP')
                                )
        OR NOT EXISTS (SELECT 'x' FROM asz03_sap_roles asz03 WHERE sysdate BETWEEN ASZ03.date_start AND ASZ03.date_end
        AND asz03.asz02_id = asz02.id))
        AND asz02.add_remove_visibility IN (0,1)
        AND '$order_type' = 'ADD_PRIVS'
        UNION ALL
        SELECT
        ASZ02.name DN__NAME,
        ASZ02.id DN__ID,
        DECODE(asz02.name,'ОТСУТСТВУЕТ В СПИСКЕ (УКАЗАТЬ ВРУЧНУЮ)','ЯЯЯЯЯЯ',asz02.name) ORDSEQ
        FROM
        ASZ02_SAP_PROCESS_GROUPS ASZ02,
        ASZ00_SAP_SYSTEMS ASZ00,  
        ASZ22_CORP_SYSTEMS ASZ22
        WHERE
        ASZ02.ASZ00_ID = ASZ00.ID AND
        ASZ00.ASZ22_ID  = ASZ22.ID AND
        (ASZ02.ID IN (SELECT asz03.ASZ02_ID FROM asz03_sap_roles asz03 WHERE
                    ASZ03.ID IN (SELECT iacp.asz03_id
                                FROM int_asz_current_privs iacp
                                WHERE iacp.app12_id = $app12_id AND
                                iacp.asz00_id = $asz00_id)) OR (ASZ00.id = $asz00_id AND asz22.system_prefix = 'GIS_JKH')) AND
        '$order_type' = 'REMOVE_PRIVS' AND
        asz02.add_remove_visibility IN (0,2)
        ORDER BY 3
        ";
        return select( $props );        
    }

    function getRoleList( $props ) {

        $asz00_id = isset( $props['asz00_id'] ) ? cleanData( $props['asz00_id'], 'i' ) : null;    
        $asz01_id = isset( $props['asz01_id'] ) ? cleanData( $props['asz01_id'], 'i' ) : null;    
        $app12_id = isset( $props['app12_id'] ) ? cleanData( $props['app12_id'], 'i' ) : null;    
        $app12_id_author = isset( $props['app12_id_author'] ) ? cleanData( $props['app12_id_author'], 'i' ) : null;    
        $asz02_id = isset( $props['asz02_id'] ) ? cleanData( $props['asz02_id'], 'i' ) : null;    
        $order_type = isset( $props['order_type'] ) ? cleanData( $props['order_type']) : null;    
        $instance_type = isset( $props['instance_type'] ) ? cleanData( $props['instance_type']) : null;
        
        if ( $order_type !== 'ADD_PRIVS' and $order_type !== 'REMOVE_PRIVS') $order_type = null;
        if ( $instance_type !== 'PROD' and $instance_type !== 'DEV' and $instance_type !== 'TEST') $instance_type = null;

        if ( $asz02_id ) $where_group_process = "ASZ03.ASZ02_ID = $asz02_id";
        else $where_group_process = '1=1';

        $props['query'] = "
            SELECT 
                ASZ03.name DN__NAME,
                ASZ03.id DN__ID,
                ASZ03.ROLE_TYPE DN__ROLE_TYPE,
                ASZ03.CODE DN__CODE,
                ASZ02.NAME DN__GROUP_NAME,
                ASZ03.DESCRIPTION DN__DESCRIPTION,
                ASZ03.DISABLE_AGREE_SELECT DN__DISABLE_AGREE_SELECT,
                DECODE(ASZ22.SYSTEM_PREFIX, 'SERVICEACC',REPLACE(REPLACE(ASZ03.CODE,'WITHOUT_MAIL','0'),'WITH_MAIL','1'),ASZ03.NAME) ORD
            FROM 
                ASZ03_SAP_ROLES ASZ03,
                ASZ00_SAP_SYSTEMS ASZ00,
                ASZ22_CORP_SYSTEMS ASZ22,
                ASZ02_SAP_PROCESS_GROUPS ASZ02
            WHERE 
                ASZ03.ASZ00_ID = ASZ00.ID AND
                ASZ03.ASZ02_ID = ASZ02.ID AND
                ASZ00.ASZ22_ID = ASZ22.ID AND
                ((
                    (ASZ03.ID IN (SELECT ASZ04.ASZ03_ID FROM ASZ04_ASZ03_ASZ01 ASZ04 WHERE ASZ04.ASZ01_ID IN (
                                                        SELECT asz01.id FROM asz01_sap_branches asz01
                                                        WHERE SYSDATE BETWEEN asz01.date_start AND asz01.date_end
                                                        CONNECT BY asz01.id = PRIOR asz01.asz01_id
                                                        START WITH asz01.id = $asz01_id   
                                ) AND
                                SYSDATE BETWEEN ASZ04.DATE_START AND ASZ04.DATE_END) OR NOT EXISTS
                                (SELECT ASZ04.ASZ03_ID FROM ASZ04_ASZ03_ASZ01 ASZ04 WHERE ASZ04.ASZ03_ID = ASZ03.ID AND
                                SYSDATE BETWEEN ASZ04.DATE_START AND ASZ04.DATE_END)
                    ) AND
                    (ASZ03.ID NOT IN (SELECT asz51.ASZ03_ID FROM asz51_asz03_asz01_deny asz51 WHERE asz51.ASZ01_ID IN (
                                                        SELECT asz01.id FROM asz01_sap_branches asz01
                                                        WHERE SYSDATE BETWEEN asz01.date_start AND asz01.date_end
                                                        CONNECT BY asz01.id = PRIOR asz01.asz01_id
                                                        START WITH asz01.id = $asz01_id
                    ) AND
                    SYSDATE BETWEEN ASZ51.DATE_START AND ASZ51.DATE_END)
                    )) OR ASZ03.ID IN (SELECT asz18.asz03_id_force FROM asz18_authors_role_force asz18 WHERE asz18.app12_id_author = $app12_id_author)
                ) AND
                ASZ00.ID = $asz00_id AND
                $where_group_process AND                /* ASZ03.ASZ02_ID = $asz02_id */
                SYSDATE BETWEEN ASZ03.DATE_START AND ASZ03.DATE_END
                AND asz02.add_remove_visibility IN (0,1)
                AND '$order_type' = 'ADD_PRIVS'
                AND ((asz03.code <> 'FR00000000' AND asz22.system_prefix = 'FILERES' AND '$instance_type' = 'PROD') OR '$instance_type' <> 'PROD' OR asz22.system_prefix <> 'FILERES')
                --AND (NVL(asz02.code,'~~~') <> 'CERT_EP_GISTEK' OR '$instance_type' <> 'PROD')
            UNION ALL   
                SELECT
                    ASZ03.name DN__NAME,
                    ASZ03.id DN__ID,
                    ASZ03.ROLE_TYPE DN__ROLE_TYPE,
                    ASZ03.CODE DN__CODE,
                    ASZ02.NAME DN__GROUP_NAME,
                    ASZ03.DESCRIPTION DN__DESCRIPTION,
                    ASZ03.DISABLE_AGREE_SELECT DISABLE_AGREE_SELECT,
                    DECODE(ASZ22.SYSTEM_PREFIX, 'SERVICEACC',REPLACE(REPLACE(ASZ03.CODE,'WITHOUT_MAIL','0'),'WITH_MAIL','1'),ASZ03.NAME) ORD
                FROM 
                    ASZ03_SAP_ROLES ASZ03,
                    ASZ02_SAP_PROCESS_GROUPS ASZ02,
                    ASZ00_SAP_SYSTEMS ASZ00,
                    ASZ22_CORP_SYSTEMS ASZ22
                WHERE
                    ASZ03.ASZ02_ID = ASZ02.ID AND
                    ASZ02.ASZ00_ID  = ASZ00.ID AND ASZ00.ASZ22_ID = ASZ22.ID AND
                    (ASZ03.ID IN (SELECT iacp.asz03_id
                        FROM int_asz_current_privs iacp
                        WHERE iacp.app12_id = $app12_id AND
                                iacp.asz00_id = $asz00_id) OR (ASZ00.id = $asz00_id AND asz22.system_prefix = 'GIS_JKH')) AND
                    '$order_type' = 'REMOVE_PRIVS' AND
                    asz02.add_remove_visibility IN (0,2)
                ORDER BY DN__GROUP_NAME, ORD
        ";
        return select( $props );        
    }
    
    function getGroupByRole( $props ) {

        $asz00_id = isset( $props['asz00_id'] ) ? cleanData( $props['asz00_id'], 'i' ) : null;    
        $asz03_id = isset( $props['asz03_id'] ) ? cleanData( $props['asz03_id'], 'i' ) : null;
        
        $props['query'] = "
            SELECT 
                ASZ02.ID DN__ID, 
                ASZ02.NAME DN__NAME 
            FROM 
                ASZ02_SAP_PROCESS_GROUPS ASZ02,
                ASZ03_SAP_ROLES ASZ03
            WHERE
                ASZ03.ASZ02_ID = ASZ02.ID AND
                SYSDATE BETWEEN ASZ02.DATE_START AND ASZ02.DATE_END AND
                SYSDATE BETWEEN ASZ03.DATE_START AND ASZ03.DATE_END AND
                ASZ02.ASZ00_ID = $asz00_id AND
                ASZ03.ID = $asz03_id
        ";
        return select( $props )[0];        
    }    

    function clearBlockData ( $props ) {
        $session_key = isset( $props['session_key'] ) ? cleanData( $props['session_key']) : null;    
        $cnt = isset( $props['cnt'] ) ? cleanData( $props['cnt'], 'i' ) : null;
        $props['query'] = 'PROCEDURE p_asz13.clear_block_data(	
            session_key_in IN VARCHAR2,
            cnt_in IN NUMBER
        )';
        $props['props'] = [
            'session_key_in' => $session_key, 
            'cnt_in' => $cnt
        ];
        return execute( $props );
    }

    function clearSessionData ( $props ) {
        $session_key = isset( $props['session_key'] ) ? cleanData( $props['session_key']) : null;
        $props['query'] = 'PROCEDURE p_asz13.clear_session_data(session_key_in IN VARCHAR2)';
        $props['props'] = ['session_key_in' => $session_key];
        return execute( $props );
    }

    function getLevelList( $props ) {
        $asz03_id = isset( $props['asz03_id'] ) ? cleanData( $props['asz03_id'], 'i' ) : null;
        $props['query'] = "
            SELECT 
                asz05.id DN__ID,
                asz05.name DN__NAME,
                asz05.asz05_ID DN__ASZ05_ID,
                asz05.display_type DN__DISPLAY_TYPE,
                asz05.code DN__CODE,
                asz05.is_required DN__IS_REQUIRED
            FROM 
                asz05_sap_org_levels asz05,
                asz07_asz03_asz05 asz07
            WHERE 
                asz07.asz05_id = asz05.id AND
                asz07.asz03_id = $asz03_id AND
                SYSDATE BETWEEN asz05.date_start AND asz05.date_end AND
                SYSDATE BETWEEN asz07.date_start AND asz07.date_end
            ORDER BY asz07.order_seq
        ";
        return select( $props );        
    }

    function checkLevelValueExist( $props ) {
        $asz05_id = isset( $props['asz05_id'] ) ? cleanData( $props['asz05_id'], 'i' ) : null;
        $props['query'] = "
            SELECT count(*) AS DN__NUMBER
            FROM  ASZ06_SAP_ORG_LEVEL_VALUES ASZ06
            WHERE  
                asz06.asz05_id = $asz05_id AND
                SYSDATE BETWEEN asz06.date_start AND asz06.date_end
        ";
        return select( $props )[0]['number'] > 0;         
    }

    function getLevelValues( $props ) {

        $asz05_id = isset( $props['asz05_id'] ) ? cleanData( $props['asz05_id'], 'i' ) : null;
        $session_key = isset( $props['session_key'] ) ? cleanData( $props['session_key']) : null;    
        $cnt = isset( $props['cnt'] ) ? cleanData( $props['cnt'], 'i' ) : null;
        $asz00_id = isset( $props['asz00_id'] ) ? cleanData( $props['asz00_id'], 'i' ) : null;    
        $asz03_id = isset( $props['asz03_id'] ) ? cleanData( $props['asz03_id'], 'i' ) : null;
        $app12_id = isset( $props['app12_id'] ) ? cleanData( $props['app12_id'], 'i' ) : null;
        $order_type = isset( $props['order_type'] ) ? cleanData( $props['order_type']) : null; 

        $asz22_id = isset($props['asz22_id']) ? cleanData( $props['asz22_id'], 'i' ) : null;
        $process_group = isset($props['process_group']) ? cleanData( $props['process_group']) : null;

        $queryOld = "
            SELECT 
                asz06.id DN__ID,
                asz06.code DN__CODE,
                asz06.value DN__VALUE,
                DECODE(asz06.code,NULL,asz06.value,asz06.code) DN__ASZ6_VALUECODE,
                asz06_1.code DN__PARENT_CODE,
                ASZ05_1.NAME DN__PARENT_NAME,
                asz06.asz05_id ORDR_1,
                DECODE(p_asz_util.get_param_value(asz22.id,'ORGLEVELS_ORDER'),'CODE',DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE),'VALUE',ASZ06.VALUE,DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE)) ORDR_2,
                ASZ05.MULTIPLE_SELECT DN__MULTIPLE_SELECT,
                ASZ05.DISPLAY_TYPE DN__DISPLAY_TYPE
            FROM 
                ASZ06_SAP_ORG_LEVEL_VALUES ASZ06,
                ASZ06_SAP_ORG_LEVEL_VALUES ASZ06_1,
                ASZ05_SAP_ORG_LEVELS ASZ05,
                ASZ05_SAP_ORG_LEVELS ASZ05_1,
                ASZ22_CORP_SYSTEMS ASZ22
            WHERE 
                ASZ05.ASZ22_ID = ASZ22.ID AND
                asz06.asz06_id = asz06_1.id (+) AND
                asz06.asz05_id = $asz05_id AND
                asz05_1.id(+) = asz05.ASZ05_ID AND       
                ((asz06.asz06_id IN (
                    SELECT asz13.asz06_id
                    FROM asz13_sap_webform_orglevels asz13
                    WHERE  asz13.session_key = '$session_key' and asz13.blk_id = '$cnt'
                                   ) AND
                    asz06.asz06_id IS NOT NULL) OR
                    asz06.asz06_id IS NULL) AND
                    SYSDATE BETWEEN asz06.date_start AND asz06.date_end AND
                    asz06.asz05_id = asz05.id AND
                    SYSDATE BETWEEN asz05.date_start AND asz06.date_end AND
                    (asz06.id IN (SELECT asz06_2.asz06_id
                      FROM asz06_sap_org_level_values asz06_2,
                           asz05_sap_org_levels asz05_2
                     WHERE asz06_2.asz05_id = asz05_2.id AND
                           asz05_2.asz05_id = $asz05_id AND
                      asz06_2.asz05_id IN (SELECT asz07.asz05_id
                                                 FROM asz07_asz03_asz05 asz07
                                                 WHERE asz07.asz03_id = $asz03_id AND
                                                 SYSDATE BETWEEN asz07.date_start AND asz07.date_end) AND
                      SYSDATE BETWEEN asz06_2.date_start AND asz06_2.date_end AND
                      asz06_2.asz06_id IS NOT NULL) OR
                    NOT EXISTS (SELECT asz06_2.asz06_id
                      FROM asz06_sap_org_level_values asz06_2,
                           asz05_sap_org_levels asz05_2
                      WHERE asz06_2.asz05_id = asz05_2.id AND
                            asz05_2.asz05_id = $asz05_id AND
                      asz06_2.asz05_id IN (SELECT asz07.asz05_id
                                                 FROM asz07_asz03_asz05 asz07
                                                 WHERE asz07.asz03_id = $asz03_id AND
                                                 SYSDATE BETWEEN asz07.date_start AND asz07.date_end) AND
                      SYSDATE BETWEEN asz06_2.date_start AND asz06_2.date_end AND
                      asz06_2.asz06_id IS NOT NULL)
                )             
                AND
                (
                    asz06.id IN (SELECT id FROM
                    (SELECT asz06_3.id
                        FROM asz06_sap_org_level_values asz06_3
                        WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
                        CONNECT BY PRIOR asz06_3.id = asz06_3.asz06_id AND (asz06_3.asz14_id IS NOT NULL OR NOT EXISTS 
                                                                                (SELECT 'x'
                    FROM asz06_sap_org_level_values asz06_4
                    WHERE SYSDATE BETWEEN asz06_4.date_start AND asz06_4.date_end AND
                    asz06_4.asz06_id = asz06_3.asz06_id AND asz06_4.asz14_id IS NOT NULL AND asz06_4.asz14_id IN (SELECT asz16.asz14_id FROM asz16_asz03_asz14 asz16 WHERE asz16.asz03_id = $asz03_id)))
                        START WITH asz06_3.id IN (SELECT asz06s.id
                                                                        FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                                    WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                    UNION ALL
                                                                    SELECT asz06s.id
                                                                    FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                                    WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                        )
                        AND $asz03_id IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
                )
            UNION ALL
            (SELECT asz06_3.id
                FROM asz06_sap_org_level_values asz06_3
                WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
                CONNECT BY asz06_3.id = PRIOR asz06_3.asz06_id
                START WITH asz06_3.id IN (SELECT asz06s.id
                                                            FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                            WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                            UNION ALL
                                                            SELECT asz06s.id
                                                            FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                            WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                )
                AND $asz03_id IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
                )
            UNION ALL
            SELECT id
            FROM asz06_sap_org_level_values asz06_3
            WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
                AND $asz03_id NOT IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
                    UNION ALL
                    SELECT id
                    FROM asz06_sap_org_level_values asz06_3
                    WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
                    AND $asz03_id IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16) 
                    AND asz06_3.asz05_id NOT IN (SELECT asz06_4.asz05_id
                                            FROM asz06_sap_org_level_values asz06_4
                                            WHERE SYSDATE BETWEEN asz06_4.date_start AND asz06_4.date_end
                                            CONNECT BY PRIOR asz06_4.id = asz06_4.asz06_id
                                            START WITH asz06_4.id IN (SELECT asz06s.id
                                                                    FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                                WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                UNION ALL
                                                                SELECT asz06s.id
                                                                FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                                WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                    )
                                            UNION ALL
                                            SELECT asz06_4.asz05_id
                                            FROM asz06_sap_org_level_values asz06_4
                                            WHERE SYSDATE BETWEEN asz06_4.date_start AND asz06_4.date_end
                                            CONNECT BY asz06_4.id = PRIOR asz06_4.asz06_id
                                            START WITH asz06_4.id IN (SELECT asz06s.id
                                                                    FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                                WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                UNION ALL
                                                                SELECT asz06s.id
                                                                FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                                WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id)
                                                                    )
                                            ) 
                )
                AND ASZ06.ID IN
                (
                    SELECT 
                    asz53.asz06_id 
                    FROM 
                    asz53_sap_existing_roles asz53 
                    WHERE 
                    asz53.asz03_id = $asz03_id
                    UNION ALL
                    SELECT
                    asz06_sq2.id
                    FROM
                    asz06_sap_org_level_values asz06_sq2
                    WHERE
                    $asz03_id NOT IN (SELECT asz53.asz03_id FROM asz53_sap_existing_roles asz53)
                    OR NOT EXISTS (SELECT 'X' FROM asz05_sap_org_levels asz05 WHERE asz05.code IN ('BUKRS', 'WERKS') AND asz05.id = $asz05_id)
                    OR $asz03_id IN
                    (
                        SELECT
                        asz03_sq.id
                        FROM
                        asz03_sap_roles asz03_sq
                        WHERE
                        SYSDATE BETWEEN asz03_sq.date_start AND asz03_sq.date_end
                        AND asz03_sq.code IN
                        (
                        SELECT
                        asz21_sq.value
                        FROM
                        asz21_parameters asz21_sq
                        WHERE
                        asz21_sq.asz22_id = 1
                        AND asz21_sq.code = 'ROLE_EXISTS_EXCEPTION'
                        )
                    )
                )
                AND '$order_type' = 'ADD_PRIVS'
                UNION ALL
                SELECT asz06.id DN__ID,
                        asz06.code DN__CODE,
                        asz06.value DN__VALUE,
                        DECODE(asz06.code,NULL,asz06.value,asz06.code) DN__ASZ6_VALUECODE,
                        asz06_1.code DN__PARENT_CODE,
                        ASZ05_1.NAME DN__PARENT_NAME,
                        asz06.asz05_id ORDR_1,
                        DECODE(p_asz_util.get_param_value(asz22.id,'ORGLEVELS_ORDER'),'CODE',DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE),'VALUE',ASZ06.VALUE,DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE)) ORDR_2,
                        ASZ05.MULTIPLE_SELECT DN__MULTIPLE_SELECT,
                        ASZ05.DISPLAY_TYPE DN__DISPLAY_TYPE
                FROM ASZ06_SAP_ORG_LEVEL_VALUES ASZ06,
                        ASZ06_SAP_ORG_LEVEL_VALUES ASZ06_1,
                        ASZ05_SAP_ORG_LEVELS ASZ05,
                    ASZ05_SAP_ORG_LEVELS ASZ05_1,
                    ASZ22_CORP_SYSTEMS ASZ22
                WHERE ASZ05.ASZ22_ID = ASZ22.ID AND
                    asz06.asz06_id = asz06_1.id (+) AND
                    asz06.asz05_id = $asz05_id AND
                    asz05_1.id(+) = asz05.ASZ05_ID AND       
                    asz06.asz05_id = asz05.id AND
                    (asz06.id IN (SELECT iacp.asz06_id FROM int_asz_current_privs iacp WHERE iacp.app12_id = $app12_id AND iacp.asz00_id = $asz00_id AND iacp.asz03_id = $asz03_id ) OR $asz05_id IN (SELECT asz05_sq.id FROM asz05_sap_org_levels asz05_sq, asz00_sap_systems asz00_sq, asz22_corp_systems asz22_sq WHERE asz05_sq.asz00_id = asz00_sq.id AND asz00_sq.asz22_id = asz22_sq.id AND asz22_sq.system_prefix = 'GIS_JKH')) AND
                    '$order_type' = 'REMOVE_PRIVS'
                ORDER BY 7,8, 5 NULLS LAST   
        ";
        
        $queryNew = "              
            SELECT asz06.id DN__ID,
                 asz06.code DN__CODE,
                 asz06.value DN__VALUE,
                 DECODE(asz06.code,NULL,asz06.value,asz06.code) DN__ASZ6_VALUECODE,
                 asz06_1.code DN__PARENT_CODE,
                 ASZ05_1.NAME DN__PARENT_NAME,
                 asz06.asz05_id ORDR_1,
                 DECODE(p_asz_util.get_param_value(asz22.id,'ORGLEVELS_ORDER'),'CODE',DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE),'VALUE',ASZ06.VALUE,DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE)) ORDR_2,
                 ASZ05.MULTIPLE_SELECT DN__MULTIPLE_SELECT,
                 ASZ05.DISPLAY_TYPE DN__DISPLAY_TYPE
            FROM ASZ06_SAP_ORG_LEVEL_VALUES ASZ06,
                 ASZ06_SAP_ORG_LEVEL_VALUES ASZ06_1,
                 ASZ05_SAP_ORG_LEVELS ASZ05,
                 ASZ05_SAP_ORG_LEVELS ASZ05_1,
                 ASZ22_CORP_SYSTEMS ASZ22
            WHERE ASZ05.ASZ22_ID = ASZ22.ID AND
                 asz06.asz06_id = asz06_1.id (+) AND
                 asz06.asz05_id = $asz05_id AND
                 asz05_1.id(+) = asz05.ASZ05_ID AND      
                 ((asz06.asz06_id IN (
                                SELECT asz13.asz06_id
                                FROM asz13_sap_webform_orglevels asz13
                                WHERE  asz13.session_key = '$session_key' and asz13.blk_id = '$cnt'
                                            ) AND
                          asz06.asz06_id IS NOT NULL) OR
                          asz06.asz06_id IS NULL) AND
                 SYSDATE BETWEEN asz06.date_start AND asz06.date_end AND
                 asz06.asz05_id = asz05.id AND
                 SYSDATE BETWEEN asz05.date_start AND asz06.date_end AND
                 (asz06.id IN (
                             SELECT asz06_2.id
                               FROM asz06_sap_org_level_values asz06_2,
                                    asz05_sap_org_levels asz05_2
                              WHERE asz06_2.asz05_id = asz05_2.id AND
                               asz06_2.asz05_id IN (SELECT asz07.asz05_id
                                                    FROM asz07_asz03_asz05 asz07
                                                    WHERE asz07.asz03_id = $asz03_id AND
                                                    SYSDATE BETWEEN asz07.date_start AND asz07.date_end) AND 
                               SYSDATE BETWEEN asz06_2.date_start AND asz06_2.date_end 
                               CONNECT BY PRIOR asz06_2.asz06_id = asz06_2.id 
                               START WITH asz05_2.id IN (SELECT asz05_5.id 
                                                               FROM asz05_sap_org_levels asz05_5
                                                              WHERE asz05_5.id IN (
                                                             SELECT asz05_3.id
                                                                 FROM asz07_asz03_asz05 asz07,
                                                                      asz05_sap_org_levels asz05_3
                                                                WHERE asz07.asz05_id = asz05_3.id AND
                                                                asz07.asz03_id = $asz03_id AND
                                                              SYSDATE BETWEEN asz07.date_start AND asz07.date_end AND
                                                              SYSDATE BETWEEN asz05_3.date_start AND asz05_3.date_end) AND
                                                              NOT EXISTS (SELECT 'x' FROM asz05_sap_org_levels asz05_4 WHERE asz05_4.asz05_id = asz05_5.id AND 
                                                                          SYSDATE BETWEEN asz05_4.date_start AND asz05_4.date_end AND asz05_4.is_required = 1)
                                                              CONNECT BY PRIOR asz05_5.id = asz05_5.asz05_id 
                                                              START WITH asz05_5.id = $asz03_id)                                   
                              ) OR
                   NOT EXISTS (SELECT asz06_2.id
                               FROM asz06_sap_org_level_values asz06_2,
                                    asz05_sap_org_levels asz05_2
                              WHERE asz06_2.asz05_id = asz05_2.id AND
                               asz06_2.asz05_id IN (SELECT asz07.asz05_id
                                                    FROM asz07_asz03_asz05 asz07
                                                    WHERE asz07.asz03_id = $asz03_id AND
                                                    SYSDATE BETWEEN asz07.date_start AND asz07.date_end) AND 
                               SYSDATE BETWEEN asz06_2.date_start AND asz06_2.date_end 
                               CONNECT BY PRIOR asz06_2.asz06_id = asz06_2.id 
                               START WITH asz05_2.id IN (SELECT asz05_5.id 
                                                               FROM asz05_sap_org_levels asz05_5
                                                              WHERE asz05_5.id IN (
                                                             SELECT asz05_3.id
                                                                 FROM asz07_asz03_asz05 asz07,
                                                                      asz05_sap_org_levels asz05_3
                                                                WHERE asz07.asz05_id = asz05_3.id AND
                                                                asz07.asz03_id = $asz03_id AND
                                                              SYSDATE BETWEEN asz07.date_start AND asz07.date_end AND
                                                              SYSDATE BETWEEN asz05_3.date_start AND asz05_3.date_end) AND
                                                              NOT EXISTS (SELECT 'x' FROM asz05_sap_org_levels asz05_4 WHERE asz05_4.asz05_id = asz05_5.id AND 
                                                                          SYSDATE BETWEEN asz05_4.date_start AND asz05_4.date_end AND asz05_4.is_required = 1)
                                                              CONNECT BY PRIOR asz05_5.id = asz05_5.asz05_id 
                                                              START WITH asz05_5.id = $asz05_id)
                               )
                 )            
                AND
                        (
                asz06.id IN (SELECT id FROM
                                (SELECT asz06_3.id
                                FROM asz06_sap_org_level_values asz06_3
                                WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
                                CONNECT BY PRIOR asz06_3.id = asz06_3.asz06_id AND (asz06_3.asz14_id IS NOT NULL OR NOT EXISTS
                                                (SELECT 'x'
                                                FROM asz06_sap_org_level_values asz06_4
                                                WHERE SYSDATE BETWEEN asz06_4.date_start AND asz06_4.date_end AND
                                                asz06_4.asz06_id = asz06_3.asz06_id AND asz06_4.asz14_id IS NOT NULL AND asz06_4.asz14_id IN (SELECT asz16.asz14_id FROM asz16_asz03_asz14 asz16 WHERE asz16.asz03_id = $asz03_id)))
                                                START WITH asz06_3.id IN (SELECT asz06s.id
                                                                    FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                                    WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                    UNION ALL
                                                                    SELECT asz06s.id
                                                                    FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                                    WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                                    )
                    AND $asz03_id IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
                    )
            UNION ALL
            (SELECT asz06_3.id
               FROM asz06_sap_org_level_values asz06_3
              WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
              CONNECT BY asz06_3.id = PRIOR asz06_3.asz06_id
              START WITH asz06_3.id IN (SELECT asz06s.id
                                                            FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                            WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                            UNION ALL
                                                            SELECT asz06s.id
                                                            FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                           WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                             )
             AND $asz03_id IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
             )
             UNION ALL
             SELECT id
               FROM asz06_sap_org_level_values asz06_3
              WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
            AND $asz03_id NOT IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
             UNION ALL
             SELECT id
               FROM asz06_sap_org_level_values asz06_3
              WHERE SYSDATE BETWEEN asz06_3.date_start AND asz06_3.date_end
             AND $asz03_id IN (SELECT asz16.asz03_id FROM asz16_asz03_asz14 asz16)
             AND asz06_3.asz05_id NOT IN (SELECT asz06_4.asz05_id
                                     FROM asz06_sap_org_level_values asz06_4
                                     WHERE SYSDATE BETWEEN asz06_4.date_start AND asz06_4.date_end
                                     CONNECT BY PRIOR asz06_4.id = asz06_4.asz06_id
                                     START WITH asz06_4.id IN (SELECT asz06s.id
                                                             FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                            WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                            UNION ALL
                                                            SELECT asz06s.id
                                                            FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                           WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                             )
                                     UNION ALL
                                     SELECT asz06_4.asz05_id
                                     FROM asz06_sap_org_level_values asz06_4
                                     WHERE SYSDATE BETWEEN asz06_4.date_start AND asz06_4.date_end
                                     CONNECT BY asz06_4.id = PRIOR asz06_4.asz06_id
                                     START WITH asz06_4.id IN (SELECT asz06s.id
                                                             FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s
                                                            WHERE asz06s.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id
                                                            UNION ALL
                                                            SELECT asz06s.id
                                                            FROM asz16_asz03_asz14 asz16, asz06_sap_org_level_values asz06s, asz17_asz06_asz14_add asz17
                                                           WHERE asz06s.id = asz17.asz06_id AND asz17.asz14_id = asz16.asz14_id AND asz16.asz03_id = $asz03_id)
                                                             )
                                     )
            )
            AND '$order_type' = 'ADD_PRIVS'
            UNION ALL
                    SELECT asz06.id DN__ID,
                    asz06.code DN__CODE,
                    asz06.value DN__VALUE,
                    DECODE(asz06.code,NULL,asz06.value,asz06.code) DN__ASZ6_VALUECODE,
                    asz06_1.code DN__PARENT_CODE,
                    ASZ05_1.NAME DN__PARENT_NAME,
                    asz06.asz05_id ORDR_1,
                    DECODE(p_asz_util.get_param_value(asz22.id,'ORGLEVELS_ORDER'),'CODE',DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE),'VALUE',ASZ06.VALUE,DECODE(ASZ06.CODE,'ALL',NULL,ASZ06.CODE)) ORDR_2,
                    ASZ05.MULTIPLE_SELECT DN__MULTIPLE_SELECT,
                    ASZ05.DISPLAY_TYPE DN__DISPLAY_TYPE
                FROM ASZ06_SAP_ORG_LEVEL_VALUES ASZ06,
                    ASZ06_SAP_ORG_LEVEL_VALUES ASZ06_1,
                    ASZ05_SAP_ORG_LEVELS ASZ05,
                    ASZ05_SAP_ORG_LEVELS ASZ05_1,
                    ASZ22_CORP_SYSTEMS ASZ22
            WHERE ASZ05.ASZ22_ID = ASZ22.ID AND
                    asz06.asz06_id = asz06_1.id (+) AND
                    asz06.asz05_id = $asz05_id AND
                    asz05_1.id(+) = asz05.ASZ05_ID AND      
                    asz06.asz05_id = asz05.id AND
                    (asz06.id IN (SELECT iacp.asz06_id FROM int_asz_current_privs iacp WHERE iacp.app12_id = $app12_id AND iacp.asz00_id = $asz00_id AND iacp.asz03_id = $asz03_id ) OR $asz05_id IN (SELECT asz05_sq.id FROM asz05_sap_org_levels asz05_sq, asz00_sap_systems asz00_sq, asz22_corp_systems asz22_sq WHERE asz05_sq.asz00_id = asz00_sq.id AND asz00_sq.asz22_id = asz22_sq.id AND asz22_sq.system_prefix = 'GIS_JKH')) AND
                    '$order_type' = 'REMOVE_PRIVS'
            ORDER BY 7,8, 5 NULLS LAST
        ";

        if (getParamValue( $props ) == 1) {
            $props['query'] = "SELECT asz03.code DN__ASZ03_CODE FROM asz03_sap_roles asz03 WHERE asz03.id = $asz03_id";
            if ($process_group == 'ТОРО СГК' or $process_group == 'ТОРО СУЭК' 
                or select( $props )[0]['asz03_code'] == 'Z_MM_MRP_PL'
            ) $props['query'] = $queryNew;
            else $props['query'] = $queryOld;
        } else $props['query'] = $queryOld;

        return select( $props );         
    } 
