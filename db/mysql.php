<?php
    function connect() { return new mysqli("localhost", "root", "", "finorg"); }

    function select( $props ) {
        $result = array();
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        if ( $data = $conn->query($props['sql']) ) foreach ( $data as $row ) $result[] = $row;
        return $result;
    }

    function update( $props ) {
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        $sql = "UPDATE {$props['q']} SET ";
        foreach ( $props['data'] as $key => $val ) $sql .= "$key = '".cleanData( $val )."', ";
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE " . sprintf("id = '%d'", $props['data']['id']);
        return $conn->query( $sql ) ? $props['data'] : null;
    }

    function insert( $props ) {
        $conn = isset($props['connect']) ? $props['connect'] : connect();
        $sql = "INSERT INTO {$props['q']} (";
        $sqlPart = ") VALUES (";
        foreach ( $props['data'] as $key => $val ) { $sql .= "$key, "; $sqlPart .= "'$val', "; }
        $sql = substr($sql, 0, -2) . substr($sqlPart, 0, -2) . ")";
        $props['data']['id'] = $conn->insert_id;
        return $conn->query( $sql ) ? $props['data'] : null;
    }
