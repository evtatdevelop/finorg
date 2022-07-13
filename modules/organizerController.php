<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );

    function getNextDate( $date, $period ) {
        date_default_timezone_set('Asia/Bangkok');
        $dateArr = getdate($date / 1000);
        $year   = $dateArr['year'];
        $month  = $dateArr['mon'];
        $day    = $dateArr['mday'];
        switch ( $period ) {
            case 'year':    $year  += 1; break;
            case 'month':   $month += 1; break;
            case 'week':    $day   += 7; break;
            case 'day':     $day   += 1; break;
            default: $day += (int) $period;
        }
        return mktime( $dateArr['hours'], $dateArr['minutes'], $dateArr['seconds'], $month, $day, $year ) * 1000;
    }

    function getEvents( $props ) {
        $events = getOneimeEvents( $props );
        $regulars = getRegulars( $props );
        $eventsArr = array_merge($events, $regulars);
        usort( $eventsArr, function($a, $b){ return ($a['date'] - $b['date']); });
        return $eventsArr;
    }

    function getOneimeEvents( $props ) {
        $props['data'] = ['id', 'date', 'name', 'description', 'type', 'value', 'status', 'cash', 'mode'];
        return select( $props );       
    }

    function getRegulars( $props ) {
        $result = [];
        $monthFrom   = (int) $props['from'];
        $monthTo     = (int) $props['to'];
        $props['q'] = 'regulars';
        $props['data'] = ['id', 'name', 'code', 'date_from', 'date_to', 'period', 'type', 'value', 'cash', 'description', 'status', 'mode'];
        $props['where'] = "status = 'active'";
        unset( $props['from'] );
        unset( $props['to'] );

        foreach ( select( $props ) as $regEvent ) {
           $regEventFrom   = (int) $regEvent['date_from'];
           $regEventTo     = (int) $regEvent['date_to'];
           $regEventPeriod = (string) $regEvent['period'];
           $date = $regEventFrom;
           while ( $date <= $monthTo ) { 
            if ( $date >= $monthFrom ) array_push($result, [
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
            $date = getNextDate( $date, $regEventPeriod);
            }
        }
        return $result;
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
