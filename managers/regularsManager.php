<?php
    require_once( 'modules/regularsController.php' );

    function regulars( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': return getRegilars( $props ); 
            // case 'POST': return addEventt( $props );
            // case 'PATCH': return setEvent( $props );
            // case 'DELETE': return dellEvent( $props );
            default: break;
        }
    }