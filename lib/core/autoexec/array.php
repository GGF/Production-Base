<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// http://ru.php.net/manual/en/function.array-replace-recursive.php
	// PHP 5.3
	if (!function_exists('array_replace_recursive')) {
		
		function array_replace_recursive_recurse($array, $array1) {
		
			foreach ($array1 as $key => $value) {
				
				// create new key in $array, if it is empty or not an array
				if (!isset($array[$key]) || (isset($array[$key]) && !is_array($array[$key]))) $array[$key] = array();
				
				// overwrite the value in the base array
				if (is_array($value)) $value = array_replace_recursive_recurse($array[$key], $value);
				
				$array[$key] = $value;
				
			}
			
			return $array;
			
		}
		
		function array_replace_recursive($array, $array1) {
			
			// handle the arguments, merge one by one
			$args = func_get_args();
			$array = $args[0];
			
			if (!is_array($array)) return $array;
			
			for ($i = 1; $i < count($args); $i++) {
				
				if (is_array($args[$i])) {
					
					$array = array_replace_recursive_recurse($array, $args[$i]);
					
				}
				
			}
			
			return $array;
			
		}
		
	}
	
?>