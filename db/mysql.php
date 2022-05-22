<?php

    function connect() { return new mysqli("localhost", "root", "", "finorg"); }

    function select( $props ) {
        $result = array();
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        if ( $data = $conn->query($props['sql']) ) foreach ( $data as $row ) $result[] = $row;
        return $result;
    }
