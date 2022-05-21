<?php
    include_once( 'modules/userData.php' );

    function assets( $props ) {
        // if ( !checkDataSet( $props, ['search'] )) return null;
        // $userMames = getUserNames( $props );
        // return $userMames;
        return [ 
            [ 
                'id' => 0, 
                'currensy'=> 'RUB', 
                'value'=> 1000, 
                'active' => true, 
                'type'=> 'cash', 
                'time' => 1653150656389, 
            ], [ 
                'id'=> 1, 
                'currensy'=> 'RUB', 
                'value'=> 100000, 
                'active' => true, 
                'type'=> 'card', 
                'time' => 1653150656389, 
            ], [ 
                'id'=> 2, 
                'currensy'=> 'THB', 
                'value'=> 26352, 
                'active' => true, 
                'type'=> 'cash', 
                'time' => 1653150656389, 
            ], [ 
                'id'=> 3, 
                'currensy'=> 'THB', 
                'value'=> 243, 
                'active' => true, 
                'type'=> 'card', 
                'time' => 1653150656389, 
            ], [
                'id' => 4, 
                'currensy' => 'CNY', 
                'value' => 5000, 
                'active' => true, 
                'type'=> 'cash', 
                'time' => 1653150656389, 
            ], [
                'id' => 5, 
                'currensy' => 'CNY', 
                'value' => 5000, 
                'active' => false, 
                'type'=> 'card', 
                'time' => 1653150656389, 
            ], 
        ];
    }
