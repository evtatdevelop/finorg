<?php
    require_once( 'auxes/utils.php'             );
    require_once( 'auxes/validation.php'        );
    require_once( 'auxes/auth.php'              );
    require_once( 'managers/assestsManager.php' );

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH');
    header("Access-Control-Allow-Headers: Content-type,,API-Key");
    header('Content-Type: application/x-javascript; charset=utf8');

    $props = $_GET;
    if ( !checkDataSet($props, []) or !auth() ) die;
    echo json_encode( $props['data']( $props ) );
