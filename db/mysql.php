<?php
// TODO: ONE CONNECT !!!
    function connect() { return new mysqli("localhost", "root", "", "finorg"); }

    function select( $props ) {
        $result = array();
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "SELECT ";
        foreach ( $props['data'] as $val ) $sql .= "$val, ";
        $sql = substr($sql, 0, -2);
        $sql .= " FROM ". cleanData( $props['q'] );
        $sql .= isset( $props['id'] ) ? " WHERE " . sprintf("id = '%d'", $props['id']) : '';
        if ( $data = $conn->query($sql) ) foreach ( $data as $row ) $result[] = $row;
        $conn->close();
        return $result;
    }

    function update( $props ) {
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "UPDATE ". cleanData( $props['q'] ) ." SET ";
        foreach ( $props['data'] as $key => $val ) $sql .= "$key = '".cleanData( $val )."', ";
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE " . sprintf("id = '%d'", $props['data']['id']);
        $result = $conn->query( $sql ) ? $props['data'] : null;
        $conn->close();
        return $result;
    }

    function insert( $props ) {
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "INSERT INTO ". cleanData( $props['q'] ) ." (";
        $sqlPart = ") VALUES (";
        foreach ( $props['data'] as $key => $val ) { $sql .= "$key, "; $sqlPart .= "'$val', "; }
        $sql = substr($sql, 0, -2) . substr($sqlPart, 0, -2) . ")";
        $result = $conn->query( $sql ) ? $props['data'] : null;
        $result['id'] = $conn->insert_id;
        $conn->close();
        return $result;
    }

    function delete( $props ) {
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "DELETE FROM ". cleanData( $props['q'] ) ." WHERE " . sprintf("id = '%d'", $props['id']);
        $result = $conn->query( $sql ) ? $props['id'] : null;
        $conn->close();
        return $result;
    }
