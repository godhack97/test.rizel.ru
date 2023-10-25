<?php
/** @global CMain $APPLICATION */
use Bitrix\Main;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/currency/prolog.php';
$CURRENCY_RIGHT = $APPLICATION->GetGroupRight('test.gh');
if ('D' == $CURRENCY_RIGHT) {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule('test.gh');

$adminListTableID = 't_currency_rates';
$adminSort        = new CAdminSorting($adminListTableID, 'DATE', 'DESC');
$adminList        = new CAdminList($adminListTableID, $adminSort);

$arFilterFields = [
    'filter_date_from',
    'filter_date_to',
    'filter_code',
    'filter_course',
];

$adminList->InitFilter($arFilterFields);

$adminFilter = new CAdminFilter(
    $adminListTableID . '_filter',
    [
        'Дата',
        'Валюта',
        'Курс',
    ]
);

$filter = [];

if (!empty($filter_course)) {
    $filter['=COURSE'] = $filter_course;
}

if (!empty($filter_code)) {
    $filter['=CODE'] = $filter_code;
}

if (!empty($filter_date_from)) {
    try
    {
        $filter['>=DATE'] = new Main\Type\Date($filter_date_from);
    } catch (Main\ObjectException $e) {
        $filter_date_from = '';
    }
}
if (!empty($filter_date_to)) {
    try
    {
        $filter['<=DATE'] = new Main\Type\Date($filter_date_to);
    } catch (Main\ObjectException $e) {
        $filter_date_to = '';
    }
}

$orderConvert = [
    'COURSE' => 'COURSE',
    'DATE' => 'DATE',
    'CODE' => 'CODE',
];

$by = mb_strtoupper($adminSort->getField());
if (isset($orderConvert[$by])) {
    $by = $orderConvert[$by];
}

$order     = mb_strtoupper($adminSort->getOrder());
$rateOrder = [];

if ('W' == $CURRENCY_RIGHT && $adminList->EditAction()) {
    foreach ($adminList->GetEditFields() as $ID => $arFields) {
        $ID = (int) $ID;

        $arCurR  = Gh\CurrencyTable::GetByID($ID)->fetch();

        $arFields['DATE'] = new DateTime($arFields['DATE']);
        $res = Gh\CurrencyTable::Update($ID, $arFields);

        if (!$res) {
            if ($e = $APPLICATION->GetException()) {
                $adminList->AddUpdateError(GetMessage('SAVE_ERROR') . $ID . ': ' . str_replace('<br>', ' ', $e->GetString()), $ID);
            }

        }
    }
}

if ('W' == $CURRENCY_RIGHT && $arID = $adminList->GroupAction()) {
    if ('selected' == $_REQUEST['action_target']) {
        $arID         = [];
        $rateIterator = Gh\CurrencyTable::getList([
            'select' => ['ID'],
            'filter' => $filter,
            'order'  => $rateOrder,
        ]);

        while ($rate = $rateIterator->fetch()) {
            $arID[] = (int) $rate['ID'];
        }

        unset($rate, $rateIterator);
    }

    foreach ($arID as $ID) {
        $ID = (int) ($ID);

        if ($ID <= 0) {
            continue;
        }

        switch ($_REQUEST['action']) {
            case 'delete':
                Gh\CurrencyTable::Delete($ID);
                break;
        }
    }
}

$currencyList = Gh\CurrencyTable::getList();

$usePageNavigation = true;
$navyParams        = [];
if ($adminList->isExportMode()) {
    $usePageNavigation = false;
} else {
    $navyParams = CDBResult::GetNavParams(CAdminResult::GetNavSize($adminListTableID));
    if ($navyParams['SHOW_ALL']) {
        $usePageNavigation = false;
    } else {
        $navyParams['PAGEN'] = (int) $navyParams['PAGEN'];
        $navyParams['SIZEN'] = (int) $navyParams['SIZEN'];
    }
}


$selectFields  = array('*');
$getListParams = array(
    'select' => $selectFields,
    'filter' => $filter,
    'order'  => $rateOrder,
);
if ($usePageNavigation) {
    $getListParams['limit']  = $navyParams['SIZEN'];
    $getListParams['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
}
$totalPages = 0;
$totalCount = 0;
if ($usePageNavigation) {
    $countQuery = new Main\Entity\Query(Gh\CurrencyTable::getEntity());
    $countQuery->addSelect(new Main\Entity\ExpressionField('CNT', 'COUNT(1)'));
    $countQuery->setFilter($getListParams['filter']);
    $totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
    unset($countQuery);
    $totalCount = (int) $totalCount['CNT'];
    if ($totalCount > 0) {
        $totalPages = ceil($totalCount / $navyParams['SIZEN']);
        if ($navyParams['PAGEN'] > $totalPages) {
            $navyParams['PAGEN'] = $totalPages;
        }

        $getListParams['limit']  = $navyParams['SIZEN'];
        $getListParams['offset'] = $navyParams['SIZEN'] * ($navyParams['PAGEN'] - 1);
    } else {
        $navyParams['PAGEN']     = 1;
        $getListParams['limit']  = $navyParams['SIZEN'];
        $getListParams['offset'] = 0;
    }
}


$rateIterator = new CAdminResult(Gh\CurrencyTable::getList($getListParams), $adminListTableID);
if ($usePageNavigation) {
    $rateIterator->NavStart($getListParams['limit'], $navyParams['SHOW_ALL'], $navyParams['PAGEN']);
    $rateIterator->NavRecordCount = $totalCount;
    $rateIterator->NavPageCount   = $totalPages;
    $rateIterator->NavPageNomer   = $navyParams['PAGEN'];
} else {
    $rateIterator->NavStart();
}

$adminList->NavText($rateIterator->GetNavPrint(GetMessage('curr_rates_nav')));

$arHeaders   = [];
$arHeaders[] = [
    'id'      => 'ID',
    'content' => 'ID',
    'default' => true,
];
$arHeaders[] = [
    'id'      => 'DATE',
    'content' => 'DATE',
    'sort'    => 'DATE',
    'default' => false,
];
$arHeaders[] = [
    'id'      => 'COURSE',
    'content' => 'COURSE',
    'sort'    => 'COURSE',
    'default' => false,
];
$arHeaders[] = [
    'id'      => 'CODE',
    'content' => 'CODE',
    'sort'    => 'CODE',
    'default' => false,
];

$adminList->AddHeaders($arHeaders);

while ($rate = $rateIterator->Fetch()) {
    $row     = &$adminList->AddRow($rate['ID'], $rate, $editUrl, GetMessage('CURRENCY_RATES_A_EDIT'));

    $row->AddCalendarField('DATE');
    $row->AddInputField('COURSE', ['size' => '5']);
    $row->AddInputField('CODE', ['size' => '10']);

    $arActions = [];

    if ('W' == $CURRENCY_RIGHT) {
        $arActions[] = ['SEPARATOR' => true];
        $arActions[] = [
            'ICON'   => 'delete',
            'TEXT'   => 'Удалить',
            'ACTION' => "if(confirm('Удалить элемент?')) " . $adminList->ActionDoGroup($rate['ID'], 'delete'),
        ];
    }

    $row->AddActions($arActions);

    unset($editUrl);
}

if ('W' == $CURRENCY_RIGHT) {
    $adminList->AddGroupActionTable([
        'delete' => 'Удалить',
    ]);
}


$adminList->CheckListMode();

$APPLICATION->SetTitle('Курсы валют');
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';
?>
<?php
$adminList->DisplayList();
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';
