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
        $props['q'] = 'regulars';
        $regulars = getRegulars( $props );
        $eventsArr = array_merge($events, $regulars);
        usort( $eventsArr, function($a, $b){ return ($a['date'] - $b['date']); });
        return $eventsArr;
    }

    function getOneimeEvents( $props ) {
        $props['data'] = ['id', 'date', 'name', 'description', 'type', 'value', 'status', 'cash', 'mode', 'currency'];
        return select( $props );       
    }

    function getRegulars( $props ) {
        $result = [];
        $monthFrom   = (int) $props['from'];
        $monthTo     = (int) $props['to'];
        // $props['q'] = 'regulars';
        $props['data'] = ['id', 'name', 'code', 'date_from', 'date_to', 'last_date', 'period', 'type', 'value', 'cash', 'description', 'status', 'mode', 'currency'];
        $props['where'] = "status = 'active'";
        unset( $props['from'] );
        unset( $props['to'] );

        foreach ( select( $props ) as $regEvent ) {
           $regEventFrom   = (int) $regEvent['date_from'];
           $regEventTo     = (bool) $regEvent['date_to'] ? (int) $regEvent['date_to'] : null;
           $regEventPeriod = (string) $regEvent['period'];
           $date = $regEventFrom;
           while ( $date <= $monthTo) { 
            if ( $date >= $monthFrom and                                                                    // alredy work
                ($date <= $regEventTo or !$regEventTo)                                                      // work yet 
                // and $regEvent['last_date'] + (getNextDate( $date, $regEventPeriod) - $date) < $date      // ? last_date + period 
                and ( $regEvent['last_date'] == $date
                || $regEvent['last_date'] + (getNextDate( $date, $regEventPeriod) - $date) <= $date )
                ) array_push($result, [
                "id" => $regEvent['id'] .'-'. $date,
                "date" => $date,
                "date_from" => $regEventFrom,
                "date_to" => $regEventTo,
                "name" => $regEvent['name'],
                "description" => $regEvent['description'],
                "type" => $regEvent['type'],
                "value" => $regEvent['value'],
                "currency" => $regEvent['currency'],
                "status" => $regEvent['status'],
                "cash" => $regEvent['cash'],
                "mode" => $regEvent['mode'],
                "period" => $regEvent['period'],
                "last_date" => $regEvent['last_date'],
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



    function setRegulars( $props ) {
        $data = normalizEventData( json_decode( file_get_contents( 'php://input' ), true ) );
        $props['q'] = 'regulars';
        $props['id'] = $data['id'];
        $props['data'] = ['last_date'];
        $props['prev_data'] = select( $props )[0]['last_date'];
        $props['data'] = $data;
        if ( $props['data']['last_date'] != $props['prev_data'] ) {
            // ? Add case when two dates of one reg-event exist
            // ? Add case accepting before reg-event date
            $props['q'] = 'events';
            $props['data']['date'] = time()*1000;
            $props['data']['status'] = 'success';
            unset( $props['data']['id'] );
            unset( $props['data']['date_from'] );
            unset( $props['data']['date_to'] );
            unset( $props['data']['period'] );
            unset( $props['data']['last_date'] );
            unset( $props['data']['code'] );
            insert( $props );
            $props['data'] = $data;
        }        
        $props['q'] = 'regulars';
        return update( $props );
    }

    function addRegulars( $props ) {
        $props['data'] = normalizEventData( json_decode( file_get_contents( 'php://input' ), true ) );
        $props['data']['status'] = 'active';
        return insert( $props );
    }
    
    function dellRegulars( $props ) {
        $props['q'] = 'regulars';
        $props['data'] = ['id', 'name', 'code', 'date_from', 'date_to', 'last_date', 'period', 'type', 'value', 'cash', 'description', 'status', 'mode'];
        $props['data'] = select( $props )[0];
        $props['data']['date_to'] = time()*1000;
        return update( $props );
    }

    function normalizEventData( $data ) {
        // global $config;
        if ( $data['mode'] == 'onetime' ) $data['date'] = (int) $data['date'];    
        elseif ( $data['mode'] == 'regular' ) {
            unset( $data['date'] );
            $data['date_from']  = (int) $data['date_from'];
            $data['date_to']    = (bool) $data['date_to'] ? (int) $data['date_to'] : null;
            $data['period']     = (string) $data['period'];
            $data['last_date']  = (int) $data['last_date'];
            $data['code']  =      keyGen(7);
        }
        $data['name']         = (string) $data['name'];
        $data['type']         = (string) $data['type'];
        $data['value']        = $data['type'] == 'event' ? null : ( (bool) $data['value'] ? cleanData($data['value']) : 0 );
        $data['currency']     = isset($data['currency']) ? mb_substr( mb_strtoupper( rus2translit( $data['currency'] ), 'UTF-8' ), 0, 3, 'utf-8' ) : null;
        $data['cash']         = (bool) $data['cash'] ? (string) $data['cash'] : null;
        $data['description']  = (bool) trim($data['description']) ? (string) trim($data['description']) : null;
        $data['status']       = isset($data['status']) && (bool) trim($data['status']) ? (string) trim($data['status']) : 'active';
        
        return $data;
    }
