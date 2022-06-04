<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getAssrets( $props ) {       
        $props['data'] = ['id', 'currensy', 'value', 'status', 'type', 'time'];
        return select( $props );   
    }

    function getOneAsset( $props ) {
        $props['data'] = ['id', 'currensy', 'value', 'status', 'type', 'time'];
        return select( $props )[0]; 
    }

    function setAsset( $props ) {
        $props['data']['time'] = round(microtime(true) * 1000);
        return update( $props );
    }
    
    function addAsset( $props ) {
        $props['data']['time'] = round(microtime(true) * 1000);
        return insert( $props );
    }
    
    function dellAsset( $props ) {
        return delete( $props );
    }
