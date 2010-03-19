<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsImg {

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : VARIABLE INITIALIZATION                                                                                                                         //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	var $width;
	var $height;
	
	var $type;
	var $typeReal;
	
	var $image;
	var $temp;
	
	var $method = false;
	var $color = array();
	
	var $errors = array();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function cmsImg($path, $absPath = false) {
		
		$types = array(
			1 => 'gif',
			2 => 'jpg',
			3 => 'png',
			6 => 'bmp'
		);
		
		if (!$absPath) $path = cmsFile_path($path);
		
		if (!is_file($path)) { $this->errors[] = "Файл не найден"; return false; }
		
		$this->name = cmsFile_name($path);
		
		$properties = getImageSize($path) or die("Невозможно получить информацию о файле");
		
		$this->type = mb_strtolower(cmsFile_name($path, 'ext'));
		$this->typeReal = $types[$properties[2]];
		$this->width  = $properties[0];
		$this->height = $properties[1];
		
		if ($this->typeReal == 'jpg') $this->image = imageCreateFromJPEG($path);
		if ($this->typeReal == 'gif') $this->image = imageCreateFromGIF($path);
		if ($this->typeReal == 'png') $this->image = imageCreateFromPNG($path);
		
		if (!$this->image) { $this->errors[] = "Формат файла не поддерживается"; return false; }
		
		imageAlphaBlending($this->image, false);
		imageSaveAlpha($this->image, true);
		
		return true;
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   M E T H O D S                                                                                                                                                 //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function error($text) {
		
		return $text;
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function palette(&$image, $colors = 256) {
		
		$width = imagesx( $image );
		$height = imagesy( $image );
		
		$colors_handle = ImageCreateTrueColor( $width, $height );
		
		ImageCopyMerge( $colors_handle, $image, 0, 0, 0, 0, $width, $height, 100 );
		ImageTrueColorToPalette( $image, true, $colors );
		ImageColorMatch( $colors_handle, $image );
		ImageDestroy( $colors_handle );
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function rotate($angle) { // clockwise
		
		$this->image = imageRotate($this->image, -1 * $angle, -1);
		$this->width = imageSX($this->image);
		$this->height = imageSY($this->image);
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function watermark($place) {
		
		$path = $_SERVER[project][dir] . "/watermark.png";
		
		$watermark = new cmsImg($path);
		$watermark->scale($this->width, $this->height, 'fit-x');
		
		$dstX = 0;
		$dstY = 0;
		
		if ($place != "C") {
			
			$place = preg_split('//', $place, -1, PREG_SPLIT_NO_EMPTY);
			
			if ($place[0] == "L" || $place[1] == "L") $dstX = 0;
			if ($place[0] == "R" || $place[1] == "R") $dstX = $this->width - $watermark->width;
			if ($place[0] == "T" || $place[1] == "T") $dstY = 0;
			if ($place[0] == "B" || $place[1] == "B") $dstY = $this->height - $watermark->height;
			
		} else {
			
			$dstX = ($this->width  - $watermark->width) / 2;
			$dstY = ($this->height - $watermark->height) / 2;
			
		}
		
		imageAlphaBlending($this->image, true);
		imageCopy($this->image, $watermark->image, $dstX, $dstY, 0, 0, $watermark->width, $watermark->height);
		imageAlphaBlending($this->image, false);
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function scale($width = '100', $height = '100', $method = 'fit', $forceColor = false) {
		
		$this->method = $method;
		
		$srcLeft		= 0;
		$srcTop			= 0;
		$dstLeft		= 0;
		$dstTop			= 0;
		$srcWidth		= $this->width;
		$srcHeight	= $this->height;
		
		if ($width >= $this->width && $height >= $this->height) { // не меняем размер, изображение по центру
			
			$dstWidth  = $this->width;
			$dstHeight = $this->height;
			
			if ($method == 'fit-x') {
				
				$width		= $dstWidth;
				$height		= $dstHeight;
				
			}
			
			$dstLeft   = floor(($width - $dstWidth) / 2);
			$dstTop    = floor(($height - $dstHeight) / 2);
			
		} else {
			
			// -------------------------------------------- //
			// METHOD : FIT                                 //
			// -------------------------------------------- //
			
			if ($method == 'fit') { // вписать в область WxH изнутри
				
				if (($width / $this->width) > ($height / $this->height)) { 
					
					$dstWidth  = floor($this->width * $height / $this->height);
					$dstHeight = $height;
					$dstLeft   = floor(($width - $dstWidth) / 2);
					$dstTop    = 0;
					
				} else {
					
					$dstWidth  = $width;
					$dstHeight = floor($this->height * $width / $this->width);
					$dstLeft   = 0;
					$dstTop    = floor(($height - $dstHeight) / 2);
					
				}
				
			}
			
			// -------------------------------------------- //
			// METHOD : FIT                                 //
			// -------------------------------------------- //
			
			if ($method == 'fit-x') { // вписать в область WxH изнутри
				
				if (($width / $this->width) > ($height / $this->height)) { 
					
					$dstWidth  = floor($this->width * $height / $this->height);
					$dstHeight = $height;
					
				} else {
					
					$dstWidth  = $width;
					$dstHeight = floor($this->height * $width / $this->width);
					
				}
				
				$dstLeft	= 0;
				$dstTop		= 0;
				$width		= $dstWidth;
				$height		= $dstHeight;
				
			}
			
			// -------------------------------------------- //
			// METHOD : CROP                                //
			// -------------------------------------------- //
			
			if ($method == 'crop') { // вписать в область WxH снаружи
				
				if (($width / $this->width) > ($height / $this->height)) { // height
					
					$srcWidth  = $this->width;
					$srcHeight = $this->width * $height / $width;
					$srcLeft   = 0;
					$srcTop    = floor(($this->height - $srcHeight) / 2);
					
				} else { // width
					
					$srcWidth  = $this->height * $width / $height;
					$srcHeight = $this->height;
					$srcLeft   = floor(($this->width - $srcWidth) / 2);
					$srcTop    = 0;
					
				}
				
				$dstWidth = $width;
				$dstHeight = $height;
				
			}
			
			// -------------------------------------------- //
			// METHOD : FIT-WIDTH                           //
			// -------------------------------------------- //
			
			if ($method == 'fit-width') { // сжать до определенной ширины
				
				if ($this->width > $width) {
					
					$scale = $width / $this->width;
					$dstWidth  = $width;
					$dstHeight = $this->height * $scale;
					
				} else {
					
					$dstWidth  = $this->width;
					$dstHeight = $this->height;
					$dstLeft   = floor(($width - $this->width) / 2);
					
				}
				
				$width = $dstWidth;
				$height = $dstHeight;
				
			}
			
			// -------------------------------------------- //
			// METHOD : FIT-HEIGHT                          //
			// -------------------------------------------- //
			
			if ($method == 'fit-height') { // сжать до определенной высоты
				
				if ($this->height > $height) {
					
					$scale = $height / $this->height;
					$dstWidth  = $this->width * $scale;
					$dstHeight = $height;
					
				} else {
					
					$dstWidth  = $this->width;
					$dstHeight = $this->height;
					$dstTop    = floor(($height - $this->height) / 2);
					
				}
				
				$width = $dstWidth;
				$height = $dstHeight;
				
			}
			
		}
		
		// RESIZING
		
		$temp = imageCreateTrueColor($width, $height);
		
		/*
		if (($this->typeReal != 'png' || $forceColor) && $method == 'fit') {
			
			if (mb_strlen($forceColor) != 7) $forceColor = "#FF00FF";
			$r = base_convert(mb_substr($forceColor, 1, 2), 16, 10);
			$g = base_convert(mb_substr($forceColor, 3, 2), 16, 10);
			$b = base_convert(mb_substr($forceColor, 5, 2), 16, 10);
			
			$trans = imageColorTransparent($temp, imageColorAllocate($temp, $r, $g, $b));
			imageFill($temp, 0, 0, $trans);
			
		} else {
		*/
			
			imageAlphaBlending($temp, false);
			imageSaveAlpha($temp, true);
			
			$patch = imageCreateFromPNG($_SERVER[DOCUMENT_ROOT] . "/admin/ui/free.png");
			imageCopyResampled($temp, $patch, 0, 0, 0, 0, $width, $height, 1, 1);
			
		/*
		}
		*/
		
		imageCopyResampled($temp, $this->image, $dstLeft, $dstTop, $srcLeft, $srcTop, $dstWidth, $dstHeight, $srcWidth, $srcHeight);
		
		//if (($this->typeReal != 'png' || $forceColor) && $method == 'fit') imageTrueColorToPalette($temp, true, 256);
		
		$this->image = $temp;
		$this->width = imageSX($this->image);
		$this->height = imageSY($this->image);
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

	function out() {
		
		header("Content-type: image/png");
		imagePNG($this->image);
		
	}
	
	function save($path, $override = false) {
		
		$path = cmsFile_path($path);
		
		$type = ($override) ? $override : mb_strtolower(cmsFile_name($path, 'ext'));
		$type = ($this->method == 'fit') ? 'png' : $type;
		
		if ($type == 'jpg') imageJPEG($this->image, $path, 90); 
		if ($type == 'gif') imageGIF ($this->image, $path); 
		if ($type == 'png') imagePNG ($this->image, $path); 
		
	}

	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//

}

?>