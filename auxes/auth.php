<?php
    $keys = [
        'fL1XVQ5CeeyZ6sBcQlgthfoXeZDxqY',
        'CrgFJ2MlXCB1JZXw94kqzg3fZZL1wK',
        'N7Ej1YO2kqFH2FnqNiKA6tm980bwMS',
    ];
    
    function auth(){
        global $keys;
        $key = getallheaders()['API-Key'];
        if ( !in_array(cleanData($key), $keys) ) return false;
        // if ( !in_array(cleanData(getallheaders()['API-Key']), $keys) ) return false;
		return true;
	}