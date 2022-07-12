<?php
// TODO: ONE CONNECT !!!
    function connect() { return new mysqli("localhost", "root", "", "finorg"); }

    function getSqlStr( $val ) {
        switch ( gettype($val) ) {
            case "string":   $sqlStr = "'". cleanData( $val ) ."'";   break;
            case "NULL":     $sqlStr = "NULL";                        break;
            case "integer":  $sqlStr = cleanData( $val );             break;
            default:         $sqlStr = "'". cleanData( $val ) ."'";   break;
        }
        return $sqlStr;
    }

    function select( $props ) {
        $result = array();
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "SELECT ";
        foreach ( $props['data'] as $val ) $sql .= "$val, ";
        $sql = substr($sql, 0, -2);
        $sql .= " FROM ". cleanData( $props['q'] );

        $where = '';
        if ( isset( $props['id'] ) 
            or (isset( $props['from'] ) and isset( $props['to'] ))
            or isset( $props['where'] )
        ) {
            $where .= " WHERE ";
            if ( isset( $props['id'] )) $where .=  sprintf("id = '%d'", $props['id']);
            if ( isset( $props['where'] )) $where .=  " {$props['where'] }";
            if (isset( $props['from'] ) and isset( $props['to'] )) $where .=  "date BETWEEN " . sprintf("%d", $props['from']) . " AND " . sprintf("%d", $props['to']) . " ORDER BY date";
        }
        $sql .= $where;



        if ( $data = $conn->query($sql) ) foreach ( $data as $row ) $result[] = $row;
        $conn->close();
        // $props['sql'] = $sql;
        // return $props;
        return $result;
    }

    function update( $props ) {
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "UPDATE ". cleanData( $props['q'] ) ." SET ";
        foreach ( $props['data'] as $key => $val ) $sql .= "$key = ". getSqlStr( $val ) .", ";
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE " . sprintf("id = '%d'", $props['data']['id']);
        $result = $conn->query( $sql ) ? $props['data'] : null;
        $conn->close();
        // $props['sql'] = $sql;
        // return $props;
        return $result;
    }

    function insert( $props ) {
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "INSERT INTO ". cleanData( $props['q'] ) ." (";
        $sqlPart = ") VALUES (";
        foreach ( $props['data'] as $key => $val ) { 
            $sql .= "$key, "; 
            $sqlPart .= getSqlStr( $val ) . ", ";
        }
        $sql = substr($sql, 0, -2) . substr($sqlPart, 0, -2) . ")";
        $result = $conn->query( $sql ) ? $props['data'] : null;
        $result['id'] = $conn->insert_id;
        $conn->close();
        // $props['sql'] = $sql;
        // return $props;
        return $result;
    }

    function delete( $props ) {
        $conn = isset( $props['connect'] ) ? $props['connect'] : connect();
        $sql = "DELETE FROM ". cleanData( $props['q'] ) ." WHERE " . sprintf("id = '%d'", $props['id']);
        $result = $conn->query( $sql ) ? $props['id'] : null;
        $conn->close();
        return $result;
    }
