<?php
    require_once( 'config.php'                      );
    require_once( 'auxes/utils.php'                 );
    require_once( 'auxes/validation.php'            );
    require_once( 'auxes/auth.php'                  );
    require_once( 'managers/assestsManager.php'     );
    require_once( 'managers/organizerManager.php'   );
    require_once( 'managers/regularsManager.php'   );

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE');
    header("Access-Control-Allow-Headers: Content-type, API-Key");
    header('Content-Type: application/x-javascript; charset=utf8');
    
    if ( !auth() ) die;
    $props = $_GET;
    echo json_encode( $props['q']( $props ) );
