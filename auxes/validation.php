<?php
	function checkDataSet( $props, $requiredList ) {
		foreach ( $requiredList as $item ) {
			if ( !array_key_exists($item, $props) 
				or empty($props[$item])
			) return false;
		}
		return true;
	}

	function cleanData( $data ) {
		if ( !$data ) return null;
		if ( ctype_digit( (string)$data )) return abs( (int)$data );
		else return trim( htmlspecialchars( strip_tags( $data )));
	}