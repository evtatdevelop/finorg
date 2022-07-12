<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getEvents( $props ) {       
        $props['data'] = ['id', 'date', 'name', 'description', 'type', 'value', 'status', 'cash', 'mode'];
        $events = select( $props );

        $regulars = [];
        $monthFrom   = (int) $props['from'];
        $monthTo     = (int) $props['to'];
        foreach ( getRegulars() as $regEvent ) {
           $regEventFrom   = (int) $regEvent['date_from'];
           $regEventTo     = (int) $regEvent['date_to'];
           $regEventPeriod = (string) $regEvent['period'];
           $ms = 24*60*60*1000;
           $steps = ['year' => 365 * $ms, 'month' => 30 * $ms, 'week' => 7 * $ms, 'day' => 1 * $ms, ];

           $date = $regEventFrom;

           while ( $date < $monthTo ) { 
            if ( $date >= $monthFrom ) array_push($regulars, [
                    "id" => $regEvent['id'] .'-'. $date,
                    "date" => $date,
                    "name" => $regEvent['name'],
                    "description" => $regEvent['description'],
                    "type" => $regEvent['type'],
                    "value" => $regEvent['value'],
                    "status" => $regEvent['status'],
                    "cash" => $regEvent['cash'],
                    "mode" => $regEvent['mode'],
                ]);
                $date += $steps[$regEventPeriod];
            } ;


        }

        
        // dump($regulars);
        // dump($events);
        return $regulars;

        return $events;
        // return select( $props );
    }

    function getRegulars() {
        $props['q'] = 'regulars';
        $props['data'] = ['id', 'name', 'code', 'date_from', 'date_to', 'period', 'type', 'value', 'cash', 'description', 'status', 'mode'];
        $props['where'] = "status = 'active'";
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
