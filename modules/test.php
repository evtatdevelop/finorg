<?php 
    include_once('userData.php');
    include_once('systemData.php');
    include_once('roleData.php');



    $conn = connection('PROD');

    echo "<li class='subLiGroup'>userData</li>";
                
    // Testing of userData -> getUserNames
    function numNames($clause) { return count( getUserNames($clause) ); }
    check(
        numNames(['conn' => $conn, 'search' => 'татар', 'system' => 'SAP' ]) > 0 
        and numNames(['conn' => $conn, 'search' => 'татар']) > 0 
        and numNames(['conn' => $conn, 'search' => 'qwertyuiop']) === 0
        and numNames(['conn' => $conn, 'search' => 'татар', 'system' => 'SYS' ]) === 0 
        and numNames(['conn' => $conn, 'search' => 'Татаренко Евгений Геннадьевич']) === 1
        and numNames(['conn' => $conn, ]) === 0,
        'getUserNames'
    );

    // Testing of userData -> getAddUserNames
    check(
        is_array( getAddUserNames(['conn' => $conn, 'search' => 'тата', 'system' => 'SAP' ,'asz01_id' => '22', 'ids' => '1833']) ),
        'getAddUserNames'
    );

    // Testing of userData -> getUserDataApp12
    $data1 = getUserDataApp12(['conn' => $conn, 'id' => 1833]);
    $data2 = getUserDataApp12(['conn' => $conn, ]);
    $data3 = getUserDataApp12(['conn' => $conn, 'id' => '1833']);
    $data4 = getUserDataApp12(['conn' => $conn, 'id' => 'string']);
    check(
        $data1['id'] == 1833 
        and count($data2) === 0 
        and $data3['id'] == 1833
        and count($data2) === 0,
        'getUserDataApp12'
    );

    // Testing of userData -> get_app12_id_by_login
    check(
        get_app12_id_by_login(['conn' => $conn, 'login' => 'suekcorp\tatarenkoeg']) == 1833
        and get_app12_id_by_login(['conn' => $conn, ]) === null
        and get_app12_id_by_login(['conn' => $conn, 'login' => 'qwertyuiop']) == null
        and get_app12_id_by_login(['conn' => $conn, 'login' => '']) == null,
        'get_app12_id_by_login'
    );
    
    // Testing of userData -> get_remote_login
    check(
        (get_remote_login(['conn' => $conn, 'login' => 'TatarenkoEG', 'domain' => 'SUEKCORP', 'asz00_id' => 1121]) // test
        or get_remote_login(['conn' => $conn, 'login' => 'TatarenkoEG', 'domain' => 'SUEKCORP', 'asz00_id' => 1])) // prod
        and get_remote_login(['conn' => $conn, ]) === null,
        'get_remote_login (sap login)'
    ); 
    
    // Testing of userData -> get_app12_hrs01_id
    check(
        is_numeric(get_app12_hrs01_id(['conn' => $conn, 'app12_id' => 1833]))
        and !get_app12_hrs01_id(['conn' => $conn, 'app12_id' => ''])
        and !get_app12_hrs01_id(['conn' => $conn, ]),
        'get_app12_hrs01_id (is used in getCompanyByUserId)'
    ); 

     // Testing of userData -> getCompanyByUserId
     check(
        is_numeric(getCompanyByUserId(['conn' => $conn, 'app12_id' => 1833])['hrs01_id'])
        and !getCompanyByUserId(['conn' => $conn, 'app12_id' => ''])
        and !getCompanyByUserId([]),
        'getCompanyByUserId'
    );    

    // Testing of userData -> get_app12_hrs05_id_asz
    check(
        is_numeric(get_app12_hrs05_id_asz(['conn' => $conn, 'app12_id' => 1833]))
        and !get_app12_hrs05_id_asz(['conn' => $conn, 'app12_id' => ''])
        and !get_app12_hrs05_id_asz(['conn' => $conn, ]),
        'get_app12_hrs05_id_asz (is used in getBranchByUserId)'
    ); 
    
    // Testing of userData -> getBranchByUserId
     check(
        is_numeric(getBranchByUserId(['conn' => $conn, 'app12_id' => 1833])['hrs05_id'])
        and !getBranchByUserId(['conn' => $conn, 'app12_id' => ''])
        and !getBranchByUserId([]),
        'getBranchByUserId'
    );
    
    // Testing of userData -> get_app12_app22_id
     check(
        is_numeric(get_app12_app22_id(['conn' => $conn, 'app12_id' => 1833]))
        and !get_app12_app22_id(['conn' => $conn, 'app12_id' => ''])
        and !get_app12_app22_id(['conn' => $conn, ]),
        'get_app12_app22_id (is used in getSapBranchByUserId)'
    );
    
    // Testing of userData -> get_app22_asz01_id
     check(
        is_numeric(
            get_app22_asz01_id(['conn' => $conn, 
                'app22_id' => get_app12_app22_id(['conn' => $conn, 'app12_id' => 1833])
            ])
        )
        and !get_app22_asz01_id(['conn' => $conn, 'app12_id' => ''])
        and !get_app22_asz01_id(['conn' => $conn, ]),
        'get_app22_asz01_id (is used in getSapBranchByUserId)'
    );

    // Testing of userData -> get_hrs05_asz01_id
    check(
        is_numeric(get_hrs05_asz01_id(['conn' => $conn, 'hrs05_id' => 219]))
        and !get_hrs05_asz01_id(['conn' => $conn, 'app12_id' => ''])
        and !get_hrs05_asz01_id(['conn' => $conn, ]),
        'get_hrs05_asz01_id (is used in getSapBranchByUserId)'
    );

    // Testing of userData -> getSapBranchByUserId
    check(
        is_numeric( getSapBranchByUserId(['conn' => $conn, 'app12_id' => 1833])['asz01_id'] )
        and is_numeric( getSapBranchByUserId(['conn' => $conn, 'hrs05_id' => 219])['asz01_id'] )
        and is_numeric(getSapBranchByUserId(['conn' => $conn, 'app22_id' => get_app12_app22_id(['conn' => $conn, 'app12_id' => 1833])])['asz01_id'])
        and !getSapBranchByUserId(['conn' => $conn, ])
        and !getSapBranchByUserId(['conn' => $conn, 'app22_id' => null]),
        'getSapBranchByUserId'
    );

    // Testing of userData -> getUserLang
    check(
        is_string( getUserLang(['conn' => $conn, 'app12_id' => 1833]) )
        ,'getUserLang'
    );

    // Testing of userData -> setUserLang
    check(
        is_array( setUserLang(['conn' => $conn, 'app12_id' => 1833, 'lang' => 'EN']) )
        and setUserLang(['conn' => $conn, 'app12_id' => 1833, 'lang' => 'EN'])['lang_in'] == 'EN',
        'setUserLang'
    );
    
    /**
     * SYSSTEM DATA
     */
    echo "<li class='subLiGroup'>systemData</li>";
    
    // Testing of systemData -> getSystemData
    $data = getSystemData(['conn' => $conn, 
        'url' => 'http://request-tst.sibgenco.local/corpsystems/',
        'path' => '/sap_devform/',
        'id' => 21,
    ]);
    $data1 = getSystemData(['conn' => $conn, 
        'url' => 'http://request.sibgenco.local/corpsystems/',
        'path' => '/sap/',
        'id' => 4,
    ]);
    check( (isset($data['asz24_id']) and $data['asz24_id'] == 21) 
    or (isset($data1['asz24_id']) and $data1['asz24_id'] == 4),
    'getSystemData');

    // Testing getSapSystems
    check(
        count(getSapSystems(['conn' => $conn,  'asz22_id' => 1 ])) > 0
        and !getSapSystems([]),
        'getSapSystems'
    );

    // Testing getLocations (there is not data in test DB (asz60_hrs05_companies))
    check( is_array(getLocations(['conn' => $conn, 'hrs05_id' => 219])), 'getLocations');

    // Test of getCompanies
    $companies = getCompanies(['conn' => $conn, ]);
    check( count($companies) > 0, 'getCompanies' );

    // Test of getBranches
    $branches = getBranches(['conn' => $conn, 'hrs01_id' => $companies[9]['id']]);
    check( count($branches) > 0, 'getBranches' );

    // Testing of getDivisions
    check( count( $branches[0]['id'] ) > 0, 'getDivisions' );

    // Testing of getPhrase
    check( is_string( getPhrase(['conn' => $conn, 'form_name' => 'mainpage', 'phrase' => 'head_systemname', 'lang' => 'EN']) ),
    'getPhrase'
    );

    // Testing of getSystemName
    check( is_string( getSystemName(['conn' => $conn, 'asz22_id' => 1])),
    'getSystemName'
    );

    // Testing of setCurrentLanguage
    check( setCurrentLanguage(['conn' => $conn, 'lang' => 'EN'])['lang_in'] == 'EN', 'setCurrentLanguage');
    
    // Testing of getMainPageSections
    check( is_array(getMainPageSections(['conn' => $conn, ])), 'getMainPageSections');
    
    // Testing of getMainPageSystemsSections
    check( is_array(getMainPageSystemsSections(['conn' => $conn, 'asz68_id' => 3])), 'getMainPageSystemsSections');
    
    // Testing of getParamValue
    check( !is_null( getMainPageSystemsSections(['conn' => $conn, 'asz68_id' => 3])), 'getParamValue');



    
    /**
     * ROLE DATA
    */

    echo "<li class='subLiGroup'>roleData</li>";

    // Testing of getGroupList
    check( is_array( getGroupList([
        'conn' => $conn,
        'asz00_id' => 1,
        'asz01_id' => 22,
        'app12_id' => 1833,
        'app12_id_author' => 1833,
        'order_type' => 'ADD_PRIVS',
        'instance_type' => 'PROD',
    ]) ), 'getGroupList');

    // Testing of getRoleList
    check( is_array( getRoleList([
        'conn' => $conn,
        'asz00_id' => 1,
        'asz01_id' => 22,
        'app12_id' => 1833,
        'app12_id_author' => 1833,
        'asz02_id' => 44,
        'order_type' => 'ADD_PRIVS',
        'instance_type' => 'PROD',
    ]) ), 'getRoleList');

    // Testing of getGroupByRole
    check( is_array( getGroupByRole([
        'conn' => $conn,
        'asz00_id' => 1,
        'asz03_id' => 663,
    ]) ), 'getGroupByRole');

    // Testing of clearBlockData
    check( clearBlockData([
        'conn' => $conn,
        'session_key' => 'session_key',
        'cnt' => 1,
    ]), 'clearBlockData' );

    // Testing of clearSessionData
    check( clearSessionData([
        'conn' => $conn,
        'session_key' => 'session_key'
    ]), 'clearSessionData' );

    // debug( getGroupList([
    //     'conn' => $conn,
    //     'asz00_id' => 1,
    //     'asz01_id' => 22,
    //     'app12_id' => 1833,
    //     'app12_id_author' => 1833,
    //     'order_type' => 'ADD_PRIVS',
    //     'instance_type' => 'PROD',
    // ]) );

    // debug( getRoleList([
    //     'conn' => $conn,
    //     'asz00_id' => 1,
    //     'asz01_id' => 22,
    //     'app12_id' => 1833,
    //     'app12_id_author' => 1833,
    //     'asz02_id' => null,
    //     'order_type' => 'ADD_PRIVS',
    //     'instance_type' => 'PROD',
    // ]) );

    // debug( getGroupByRole([
    //     'conn' => $conn,
    //     'asz00_id' => 1,
    //     'asz03_id' => 663,
    // ]) );

// debug(getSapSystems(['conn' => $conn,  'asz22_id' => 50407 ]));

    // foreach(getRoleList([
    //     'conn' => $conn,
    //     'asz00_id' => 1121,
    //     'asz01_id' => 1,
    //     'app12_id' => 1833,
    //     'app12_id_author' => 1833,
    //     'asz02_id' => null,
    //     'order_type' => 'ADD_PRIVS',
    //     'instance_type' => 'PROD',
    // ]) as $item){
    // echo $item['id'];
    // debug( getLevelList([
    //     'conn' => $conn,
    //     'asz03_id' => $item['id']
    // ]) );}

    // Testing of getLevelList
    check( is_array( getLevelList(['conn' => $conn, 'asz03_id' => 50407 ]) ), 'getLevelList');
    // debug( getLevelList(['conn' => $conn, 'asz03_id' => 50407 ]) );

    // Testing of checkLevelValueExist
    check( is_bool( checkLevelValueExist(['conn' => $conn, 'asz05_id' => 9115 ]) ), 'checkLevelValueExist');
    // debug( checkLevelValueExist(['conn' => $conn, 'asz05_id' => 9115 ]) );

    // Testing of getLevelValues
    check( is_array( getLevelValues([
            'conn' => $conn,
            'asz05_id' => 9115,
            'session_key' => 'TEST',
            'cnt' => 1,
            'asz00_id' => 1121,
            'asz03_id' => 663,
            'app12_id' => 1833,
            'order_type' => 'ADD_PRIVS',
            'asz22_id' => '50407',
            'process_group' => ''
        ])), 'getLevelValues');

    ocilogoff($conn);