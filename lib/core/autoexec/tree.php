<? 
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	defined("CMSTREE_MAX_RECURSION") or define("CMSTREE_MAX_RECURSION", 20);
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	������� ���������� �������� ������ ���������, � ������ �� �����������
	 *	
	 *	@param	string	$table	�������
	 *	@param	string	$ids		������ ID, ��� �������������� ������ ��ID ��������� �������� (������ ���� ��� ������ ������)
	 *	@param	string	$what		��� �������� � ������ ����� ��� �������, ����������� �����������
	 *	@return	array
	 */
	function cmsTree_get($table, $ids, $what = "") {
		
		if ($what) $what = ", " . $what;
		
		// patch for single id
		//if (!is_array($ids)) $ids = array($ids);
		
		$level = 0;
		$tree = array();
		
		do {
			
			$r = sql::fetchAll("SELECT id, parent, pos, name, status{$what} FROM {$table} WHERE parent IN ('{$ids}') ORDER BY parent, pos");
			$aIds = array();
			
			foreach ($r as $f) {
				
				$aIds[] = $f[id];
				$tree[$level][] = $f;
				
			}
			
			$ids = join("','", $aIds);
			$level++;
			
			if ($level > CMSTREE_MAX_RECURSION) { cmsError("����������� ���� ({$level})"); break; }
			
		} while (count($aIds));
		
		return $tree;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	������� ������ ������ ��������� � �������� ������ ��� ����� �����������
	 *	
	 *	@param	array		$tree		����� ������ ���������
	 *	@return	array
	 */
	function cmsTree_parsePlain(&$tree) {
		
		$res = array();
		
		foreach ($tree as $arr) $res = array_merge($res, $arr);
		
		return $res;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/**
	 *	������� ������ ������ ��������� � ����������� ������ � ������ �����������, ��������� ������������ � ������� children
	 *	
	 *	@param	array		$tree		����� ������ ���������
	 *	@return	array
	 */
	function cmsTree_parse($tree, $parent, $level = 0) {
		
		$treeOut = array();
		
		if (count($tree[$level])) foreach ($tree[$level] as $n => $f) {
			
			if ($f[parent] == $parent) {
				
				$treeOut[$f[id]] = $f;
				$treeOut[$f[id]][depth]			= $level;
				$treeOut[$f[id]][children]	= cmsTree_parse($tree, $f[id], $level + 1);
				
			}
			
		}
		
		return $treeOut;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//////
	
?>