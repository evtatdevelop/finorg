<?php
    include_once( './auxes/validation.php' );
  
    /**
     * Adding a new order
     */
    function addNewOrder() {
      $props = json_decode( file_get_contents( 'php://input' ), true );
      // if ( !checkDataSet( $props, ['email', 'company_name', 'branch_name', 'div_name'] )) 
      //   return array('Error:' => 'incomplete data');
      $result = [
        'email' => $props['email'],
        'ad_user' => $props['ad_user'],
        'company_name' => $props['company_name'],
        'branch_name' => $props['branch_name'],
        'div_name' => $props['div_name'],
        'position_name' => $props['position_name'],
        'location' => $props['location'],
        'phone' => $props['phone'],
        'sap_branch_name' => isset($props['sap_branch_name']) ? $props['sap_branch_name'] : null,
        'bossId' => isset($props['bossId']) ? $props['bossId'] : null,
        'additionalUsers' => isset($props['additionalUsers']) ? $props['additionalUsers'] : null,
        'asz00_id' => isset($props['asz00_id']) ? $props['asz00_id'] : null,
        'role' => $props['role'][0]['id'],
      ];
      return $props;
      return $result;
    }