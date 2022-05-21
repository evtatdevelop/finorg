<?php 
    include_once('roleData.php');



    $conn = connection('PROD');


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

    // Testing of addAsz06Id
    check( addAsz06Id([
        'conn' => $conn,
        'session_key' => 'session_key',
        'cnt' => 1,
        'asz06_id' => 1,
        'asz03_id' => 663,
    ]), 'addAsz06Id' );

    // Testing of delAsz06Id
    check( delAsz06Id([
        'conn' => $conn,
        'session_key' => 'session_key',
        'cnt' => 1,
        'asz06_id' => 1
    ]), 'delAsz06Id' );

    // Testing of runAsz06IdList
    check( runAsz06IdList([
        'conn' => $conn,
        'session_key' => 'session_key',
        'cnt' => 1,
        'mode_asz06_id_list' => 'add',
        'asz06_id_list' => '1,2,3',
        'asz06_id_previous_list' => '1,3',
        'asz03_id' => 663,
    ]), 'runAsz06IdList' ); 
 

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

    // Testing of setApp12IdBoss
    check ( is_array( setApp12IdBoss([
        'conn' => $conn, 
        'session_key' => 'session_key',
        'app12_id_boss' => '1833'
    ])), 'setApp12IdBoss (is used with getRoleAgreement)');

    // Testing of setRoleAgreementParams
    check ( is_array( setRoleAgreementParams([
        'conn' => $conn, 
        'asz01_id' => 22, 
        'asz03_id' => 663, 
        'session_key' => 'session_key',
        'cnt' => 1 
    ])), 'setRoleAgreementParams (is used with getRoleAgreement)');
    
    // Testing of getRoleAgreement
    check( is_array( getRoleAgreement(['conn' => $conn])), 'getRoleAgreement' );





    ocilogoff($conn);