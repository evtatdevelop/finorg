<?php
    require_once( 'modules/organizerController.php' );

    function events( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': return getEvents( $props ); 
            case 'POST': return addEventt( $props );
            case 'PATCH': return setEvent( $props );
            case 'DELETE': return dellEvent( $props );
            default: break;
        }
    }