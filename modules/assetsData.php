<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getAssrets( $props ) {
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets";
        return select( $props );   
    }

    function getOneAsset( $props ) {
        $id = $props['id'];
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets where id = $id";
        return select( $props ); 
    }
