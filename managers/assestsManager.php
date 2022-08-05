<?php
    require_once( 'modules/assetsController.php' );

    function assets( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': 
                if ( isset( $props['id'] )) 
                    return getOneAsset( $props ); 
                else return getAssrets( $props ); 
            case 'POST': return addAsset( $props );
            case 'PATCH': return setAsset( $props );
            case 'DELETE': return dellAsset( $props );
            default: break;
        }
    }