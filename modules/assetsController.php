<?php
    require_once( 'db/mysql.php'           );
    require_once( './auxes/validation.php' );
    require_once( 'organizerController.php' );

    function getAssrets( $props ) {       
        $props['data'] = ['id', 'currensy', 'value', 'status', 'type', 'time'];
        $props['order'] = "queue";
        $assets = select( $props );
        
        // dump($props);

        $props['q'] = 'events';
        unset( $props['order'] );
        unset( $props['where'] );
        $events = getOneTimeEvents( $props );
        // dump($events);

        foreach ( $assets as &$asset ) {
            foreach ( $events as $event ) {
                if ( $event['date'] >= $asset['time'] and $event['date'] <= time()*1000 
                    and $event['status'] == 'success'
                    and $event['currency'] == $asset['currensy']
                    and $event['cash'] == $asset['type']
                ) {
                    if ( $event['type'] == 'costs' ) $asset['value'] -= $event['value'];
                    if ( $event['type'] == 'profit' ) $asset['value'] += $event['value'];
                }
            }
        }

        return $assets; 
    }

    function getOneAsset( $props ) {
        $props['data'] = ['id', 'currensy', 'value', 'status', 'type', 'time'];

        // dump($props);
        return select( $props )[0]; 
    }

    function setAsset( $props ) {
        $props['data'] = normalizAssetsData( json_decode( file_get_contents( 'php://input' ), true ) );
        return update( $props );
    }
    
    function addAsset( $props ) {
        $props['data'] = normalizAssetsData( json_decode( file_get_contents( 'php://input' ), true ) );
        return insert( $props );
    }
    
    function dellAsset( $props ) {
        return delete( $props );
    }

    function normalizAssetsData( $data ) {
        global $config;
        $data['time'] = round(microtime(true) * 1000);
        $data['currensy'] = mb_substr( mb_strtoupper( rus2translit( $data['currensy'] ), 'UTF-8' ), 0, 3, 'utf-8' );
        $data['value'] = $data['value'] < 0 ? 0 : intval( round( $data['value'] ));
        $data['type'] = in_array($data['type'], $config['currensy']['type']) ? $data['type'] : 'cash';
        $data['status'] = !empty($data['status']) ? $data['status'] : 'active';
        return $data;
    }
