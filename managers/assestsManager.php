<?php
    require_once( 'modules/assetsData.php' );

    function assets( $props ) { 
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if ( isset( $props['id'] )) return getOneAsset( $props )[0];
                else return getAssrets( $props ); 
            case 'POST': break;
            case 'PATCH': return setAsset( $props );
            case 'DELETE': break;
            default: break;
        }
    }