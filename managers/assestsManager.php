<?php
    require_once( 'modules/assetsData.php' );

    function assets( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': 
                if ( isset( $props['id'] )) return getOneAsset( $props ); 
                else return getAssrets( $props ); 
            case 'POST': 
            case 'PATCH': 
                $props['data'] = json_decode( file_get_contents( 'php://input' ), true );
                if ( $method == 'PATCH' ) return setAsset( $props );
                else return addAsset( $props );
            case 'DELETE': break;
            default: break;
        }
    }