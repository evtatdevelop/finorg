<?php
    function dump($var, $simple = true) {
        echo '<pre>';
            if ($simple)
                print_r($var);
            else
                var_dump($var);
        echo '</pre>';
        return true;
    }

    function keyGen($length = 30){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
		$code = "";
		$clen = strlen($chars) - 1;
		while (strlen($code) < $length) 
			$code .= $chars[mt_rand(0, $clen)];  	

		return $code;
	}