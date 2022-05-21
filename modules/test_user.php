<?php 
    include_once('userData.php');



    $conn = connection('PROD');

                
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
   
    ocilogoff($conn);