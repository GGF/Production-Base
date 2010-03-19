<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsTemplate_print($file, &$template=false, $abs = false) {
		
		print cmsTemplate($file, $template, $abs);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsTemplate($file, &$template = false, $abs = false) {
		
		$path = (!$abs) ? $_SERVER[TEMPLATES] . $file : cmsFile_path($file);
    
		// восстанавливаем переменную, с сохранением ссылки, естественно
    if (is_array($template) && $template[formObject]) $form = &$template[formObject];		
		
		if (cmsFile_touch($path)) {
			
			ob_start();
			REQUIRE $path;
			$return = ob_get_clean();
			
			return tokens::parseTemplate($return, $template);
			
		} else {
			
			ob_start();
			cmsError("Шаблон <b>«" . $file . "»</b> не найден.");
			return ob_get_clean();
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsTemplate_formReturn($file, &$form, $template=false, $abs = false) {
		
		$template[formObject] = &$form;
		return cmsTemplate($file, $template, $abs);
		
	}
	
	function cmsTemplate_form($file, &$form, $template=false, $abs = false) {
		
		$template[formObject] = &$form;
		return cmsTemplate_print($file, $template, $abs);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsTemplate_var($var, $template) {
		
		foreach ($template as $k => $v) {
			
			$var = str_replace("%template." . $k . "%", $v, $var);
			
		}
		
		return $var;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>