<?php
    require_once( 'modules/organizerController.php' );

    function events( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': return getEvents( $props ); 
            // case 'POST': return addAsset( $props );
            // case 'PATCH': return setAsset( $props );
            // case 'DELETE': return dellAsset( $props );
            default: break;
        }
    }