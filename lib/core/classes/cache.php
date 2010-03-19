<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

class cmsCache {
	
	/**
	 *	@var ���������� URL ����� � �����
	 */
	protected $url = "";
	/**
	 *	@var �������� ������ ���� ����� � �����
	 */
	protected $file = "";
	/**
	 *	@var ������ ������ ��� ������
	 */
	protected $list = array();
	/**
	 *	@var ��� ������
	 */
	protected $type = "";
	/**
	 *	@var ������ ����� � ������� ���� �� ������������
	 */
	protected $options = array();
	/**
	 *	@var ���� ���������� ����
	 */
	protected $dateCache	= false;
	/**
	 *	@var �������� ���� ���������� �������������� ������ �� ������
	 */
	protected $dateActual	= false;
	
	
	protected function pathAbs($file) {
		
		return $_SERVER['DOCUMENT_ROOT'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
		
	}
	
	protected function pathRel($file) {
		
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
		
	}
	
	
	
	
	public static function build($file, array $list, $type = 'js', $options = array()) {
		
		$cache = new self($file, $list, $type, $options);
		
		return $cache->url;
		
	}
	
	public static function buildPHP($file, array $list, $options = array()) {
		
		$file = $file = $_SERVER['SYSCACHE'] . '/autoexec_' . $file . '.php';
		
		return self::build($file, $list, 'php', $options);
		
	}
	
	public static function buildScript($list, $type, $options = array()) {
		
		$file = $_SERVER['SYSCACHE'] . '/autoexec_' . md5(implode($list)) . "." . $type;
		
		// ���� ���������� ����� PAGE � ������ ��� � ���������� ������, �.�. �� ����� ����� ��� ���������������� ����
		// TODO ���������� ������ ��� �����, �� ��� ������ ������ ��-��������
		if (class_exists('page')) page::$assets[$type] = $list;
		
		return self::build($file, $list, $type, $options);
		
	}
	
	
	
	
	public function __construct($file, $list, $type, $options = array()) {
		
		$this->file			= $this->pathAbs($file);
		$this->url			= $this->pathRel($this->file);
		$this->list			= $list;
		$this->type			= $type;
		$this->options	= $options;
		
		// �������� �� ����� ������ � ������� ������ ������, � ����� ������ ������ ����
		foreach ($this->list as $k => $f) {
			
			if ($f) {
				
				$this->list[$k] = $this->pathAbs($f);
				
			} else unset($this->list[$k]);
			
		}
		
		// ��������� ���������� ����, � ���� �� ������ ���������������
		if (!$this->checkValid()) $this->rebuild();
		
	}
	
	public function checkValid() {
		
		if (!$_SERVER['modCache']['assets']['noValidate']) {
			
			// ���� �� ����� ���� noValidate ���� �������� �� ����� ������� � �������, ���������� �� �����, � � ������ ��� ������
			
			$this->dateCache	= @fileMTime($this->file); // ������ �.�. ����� ����� � �� ����
			$this->dateActual	= 0;
			
			// �������� �� ������ � ������� ����, ���������� ������� �������� ���� ��������� ���������
			foreach ($this->list as $f) {
				
				$date = @fileMTime($f);
				if ($date > $this->dateActual) $this->dateActual = $date;
				
			}
			
			return ($this->dateActual && $this->dateActual <= $this->dateCache);
			
		} else {
			
			// ���� ����� ���� noValidate ���� ������ �������, ���������� �� ���� � �����, �� �������� ��� ����������
			return file_exists($this->file);
			
		}
		
	}
	
	
	public function rebuild() {
		
		if ($this->type == 'php') {
			
			$contents = array();
			
			// �������� �� ������ ������ � ���������� ��� � ���� ������� �����
			// TOTO ����������� �?php � ������ ����� ��� ������������ ����
			foreach ($this->list as $f) {
				
				if (file_exists($f)) $contents[] = trim(file_get_contents($f));
				
			}
			
			$contents = implode('', $contents);
			
		} else {
			
			if(!class_exists('Minify')) {
				
				require($_SERVER['CORE'] . '/contrib/minify/config.php');
				set_include_path($min_libPath . PATH_SEPARATOR . get_include_path());
				require('Minify.php');
				
			}
			
			$contents = Minify::combine($this->list);
			
		}
		
		// ���������� ������ � ����
		file_put_contents($this->file, $contents);
		
		// ������������� ����
		@touch($this->file, $this->dateActual);
		
	}
	
}

?>