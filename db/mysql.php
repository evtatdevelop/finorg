<?php

    function connect() { return new mysqli("localhost", "root", "", "finorg"); }

    function select( $props ) {
        $result = array();
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        if ( $data = $conn->query($props['sql']) ) {
            $defineNames = explode(', ',trim(explode('from',explode('select',preg_replace("/^([\s]+)|([\s]){2,}/m",' ',preg_replace("/\r\n|\n/",' ',strtolower($props['sql']))))[1])[0]));
            foreach ( $data as $row ) {
                $item = array();
                foreach ($defineNames as $name) $item[$name] = $row[$name];
                $result[] = $item;
            };
        }
        return $result;
    }
