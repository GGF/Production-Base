<?
	
	defined("CMS") or die("Restricted usage: " . basename(__FILE__));
	
	$_SERVER[cmsEncoding]			= "windows-1251";	// UTF-8	— e.g. in HEADERs and XML
	$_SERVER[cmsEncodingCP]		= "CP1251";				// UTF-8	— where CP instead of WIN is required (iconv)
	$_SERVER[cmsEncodingSQL]	= "CP1251";				// UTF8		— in SQL

	mb_internal_encoding($_SERVER[cmsEncodingCP]);
	
	setLocale(LC_ALL,			"ru_RU.{$_SERVER[cmsEncodingCP]}", "Russian.{$_SERVER[cmsEncodingCP]}", "ru_RU", "Russian");
	setLocale(LC_NUMERIC,	"C", "en_US.UTF-8", "en_US", "English");
	
	setLocale(LC_ALL,			0);
	setLocale(LC_NUMERIC,	0);
	
	header("Content-type: text/html; charset={$_SERVER[cmsEncoding]}");

?>