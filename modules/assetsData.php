<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getAssrets( $props ) {
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets";
        return select( $props );   
    }

    function getOneAsset( $props ) {
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets where " . sprintf("id = '%d'", $props['id']);
        return select( $props ); 
    }

    function setAsset( $props ) {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        // todo isset id check  
        $data['time'] = round(microtime(true) * 1000);
        $props['data'] = $data;
        return update( $props );
    }
    
    function addAsset( $props ) {
        $data = json_decode( file_get_contents( 'php://input' ), true );
        $data['time'] = round(microtime(true) * 1000);
        $props['data'] = $data;
        return insert( $props );
    }
