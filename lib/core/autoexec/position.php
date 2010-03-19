<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	// Проверить позиции элементов в таблице $table, зная $id
	
	function cmsPosition_checkID($table, $id, $parentField = "parent") {
		
		$i = 0;
		$f = sql::fetch("SELECT * FROM {$table} WHERE id='{$id}'");
		
		foreach (sql::fetchAll("SELECT * FROM {$table} WHERE {$parentField}='{$f[parent]}' ORDER BY pos") as $f) {
			
			sql::query("UPDATE {$table} SET pos='{$i}' WHERE id='{$f[id]}'");
			$i++;
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	// Проверить позиции элементов в таблице $table, под родителем $parent
	
	function cmsPosition_checkParent($table, $parent, $parentField = "parent") {
		
		$i = 0;
		
		foreach (sql::fetchAll("SELECT * FROM {$table} WHERE {$parentField}='{$parent}' ORDER BY pos") as $f) {
			
			sql::query("UPDATE {$table} SET pos='{$i}' WHERE id='{$f[id]}'");
			$i++;
			
		}
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	// Сменить позицию элемента $id в таблице $table, сдвинув его в сторону $where
	
	function cmsPosition($table, $id, $where = "up", $parentField = "parent") {
		
		if ($id != index) {
			
			// выбираем элемент
			$f = sql::fetch("SELECT * FROM {$table} WHERE id='{$id}'");
			
			$offset = ($where == 'up') ? -1 : 1;
			
			// выбираем крайний элемент под родителем
			$maxPos = sql::result("SELECT pos FROM {$table} WHERE {$parentField}='{$f[parent]}' ORDER BY pos DESC LIMIT 1");
			
			$newPos = $f[pos] + $offset;
			$oldPos = $f[pos];
			
			if ($newPos <= $maxPos && $newPos >= 0) { // новая позиция должна пренадлежать интервалу [0, max]
				
				$freq = sql::fetch("SELECT * FROM {$table} WHERE {$parentField}='{$f[parent]}' AND pos='{$newPos}' LIMIT 1");
				
				sql::query("UPDATE {$table} SET pos='{$newPos}' WHERE id='{$f[id]}'");
				sql::query("UPDATE {$table} SET pos='{$oldPos}' WHERE id='{$freq[id]}'");
				
				$report = true;
				
			} else $report = false;
			
		} else $report = false;
		
		return $report;
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	/*
	function cmsPosition_checkOffset_get($table, $id = "", $array = array(), $lft = 0, $rgt = 0) {
		
		$r = sql::fetchAll("SELECT id, parent, name FROM {$table} WHERE parent='{$id}' ORDER BY pos");
		
		foreach ($r as $f) {
			
			$lft++;
			$rgt = $lft + 1;
			$xrgt = $rgt;
			
			list ($rgt, $array) = cmsPosition_checkOffset_get($table, $f[id], $array, $lft, $rgt);
			
			if ($xrgt != $rgt) $rgt++;
			
			$array[$f[id]] = array(
				"id"			=> $f[id],
				"name"		=> $f[name],
				"lft"			=> $lft,
				"rgt"			=> $rgt,
			);
			
			if ($xrgt != $rgt) $lft = $rgt; else $lft = $rgt + 1;
			
		}
		
		return array($rgt, $array);
		
	}
	
	function cmsPosition_checkOffset($table, $id = "") {
		
		list ($rgt, $array) = cmsPosition_checkOffset_get($table, $id);
		
		foreach ($array as $pid => $f) {
			
			sql::update($table, "id='%id%'", array("id" => $f[id]), array(
				"lft" => $f[lft],
				"rgt" => $f[rgt]
			));
			
		}
		
	}
	*/
	
	function cmsPosition_checkOffset_get(&$subtree, &$update, $lft = 0) {
		
		foreach ($subtree as $id => $f) {
			
			if (count($f[children])) {
				
				$rgt = cmsPosition_checkOffset_get($subtree[$id][children], $update, $lft + 1) + 1;
				
			} else {
				
				$rgt = $lft + 1;
				
			}
			
			// this is optional
			$subtree[$id][lft] = $lft;
			$subtree[$id][rgt] = $rgt;
			
			$update[$id] = array(
				"id"		=> $f[id],
				//"name"	=> $f[name], // это избыточно — только для красоты
				"lft"		=> $lft,
				"rgt"		=> $rgt,
			);
			
			$lft = $rgt + 1;
			
		}
		
		// should return right of the last updated element
		return $rgt;
		
	}

	function cmsPosition_checkOffset($table, $root = 0) {
		
		$tree = cmsTree_get($table, $root);
		$tree = cmsTree_parse($tree, $root);
		
		$update = array();
		
		cmsPosition_checkOffset_get($tree, $update);
		sql::insertUpdate("cms_xcat_structure", $update);
		
	}
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>