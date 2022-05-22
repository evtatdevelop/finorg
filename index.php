<?php
    require_once( 'auxes/utils.php'             );
    require_once( 'auxes/validation.php'        );
    require_once( 'auxes/auth.php'              );
    require_once( 'managers/assestsManager.php' );

    $props = $_GET;
    if ( !checkDataSet($props, ['key']) or !auth($props['key']) ) die;
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: Content-type");
    header('Content-Type: application/x-javascript; charset=utf8');
    echo json_encode( $props['data']( $props ) );
    