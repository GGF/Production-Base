<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsMail {

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : VARIABLE INITIALIZATION                                                                                                                         //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	var $contentType	= "";
	var $encoding			= "";
	var $boundary			= "";
	
	var $headers			= "";
	var $subject			= "";
	var $content			= "";
	
	var $sent					= false;
	var $attaches			= array();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsMail($html = false) {
		
		$contentType = ($html) ? 'text/html' : 'text/plain'; 
		
		$this->contentType	= $contentType;
		$this->encoding			= $_SERVER[cmsEncoding];
		$this->boundary			= substr(md5(uniqid(rand(), true)), 0, 16); 
		
		$this->headers = "";
		$this->headers .= "MIME-Version: 1.0\n";
		$this->headers .= "X-Mailer: Osmio cmsMail\n";
		
		$this->address('From', 'robot@' . $_SERVER[HTTP_HOST], cmsLang_var("mail.robot"));
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   M E T H O D S                                                                                                                                                 //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function address($type, $address, $string) {
		
		$string = $this->encode64($string);
		
		$this->headers .= $type . ": \"" . $string . "\" <" . $address . ">\n";
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function encode64($string) {
		
		return "=?" . $this->encoding . "?B?" . base64_encode($string) . "?=";
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function subject($subject) { 
		
		$this->subject = $subject;
		
	}

	function content($content, $css = '/core/css/mail.css') { // '/core/css/style.php?plain=true'
		
		$css = ($this->contentType == "text/html") ? cmsFile_read($css) : "";
		//$css = cmsFile_download("http://{$_SERVER[HTTP_HOST]}{$css}", false);
		
		// content
		
		if ($this->contentType == 'text/html') {
			
			$template = array(
				"css"				=> $css,
				"encoding"	=> $this->encoding,
				"title"			=> $this->subject,
				"host"			=> $_SERVER[HTTP_HOST],
				"signature"	=> cmsFile_touch($_SERVER[TEMPLATES] . "/mail/_signature_html.php") ? "<br>--<br><br>" . cmsTemplate("/mail/_signature_html.php") : "",
				"content"		=> $content,
			);
			
			$this->content = cmsTemplate("/core/templates/mail.php", $template, true);
			
		} else {
			
			$signature = cmsFile_touch($_SERVER[TEMPLATES] . "/mail/_signature_plain.php") ? "\n\n--\n\n" . cmsTemplate("/mail/_signature_plain.php") : "";
			
			$this->content .= $content . $signature;
			
		}
		
		$this->content = chunk_split(base64_encode(tokens::parse($this->content, array("multiPass" => true))));
		
		// attaches
		
		if (count($this->attaches)) {
			
			$content = $this->content;
			$this->content = "";
			
			$this->headers .= "Content-Type: multipart/mixed; boundary=\"{$this->boundary}\"";
			
			$this->content .= "--{$this->boundary}\n";
			$this->content .= "Content-Transfer-Encoding: base64\n";
			$this->content .= "Content-type: " . $this->contentType . "; charset=\"" . $this->encoding . "\"\n";
			$this->content .= "\n";
			$this->content .= $content;
			$this->content .= "\n";
			
			foreach ($this->attaches as $attach) {
				
				$this->content .= "--{$this->boundary}\n";
				$this->content .= $attach[content];
				
			}
			
			$this->sBody .= "--{$this->boundary}--";
			
		} else {
			
			$this->headers .= "Content-Disposition: inline\n";
			$this->headers .= "Content-Transfer-Encoding: base64\n";
			$this->headers .= "Content-type: " . $this->contentType . "; charset=\"" . $this->encoding . "\"";
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function attach($content, $name, $type) {
		
		$attach = array(
			"name"		=> $name,
			"type"		=> $type,
			"content"	=> array(),
		);
		
		$attach[content][] = "Content-Type: {$type}; name=\"" . $this->encode64($name) . "\"";
		$attach[content][] = "Content-Disposition: attachment; filename=\"" . $this->encode64($name) . "\"";
		$attach[content][] = "Content-Transfer-Encoding: base64";
		$attach[content][] = "";
		$attach[content][] = chunk_split(base64_encode($content)) . "\n";
		$attach[content] = implode("\n", $attach[content]);
		
		$this->attaches[] = $attach;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function send($address) {
		
		if (is_array($address)) $address = implode(", ", $address);
		
		if ($address) {
			
			if (mail($address, $this->encode64($this->subject), $this->content, $this->headers)) {
				
				$this->sent = true;
				
			} else {
				
				$this->sent = false;
				cmsError("Скрипт не смог отправить письмо по адресу «{$address}»");
				
			}
			
		}
		
		return $this->sent;
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function mailSend($address) { $this->send($address); }
	function mailContent($subject, $content, $css = '/core/css/mail.css') { $this->subject($subject); $this->content($content, $css); }
	function mailHeaders($type, $address, $string) { $this->address($type, $address, $string); }
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
}

?>