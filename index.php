<?php
    $DB_INST = 'PROD';

    require_once( 'auxes/utils.php'             );
    require_once( 'auxes/validation.php'        );
    require_once( 'auxes/auth.php'              );
    require_once( 'managers/userManager.php'    );
    require_once( 'managers/systemManager.php'  );
    require_once( 'managers/orderManager.php'   );
    require_once( 'managers/roleManager.php'    );
    require_once( 'oracle/driver.php'           );
    
    require_once( 'managers/assestsManager.php' );

    if (count($_GET) == 0) header("Location: intro.php"); // TEST: Performing page 

    $props = $_GET;
    if ( !checkDataSet($props, ['key']) ) die;
    if ( !auth($props['key']) ) die;
    // $conn = connection();
    // $props['conn'] = $conn;
    $props['conn'] = '$conn';
    $data = $props['data']( $props );
    // ocilogoff( $conn );

    // debug( $data );

    header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Origin: http://localhost:3000');
    header('Access-Control-Allow-Methods: GET, POST');
    // header("Access-Control-Allow-Headers: X-Requested-With");
    header("Access-Control-Allow-Headers: Content-type");
    header('Content-Type: application/x-javascript; charset=utf8'); 
	echo json_encode( $data );
    