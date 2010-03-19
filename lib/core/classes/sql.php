<?

defined("CMS") or die("Restricted usage: " . basename(__FILE__));

define(CMSSQL_CONNECTION_LANG,		"LANG");
define(CMSSQL_CONNECTION_SHARED,	"SHARED");

define(CMSSQL_REPORT_ARRAY,	false);
define(CMSSQL_REPORT_HTML,	true);
define(CMSSQL_REPORT_CLEAN,	true);

define(CMSSQL_FETCH_ID,			"fetch-id");
define(CMSSQL_FETCH,				false);
define(CMSSQL_FETCHID,			CMSSQL_FETCH_ID);

define(CMSSQL_TYPE_NORMAL,	"normal");
define(CMSSQL_TYPE_QUERY,		"query");
define(CMSSQL_TYPE_ERROR,		"error");
define(CMSSQL_TYPE_WARNING,	"warning");
define(CMSSQL_TYPE_NOTICE,	"notice");

/**
 *	������ ���� block � null, log - true, nolog - false
 *	������ block � ��������� ������, log ����-���������, force �������� � ����� ������
 */
define(CMSSQL_LOG,					false);				// default � log, but not display
define(CMSSQL_LOG_DEFAULT,	CMSSQL_LOG);	// synonym � DEPRECATED
define(CMSSQL_NOLOG,				CMSSQL_LOG);	// synonym � DEPRECATED
define(CMSSQL_LOG_BLOCK,		null);				// block
define(CMSSQL_LOG_FORCE,		true);				// force

class cmsSQL {
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : VARIABLE INITIALIZATION                                                                                                                         //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	public $_type = "";
	public $_connection = false;
	public $_persistent = false;
	public $_encoding = "";
	public $_base = "";
	public $_host = "";
	public $_maxPacket = false;
	
	public $_queries = 0;
	public $_errors = 0;
	public $_errorsArray = array();
	public $_warnings = 0;
	public $_log = array();
	public $_logLevel = false;
	public $_logForce = false;
	public $_execTime = 0;
	public $_queryTime = 0;
	public $_fetchTime = 0;
	public $_lastQuery = "";
	public $_tokens = array("AS", "BY", "IF", "IN", "IS", "ON", "OR", "TO", "ADD", "ALL", "AND", "ASC", "DEC", "DIV", "FOR", "INT", "KEY", "MOD", "NOT", "OUT", "SET", "SSL", "USE", "XOR", "BLOB", "BOTH", "CALL", "CASE", "CHAR", "DESC", "DROP", "DUAL", "EACH", "ELSE", "EXIT", "FROM", "GOTO", "INTO", "JOIN", "KEYS", "KILL", "LEFT", "LIKE", "LOAD", "LOCK", "LONG", "LOOP", "NULL", "READ", "REAL", "SHOW", "THEN", "TRUE", "UNDO", "WHEN", "WITH", "ALTER", "CHECK", "CROSS", "FALSE", "FETCH", "FLOAT", "FORCE", "FOUND", "GRANT", "GROUP", "INDEX", "INNER", "INOUT", "LEAVE", "LIMIT", "LINES", "MATCH", "ORDER", "OUTER", "PURGE", "RIGHT", "RLIKE", "TABLE", "UNION", "USAGE", "USING", "WHERE", "WHILE", "WRITE", "BEFORE", "BIGINT", "BINARY", "CHANGE", "COLUMN", "CREATE", "CURSOR", "DELETE", "DOUBLE", "ELSEIF", "EXISTS", "FIELDS", "HAVING", "IGNORE", "INFILE", "INSERT", "OPTION", "REGEXP", "RENAME", "REPEAT", "RETURN", "REVOKE", "SCHEMA", "SELECT", "SONAME", "TABLES", "UNIQUE", "UNLOCK", "UPDATE", "VALUES", "ANALYZE", "BETWEEN", "CASCADE", "COLLATE", "COLUMNS", "CONVERT", "DECIMAL", "DECLARE", "DEFAULT", "DELAYED", "ESCAPED", "EXPLAIN", "FOREIGN", "INTEGER", "ITERATE", "LEADING", "NATURAL", "NUMERIC", "OUTFILE", "PRIMARY", "REPLACE", "REQUIRE", "SCHEMAS", "SPATIAL", "TINYINT", "TRIGGER", "VARCHAR", "VARYING", "CONTINUE", "DATABASE", "DAY_HOUR", "DESCRIBE", "DISTINCT", "ENCLOSED", "FULLTEXT", "INTERVAL", "LONGBLOB", "LONGTEXT", "OPTIMIZE", "RESTRICT", "SMALLINT", "SPECIFIC", "SQLSTATE", "STARTING", "TINYBLOB", "TINYTEXT", "TRAILING", "UNSIGNED", "UTC_DATE", "UTC_TIME", "ZEROFILL", "CHARACTER", "CONDITION", "DATABASES", "LOCALTIME", "MEDIUMINT", "MIDDLEINT", "PRECISION", "PROCEDURE", "SENSITIVE", "SEPARATOR", "VARBINARY", "ASENSITIVE", "CONNECTION", "CONSTRAINT", "DAY_MINUTE", "DAY_SECOND", "MEDIUMBLOB", "MEDIUMTEXT", "OPTIONALLY", "PRIVILEGES", "REFERENCES", "SQLWARNING", "TERMINATED", "YEAR_MONTH", "DISTINCTROW", "HOUR_MINUTE", "HOUR_SECOND", "INSENSITIVE", "CURRENT_DATE", "CURRENT_TIME", "CURRENT_USER", "LOW_PRIORITY", "SQLEXCEPTION", "VARCHARACTER", "DETERMINISTIC", "HIGH_PRIORITY", "MINUTE_SECOND", "STRAIGHT_JOIN", "UTC_TIMESTAMP", "LOCALTIMESTAMP", "SQL_BIG_RESULT", "DAY_MICROSECOND", "HOUR_MICROSECOND", "SQL_SMALL_RESULT", "CURRENT_TIMESTAMP", "MINUTE_MICROSECOND", "NO_WRITE_TO_BINLOG", "SECOND_MICROSECOND", "SQL_CALC_FOUND_ROWS"); //, "SQL"
	public $_PREGtokens = array();
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	//                                                                                                                                                                 //
	//   CONSTRUCTOR : CLASS INITIALIZATION                                                                                                                            //
	//                                                                                                                                                                 //
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	function __construct($type, $array) {
		
		$logLevel = $array[log];
		$encoding = $array[encoding] ? $array[encoding] : $_SERVER[cmsEncodingSQL];
		
		if (!is_array($logLevel) || !count($logLevel)) $logLevel = array(CMSSQL_TYPE_ERROR	=> true);
		
		// always log normal
		$logLevel[CMSSQL_TYPE_NORMAL] = true;
		
		$this->_type = $type;
		
		$this->_tokens = array_reverse($this->_tokens);
		foreach ($this->_tokens as $t) {
			$this->_PREGtokens[patterns][] = "$t ";
			$this->_PREGtokens[replaces][] = "<b>{$t}</b> ";
			$this->_PREGtokens[patterns][] = " $t";
			$this->_PREGtokens[replaces][] = " <b>{$t}</b>";
		}
		
		$this->_logLevel = $logLevel;
		
		$connect = $array[persistent] ? "mysql_pconnect" : "mysql_connect";
		$this->_persistent = $array[persistent] ? true : false;
		
		$this->_connection = $connect($array[host], $array[name], $array[pass], true) or die("������ �����������: " . mysql_error());
		
		if (@mysql_select_db($array[base], $this->_connection)) {
			
			$this->_base = $array[base];
			$this->_host = $array[host];
			
			if (!$array[noCollation]) {
				
				// ��������� ��������� �����������
				$this->query("SET names '{$encoding}'");
				$this->query("SET character_set_client='{$encoding}', character_set_results='{$encoding}', collation_connection='{$encoding}_GENERAL_CI'");
				
			}
			
			$this->_encoding = $encoding;
			
		} else die("������ ������ ��: " . $this->error());
		
	}
	
	
	function maxPacket() {
		
		if (!$this->_maxPacket) {
			
			// ����������� ������������ ����� ������
			$r = $this->fetch("SHOW GLOBAL VARIABLES LIKE 'max_allowed_packet';");
			$this->_maxPacket = $r['Value'];
			
		}
		
		return $this->_maxPacket;
		
	}
	
	/**
	 *	���������� UNIX TIMESTAMP � ������ �����������
	 *	@return		float
	 */
	function time() {
		
		return array_sum(explode(" ", microTime()));
		
	}
	
	
	/**
	 *	���������� ��������������� ����� � �������������
	 *	@return		float
	 */
	function timeFormat($time) {
		
		return number_format($time * 1000, 0, ".", " ") . " ��";
		
	}
	
	
	/**
	 *	��������/��������� �������������� ����������� ����� ������
	 *	@param		string	$message			���������
	 *	@param		string	$type					��� ��������� (CMSSQL_TYPE_[NORMAL|QUERY|ERROR|WARNING|NOTICE])
	 *	@param		bool		$logOverride	�������� �� ��������� ��� ����������� ������ ����������� ��������� ����� ����
	 */
	function logForce($force = true) {
		
		$this->_logForce = $force;
		return ($force) ? "<b>{$this->_type}:</b> ������� ����� ������ ������ MySQL." : "<b>{$this->_type}:</b> �������� ����� ������ ������ MySQL.</b>";
		
	}
	
	
	/**
	 *	���������� ����� ������� � ������ ������
	 *	@param		string	$message				���������
	 *	@param		string	$type						��� ��������� (CMSSQL_TYPE_[NORMAL|QUERY|ERROR|WARNING|NOTICE])
	 *	@param		bool		$logOverride		�������� �� ��������� ��� ����������� ������ ����������� ��������� ����� ����
	 */
	function log($message, $type = CMSSQL_TYPE_NORMAL, $logOverride = false) {
		
		if ($type == CMSSQL_TYPE_WARNING) $this->_warnings++;
		if ($type == CMSSQL_TYPE_ERROR)	{
			
			$this->_errors++;
			$this->_errorsArray[] = $message;
			
		}
		
		$this->_log[$this->_queries][] = array($type, $message, $logOverride);
		
		// ���� ����� ������������� ����� ������ ������ � ������� �����
		if ($type == "error" && $this->_logForce) $this->error(true);
		
	}
	
	
	/**
	 *	���������� ����������������� ������
	 *	@param		string	$query	������
	 *	@return		string
	 */
	function format($query) {
		
		$query = str_replace("\r", "", $query);
		preg_match_all('/^([\t]+)/sim', $query, $regs, PREG_PATTERN_ORDER);
		if (count($regs[0])) {
			
			$a = array();
			foreach ($regs[0] as $k => $v) $a[] = mb_strlen($v);
			
			$a = '/^' . str_repeat('\t', min($a)) . '/sim';
			$xquery = array();
			foreach (explode("\n", $query) as $q) $xquery[] = preg_replace($a, '', $q);
			
			$query = "\n" . implode("\n", $xquery);
			
		}
		
		return str_replace($this->_PREGtokens[patterns], $this->_PREGtokens[replaces], $query);
		
	}
	
	
	/**
	 *	������� ���
	 *	@param	bool	$html		�������� � ���� html (����� � ���� �������) [CMSSQL_REPORT_ARRAY|CMSSQL_REPORT_HTML]
	 *	@param	bool	$clean	������� �� ��� ����� ������ [CMSSQL_REPORT_CLEAN]
	 *	@return	mixed
	 */
	function logOut($html = CMSSQL_REPORT_HTML, $clean = false) {
		
		$k = 0;
		$array = array();
		
		$array[] = array("<b>����� ��� MySQL ���������� �{$this->_type}�.</b>", CMSCONSOLE_NOTICE);
		$array[] = array("�������� " . ($this->_persistent ? "����������" : "�������") . " ����������� � <b>{$this->_base}@{$this->_host}</b>,  ��������� ����������: <b>{$this->_encoding}</b>.", CMSCONSOLE_NOTICE);
		//$array[] = array("������������ ������ ������ <small>(max_allowed_packet)</small>: <b>{$this->_maxPacket} ����</b>.", CMSCONSOLE_NOTICE);
		
		$array[] = array("<img src='/images/free.gif'>", "");
		
		//cmsVar($this->_log);
		
		//for ($i = 0, $im = count($this->_log); $i < $im; $i++) { 
		foreach ($this->_log as $i => $logLine) {
			
			// ��-��������� �������, ��� ��� �� :)
			$hasErrors		= false;
			$hasWarnings	= false;
			$hasNotices		= false;
			$hasOverrides	= false;
			
			// ������ ����, ��������, ��� ������
			//if (count($this->_log[$i])) foreach ($this->_log[$i] as $l) {
			if (count($logLine)) {
				
				foreach ($logLine as $l) {
				
					list($type, $msg, $override) = $l;
					
					if ($override == CMSSQL_LOG_FORCE)	$hasOverrides	= true;
					if ($type == CMSSQL_TYPE_WARNING)		$hasWarnings	= true;
					if ($type == CMSSQL_TYPE_ERROR)			$hasErrors		= true;
					if ($type == CMSSQL_TYPE_NOTICE)		$hasNotices		= true;
					
				}
				
				$out = false;
				
				// ������ ���� � �������� ������
				//if (count($this->_log[$i])) foreach ($this->_log[$i] as $l) {
				foreach ($logLine as $l) {
					
					list($type, $msg, $override) = $l;
					$msg = trim($msg);
					
					$consoleType = ($type == CMSSQL_TYPE_WARNING || $type == CMSSQL_TYPE_ERROR || $type == CMSSQL_TYPE_NOTICE) ? $type : "";
					
					// ������ � CMSSQL_LOG_BLOCK �� ���� ���� �� ������
					if (
						// ���� ��������
						$hasOverrides ||
						// ���������� �����
						$type == CMSSQL_TYPE_NORMAL ||
						// �������� �������� � �� ����� �������, ��� ������ ���� ������/�������������� � �� ����� �������
						(
							($type == CMSSQL_TYPE_QUERY && array_key_exists(CMSSQL_TYPE_QUERY, $this->_logLevel)) || (
								($hasErrors		&& array_key_exists(CMSSQL_TYPE_ERROR,		$this->_logLevel)) ||
								($hasWarnings	&& array_key_exists(CMSSQL_TYPE_WARNING,	$this->_logLevel))
							)
						) ||
						// �������� �������/���������������/������������ � ��� ����� �������
						($type == CMSSQL_TYPE_ERROR		&& array_key_exists(CMSSQL_TYPE_ERROR,		$this->_logLevel)) ||
						($type == CMSSQL_TYPE_WARNING	&& array_key_exists(CMSSQL_TYPE_WARNING,	$this->_logLevel)) ||
						($type == CMSSQL_TYPE_NOTICE	&& array_key_exists(CMSSQL_TYPE_NOTICE,		$this->_logLevel))
					) {
						
						if ($type == CMSSQL_TYPE_QUERY) $msg = $this->format($msg);
						
						$cssType = "mysql_{$type}";
						
						if ($hasNotices)	$cssType .= " mysql_" . CMSSQL_TYPE_NOTICE;
						if ($hasWarnings)	$cssType .= " mysql_" . CMSSQL_TYPE_WARNING;
						if ($hasErrors)		$cssType .= " mysql_" . CMSSQL_TYPE_ERROR;
						
						$array[] = array("<pre class='mysql {$cssType}'><b>" . UCFirst($type) . ":</b> {$msg}</pre>", $consoleType);
						
						$out = true;
						
					}
					
				}
				
			}
			
			if ($out) $array[] = array("<img src='/images/free.gif'>", "");
			
		}
		
		$array[] = array(print_r($this->_logLevel, 1), CMSCONSOLE_NOTICE);
		$array[] = array("��������: <b>{$this->_queries}</b>, ��������������: <b>{$this->_warnings}</b>, ������: <b>{$this->_errors}</b>.", CMSCONSOLE_NOTICE);
		$array[] = array("<b>������ ����� ����������: <u>" . $this->timeFormat($this->_execTime) . "</u>, ��������:  <u>" . $this->timeFormat($this->_queryTime) . "</u>, ������� ������: <u>" . $this->timeFormat($this->_fetchTime) . "</u>.</b>", CMSCONSOLE_NOTICE);
		
		if ($clean) $this->_log = array();
		
		if ($html) {
			
			ob_start();
			foreach ($array as $a) print "<div>{$a[0]}</div>";
			return ob_get_clean();
			
		} else return $array;
		
	}
	
	
	/**
	 *	������� ���������� ��� ������� ����� � ��������� ������
	 *	@param	bool	$print	�������� ��� ����������
	 *	@return	mixed[string|void]
	 */
	function error($print = false) {
		
		$error = mysql_errno($this->_connection);
		
		if ($error) {
			
			$return = "<b>MYSQL ERROR #{$error}:</b> " . mysql_error($this->_connection);
			
			if ($print) print "<div class='cmsError'>{$return}<br><b>IN QUERY:</b><br><pre class='mysql'>" . $this->format(htmlSpecialChars($this->_lastQuery)) . "\n" . cmsBacktrace(CMSBACKTRACE_RAW) . "</pre></div>"; else return $return;
			
		} else return null;
		
	}
	
	
	/**
	 *	������� ���������� escape ����������
	 *	@param	string	$text		������
	 *	@return	string
	 */
	function check($text) {
		
		if (is_array($text)) {
			
			foreach ($text as $k => $v) $text[$k] = $this->check($v);
			
			return $text;
			
		} else {
			
			return mysql_real_escape_string($text, $this->_connection);
			
		}
		
	}
	
	
	/**
	 *	������� ���������� ������� ID ������ c AUTO_INCREMENT, ������� �� ���������, �.�. ����� ����� �������� ������ ������� ����� ���-���� �������� � �������� ����
	 *	@return	int
	 */
	function nextId($table) {
		
		$res = $this->fetch("SHOW TABLE STATUS LIKE '{$table}'");
		return $res['Auto_increment'];
		
	}
	
	
	/**
	 *	������� ���������� ��������� ����������� ID ������ c AUTO_INCREMENT
	 *	@return	int
	 */
	function lastId() {
		
		//return mysql_num_rows(mysql_query("SELECT LAST_INSERT_ID() FROM $table"))
		return mysql_insert_id($this->_connection);
		
	}
	
	
	/**
	 *	������� ���������� ���-�� �����, ������� ���� ��������� ��������� ���������
	 *	@return	int
	 */
	function affected() {
		
		return mysql_affected_rows($this->_connection);
		
	}
	
	
	/**
	 *	������� ���������� ������ ������� %���% �� ���������� �������
	 *	@param	string	$SQL		������
	 *	@param	array		$array	������ ��� �����������
	 */
	function prepare($SQL, $array) {
		
		if (count($array)) foreach ($array as $k => $v) {
			
			$SQL = str_replace("%{$k}%", $this->check(strval($v)), $SQL);
			
		}
		
		return $SQL;
		
	}
	
	
	/**
	 *	������� ������������ ������ ������� ����������� � ��������� ������
	 *	@param	string			$SQL		������
	 *	@param	array|bool	$array	������ ��� ����������, ��� ������������� ����� ������� ����� ���
	 *	@param	bool				$log		����� ����: CMSSQL_LOG � �����, �� �� �������, _FORCE ������� � ������� ������, _BLOCK ����������� ������, �� ���� ����� ������ � ��� ����� ��������� � �������, �� ������ ������� ����� ����� � ����������
	 */
	function query($SQL, $array = array(), $log = CMSSQL_LOG) {
		
		$time = $this->time();
		
		$deprecated = false;
		
		$stack = is_callable("cmsBacktrace") ? "\n" . cmsBacktrace(CMSBACKTRACE_RAW) : "������� cmsBacktrace ��� �� ����������������.";
		
		// ������ �� ���� ��������, ������� ������ ��� �������� ������������� ������ ����� �� ���������
		// backward compatibility ��DEPRECATED!!!
		if (!is_array($array)) {
			
			$deprecated = true;
			
			$log = $array;
			$array = array();
			
		}
		
		$SQL = $this->prepare($SQL, $array);
		
		$SQLlog = ($log !== CMSSQL_LOG_BLOCK) ? $SQL : "������ ������� ������������� ����� LOG_BLOCK.";
		
		// exec
		$r = @mysql_query($SQL, $this->_connection); // suppress or not suppress ;)
		$this->_queries++;
		$this->_lastQuery = $SQLlog;
		
		$this->log($SQLlog, CMSSQL_TYPE_QUERY, $log);
		
		if ($deprecated) $this->log("������� ���� ������� �� ������ ������� ���������� (��� �������).{$stack}", CMSSQL_TYPE_WARNING, CMSSQL_LOG_FORCE); // ���� ������� �������� ���� � ���
		
		if ($this->error()) {
			$r = false;
			$this->log($this->error() . $stack, CMSSQL_TYPE_ERROR, CMSSQL_LOG_FORCE); // ������ � ��� ���� ��� ����������� �� ���������
		}
		
		$time = $this->time() - $time;
		$this->_queryTime += $time;
		$this->_execTime += $time;
		
		return $r;
		
	}
	
	
	/**
	 *	������� ��������� result() ���� ��� ������� result(), ���� ������ ������ � ���������� ������ ������ ������
	 *	@param	resource|string		$res		���� �������� ������ � ������ ������, ���� ������� ������ � ���������� ���, ���� �� �������� ������ � ����� ��������� ���������� ������ � �����������
	 *	@param	array							$array	������ ��� ����������� � ������ (����� ��� ����� ���� � ����� ��� ����, ��. ������� query() � ��� DEPRECATED!)
	 *	@param	int								$i			����� ���� ��� ��������
	 *	@param	bool							$log		��� ����, ��. ������� query()
	 *	@return	array
	 */
	function result($res, $array = array(), $i = 0, $log = CMSSQL_LOG) {
		
		if (!is_int($i)) {
			
			$log = $i;
			$i = 0;
			
		}
		
		$time = $this->time();
		
		if ($res && !is_resource($res)) {
			
			$return = $this->resultOne($res, $array, $i, $log);
			
		} else {
			
			$return = @mysql_result($res, $i); // $i �������������� ��������!
			
		}
		
		//if ($return == false) $this->log("result(): ������ ����� ��", CMSSQL_TYPE_WARNING);
		
		$time = $this->time() - $time;
		$this->_execTime += $time;
		
		return $return;
		
	}
	
	
	/**
	 *	������� ��������� ������ � ���������� ������ ������
	 *	@param	string		$query	������
	 *	@param	array			$array	������ ��� ����������� � ������ (����� ��� ����� ���� � ����� ��� ����, ��. ������� query() � ��� DEPRECATED!)
	 *	@param	int				$i			����� ���� ��� ��������
	 *	@param	bool			$log		��� ����, ��. ������� query()
	 *	@return	array
	 */
	function resultOne($query, $array = array(), $i = 0, $log = CMSSQL_LOG) {
		
		return $this->result($this->query($query, $array, $log), $array, $i, $log);
		
	}
	
	
	/**
	 *	������� ��������� ���� ���� ��� ������� fetch_assoc(), ���� ������ ������ � ���������� ������ ������ ������
	 *	@param	resource|string		$res		���� �������� ������ � ������ ������, ���� ������� ������ � ���������� ���, ���� �� �������� ������ � ����� ��������� ���������� ������ � �����������
	 *	@param	array							$array	������ ��� ����������� � ������ (����� ��� ����� ���� � ����� ��� ����, ��. ������� query() � ��� DEPRECATED!)
	 *	@param	bool							$log		��� ����, ��. ������� query()
	 *	@return	array
	 */
	function fetch($res, $array = array(), $log = CMSSQL_LOG) {
		
		$time = $this->time();
		
		if (is_string($res)) {
			
			$return =  $this->fetchOne($res, $array, $log);
			
		} else {
			
			$return = @mysql_fetch_assoc($res);
			
		}
		
		//if (!count($return) || !$return) $this->log("fetch(): ������ ����� ��", CMSSQL_TYPE_WARNING);
		
		$time = $this->time() - $time;
		$this->_fetchTime += $time;
		$this->_execTime += $time;
		
		return $return;
		
	}
	
	
	/**
	 *	������� ��������� ������ � ���������� ��� ������
	 *	@param	string		$query	������
	 *	@param	array			$array	������ ��� ����������� � ������ (����� ��� ����� ���� � ����� ��� ����, ��. ������� query() � ��� DEPRECATED!)
	 *	@param	bool			$log		��� ����, ��. ������� query()
	 *	@param	bool			$id			���� �������� ��������� CMSSQL_FETCH_ID �������� ������������� ������ � ����� ID �� ������ ������ � �������� ������ [CMSSQL_FETCH|CMSSQL_FETCH_ID]
	 *	@return	array
	 */
	function fetchAll($SQL, $array = array(), $log = CMSSQL_LOG, $id = CMSSQL_FETCH) {
		
		// ��� ��������� ���� ����� �������� ������� �������� FETCHID, � ����� ��� LOG, �.�. � ����������� ������� ��� �������� ���������, � �������� ������ ����
		if ($log === CMSSQL_FETCH_ID) {
			
			$log = ($id != CMSSQL_FETCH) ? $id : CMSSQL_LOG;
			$id = CMSSQL_FETCH_ID;
			
		}
		
		$time = $this->time();
		$output = array();
		$k = 0;
		
		$r = $this->query($SQL, $array, $log);
		if ($r) {
			
			while ($f = $this->fetch($r)) {
				
				if (isset($f[id]) && $id == CMSSQL_FETCH_ID) $output[$f[id]] = $f; else { $output[$k++] = $f; }
				
			}
			
		}
		
		//if (count($output) == 0) $this->log("fetchAll(): ������ ����� ��", CMSSQL_TYPE_WARNING);
		
		$time = $this->time() - $time;
		$this->_execTime += $time;
		
		return $output;
		
	}
	
	
	/**
	 *	������� ��������� ������ � ���������� ������ ������
	 *	@param	string		$query	������
	 *	@param	array			$array	������ ��� ����������� � ������ (����� ��� ����� ���� � ����� ��� ����, ��. ������� query() � ��� DEPRECATED!)
	 *	@param	bool			$log		��� ����, ��. ������� query()
	 *	@return	array
	 */
	function fetchOne($query, $array = array(), $log = CMSSQL_LOG) {
		
		return $this->fetch($this->query($query, $array, $log), $array, $log);
		
	}
	
	
	/**
	 *	������� ���������� ����� ����� �� ������ ������� ��� �������, ������ ��� ��� �� ������� ����������
	 *	@param	array		$data					������ ��� �������, ������ ������� ��� �������� ������
	 *	@param	bool		$returnArray	���������� ������ ��� ����� ������ implode
	 *	@return mixed[array|bool]
	 */
	function insert_getFields(&$data, $returnArray = false) {
		
		$fields = array();
		foreach ($data as $k => $v) $fields[] = "`{$k}`";
		
		return $returnArray ? $fields : implode(", ", $fields);
		
	}
	
	
	/**
	 *	������� �������������� ������� ������ ��� �������
	 *	@param	array		$data		������ ��� �������, ������ ������� ��� �������� ������
	 *	@return array
	 */
	function insert_prepare(&$data) {
		
		$sql = "";
		
		foreach ($data as $k => $v) $data[$k] = "'" . $this->check($v) . "'";
		return "(" . implode(", ", $data) . ")";
		
	}
	
	
	/**
	 *	������� ��������� � �������������� ������ ��� �������, � ���������� ��������������� ������� � �������, � ����������� ������������, ���������� affected rows
	 *	@param	string	$table	��� �������
	 *	@param	array		$data		������ ��� �������. ������ ����� ���� ����� �� ���������: ����� ������ ���������, ������ �� �������� ��������� � ���� ��������, ��� ������, ���� � ������� (����� � ����� �����, � ����� ������ ����� ���� �������� �������, � ������������ �������). ������� ���������� ��������� ������ ���� ���� �� ��� ����������.
	 *	@param	bool		$log		��� ����, ��. ������� query()
	 *	@return	int
	 */
	function insert($table, $data, $log = CMSSQL_LOG) {
		
		if (isset($data[0]) && is_array($data[0])) { // ������������� �������
			
			$time = $this->time();
			
			// ����������, �������� �� ������ࠫ�����������
			$fields = isset($data[0][0]) ? "" : "(" . $this->insert_getFields($data[0]) . ") ";
			$values = array();
			
			$SQL = array();
			$SQLn = 0;
			$SQLbase = "INSERT INTO `{$table}` {$fields} VALUES ";
			
			foreach ($data as $dataRow) $values[] = $this->insert_prepare($dataRow);
			
			$SQL[0] = $SQLbase;
			foreach ($values as $SQLrow) {
				
				if (mb_strlen($SQL . $SQLrow . ", ") > $this->maxPacket()) { $SQLn++; $SQL[$SQLn] = $SQLbase; $this->log("insert(): ����� ��������� �{$c}", CMSSQL_TYPE_WARNING); }
				$SQL[$SQLn] .= $SQLrow . ", ";
				
			}
			
			$time = $this->time() - $time;
			$this->_execTime += $time;
			
			foreach ($SQL as $query) $this->query(mb_substr($query, 0, -2), array(), $log);
			
		} elseif (isset($data[0]) && !is_array($data[0])) { // plain
			
			$values = $this->insert_prepare($data);
			
			$this->query("INSERT INTO `{$table}` VALUES {$values}", array(), $log);
			
		} else { // named
			
			$fields = $this->insert_getFields($data);
			$values = $this->insert_prepare($data);
			
			$this->query("INSERT INTO `{$table}` ({$fields}) VALUES {$values}", array(), $log);
			
		}
		
		//if (!$this->error()) $this->query("OPTIMIZE TABLE {$table}", array(), CMSSQL_LOG_BLOCK);
		
		// Cleanup
		unset($data);
		unset($SQL);
		unset($query);
		unset($values);
		
		return $this->affected();
		
	}
	
	
	/**
	 *	������� ��������� � �������� �������, ����� ������������ ��� ��������� update, ���������� affected rows
	 *	@param	string	$table		������� ��� ����������
	 *	@param	array		$data			������ �� �������� � �������, ����� ��������, ��� ����� ����� ��� ������� ������� �� ������� ����������
	 *	@param	array		$exclude	������ �����, �� ����������� � ���������� (������ ID � �� ����, �� ������� ����� ���������������� ������������ �������, ���-�� ���)
	 *	@param	bool		$log			��� ����, ��. ������� query()
	 *	@return	int
	 */
	function insertUpdate($table, $data, $exclude = array("id"), $log = CMSSQL_LOG) {
		
		if (!is_array($data) || count($data) < 1) return false;
		
		$fields = $this->insert_getFields(reset($data), true);
		$valuse = array();
		
		foreach ($data as $dataRow) $values[] = $this->insert_prepare($dataRow);
		
		$upd = array();
		$sql = "INSERT INTO `{$table}` (" . implode(", ", $fields) . ") VALUES ";
		foreach ($fields as $field) if (@!in_array(substr($field, 1, -1), $exclude)) $upd[] = "{$field} = VALUES({$field})";
		$upd = " ON DUPLICATE KEY UPDATE " . implode(", ", $upd);
		
		$SQLs = array();
		$n = 0;
		
		foreach ($values as $value) {
			
			if (mb_strlen($sql) + mb_strlen($SQLs[$n] . $value . ", ") + mb_strlen($upd) > $this->maxPacket()) { $n++; $this->log("insertUpdate(): ����� ��������� �{$n}", CMSSQL_TYPE_WARNING); }
			
			$SQLs[$n] .= $value . ", ";
			
		}
		
		foreach ($SQLs as $SQLn) $this->query($sql . substr($SQLn, 0, -2) . $upd, array(), $log);
		
		// ������������, ���� ��� ������
		//if (!$this->error()) $this->query("OPTIMIZE TABLE {$table}", array(), CMSSQL_LOG_BLOCK);
		
		// Cleanup
		unset($data);
		unset($SQLs);
		unset($upd);
		
		return $this->affected();
		
	}
	
	
	/**
	 *	������� ��������� ������� ��������� update �� �������, ���������� affected rows
	 *	@param	string	$table		������� ��� ����������
	 *	@param	array		$where		��������� ������� ��� WHERE, ����� ��������� ������ %xxx%
	 *	@param	array		$array		������ ��� ����������� � ������ (����� ��� ����� ���� � ����� ��� ����, ��. ������� query() � ��� DEPRECATED!)
	 *	@param	array		$data			������ �� �������� � �������, ����� ��������, ��� ����� ����� ��� ������� ������� �� ������� ����������
	 *	@param	bool		$log			��� ����, ��. ������� query()
	 *	@return	int
	 */
	function update($table, $where, $array, $data = false, $log = CMSSQL_LOG) {
		
		// ������ �� ���� ��������, ������� ������ ��� �������� ������������� ������ ����� �� ���������
		// backward compatibility ��DEPRECATED!!!
		if (!$data) {
			
			$data = $array;
			$array = array();
			
		}
		
		$time = $this->time();
		$sql = array();
		
		foreach ($data as $k => $v) $sql[] = $this->check($k) . " = '" . $this->check($v) . "'";
		$this->query("UPDATE `{$table}` SET " . implode(", ", $sql) . " WHERE {$where}", $array, $log);
		
		// ������������, ���� ��� ������
		//if (!$this->error()) $this->query("OPTIMIZE TABLE {$table}", array(), CMSSQL_LOG_BLOCK);
		
		return $this->affected();
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
}

?>