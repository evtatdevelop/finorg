<?php
	function checkDataSet($props, $requiredList) {
		foreach ($requiredList as $item) {
			if ( !array_key_exists($item, $props) 
				or empty($props[$item])
			) return false;
		}
		return true;
	}

	function cleanData($data, $type="s"){
		if (!$data) return null;
		switch ($type) {
			// case "s": return trim(strip_tags($data)); 
			case "s": return trim(htmlspecialchars($data)); 
			case "i": return abs((int)$data);
			
			default: return null;
		}
	}