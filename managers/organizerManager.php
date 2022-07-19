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
    
    function regulars( $props ) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ( $method ) {
            case 'GET': return getRegulars( $props ); 
            case 'POST': return addRegulars( $props );
            case 'PATCH': return setRegulars( $props );
            case 'DELETE': return dellRegulars( $props );
            default: break;
        }
    }   