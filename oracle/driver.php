<?php

    function connection($mode = null) {
        if (!$mode) {
            global $DB_INST;
            if (isset($DB_INST))
                $mode = $DB_INST;
            else  $mode = 'TEST';
        }
        
        $connect = [
            'TEST' => [
                'dbu' => 'webnsi',
                'dbp' => 'webnsi123',
                'dbo' => 'devapp',
                'dbc' => 'UTF8',
            ],
            'PROD' => [
                'dbu' => 'webnsi',
                'dbp' => 'KJw2y4r972',
                'dbo' => 'prodapp',
                'dbc' => 'UTF8',
            ],
        ];

        $dbc = $connect[$mode];
        return oci_connect($dbc['dbu'], $dbc['dbp'], $dbc['dbo'], $dbc['dbc']);           
    }

    /**
     * Simple select from DB
     */
	function select($props) {       
        $conn = isset($props['conn']) ? $props['conn'] : null;
        $query = isset($props['query']) ? $props['query'] : null;
        
		preg_match_all("/DN__[\w]+/", $query, $defineNames);
		foreach ($defineNames[0] as $key => $name) {
			$defineNames[$key] = explode('__', $name)[1];
		}
        $cntDefineNames = count($defineNames);

        $s = oci_parse($conn, $query);

        for ($i = 0; $i < $cntDefineNames; $i++) {
            $name = $defineNames[$i];
            $lowName = strtolower($name);
            oci_define_by_name($s, "DN__$name", $$lowName);
        }

        $result = array();
        if(!oci_execute($s)) {
            $e = oci_error($s);  echo htmlentities($e['message']) . "<br><br>$query";
            oci_close($conn);
            return null;
        }else{
            while( oci_fetch($s) ){
                $row =  array();
                for ($i = 0; $i < $cntDefineNames; $i++) {
                    $nm = strtolower($defineNames[$i]);
                    $row[$nm] = $$nm;
                }
                array_push($result, $row);
            }	
        }
        return $result;
	}

    /**
     * Functions and procedures executing
     */
    function execute($conn, $function, $props) {
        $function = preg_replace("/\s+/", ' ', trim($function));
        if ($function == '') return null;

        $typeSise = [
            'NUMBER' => '32',
            'VARCHAR2' => '1000',
        ];

        // Parsing function string
        $funcType = 'PROCEDURE';
        $split = explode(" ", $function);
        if ( strtoupper($split[0]) == 'FUNCTION' ) {
            $funcType = 'FUNCTION';
            $typeFuncResult = strtoupper($split[count($split)-1]);
        }
        preg_match("/[0-9a-z,_.]+\(.+\)/s", $function, $main);
        if (!isset($main[0])) return null;
        $mainParts = explode('(', $main[0]);
        $funcStr = trim($mainParts[0]);
        $funcOpt = explode(',', substr($mainParts[1],0,-1));
        $options = array();
        foreach ($funcOpt as $option) {
            $optionParts = explode(' ', trim($option));
            array_push($options, array(
                'name' => trim($optionParts[0]),
                'inout' => trim($optionParts[1]),
                'type' => trim($optionParts[2]),
                'size' => $typeSise[trim($optionParts[2])],
            )); 
        }

        // Generate query string
        $query = 'BEGIN ';
        if ($funcType == 'PROCEDURE') {
            $query .= "$funcStr(";
        } elseif ($funcType == 'FUNCTION') {
            $query .= ":result := $funcStr(";
        }
        $first = true;
        foreach ($options as $option) {
            if (!$first) $query .= ", ";
            else $first = false;           
            $query .= ":".$option['name'];
        }
        $query .= '); END;';

        // $conn = connection();
		$s = oci_parse($conn, $query);

// echo $query;

        // Binding variables
		if ($funcType == 'FUNCTION') {
            oci_bind_by_name($s, ':result', $result, $typeSise[$typeFuncResult]);
        }
        foreach ($options as $option) {
            $bindName = ":".$option['name'];
            oci_bind_by_name($s, $bindName, $props[$option['name']], $option['size']);
        }
 
        if( !oci_execute($s) ){
			$e = oci_error($s); echo "$function <br>". htmlentities($e['message']);
			oci_close($conn);
            return null;
		}

// debug($props);

        if ($funcType == 'PROCEDURE') {
            return $props;
        } elseif ($funcType == 'FUNCTION') {
            return $result;
        }
    }



