<?php
    $keys = [
        'fL1XVQ5CeeyZ6sBcQlgthfoXeZDxqY',
        'CrgFJ2MlXCB1JZXw94kqzg3fZZL1wK',
        'N7Ej1YO2kqFH2FnqNiKA6tm980bwMS',
    ];
    
    function auth($key){
        global $keys;
        if ( !in_array(cleanData($key), $keys) ) return false;  	
		return true;
	}