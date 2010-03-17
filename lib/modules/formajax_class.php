<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

define("CMSFORM_TYPE_CODE",					"code");
define("CMSFORM_TYPE_HIDDEN",				"hidden");
define("CMSFORM_TYPE_TEXT",					"text");
define("CMSFORM_TYPE_PASSWORD",			"password");
define("CMSFORM_TYPE_TEXTAREA",			"textarea");
define("CMSFORM_TYPE_BUTTON",				"button");
define("CMSFORM_TYPE_SUBMIT",				"submit");
define("CMSFORM_TYPE_RESET",				"reset");
define("CMSFORM_TYPE_IMAGE",				"image");
define("CMSFORM_TYPE_CHECKBOX",			"checkbox");
define("CMSFORM_TYPE_CHECKBOXES",		"checkboxes");
define("CMSFORM_TYPE_RADIO",				"radio");
define("CMSFORM_TYPE_SELECT",				"select");
define("CMSFORM_TYPE_FILE",					"file");
define("CMSFORM_TYPE_DATE",					"date");

define("CMSFORM_ERROR",							"error"); // general error
define("CMSFORM_ERROR_CHECK",				"check");
define("CMSFORM_ERROR_FORMAT",			"format");
define("CMSFORM_ERROR_OBLIGATORY",	"obligatory");
define("CMSFORM_ERROR_CONFIRM",			"confirm");
define("CMSFORM_ERROR_LENGTH",			"length");
define("CMSFORM_ERROR_CUSTOM",			"custom");	// ����������� ��� ��� ������ �� ��������� ������ �� �����, � ��������������
define("CMSFORM_ERROR_CRITICAL",		"critical");

define("CMSFORM_CHECK_CUSTOM",			"check_custom");
define("CMSFORM_CHECK_DEFAULT",			"check_default");
define("CMSFORM_CHECK_NUMERIC",			"check_numeric");
define("CMSFORM_CHECK_LOGIN",				"check_login");
define("CMSFORM_CHECK_CODE",				"check_code");
define("CMSFORM_CHECK_PHONE",				"check_phone");
define("CMSFORM_CHECK_MAIL",				"check_mail");
define("CMSFORM_CHECK_URL",					"check_url");

define("CMSFORM_FORMAT_CUSTOM",			"format_custom");
define("CMSFORM_FORMAT_PHONE",			"format_phone");
define("CMSFORM_FORMAT_MAIL",				"format_mail");
define("CMSFORM_FORMAT_URL",				"format_url");
define("CMSFORM_FORMAT_LOGIN",			"format_login");

define("CMSFORM_SESSION_PARTIAL",		true);
define("CMSFORM_TEMP",							"temp");
define("CMSFORM_CACHE",							isset($_SERVER[modForm][cachePath])			? $_SERVER[modForm][cachePath] : $_SERVER[CACHE] . "/form_ajax");
define("CMSFORM_CACHE_LIFETIME",		isset($_SERVER[modForm][cacheLifetime])	? $_SERVER[modForm][cacheLifetime] : 60 * 60 * 12); // half day

class cmsForm_ajax {
	
	public static $captcha = array("width" => 90, "height" => 19);
	
	public static $registered = array();
	public static $forms = array();
	
	public $name = "";			// �������� �����
	public $uid = null;		// ���������� ID �����
	public $action = "";
	public $class = "";
	public $options = array();
	
	public $_session = null; // ��� ��� ����� ��� ������ �� ����
	public $_result = array();
	
	public $errors = array();
	
	public $fields = array();
	public $files = array();
	public $request = array();
	public $session = array(); // ����� ����� ������ �����
	
	public $obligatory = array();
	public $checkers = array();
	public $formats = array();
	
	public $debug = false;
	
	// backend vars
	
	public $result = array(
		"alert"				=> array(),
		"html"				=> array(),
		"htmlReplace"	=> array(),
	);
	
	public $htmlReplace = "";
	public $html = "";
	public $alert = "";
	public $redirect = "";
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
	//                                                                                                                                                                 //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * ������������ ������ ������. ������� �����������, �� ���� �� ���������������� ����� � ����� ID
	 * 
	 * @param		string	$id				ID �����
	 * @param		string	$action		����� �������-�����������
	 * @param		string	$class		����� ���� �����
	 * @param		array		$options	������ � ������� ����� (�������� � noAjax)
	 */
	function __construct($name, $action = "", $class = "", $options = array()) {
		
		GLOBAL $_RESULT;
		
		if (substr($name, 0, 5) == "form_") $name = substr($name, 5);
		
		if ($action) {
			
			if (cmsForm_ajax::$registered[$name]) cmsError("FORM_REGISTERED: ����� �{$name}� ��� ���������������� �� ���� ��������. ������ ��������� ������, �� ����������������� �� �������������.");
			cmsForm_ajax::$registered[registered][$name] = true;
			
		}
		
		$this->name			= "form_" . $name; //$_SERVER[cmsForm_ajax];
		$this->action		= $action;
		$this->class		= $class;
		$this->options	= $options;
		$this->_result	= &$_RESULT;
		
		$this->options[debug]		= isset($options[debug]);
		$this->options[ajax]		= isset($options[ajax]) ? $options[ajax] : !isset($options[noAjax]);
		$this->options[raw]			= isset($options[raw]);
		$this->options[method]	= $options[method] == "get" ? "get" : "post";
		
		if ($this->options[raw]) $this->options[ajax] = false;
		
		if ($name != CMSFORM_TEMP) {
			
			//$this->_session = &$_SESSION[cmsForm][$this->name]; // link $_SESSION to internal
			$session = $this->sessionRead();
			if (!$action && $session) $this->errorCritical("�� ������� ��������� ���� � ������� ({$session}) � ���� ���� ���������, ���� � Backend ������ ������������ ID �����, ���� ID ������ ��������� � �������� ������.");
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	//                                                                                                                                                                 //
	//   M E T H O D S                                                                                                                                                 //
	//                                                                                                                                                                 //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * ������������ ����� � frontend. ������� ������ � ������ ������ � ������ �������������
	 */
	function init() {
		
		// �������� ������ ������ � ������
		$this->sessionGet(CMSFORM_SESSION_PARTIAL);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * ������������ ����� � backend ��� ��������� ��������.
	 */
	function initConfirm() {
		
		if (!$this->sessionGet()) $this->errorCritical("�� ������� ����� ������ �{$this->name}�.");
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * ������������ ����� � backend � ��������� �������� ������.
	 */
	function initBackend() {
		
		$req = array();
		
		// ������� ��� ������ �� ���������, � ��������� �������, ������������ ����� � �.�.
		// ���� ������ ��� � ������ ���� ��� ������, ���� ���-�� ������.
		if (!$this->sessionGet()) $this->errorCritical("�� ������� ����� ������ �{$this->name}�.");
		if (!$this->fields) $this->errorCritical("������ ������ � ������������� ����� (\$form->fields).");
		
		// � ������ ����� ������ ��������� ��������, ������� ����� �����
		foreach ($this->fields as $field) {
			
			if (@isset($_REQUEST[$this->name][$field[name]])) $req[$field[name]] = $_REQUEST[$this->name][$field[name]];
			
			if ($field[type] == CMSFORM_TYPE_CHECKBOXES) {
				
				foreach ($field[values] as $k => $v) if (@isset($_REQUEST[$this->name][$field[name] . "|" . $k])) $req[$field[name] . "|" . $k] = true;
				
			}
			
		}
		//$req = $_REQUEST[$this->name];
		//$this->alert(print_r($req, true));
		
		if (count($_FILES[$this->name][name])) {
			
			foreach ($_FILES[$this->name][name] as $file => $name) {
				
				$this->files[$file] = array(
					"name"			=> $_FILES[$this->name][name][$file],
					"error"			=> $_FILES[$this->name][errors][$file],
					"errors"		=> $_FILES[$this->name][errors][$file], // compatibility
					"type"			=> $_FILES[$this->name][type][$file],
					"size"			=> $_FILES[$this->name][size][$file],
					"tmp"				=> $_FILES[$this->name][tmp_name][$file],
					"tmp_name"	=> $_FILES[$this->name][tmp_name][$file], // compatibility
					"uploaded"	=> strlen($_FILES[$this->name][tmp_name][$file]) > 0 ? true : false,
				);
				
			}
			
		}
		
		$this->uid = checkString($req[uid], "id");
		
		// ������ ��������, ����� ��� �� ������ �� ������, ���, �� ������ ������.
		// � ����� ������ ��� ����� � � ������. ���� ���-�� ���������� �
		// ������ ��� � �� ������ ��������� � ������ ��-��������� ����������
		$this->session = $req;
		$this->errors = array();
		
		// ��������� �����
		foreach ($this->fields as $field) {
			
			if ($field[options][length] && mb_strlen($req[$field[name]]) > $field[options][length]) {
				
				$this->error(CMSFORM_ERROR_FORMAT, $field[name], cmsLang("cmsForm.error.length") . ": " . $field[options][length]);
				$req[$field[name]] = mb_substr($req[$field[name]], 0, $field[options][length]);
				
			}
			
		}
		
		// ��������� ������������ �������
		if (count($this->formats)) foreach ($this->formats as $format) {
			
			$res = $this->checkFormat($req[$format[name]], $format[type], $format[pregPattern]);
			
			//$this->alert("FORMAT: " . var_export($format, true) . "\nREQ: " . $req[$format[name]] . "\nSTATUS: " . var_export($res, true));
			
			if ($res === false && $req[$format[name]]) {
				
				$html = ($format[type] == CMSFORM_FORMAT_CUSTOM) ? $format[html] : cmsLang("cmsForm.error.format.{$format[type]}");
				$this->error(CMSFORM_ERROR_FORMAT, $format[name], $html);
				
			} elseif ($res === null) {
				
				$this->errorCritical("��� ���� �{$name}� �� ������ ������ �{$format[type]}�.");
				
			}
			
		}
		
		// ��������� ���� $_REQUEST ��������� ��-���������
		$req = $this->checkField($req);
		
		// �������� �� ������� �������� � ������ ������� $_REQUEST ���������
		// ����� �����, ����� ����� ��������� ������������ ������ � ��������� ���������, ����������� � �������
		if (count($this->checkers)) {
			
			// ��������� ������� �������
			foreach ($this->checkers as $checker) {
				
				$req[$checker[name]] = $this->checkField($req[$checker[name]], $checker[name], $checker[type], $checker[pregPattern], $checker[pregReplace]);
				
			}
			
			$errors = array();
			
			// ������ ������ � ������� ��� ����� ����������
			foreach ($this->checkers as $checker) {
				
				$name = $checker[name];
				$field = $this->fields[$name];
				
				//$this->alert("�������� ��������: �mb_strtolower($req[$name]) . "� �> �" . mb_strtolower($this->session[$name]) . "�");
				
				// ����� ��������� ������� �������� ����� ������, ��� � ������� �������� �� ������� ������������� ��������
				if (mb_strtolower($req[$name]) != mb_strtolower($this->session[$name])) { // || (($field[type] == CMSFORM_TYPE_SELECT || $field[type] == CMSFORM_TYPE_RADIO) && $this->value($name, $req[$name])) === null
					
					// �� ����������� ���� ��� ������ �� ����� � ����� �������, ����� �� ����� ��� ����� ������
					// ���� ����� ������ ����� ������� ��� �� �������
					
					if ($checker[type] != CMSFORM_CHECK_DEFAULT) $this->error(CMSFORM_ERROR_CHECK, $name, ($checker[type] == CMSFORM_CHECK_CUSTOM) ? $checker[html] : cmsLang_var("cmsForm.error.checker." . $checker[type]));
					$errors[$name] = true;
					
				}
				
			}
			
		}
		
		//$this->alert(print_r($this->obligatory, 1));
		//$this->alert(print_r($this->files, 1));
		
		// ��������� ������������� ������������ �����
		if (count($this->obligatory)) foreach ($this->obligatory as $name => $tmp) {
			
			$field = $this->fields[$name];
			
			//$this->alert("Obligatory check: {$field[name]}, " . var_export($this->value($name, $req[$name]), 1));
			
			// ����� ��������� ������� �������� ����� ������, ��� � ������� �������� �� ������� ������������� ��������
			if ($this->value($name, $req[$name]) === null) {
				
				$html = ($field[type] == CMSFORM_TYPE_CHECKBOX || $field[type] == CMSFORM_TYPE_RADIO || $field[type] == CMSFORM_TYPE_SELECT || $field[type] == CMSFORM_TYPE_FILE)
					? cmsLang_var("cmsForm.error.obligatory." . $field[type])
					: cmsLang_var("cmsForm.error.obligatory.empty");
				
				$this->error(CMSFORM_ERROR_OBLIGATORY, $name, $html);
				
			}
			
			// �������� ������, ���� �� ��� ������ ����� �������� (���������� ��������� ��� �
			// ���� ������ ��� �����, �� null � ������� ������ ������� ����� ���������
			if (($field[type] == CMSFORM_TYPE_SELECT || $field[type] == CMSFORM_TYPE_RADIO) && $this->value($name, $req[$name]) === null && !empty($req[$name])) { // ���� ���� � �������
				
				$this->error(CMSFORM_ERROR_OBLIGATORY, $name, cmsLang("cmsForm.error.obligatory.not-found"));
				continue; // ��� break� ���� ����� �� ��������
				
			}
			
		}
		
		// ��������� ���, ���� ������� ���� � �������
		if ($this->fields[confirm]) {
			
			$req[confirm] = $this->confirmReplace($req[confirm]);
			
			if ($req[confirm] != $this->fields[confirm][value]) {
				
				$this->error(CMSFORM_ERROR_CONFIRM, "confirm", cmsLang_var("cmsForm.error.code"));
				//$this->alert($req[confirm] . "|" . $this->fields[confirm][value]);
				
			}
			
		}
		
		//$this->alert($res, true);
		
		foreach ($this->fields as $field) {
			
			
			if (($field[type] == CMSFORM_TYPE_TEXT || $field[type] == CMSFORM_TYPE_TEXTAREA || $field[type] == CMSFORM_TYPE_HIDDEN || $field[type] == CMSFORM_TYPE_PASSWORD) && !$field[options][allowHTML]) {
				
				if (!$field[options][raw]) $req[$field[name]] = htmlSpecialChars($req[$field[name]]);
				//$this->alert($field[type] . "���" . $field[name] . " ��" . $req[$field[name]]);
				
			}
			
		}
		
		//$this->alert(print_r($this->errors, 1));
		
		$this->request = $req;
		$this->parseRequest();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * ����������� ��������� �����
	 */
	function destroy() {
		
		$json = array();
		
		foreach ($this->fields as $name => $array) {
			
			if ($array[type] != CMSFORM_TYPE_BUTTON && $array[type] != CMSFORM_TYPE_RESET) {
				
				$json[$array[name]] = array(
					"name"		=> $array[name],
					"type"		=> $array[type],
					"values"	=> @array_keys($array[values]),
				);
				
			}
			
		}
		
		print "<script> cmsForm_ajax.fields[\"{$this->name}\"] = " . cmsJSON_encode($json) . "; </script>\n";
		
		if (!$this->alert) {
			
			// ��������� ������, ������ � ������ ������ ���� ������ ������ � ������ �� backend ��� ����������� JS.
			// ��� ���������� JS ��� ��� ����� �������� � ����� ������ backend ����� ������� �������.
			// ������ ����� � ���� � ����� ������� JS, �� ����� �������� � ������ �� ������� ��������, � ������ �� �����.
			// ���� JS �������� � �� ���������� ���������� ������� ����-���� ����� frontend � backend, ������� ������ ���� ���������.
			$this->sessionSet();
			
			// ������� � ������ ������ � ������, �.�. ���� ���� ������ F5 ��� ����� �� �������� � ��� ��������� ������ ����� ���������� �������� ����,
			// � �� ��, ������� �� ���� � �� ���� ��������� (��������)
			$this->sessionEnd(CMSFORM_SESSION_PARTIAL);
			
		} else {
			
			// ���� � ������ ���� ����� � ��� ������, ��� �� ��������� � ������ ��� ��������
			//cmsAlert_html($this->_session[alert]);
			cmsAlert($this->alert);
			
			// ����� ������ ������ ���� ��� ����� �� ������ �����, ����� ����� ������������ ���������
			// �.�. ��������� ������ ��� ���������, �� ����� �������, ���������� � ������ �� ��,
			// �� ������ ��� �� �� �����, �� ��� ������
			$this->alert = array();
			
			$this->sessionSet();
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- SESSION FUNCTIONS                                                                                                                                         -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * �������� ������. ��� ������ ���������� true
	 * 
	 * @param		bool		$partial	�������� �� ������ ��������
	 * @return	bool
	 */
	function sessionGet($partial = false) {
		
		if (count($this->_session)) {
			
			$this->errors				= $this->_session[errors];
			$this->session			= $this->_session[session];
			$this->html					= $this->_session[html];
			$this->alert				= $this->_session[alert];
			
			if (!$partial) {
				
				$this->fields			= $this->_session[fields];
				$this->obligatory	= $this->_session[obligatory];
				$this->checkers		= $this->_session[checkers];
				$this->formats		= $this->_session[formats];
				
			}
			
			return true;
			
		} else return false;
		
	}
	
	/**
	 * ��������� ������.
	 * 
	 * @return	bool
	 */
	function sessionSet() {
		
		$this->_session = array(
			"uid"					=> $this->uid,
			"fields"			=> $this->fields,
			"session"			=> $this->session,
			"errors"			=> $this->errors,
			"obligatory"	=> $this->obligatory,
			"checkers"		=> $this->checkers,
			"formats"			=> $this->formats,
			"html"				=> $this->html,
			"alert"				=> $this->alert,
		);
		
		$this->sessionWrite();
		
		return true;
		
	}
	
	/**
	 * ������� ������.
	 * 
	 * @param		bool		$partial	������� �� ������ ��������
	 * @return	bool
	 */
	function sessionEnd($partial = false) {
		
		if ($partial) {
			
			$this->_session[session]			= null;
			$this->_session[errors]				= null;
			$this->_session[html]					= "";
			$this->_session[alert]				= "";
			//$this->_session[uid]					= "";
			
		} else $this->_session = array();
		
		$this->sessionWrite();
		
	}
	
	/**
	 * ���������� ������ � ����.
	 */
	function sessionWrite() {
		/*
		@mkdir(CMSFORM_CACHE);
		@mkdir(CMSFORM_CACHE . "/" . session_id());
		file_put_contents(CMSFORM_CACHE . "/" . session_id() . "/{$this->name}.php", "<?\n\nreturn " . var_export($this->_session, true) . ";\n\n?>");
		*/
	}
	
	/**
	 * ������ ������ �� ����� � ���������� ���������.
	 * 
	 * @return	bool
	 */
	function sessionRead() {
		/*
		$file = CMSFORM_CACHE . "/" . session_id() . "/{$this->name}.php";
		
		if (@is_file($file)) {
			
			$this->_session = include($file);
			
		} else return $file;
		*/
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- BACKEND FUNCTIONS                                                                                                                                         -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 * ��������� ��������� � backend � ��������� ������ ��� AJAX � ������������ ������
	 */
	function processed($data = array()) {
		
		$this->alert				= implode("\n\n", $this->result[alert]);
		$this->html					= implode($this->result[html]);
		$this->htmlReplace	= implode($this->result[htmlReplace]);
		
		if (class_exists("cmsAjax") && cmsAjax::$ajax) {
			
			$this->_result[alert]				= $this->alert;
			$this->_result[html]				= $this->html;
			$this->_result[htmlReplace]	= $this->htmlReplace;
			$this->_result[errors]			= $this->errors;
			$this->_result[redirect]		= $this->redirect;
			$this->_result[data]				= $data; // ��������� ����������, ����� � ��� ���, �� ��� ������� :)
			
			// � AJAX backend ������ ��� ������� �� �����, �� ����� ������� ID � CODE, ������� ������� �� �������
			$this->sessionEnd(CMSFORM_SESSION_PARTIAL);
			
		} else { // if JS disabled, for example
			
			$this->sessionSet();
			
			// ���� �������� ������ � ������ �� ���������, �� ���� �������, ����� ��� ��� � ������ ����������� �� ��������� ���������� ���
			// ������������ � ����� ���� �������� ����� ������� ����� �������, ��� ����������� ��� ����� ������� (�������� ������� ����� ������)
			$url = $this->redirect ? $this->redirect : $_SERVER[HTTP_REFERER];
			
			cmsRedirect($url); // . "#postBack-{$this->uid}"
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- FORM FUNCTIONS                                                                                                                                            -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function form($uid = false)	{ 
		
		cmsForm_ajax::$forms[$this->name]++;
		$this->uid = $uid ? "{$this->name}_{$uid}" : "{$this->name}_" . cmsForm_ajax::$forms[$this->name];
		
		list($optionsHTML, $options) = $this->parseOptions($this->options);
		
		print "<form class='{$this->class}' name='{$this->name}' id='{$this->uid}' action='{$this->action}' method='{$this->options[method]}' enctype='multipart/form-data'{$optionsHTML}>";
		
	}
	
	function end()	{
		
		$this->field(array(
			"type"	=> CMSFORM_TYPE_HIDDEN,
			"name"	=> "uid",
			"value"	=> 0,
		));
		print $this->add("uid", $this->uid);
		
		print "</form>\n";
		
		// ��� ������ � SimpleModal � ������� ��������� ��������� unbind(), ����� ������ ������ �������.
		// ��������������, ��� � ��������� ����� ���� ������ ����� ��������.
		if ($this->options[ajax]) print "<script type='text/javascript'> $(\"#{$this->uid}\").unbind('submit').submit(function() { cmsForm_ajax.submit(this); return false; }); </script>";
		
		//$this->uid = null;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- CONFIRM FUNCTIONS                                                                                                                                         -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 *	������� ����������, ��������� ������� ����� � ���������� ��� �������������
	 *	@return	string
	 */
	function confirmGenerate() {
		
		$s = "";
		
		mt_srand(microtime(true));
		
		for ($i = 0; $i < 6; $i++) $s .= mt_rand(0, 9);
		
		$this->fields[confirm][value] = $this->confirmReplace(mb_strtoupper($s));
		
		return $this->fields[confirm][value];
		
	}
	
	/**
	 *	������� ���������� ������ ������� �������� � ���� �������������
	 *	@param	string	$var		������ ��� ������
	 *	@return	string
	 */
	function confirmReplace($var) {
		
		$replaces = array(
			// en
			"I" => "1",
			"O" => "0",
			"D" => "O",
			"S" => "5",
			"Z" => "2",
			"B" => "8",
			"G" => "6",
			// ru
			"�" => "0",
			"�" => "8",
		);
		
		return mb_strToUpper(cmsReplace($replaces, $var, true));
		
	}
	
	/**
	 *	������� ���������� � ������� ����� PNG � ����������� � ����� ������������� (��� ������� �� ������� �����)
	 *	���������� ��������� ���������� imagePNG
	 *	@return	bool
	 */
	function confirmImage() {
		
		$width	= cmsForm_ajax::$captcha[width];
		$height	= cmsForm_ajax::$captcha[height];
		
		$multi = 4;
		$xwidth		= $width * $multi;
		$xheight	= $height * $multi;
		
		$code = $this->fields[confirm][value];
		
		$im = imageCreateTrueColor($xwidth, $xheight);
		$w = imageColorAllocate($im, 255, 255, 255);
		$b = imageColorAllocate($im, 000, 000, 000);
		$g = imageColorAllocate($im, 100, 100, 100);
		imageFill($im, 1, 1, $w);
		$w = imageColorTransparent($im, $w);
		
		$r = mt_rand(0, $xheight);
		for ($x = 0; $x < $xwidth + $xheight; $x += 4 * $multi) {
			
			for ($i = 0; $i < $multi; $i++) imageLine($im, $x + $i, 0, $x + $i - $xheight, $xheight + $r, $g);
			
		}
		
		$arr = preg_split('//', $code, -1, PREG_SPLIT_NO_EMPTY);
		for ($i = 0; $i < count($arr); $i++) {
			
			$x = ($xwidth * 0.04) + (floor(($xwidth * 0.97) * 0.167)) * $i; // ��������
			$y = ($xheight * 0.8);
			$s = ($xheight * 0.5) * (96 / 72); // 96 � res
			$a = mt_rand(-20, 20);
			
			imageTTFText($im, $s, $a, $x, $y, $b, $_SERVER[DOCUMENT_ROOT] . "/admin/ui/consolas.ttf", $arr[$i]); //
			
		}
		
		$temp = imageCreateTrueColor($xwidth, $xheight);
		$w = imageColorAllocate($temp, 255, 255, 255);
		imageFill($temp, 1, 1, $w);
		$w = imageColorTransparent($temp, $w);
		
		$phase = rand(-M_PI, M_PI);
		$frq = 2 * M_PI / $xheight;
		$amp = $xwidth * 0.02;
		
		for ($y = 0; $y < $xheight; $y++) {
			
			$dstx = $amp + sin($y * $frq + $phase) * $amp;
			
			imagecopy($temp, $im, $dstx, $y, 0, $y, $xwidth, 1);
			//imagesetpixel($im, $dstx, $y, $b);
			
		}
		
		$res = imageCreateTrueColor($width, $height);
		$w = imageColorAllocate($res, 255, 255, 255);
		imageFill($res, 1, 1, $w);
		$w = imageColorTransparent($res, $w);
		
		imageCopyResampled($res, $temp, 0, 0, 0, 0, $width, $height, $xwidth, $xheight);
		
		imageTrueColorToPalette($res, true, 256);
		
		header("CONTENT-TYPE: IMAGE/PNG");
		return imagePNG($res);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- RETURN FUNCTIONS                                                                                                                                          -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 *	������� ��������� � ������ ������� ����� ������
	 *	@param	string	$text		����� ������
	 *	@return	void
	 */
	function alert($text) {
		
		$this->result[alert][] = html_entity_decode(strip_Tags($text));
		
	}
	
	/**
	 *	������� ��������� � ������ HTML ������ ����� ������
	 *	@param	string	$text		HTML ��� ������
	 *	@return	void
	 */
	function html($html = null) {
		
		if ($html === null) {
			
			// � ������ ��-Ajax ����� ������� html, ���� ��������� UID
			return ($this->uid == $this->_session[uid]) ? $this->html : "";
			
		} else $this->result[html][] = $html;
		
	}
	
	/**
	 *	������� ��������� � ������ HTML ������ ����� ����� ������
	 *	@param	string	$text		HTML ��� ������ �����
	 *	@return	void
	 */
	function htmlReplace($html) {
		
		$this->result[htmlReplace][] = $html;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- MISC HELPER FUNCTIONS                                                                                                                                     -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 *	������ ������ ������� �� ������� �|� � ��������� ����� ���� �� ��������� �������
	 *	@return	void
	 */
	function parseRequest() {
		
		foreach ($this->request as $k => $v) {
			
			$arr = explode("|", $k);
			if (count($arr) > 1) {
				
				$tmp =& $this->request;
				
				foreach ($arr as $n => $var) {
					
					if (!$tmp[$var]) $tmp[$var] = array();
					$tmp =& $tmp[$var];
					
					if ($n == count($arr) - 1) $tmp = $v;
					
				}
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 *	������� ����������� ������ � ��������� ��������� �����.
	 *	@param	string	$text				����� ������
	 *	@param	string	$redirect		URL ��� ��������������� ���������
	 *	@return	void
	 */
	function errorCritical($text = null, $redirect = false) {
		
		if ($text) $this->alert($text);
		if ($redirect) $this->redirect = $redirect;
		$this->error(CMSFORM_ERROR_CRITICAL, CMSFORM_ERROR_CRITICAL, $text ? $text : "���������� ���������� ������� ��� ����������� �� ������");
		$this->processed();
		exit();
		
	}
	
	/**
	 *	������� ������� ������
	 *	@param	string	$type		��� ������ (�� ��������� ������)
	 *	@param	string	$name		��� ����
	 *	@param	string	$html		����� ������
	 *	@return	void
	 */
	function error($type, $name, $html = "") {
		
		$options = $this->getOptions($name);
		$id = $this->getID($name);
		$html = print_r($html, true);
		
		$text = (!$html) ? "" : strip_tags($html); // ��� title
		$html = (!$html) ? "" : "<span class='block'>{$html}</span>"; // ��� span
		
		$this->errors[] = array(
			"type"	=> $type,
			"id"		=> $id,
			"name"	=> $name,
			"html"	=> ($options[errors][noSpan])		? "" : $html,
			"text"	=> ($options[errors][noTitle])	? "" : $text,
		);
		
	}
	
	function getCustomErrors() {
		
		$return = array();
		
		if (count($this->errors)) foreach ($this->errors as $error) {
			
			if ($error[type] == CMSFORM_ERROR_CUSTOM) $return[$error[id]] = $error[html];
			
		}
		
		return $return;
		
	}
	
	/**
	 *	������� ���� � �������, �� ��������� ������ ������� ��� ������ ������ � ������������ �����
	 *	@param	string	$name		��� ����
	 *	@param	string	$html		����� ������
	 *	@param	bool		$full		�������� �� �������������
	 *	@return	string	$html		HTML ��� ���� � �������
	 */
	function errorHTML($name, $html = "", $underline = false) {
		
		$id = $this->getID($name);
		
		$return = "";
		$return .= ($underline) ? "<span class='cmsForm_error' id='{$id}_error' style='display: block'><img src='/images/free.gif'></span>" : "";
		$return .= "<span class='cmsForm_errorText' id='{$id}_errorText' style='display: block'>{$html}</span>"; // ��� span
		
		// ��� ��������
		$return .= "<script> cmsForm_ajax.errorHTML('{$id}', '', ''); </script>"; // ��� ��� " . addSlashes($html) . " � ��� � ��� ��������
		
		return $return;
	
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function getName($name)	{	
		
		if ($this->options[raw]) {
			
			$a = explode("|", $name);
			$b = array_shift($a);
			if (count($a)) {
				
				return $b . "[" . implode("][", $a) . "]";
				
			} else return $b;
			
		} else return "{$this->name}[{$name}]";
		
	}
	
	function getID($name)		{
		
		return "{$this->uid}_" . str_replace("|", "__", $name);
		
	}
	
	function getNameFromID($id) { return str_replace("__", "|", str_replace($this->uid . "_", "", $id)); }
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function getValue($name, $value) {
		
		// ���� ������ �������� ���� � ��� ���� ���-�� ���� � ������ � ��� ������, ��� ������ ���� ���������,
		// �� backend'� ���-�� �� ����������� � ��� ����������� JS ��������� �������� �������.
		// ����������� ��� ����� �����, �������� ���� �������� �� ������, � �� ���������� �������� ����.
		// ������� �������� ������ ��������� ������� �� html-entity
		return str_replace("'", "&#39;", ($this->session[$name] && $this->_session[uid] == $this->uid) ? $this->session[$name] : $value);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function getOptions($name) {
		
		return $this->fields[$name][options];
		
	}
	
	function parseOptions($options) {
		
		$html = array();
		
		// �����, ������ � optionsHTML
		$html[autocomplete]	= (isset($options[autocomplete]))	? ($options[autocomplete] ? "autocomplete='on'" : "autocomplete='off'") : "";
		$html[rel]					=	($options[rel])				? "rel='{$options[rel]}'" : "";
		$html[style]				=	($options[style])			? "style='{$options[style]}'" : "";
		$html[disabled]			= ($options[disabled])	? "disabled" : "";
		$html[readonly]			= ($options[readonly])	? "readonly" : "";
		$html[length]				= ($options[length])		? "maxlength='{$options[length]}'" : "";
		$html[html]					= ($options[html])			? " " . $options[html] : "";
		
		// ��������������� �����
		$options[nobr]					= ($options[nobr])	? true : false;
		
		$html = implode(" ", array($html[rel], $html[style], $html[disabled], $html[readonly], $html[length], $html[autocomplete], $html[html]));
		$html = $html ? " " . $html : "";
		
		return array($html, $options);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function getBlock($nobr, $id) {
		
		$defBegin	= ""; // "<span class='group'>"; // ����� �� ����� ��� <P> ������ <DIV>
		$defEnd		= "<br />"; //"</span>";
		
		$block	= array("begin" => "", "end" => " ");
		$line		= array("begin" => "", "end" => " ");
		
		if ($nobr) {
			
			$block[begin]	= $defBegin;
			$block[end]		= $defEnd;
			
		} else {
			
			$line[begin]	= $defBegin;
			$line[end]		= $defEnd;
			
		}
		
		$block[begin]	= "<span id='{$id}' class='inlineBlock'>" . $block[begin]; // id=id + _block;
		$block[end]		= $block[end] . "</span>";
		
		return array($block, $line);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- FIELDS                                                                                                                                                    -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function file($name, $options = array()) {
		
		ob_start();
		
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		print "<input type='file' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}>";
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	
	function hidden($name, $value, $options = array()) {
		
		ob_start();
		
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		print "<input type='hidden' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "' value='{$value}'{$optionsHTML}>";
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function date($name, $value = "", $options = array()) {
		
		ob_start();
		
		$value = $this->getValue($name, $value);
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		$value += 0;
		
		if (!$value) $xvalue = ""; else $xvalue = $this->value($name, $value);
		$options[date] = true;
		$options[readonly] = true;
		$options[html] .= " onclick='cmsCalendar_show(\"" . $this->getId($name) . "\")'";
		
		print $this->text($name . "_text", $xvalue, $options);
		print $this->hidden($name, $value, array(
			"rel" => $this->getId($name) . "_text",
		));
		
		return ob_get_clean();
		
	}
	
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function text($name, $value = "", $options = array()) {
		
		ob_start();
		
		$value = $this->getValue($name, $value);
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		$cls		= ($name == "confirm") ? " confirm" : "";
		$value	= ($name == "confirm") ? "" : $value; // ���� ���� ��� ���� � ��� ������ ����������
		$type		= ($options[password]) ? CMSFORM_TYPE_PASSWORD : CMSFORM_TYPE_TEXT;
		$type		= ($options[date]) ? CMSFORM_TYPE_TEXT : $type;
		$cls		= ($options[date]) ? " date" : $cls;
		
		print "<input type='{$type}' class='text{$cls}' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "' value='{$value}'{$optionsHTML}>";
		
		return ob_get_clean();
		
	}
	
	function password($name, $value = "", $options = array()) {
		
		$options[password] = true;
		return $this->text($name, $value, $options);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function textarea($name, $value = "", $options = array()) {
		
		ob_start();
		
		$options[rows]	= ($options[rows]) ? $options[rows] : 4;
		$options[style]	.= "; height: " . ($options[rows] * 16 + 4) . "px";
		
		if ($options[wysiwyg]) {
			
			$options[rel] = $this->getId($name) . "_parent";
			$options[raw] = true;
			
		}
		
		$value = $this->getValue($name, $value);
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		print "<textarea name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}>{$value}</textarea>";
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function button($name, $value, $options = array()) {
		
		ob_start();
		
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		$type = $options[submit]	? CMSFORM_TYPE_SUBMIT : CMSFORM_TYPE_BUTTON;
		$type = $options[reset]		? CMSFORM_TYPE_RESET : $type;
		
		$html = $options[submit] ? "name='" . $this->getName($name) . "'" : "";
		
		print "<input type='" . $type . "' class='submit' {$html} id='" . $this->getId($name) . "' value='{$value}'{$optionsHTML}>";
		if ($options[submit]) print "<script> $(\"#" . $this->getId($name) . "\").click(function() { cmsForm_ajax.clicked = \"{$name}\"; }); /* ������� ����� ����, ����� �������� value � ������ */ </script>";
		
		return ob_get_clean();
		
	}
	
	function submit($name, $value, $options = array()) {
		
		$options[submit] = true;
		return $this->button($name, $value, $options);
		
	}
	
	function reset($name, $value, $options = array()) {
		
		$options[reset] = true;
		return $this->button($name, $value, $options);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function code($options = array()) {
		
		ob_start();
		
		/*
		print "<table class='frame cmsForm_captchaTable'><tr><td>";
		print $this->text("confirm", "", array("length" => 6, "errors" => array("noSpan" => true), "html" => "autocomplete='off'"));
		print "</td><td>";
		print "<img src='/core/classes/form_ajax/ajax_confirm.php?formName={$this->name}&rnd=" . cmsTime() . "' id='" . $this->getID("captcha") . "' class='captcha cmsForm_captcha' title='" . cmsLang_var("cmsForm.captcha.title") . "' width='" . cmsForm_ajax::$captcha[width] . "' height='" . cmsForm_ajax::$captcha[height] . "'>";
		print "</td><td>";
		print "<label><a href='javascript: cmsForm_ajax.reloadCaptcha(\"{$this->name}\", \"{$this->uid}\")' class='dashed'>" . cmsLang_var("cmsForm.captcha.reload") . "</a></label>";
		print "</td></tr></table>";
		print "<script> $('#" . $this->getID("confirm") . "').keyup(function(e){ cmsForm_ajax.cleanCaptcha(e, this); }); </script>";
		*/
		
		print $this->text("confirm", "", array("length" => 6, "errors" => array("noSpan" => true), "html" => "autocomplete='off'"));
		print " ";
		print "<img src='/core/classes/form_ajax/ajax_confirm.php?formName={$this->name}&rnd=" . cmsTime() . "' id='" . $this->getID("captcha") . "' class='captcha cmsForm_captcha' title='" . cmsLang_var("cmsForm.captcha.title") . "' width='" . cmsForm_ajax::$captcha[width] . "' height='" . cmsForm_ajax::$captcha[height] . "'>";
		print " ";
		print "<label><a href='javascript: cmsForm_ajax.reloadCaptcha(\"{$this->name}\", \"{$this->uid}\")' class='dashed'>" . cmsLang_var("cmsForm.captcha.reload") . "</a></label>";
		print "<script> $('#" . $this->getID("confirm") . "').keyup(function(e){ cmsForm_ajax.cleanCaptcha(e, this); }); </script>";
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function image($name, $src, $options = array()) {
		
		ob_start();
		
		$path = cmsFile_path($src);
		$info = getImageSize($path);
		
		$options[style] .= "width: {$info[0]}px; height: {$info[1]}px; background: transparent url({$src}) no-repeat";
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		print "<input type='submit' class='image' id='" . $this->getId($name) . "' value='&nbsp'{$optionsHTML}>";
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 *	@param	bool	$multiple		�������� �� ���� ������� ������ ���� CHECKBOXES
	 */
	function checkbox($name, $label, $value = 0, $options = array(), $multiple = false) {
		
		ob_start();
		
		$label = $options[label] ? $options[label] : $label;
		
		// ��������� ������ ����� ��������� ������ ������� ��������
		$options[value] = $options[value] ? $options[value] : 1;
		
		$value = $this->getValue($name, $value);
		list($optionsHTML, $options) = $this->parseOptions($options);
		list($block, $line) = $this->getBlock($options[nobr], $this->getId($name));
		
		$checked = ($value == $options[value]) ? " checked" : "";
		
		// ��� ����� ������ �����, ����� ��������� ���� ID �� ID �����
		$id = $this->getId($name) . ($multiple ? "" : "_input");
		
		if (!$multiple) print $block[begin] . $line[begin];
		print "<label class='checkboxLabel{$options[disabled]}' for='{$id}'>";
			print "<input type='checkbox' class='checkbox' name='" . $this->getName($name) . "' id='{$id}' value='{$options[value]}'{$checked}{$optionsHTML}>";
			print $label;
		print "</label>";
		if (!$multiple) print $line[end] . $block[end];
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function checkboxes($name, $values, $value, $options = array()) {
		
		ob_start();
		
		$value = $this->getValue($name, $value);
		$value = (@!is_array($value)) ? cmsJSON_decode($value) : $value;
		
		list($optionsHTML, $options) = $this->parseOptions($options);
		list($block, $line) = $this->getBlock($options[nobr], $this->getId($name));
		
		$options[nobr] = true;
		
		print $block[begin];
		if (is_array($values)) foreach ($values as $id => $label) {
			
			print $line[begin];
			$val = (@in_array($id, $value) || !empty($value[$id])) ? 1 : 0;
			print $this->checkbox($name . "|" . $id, $label, $val, $options, true);
			print $line[end];
			
		}
		print $block[end];
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function radio($name, $values, $value = "", $options = array()) {
		
		ob_start();
		
		$value = $this->getValue($name, $value);
		list($optionsHTML, $options) = $this->parseOptions($options);
		list($block, $line) = $this->getBlock($options[nobr], $this->getId($name));
		
		$i = 0;
		
		print $block[begin];
		if (is_array($values)) foreach ($values as $id => $label) {
			
			$i++;
			$checked = ($value == $id) ? " checked" : "        ";
			
			print $line[begin];
			print "<label class='label{$options[disabled]}' for='" . $this->getId($name) . "_{$id}'>";
				print "<input type='radio' class='radio' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "_{$id}' value='{$id}'{$checked}{$optionsHTML}>";
				print $label;
			print "</label>";
			print $line[end];
			
		}
		print $block[end];
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function select($name, $values, $value = "", $options = array()) {
		
		ob_start();
		
		$value = $this->getValue($name, $value);
		list($optionsHTML, $options) = $this->parseOptions($options);
		
		print "<select class='select' name='" . $this->getName($name) . "' id='" . $this->getId($name) . "'{$optionsHTML}>";
		
		if (is_array($values)) 
			foreach ($values as $id => $label) {
			
				if (is_array($label)) {
					
					print "<optgroup label='{$id}'>";
					foreach ($label as $k => $v) {
						
						$selected = ($value == $k) ? " selected" : "";
						print "<option value='{$k}'{$selected}>{$v}</option>";
						
					}
					print "</optgroup>";
					
				} else {
					
					$selected = ($value == $id) ? " selected" : "";
					print "<option value='{$id}'{$selected}>{$label}</option>";
					
				}
			
			}
		
		print "</select>";
		
		return ob_get_clean();
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	// -- ARRAY FUNCTIONS                                                                                                                                           -- //
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function field($array) {
		
		switch ($array[type]) {
			
			case CMSFORM_TYPE_CODE:				break;
			case CMSFORM_TYPE_HIDDEN:			break;
			case CMSFORM_TYPE_TEXT:				break;
			case CMSFORM_TYPE_PASSWORD:		break;
			case CMSFORM_TYPE_TEXTAREA:		break;
			case CMSFORM_TYPE_BUTTON:			break;
			case CMSFORM_TYPE_SUBMIT:			break;
			case CMSFORM_TYPE_RESET:			break;
			case CMSFORM_TYPE_IMAGE:			break;
			case CMSFORM_TYPE_CHECKBOX:		break;
			case CMSFORM_TYPE_CHECKBOXES:	break;
			case CMSFORM_TYPE_RADIO:			break;
			case CMSFORM_TYPE_SELECT:			break;
			case CMSFORM_TYPE_FILE:				break;
			case CMSFORM_TYPE_DATE:				break;
			default: 											trigger_error("��� �{$array[type]}� ��� ���� {$array[name]} " . print_r($array, 1) . " �� ���������������.");
			
		}
		
		if ($array[type] == CMSFORM_TYPE_CODE) {
			
			// ������������ ��� � ���������
			$this->addObligatory("confirm");
			$this->addChecker("confirm", CMSFORM_CHECK_CODE);
			
			// ���� ������ ��� ����, ��� ������ �������
			$array[name]		= "confirm";
			$array[value]		= $this->confirmGenerate();
			$array[options]	= array("errors" => array("noSpan" => true));
			
		}
		
		// ������� ����
		$this->fields[$array[name]] = $array;
		
	}
	
	function addFields($fields) {
		
		if (is_array($fields) && count($fields) > 0) foreach ($fields as $array) {
			
			$this->field($array);
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function delete($name) {
		
		unset($this->fields[$name]);
		unset($this->session[$name]);
		unset($this->request[$name]);
		unset($this->obligatory[$name]);
		
		foreach ($this->checkers as $i => $checker) if ($checker[name] == $name) unset($this->checkers[$i]);
		foreach ($this->formats as $i => $format) if ($format[name] == $name) unset($this->formats[$i]);
		
		unset($this->_session[fields][$name]);
		unset($this->_session[request][$name]);
		unset($this->_session[obligatory][$name]);
		
		foreach ($this->_session[checkers] as $i => $checker) if ($checker[name] == $name) unset($this->_session[checkers][$i]);
		foreach ($this->_session[formats] as $i => $format) if ($format[name] == $name) unset($this->_session[formats][$i]);
		
		$this->_result[fieldsDeleted][] = $name;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function add($name, $value = false, $options = array()) {
		
		$array = $this->fields[$name];
		$errorsHtml = array();
		$errorsText = array();
		$errors = false;
		
		// �������� ����� ������� �������� ��������, � ���� �������� ����������� � �������� ��������� ����� ���
		if ($value) $array[value] = $value;
		if ($value) $array[src]		= $value;
		
		if (count($this->errors)) {
			
			foreach($this->errors as $error) {
				
				if ($this->getID($name) == $error[id]) {
					
					$errors = true;
					
					// ���� ID ���������, �� ������� � ���� ����� ������
					$errorsHtml[] = $error[html]; // ��� span
					$errorsText[] = $error[text]; // ��� title
					
				}
				
			}
			
		}
		
		
		$return = null;
		if (!is_array($array[options])) $array[options] = array();
		$array[options] = array_merge($array[options], $options);
		
		if ($errors) { $array[options][html] .= " title='" . implode(", ", $errorsText) . "'"; } // ��� title
		
		switch ($array[type]) {
			
			case CMSFORM_TYPE_CODE:				$return = $this->code(			$array[options]); break;
			case CMSFORM_TYPE_HIDDEN:			$return = $this->hidden(		$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_TEXT:				$return = $this->text(			$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_PASSWORD:		$return = $this->password(	$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_TEXTAREA:		$return = $this->textarea(	$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_BUTTON:			$return = $this->button(		$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_SUBMIT:			$return = $this->submit(		$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_RESET:			$return = $this->reset(			$array[name], $array[value], $array[options]); break;
			case CMSFORM_TYPE_IMAGE:			$return = $this->image(			$array[name], $array[src], $array[options]); break;
			case CMSFORM_TYPE_CHECKBOX:		$return = $this->checkbox(	$array[name], $array[label], $array[value], $array[options]); break;
			case CMSFORM_TYPE_CHECKBOXES:	$return = $this->checkboxes($array[name], $array[values], $array[value], $array[options]); break;
			case CMSFORM_TYPE_RADIO:			$return = $this->radio(			$array[name], $array[values], $array[value], $array[options]); break;
			case CMSFORM_TYPE_SELECT:			$return = $this->select(		$array[name], $array[values], $array[value], $array[options]); break;
			case CMSFORM_TYPE_FILE:				$return = $this->file(			$array[name], $array[options]); break;
			case CMSFORM_TYPE_DATE:				$return = $this->date(			$array[name], $array[value], $array[options]); break;
			default: 											cmsError("���� �{$name}� �� ������� � ������� ��� ������� ����� ��� ({$array[type]}).");
			
		}
		
		//cmsVar($errors, "errors");
		
		// ���� ���� ������ � ������� �� ��� ����
		if ($errors) $return .= $this->errorHTML($name, implode($errorsHtml), true);
		
		return $return;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	/**
	 *	������� ���������� ��������� ��������� �������� ���� � ��, ��� ����� ���� ��� ������. ���� �� ������� ���������� �������� ������� ���������� NULL.
	 *	@param	string	$name				�������� ����
	 *	@param	mixed		$override		�������� �������� ������ �� ��������, �� �������� ������� �� ������� �����, ���� ��� �� �������, �� �� �������� ��-��������� ������ ����
	 *	@return	string|null
	 */
	function value($name, $override = null) {
		
		if ($this->fields[$name]) {
			
			$field = $this->fields[$name];
			$value = $override !== null ? $override : (isset($this->request[$name]) ? $this->request[$name] : $field[value]);
			
			// �����������
			if ($field[type] == CMSFORM_TYPE_RADIO) {
				
				if (empty($value)) $value = 0;
				return isset($field[values][$value]) ? $field[values][$value] : null;
				
			// ������
			} elseif ($field[type] == CMSFORM_TYPE_SELECT) {
				
				if (empty($value)) $value = 0;
				if (isset($field[values][$value])) {
					
					return $field[values][$value];
					
				} else { // � ������� ����� ���� OPTGROUP ����������� ��������
					
					foreach ($field[values] as $val => $label) {
						if (is_array($label)) foreach ($label as $k => $v) if ($k == $value) return $v;
					}
					
					return null;
				}
				
			// ������� (����)
			} elseif ($field[type] == CMSFORM_TYPE_CHECKBOX) {
				
				if (empty($value)) $value = 0;
				
				if ($field[options][value]) {
					
					return ($field[options][value] == $value) ? $field[label] : null;
					
				} else {
					
					return (!empty($value)) ? $field[label] : null; // $value == $field[value] ���������, �.�. $field[value] ����� ������� ��������, � �� ������������
					
				}
				
			// �������� (������)
			} elseif ($field[type] == CMSFORM_TYPE_CHECKBOXES) {
				
				$r = array();
				
				if (is_array($value) && count($value)) foreach ($value as $k => $v) {
					
					if (!empty($v)) $r[] = $field[values][$k];
					
				}
				
				return implode("; ", $r);
				
			// ����
			} elseif ($field[type] == CMSFORM_TYPE_FILE) {
				
				return (isset($this->files[$name]) && $this->files[$name][uploaded]) ? $this->files[$name][name] : null;
				
			// ����
			} elseif ($field[type] == CMSFORM_TYPE_DATE) {
				
				$value = (int)$value;
				return ($value > 0) ? cmsDate($value, $_SERVER[lang], CMSDATE_MOD_CUT) : null;
				
			// ������� �����
			} else {
				
				return (!empty($value)) ? nl2br($value) : null;
				
			}
			
		} else {
			
			$path = array_reverse(explode("|", $name));
			$last = $path[0];
			unset($path[0]);
			$path = implode("|", array_reverse($path));
			
			if ($this->fields[$path] && $this->fields[$path][type] == CMSFORM_TYPE_CHECKBOXES) {
				
				return ($this->fields[$path][values][$last]) ? $this->fields[$path][values][$last] : null;
				
			} else {
				
				//cmsError("�� ������� ���� �{$name}�."); // ����� ��������� �� ������������� ���� ��� ������ ��-��� ���� �������������
				return null;
				
			}
			
		}
		
	}
	
	/**
	 *	������� ���������� ��������� ��������, �������� �� ����� ������ �����
	 *	@param	array		$element		������ �����, ��� ��� �����, ���� ����� ����������� ������� �������, ���� ������� ��������� ���� ������ �����
	 *	@param	string	$prefix			������� ������� ����� ������ �������� �� root, �� ������� ���������
	 *	@return array
	 */
	function valueAll($element = null, $prefix = "") {
		
		if ($element === null && !$prefix) $element = $this->request;
		
		$return = array();
		
		if (@is_array($element)) foreach ($element as $k => $v) {
			
			$name = ($prefix) ? $prefix . "|" . $k :  $k;
			$return[$k] = $this->valueAll($element[$k], $name);
			
		} else {
			
			return $this->value($prefix, $element);
			
		}
		
		return $return;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function checkField($value, $name = false, $type = CMSFORM_CHECK_DEFAULT, $pregPattern = '//', $pregReplace = '') {
		
		if (@is_array($value)) {
			
			foreach ($value as $k => $v) $value[$k] = $this->checkField($v, $type, $pregPattern, $pregReplace);
			
		} else {
			
			$value = (string) $value;
			//if ($name && !$this->fields[$name][allowHTML]) $value = htmlSpecialChars($value);
			
			switch ($type) {
				
				case CMSFORM_CHECK_CUSTOM:	$value = preg_replace($pregPattern, $pregReplace,		$value); break;
				case CMSFORM_CHECK_NUMERIC:	$value = preg_replace('/[^0-9.,]/', ''	,						$value); break;
				case CMSFORM_CHECK_LOGIN:		$value = preg_replace('/[^-_a-z0-9]/sim', ''	,			mb_strtolower($value)); break;
				case CMSFORM_CHECK_CODE:		$value = preg_replace('/[^0-9a-z]/i', ''	,					mb_strtoupper($value)); break;
				case CMSFORM_CHECK_PHONE:		$value = preg_replace('/[^0-9- +,]/si', ''	,				$value); break;
				case CMSFORM_CHECK_MAIL:		$value = preg_replace('/[^0-9a-z@.+-_]/i', '',			$value); break;
				case CMSFORM_CHECK_URL:			$value = preg_replace('#[^0-9a-z%/?&@:.+-]#i', ''	, $value); break;
				//case CMSFORM_CHECK_URL:			$value = preg_replace('#[^0-9a-z%/?&@:.-+]#i', ''	, $value); break;
				default: break;
				
			}
			
			$value = trim($value);
			
		}
		
		return $value;
		
	}
	
	function checkFormat($value, $type, $pregPattern = '//') {
		
		switch ($type) {
			
			case CMSFORM_FORMAT_CUSTOM:	return (!empty($value) || $value == 0) && preg_match($pregPattern,																										$value);
			case CMSFORM_FORMAT_MAIL:		return (!empty($value) || $value == 0) && preg_match('/^[a-z0-9-_.+]+@[a-z0-9-.]{2,}\.[a-z0-9]{2,5}$/si',							$value);
			case CMSFORM_FORMAT_URL:		return (!empty($value) || $value == 0) && preg_match('%^((http://|https://|www\.))[a-z0-9-.]+\.[a-z0-9]{2,5}.*%i',		$value);
			case CMSFORM_FORMAT_PHONE:	return (!empty($value) || $value == 0) && preg_match('/^(?:\+\d \d{3,5} [\d-]{5,9},? ?)*$/si',												$value); // only +7 123 456-78-90
			case CMSFORM_FORMAT_LOGIN:	return (!empty($value) || $value == 0) && preg_match('/^[-_a-z0-9]{3,}?$/i',																					$value);
			//case CMSFORM_FORMAT_PHONE:	return (!empty($value) || $value == 0) && preg_match('/(\+\d{11})|(\d[ -]?\(?\d{3}\)?[ -]?[\d -]{7,9})/si',					$value);
			default: return null;
			
		}
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function addChecker($name, $type, $pregPattern = false, $pregReplace = false, $html = "") {
		
		$this->checkers[] = array(
			"name"				=> $name,
			"type"				=> $type,
			"pregPattern"	=> $pregPattern,
			"pregReplace"	=> $pregReplace,
			"html"				=> $html,
		);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function addFormat($name, $type, $pregPattern = false, $html = null) {
		
		$this->formats[] = array(
			"name"				=> $name,
			"type"				=> $type,
			"pregPattern"	=> $pregPattern,
			"html"				=> $html,
		);
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	function addObligatory($name) {
		
		$this->obligatory[$name] = true;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
	static function get($prefix) {
		
		foreach ($_REQUEST as $k => $v) if (stristr($k, "form_{$prefix}_")) return $k;
		
	}
	
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	/*
	static function cleanSessionCache() {
		
		$path = CMSFORM_CACHE;
		
		cmsFile_makeDir($path);
		
		$dirs = glob($path . "/*", GLOB_NOSORT | GLOB_ONLYDIR);
		
		if (is_array($dirs) && count($dirs)) foreach($dirs as $e) {
			
			$files = glob($e . "/*.php", GLOB_NOSORT);
			
			if (is_array($files) && count($files)) foreach($files as $f) {
				
				if (@filemtime($f) < time() - CMSFORM_CACHE_LIFETIME) @unlink($f);
				
			}
			
			@rmdir($e);
			
		}
		
	}
	*/
	// --------------------------------------------------------------------------------------------------------------------------------------------------------------- //
	
}

//if (!isset($_SERVER[modForm][cacheNoClean])) cmsForm_ajax::cleanSessionCache();

?>