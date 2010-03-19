<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	DEFINE("CMSXML_PREFIX", "list.");
	DEFINE("CMSXML_RAW", true);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsXML_name($name) {
		
		return (mb_substr($name, 0, mb_strlen(CMSXML_PREFIX)) == CMSXML_PREFIX) ? mb_substr($name, mb_strlen(CMSXML_PREFIX)) : $name;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsXML_syntax($subject) {
		$subject = nl2br(str_replace(" ", "&nbsp;", htmlSpecialChars($subject)));
		return preg_replace('/&lt;([-\/= "\'\\w]+)&gt;/', "<span class='xmltag'>&lt;\\1&gt;</span>", $subject);
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsXML($data, $raw = false) {
		
		if ($data) {
			if (is_array($data)) return trim(cmsXML_array2xml_init($data, $raw)); else {
				$array = cmsXML_xml2array_init($data, $raw);
				return $array;
			}
		} else return false;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsXML_xml2array_init($data, $raw = false) {
		
		$xml = new DOMDocument('1.0');
		$xml->loadXML($data); //, LIBXML_NOERROR
		$xml->encoding = $_SERVER[cmsEncoding];
		
		if ($xml) {
			
			$return = cmsXML_xml2array($xml);
			$return = ($raw) ? $return : $return[xmlArray];
			
			return ($_SERVER[cmsEncoding] != "UTF-8") ? cmsUTF_decode($return) : $return;
			
		} else return array("failed");
		
	}
	
	function cmsXML_xml2array($domnode) {
		
		$res = array();
		$original = "";
		$i = 0;
		
		$domnode = $domnode->firstChild;
		
		while (!is_null($domnode)) {
			
			// CDATA
			
			if ($domnode->nodeType == XML_CDATA_SECTION_NODE) {
				
				$res = $domnode->data;
				
			}
			
			// TEXT INSIDE TAG
			
			if ($domnode->nodeType == XML_TEXT_NODE) {
				
				if(trim($domnode->nodeValue)) $res = $domnode->nodeValue;
				
			}
			
			// TAG
			
			if ($domnode->nodeType == XML_ELEMENT_NODE) {
				
				// TAG-ATTRIBUTES
				$name = cmsXML_name($domnode->nodeName);
				
				if (isset($res[$name])) { $i++; $name .= ".{$i}"; } // if exists, same tag was already parsed e.g. p, then some more p
				
				if ($domnode->hasAttributes()) {
					
					if (!$res[$name]) $res[$name] = array();
					
					foreach ($attributes = $domnode->attributes as $index => $attr) {
						
						$res[$name][cmsXML_name($attr->name)] = $attr->value;
						
					}
					
				}
				
				// TAG-INNER
				
				if ($domnode->hasChildNodes()) {
					
					$inner = cmsXML_xml2array($domnode);
					
					//if (is_array($inner)) $res[$name] += $inner; else $res[$name] = $inner;
					if (is_array($inner) && is_array($res[$name])) $res[$name] += $inner; else $res[$name] = $inner;
					
				}
				
			}
			
			$domnode = $domnode->nextSibling;
			
		}
		
		return $res;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsXML_array2xml_init($array, $raw = false) {
		
		if ($_SERVER[cmsEncoding] != "UTF-8") $array = cmsUTF_encode($array);
		
		$xml  = new DOMDocument('1.0');
		
		$xml->encoding = $_SERVER[cmsEncoding];
		$xml->formatOutput = true;
		
		$root = $xml->appendChild($xml->createElement('xmlArray'));
		//$root = $xml->documentElement; // raw!!!
		
		cmsXML_array2xml($array, $xml, $root);
		
		return $xml->saveXML();		
		
	}
	
	function cmsXML_array2xml($array, &$xml, $parent) {
		
		foreach ($array as $k => $v) {
			
			if (is_integer($k)) $k = CMSXML_PREFIX . $k; // tag can't be only a digit
			
			$html = (is_string($v)) ? preg_match('/[<>]/', $v) : false;
			
			if (is_array($v) || $html) {
				
				$element = $xml->createElement($k);
				$node = $parent->appendChild($element);
				
				$inner = ($html) ? $xml->createCDATASection($v) : cmsXML_array2xml($v, $xml, $node);
				
				if ($inner) $node->appendChild($inner);
				
			} else {
				
				$node = false;
				$parent->setAttribute($k, $v);
				
			}
			
		}
		
		return $node;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>