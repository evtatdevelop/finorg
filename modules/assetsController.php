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
        $props['data'] = normalizAssetsData( json_decode( file_get_contents( 'php://input' ), true ) );
        return update( $props );
    }
    
    function addAsset( $props ) {
        $props['data'] = normalizAssetsData( json_decode( file_get_contents( 'php://input' ), true ) );
        return insert( $props );
    }
    
    function dellAsset( $props ) {
        return delete( $props );
    }

    function normalizAssetsData( $data ) {
        global $config;
        $data['time'] = round(microtime(true) * 1000);
        $data['currensy'] = mb_substr( mb_strtoupper( rus2translit( $data['currensy'] ), 'UTF-8' ), 0, 3, 'utf-8' );
        $data['value'] = $data['value'] < 0 ? 0 : intval( round( $data['value'] ));
        $data['type'] = in_array($data['type'], $config['currensy']['type']) ? $data['type'] : 'cash';
        $data['status'] = in_array($data['status'], $config['currensy']['status']) ? $data['status'] : 'active';
        return $data;
    }
