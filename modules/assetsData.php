<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getAssrets( $props ) {
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets";
        return select( $props );   
    }

    function getOneAsset( $props ) {
        $where = sprintf("id = '%d'", $props['id']);
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets where $where";
        return select( $props ); 
    }

    function setAsset() {
        $props = json_decode( file_get_contents( 'php://input' ), true );
        // $props['method'] = $_SERVER['REQUEST_METHOD'];
        $where = sprintf("id = '%d'", $props['id']);
        
        $currensy = $props['currensy'];
        $status = $props['status'];
        $type = $props['type'];
        $value = $props['value'];
        $time = microtime(true)*1000;
        $props['sql'] = "UPDATE assets SET currensy = '$currensy', status = '$status', type = '$type', value = $value, time = $time where $where";
        update( $props ); 
        return $props;
      }
