<?php
    require_once( 'auxes/utils.php'             );
    require_once( 'auxes/validation.php'        );
    require_once( 'auxes/auth.php'              );
    require_once( 'managers/assestsManager.php' );

    $props = $_GET;
    if ( !checkDataSet($props, ['key']) ) die;
    if ( !auth($props['key']) ) die;
    $data = $props['data']( $props );
    // debug( $data );

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: Content-type");
    header('Content-Type: application/x-javascript; charset=utf8');
	// header('Access-Control-Allow-Origin: http://localhost:3000');
    // header("Access-Control-Allow-Headers: X-Requested-With");

    echo json_encode( $data );
    