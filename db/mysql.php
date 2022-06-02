<?php
    function connect() { return new mysqli("localhost", "root", "", "finorg"); }

    function select( $props ) {
        $result = array();
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        if ( $data = $conn->query($props['sql']) ) foreach ( $data as $row ) $result[] = $row;
        // $result['method'] = $_SERVER['REQUEST_METHOD'];
        return $result;
    }

    function update( $props ) {
        $result = array();
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        $data = $conn->query($props['sql']);
        return $data;
    }
