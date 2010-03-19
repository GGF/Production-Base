<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	// переменная $pageContents
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
	if (isset($_REQUEST[sent]))				$pageContents .= "<script> $(document).ready(function() { alert(\"" . cmsLang_var("alerts.sent") . "\"); }); </script>";
	if (isset($_REQUEST[wrongCode]))	$pageContents .= "<script> $(document).ready(function() { alert(\"" . cmsLang_var("alerts.code") . "\"); }); </script>";
	if (isset($_REQUEST[timeout]))		$pageContents .= "<script> $(document).ready(function() { alert(\"" . cmsLang_var("alerts.time") . "\"); }); </script>";
	if (isset($_REQUEST[obligatory]))	$pageContents .= "<script> $(document).ready(function() { alert(\"" . cmsLang_var("alerts.oblg") . "\"); }); </script>";
	
	// ----------------------------------------------------------------------------------------------------------------------------------------------------------------//
	
?>