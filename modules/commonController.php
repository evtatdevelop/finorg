<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );
    require_once( 'organizerController.php' );
    require_once( 'assetsController.php' );

    function getInstantBalance( $props ) {
        $time = $props['time'];

        $props['q'] = 'assets';
        $props['where'] = "currensy = 'RUB'";
        $assets = getAssrets( $props );
        // return $assets;

        $props['q'] = 'events';
        $props['where'] = "currency = 'RUB' AND date < $time AND status = 'active'";
        $events = getOneTimeEvents( $props );
        // return $events;
        foreach ( $assets as &$asset ) {
            foreach ( $events as $event ) {
                if ( $asset['type'] == $event['cash'] ) {
                    if ( $event['type'] == 'profit' ) $asset['value'] += $event['value'];
                    if ( $event['type'] == 'costs' ) $asset['value'] -= $event['value'];
                }
            }
        }

        $props['q'] = 'regulars';
        $props['where'] = "currency = 'RUB' AND (date_to > $time or date_to is NULL) AND status = 'active'";
        $props['data'] = ['id', 'date_from', 'date_to', 'last_date', 'period', 'type', 'value', 'cash', 'currency'];
        $regulars = select( $props );
        // return $regulars;
        foreach ( $regulars as $regular ) {
            $nextDate = getNextDate($regular['last_date'], $regular['period']);
            while ( $nextDate <= $time ) {
                foreach ( $assets as &$asset ) {
                    if ( $asset['type'] == $regular['cash'] ) {
                        if ( $regular['type'] == 'profit' ) $asset['value'] += $regular['value'];
                        if ( $regular['type'] == 'costs' ) $asset['value'] -= $regular['value'];
                    }
                }
                $nextDate = getNextDate($nextDate, $regular['period']);
            }
        }

        return $assets[0]['value'] + $assets[1]['value'];
    }

