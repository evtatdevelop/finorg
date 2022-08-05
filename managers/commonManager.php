<?php
    require_once( 'modules/commonController.php' );

    function balance( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': 
                if ( isset( $props['time'] )) return getInstantBalance( $props ); 
            // case 'POST': return addAsset( $props );
            // case 'PATCH': return setAsset( $props );
            // case 'DELETE': return dellAsset( $props );
            default: break;
        }
    }