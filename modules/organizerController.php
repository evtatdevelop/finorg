<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getEvents( $props ) {       
        $props['data'] = ['id', 'date', 'name', 'description', 'type', 'value', 'status', 'cash', 'mode'];
        return select( $props );
    }

    function setEvent( $props ) {
        $props['data'] = normalizEventData( json_decode( file_get_contents( 'php://input' ), true ) );
        return update( $props );
    }
    
    function addEventt( $props ) {
        $props['data'] = normalizEventData( json_decode( file_get_contents( 'php://input' ), true ) );
        $props['data']['status'] = 'active';
        return insert( $props );
    }
    
    function dellEvent( $props ) {
        return delete( $props );
    }

    function normalizEventData( $data ) {
        // global $config;
        $data['date']         = (int) $data['date'];
        $data['name']         = (string) $data['name'];
        $data['type']         = (string) $data['type'];
        $data['value']        = $data['type'] == 'event' ? null : ( (bool) $data['value'] ? cleanData($data['value']) : 0 );       
        $data['cash']         = (bool) $data['cash'] ? (string) $data['cash'] : null;
        $data['description']  = (bool) trim($data['description']) ? (string) trim($data['description']) : null;
        $data['status']       = isset($data['status']) && (bool) trim($data['status']) ? (string) trim($data['status']) : 'active';
        $data['mode']         = 'onetime'; 
        return $data;
    }
