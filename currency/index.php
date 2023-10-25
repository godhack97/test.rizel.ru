<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?$APPLICATION->IncludeComponent(
	"test.gh:currency.list", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SHOW_COLUMNS" => array(
			0 => "ID",
			1 => "CODE",
			2 => "DATE",
			3 => "COURSE",
		),
		"LINE_ELEMENT_COUNT" => "10",
		"USE_FILTER" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>