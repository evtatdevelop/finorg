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

    function setAsset() {
        $props = json_decode( file_get_contents( 'php://input' ), true );
        // if ( !checkDataSet( $props, ['email', 'company_name', 'branch_name', 'div_name'] )) 
        //   return array('Error:' => 'incomplete data');
        // $result = [
        //   'email' => $props['email'],

        // ];
        $props['myStatus'] = 'fine!!!';
        return $props;
        // return $result;
      }
