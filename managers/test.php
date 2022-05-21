<?php 
    include_once('userManager.php');
    include_once('systemManager.php');
    include_once('roleManager.php');

    $conn = connection('PROD');

    echo "<li class='subLiGroup'>userData</li>";

    // Testing of userManager -> getNameList
    check( is_array( getNameList(['conn' => $conn, 'search' => 'татар']) ) and !is_array( getNameList([]) ),
    'getNameList' );
  
    // Testing of userManager -> getDataUser  
    check(
        is_numeric( getDataUser(['conn' => $conn, 'id' => 1833])['id'] )
        and is_numeric( getDataUser(['conn' => $conn, 'id' => 1833, 'asz00_id' => 1121])['id'] )
        and !getDataUser(['conn' => $conn, 'id' => null])
        and !getDataUser(['conn' => $conn, ]),
        'getDataUser'
    );

    // Testing of userManager -> getRemoteUser  
    check(
        is_numeric( getRemoteUser(['conn' => $conn, 'login' => 'suekcorp\tatarenkoeg'])['id'] )
        and !getDataUser(['conn' => $conn, 'login' => null])
        and !getDataUser(['conn' => $conn, ]),
        'getRemoteUser'
    );

    // Testing of userManager -> userLanguage  
    check(
        is_string( userLanguage(['conn' => $conn, 'app12_id' => 1833]) )
        and is_string( userLanguage(['conn' => $conn, 'app12_id' => 1833, 'lang' => 'EN']) ),
        'userLanguage'
    );


    echo "<li class='subLiGroup'>systemData</li>";

    // Testing of systemManager -> getSystem
    $data = getSystem(['conn' => $conn, 'asz24_id' => 21]);
    $data2 = getSystem(['conn' => $conn, 
        'url' => 'http://request-tst.sibgenco.local/corpsystems/',
        'path' => '/sap_devform/'
    ]);
    $data3 = getSystem(['conn' => $conn, 
        'url' => 'http://request.sibgenco.local/corpsystems/',
        'path' => '/sap/'
    ]);
    check( ($data['asz24_id'] == 21 or $data['asz24_id'] == 4) and ( $data2['asz24_id'] == 21 or $data3['asz24_id'] == 4 ), 'getSystem' );

    // Testing of systemManager -> getSapSystem
    check(  is_array(getSapSystemList(['conn' => $conn, 'asz22_id' => 21])), 'getSapSystem' );

    // Testing of systemManager -> getLocationList
    check(  is_array(getLocationList(['conn' => $conn, 'hrs05_id' => 219])), 'getLocationList' );

    // Testing of systemManager -> getCompanyList
    check(  is_array(getCompanyList(['conn' => $conn, ])), 'getCompanyList' );
    
    // Testing of systemManager -> getBranchList
    check(  is_array(getBranchList(['conn' => $conn, 'hrs01_id' => 97])), 'getBranchList' );
    
    // Testing of systemManager -> getDivisionList
    check(  is_array(getDivisionList(['conn' => $conn, 'hrs05_id' => 219])), 'getDivisionList' );

    // Testing of systemManager -> getMainPage
    check(  is_array(getMainPage(['conn' => $conn, 'lang' => 'EN'])) and !getDivisionList(['conn' => $conn, ]), 'getMainPage' );


    echo "<li class='subLiGroup'>roleData</li>";

    check( is_array( getProcessGroups([
        'conn' => $conn,
        'asz00_id' => 1,
        'asz01_id' => 22,
        'app12_id' => 1833,
        'app12_id_author' => 1833,
        'order_type' => 'ADD_PRIVS',
        'instance_type' => 'PROD',
    ]) ), 'getGroupList');

    // Testing of getRoleList
    check( is_array( getRoles([
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
    check( is_array( getRoleGroup([
        'conn' => $conn,
        'asz00_id' => 1,
        'asz03_id' => 663,
    ]) ), 'getGroupByRole');

    // Testing of unlock
    check( 
        unlock(['conn' => $conn, 'session_key' => 'session_key', 'cnt' => 1,])
        and unlock(['conn' => $conn, 'session_key' => 'session_key'])
        , 'unlock'
    );

    // Testing of getLevels
    check(  is_array( getLevels(['conn' => $conn, 'asz03_id' => 50407 ])), 'getLevels' );

    // debug( getLevels( ['conn' => $conn, 'asz03_id' => 50407 ] ));

    ocilogoff($conn);