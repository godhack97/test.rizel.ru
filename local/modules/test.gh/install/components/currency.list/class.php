<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;

class CurrencyList extends CBitrixComponent
{

    public $arResult;
    public $arParams;

    public function onPrepareComponentParams($arParams)
    {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }

        return $arParams;
    }

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    public function getCurrencyList(): ?array
    {
        $this->arResult['NAV'] = new \Bitrix\Main\UI\PageNavigation("nav-more");
        $this->arResult['NAV']->allowAllRecords(true)
            ->setPageSize($this->arParams['LINE_ELEMENT_COUNT'])
            ->initFromUri();

        $res = Gh\CurrencyTable::getList([
            'select' => $this->arParams['SHOW_COLUMNS'],
            'filter' => $this->getFilter(),
            'count_total' => true,
            'offset' => $this->arResult['NAV']->getOffset(),
            'limit' => $this->arResult['NAV']->getLimit(),
        ]);

        $this->arResult['NAV']->setRecordCount($res->getCount());

        return $res->fetchAll();
    }

    public function executeComponent()
    {
        Loader::IncludeModule('test.gh');
        $this->getResult();
        $this->IncludeComponentTemplate();
    }

    public function getResult()
    {
        $this->arResult['ROWS'] = Gh\CurrencyTable::getRows();
        $this->arResult['ITEMS'] = $this->getCurrencyList();
    }

    public function getFilter(): array
    {
        $filter = [];
        $request = Context::getCurrent()->getRequest();

        $request->getQueryList()->toArray();

        if ($request->get("COURSE_START") && $request->get("COURSE_END")) {
            $filter[] = [
                "LOGIC" => "AND",
                ['>=COURSE' => $request->get("COURSE_START")],
                ['<=COURSE' => $request->get("COURSE_END")],
            ];
        } elseif ($request->get("COURSE_START")) {
            $filter['>=COURSE'] = $request->get("COURSE_START");
        } elseif ($request->get("COURSE_END")) {
            $filter['<=COURSE'] = $request->get("COURSE_END");
        }

        if ($request->get("DATE_START") && $request->get("DATE_END")) {
            $filter[] = [
                "LOGIC" => "AND",
                ['>=DATE' => new DateTime($request->get("DATE_START"), 'Y-m-d')],
                ['<=DATE' => new DateTime($request->get("DATE_END"), 'Y-m-d')],
            ];
        } elseif ($request->get("DATE_START")) {
            $filter['>=DATE'] = new DateTime($request->get("DATE_START"), 'Y-m-d');
        } elseif ($request->get("DATE_END")) {
            $filter['<=DATE'] = new DateTime($request->get("DATE_END"), 'Y-m-d');
        }

        if ($request->get("CODE")) {
            $filter['=CODE'] = $request->get("CODE");
        }

        return $filter;
    }
}
