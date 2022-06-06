<?php
    function auth() {
        global $config;
        $key = getallheaders()['API-Key'];
        if ( !in_array(cleanData($key), $config['keys']) ) return false;
        return true;
	}