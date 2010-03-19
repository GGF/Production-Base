<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsProgress {
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : VARIABLE INITIALIZATION                                                                                                                         //
	//                                                                                                                                                                 //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	var $id = "";
	var $percent = 0;
	var $html = "";
	
	static $uid = 0;
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
	//                                                                                                                                                                 //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function __construct($total = 1, $cls = "") {
		
		ob_implicit_flush(true);
		
		if ($cls) $cls = " " . $cls;
		self::$uid++;
		
		$this->id = self::$uid;
		$this->total = $total;
		$this->bar = "<div class='progress{$cls}'><div id='cmsProgress_{$this->id}'></div></div>";
		$this->text = "<div id='cmsProgress_{$this->id}_text'></div>";
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function setCurrent($current, $write = true) {
		
		$percent = $this->total ? $current / $this->total : 0;
		
		if ($write) print "<script> \$('#cmsProgress_{$this->id}_text').html('Ёлемент {$current} из {$this->total} Ч " . ceil($percent * 100) . "% выполнено'); </script>";
		
		$this->setPercent($percent);
		
	}
	
	function setPercent($percent = 0) { // 0 Е 1
		
		$percent = ceil($percent * 100);
		
		if ($this->percent != $percent) {
			
			$this->percent = $percent;
			print "<script> \$('#cmsProgress_{$this->id}').stop().animate({width: '{$this->percent}%'}, 100); </script>";
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
}

?>