<?php
    require_once( 'db/mysql.php'           );
    include_once( './auxes/validation.php' );

    function getAssrets( $props ) {
        $props['sql'] = "SELECT id, currensy, value, status, type, time FROM assets";
        return select( $props );   
    }
    