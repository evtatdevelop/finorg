<?php 
    include_once('systemData.php');



    $conn = connection('PROD');

    
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
    check( !is_null( getParamValue(['conn' => $conn, 'asz22_id' => 50407, 'param'=>'ORGLEVELS_CASCADE_FILTER']))
    or is_null( getParamValue(['conn' => $conn, 'asz22_id' => 50407, 'param'=>'ORGLEVELS_CASCADE_FILTER'])), 'getParamValue');

 

    ocilogoff($conn);