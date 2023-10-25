<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Context;
use Bitrix\Main\Loader;

class CurrencyFilter extends CBitrixComponent
{

    public $arResult;
    public $arParams;

    public function onPrepareComponentParams($arParams)
    {
    }

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    public function getCurrencyList(): ?array
    {
    }

    public function executeComponent()
    {
        Loader::IncludeModule('test.gh');
        $this->getResult();
        $this->IncludeComponentTemplate();
    }

    public function getResult()
    {
        $request = Context::getCurrent()->getRequest();

        $request->getQueryList()->toArray();

        $this->arResult['COURSE_START'] = $request->get("COURSE_START");
        $this->arResult['COURSE_END'] = $request->get("COURSE_END");
        $this->arResult['DATE_START'] = $request->get("DATE_START");
        $this->arResult['DATE_END'] = $request->get("DATE_END");
        $this->arResult['CODE'] = $request->get("CODE");
    }
}
