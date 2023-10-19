<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('test.gh');


$arResult = \Gh\General::getCount($arParams['SITE']);
$this->IncludeComponentTemplate();
?> 
