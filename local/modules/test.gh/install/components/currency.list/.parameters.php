<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\Loader::IncludeModule('test.gh');

$rowList = Gh\CurrencyTable::getRows();

$arComponentParameters = [
    'GROUPS' => [
        'BASE' => [
            'NAME' => 'Основные'
        ],
        'NAV' => [
            'NAME' => 'Навигация'
        ],
    ],

    'PARAMETERS' => [
		'USE_FILTER' => array(
			'PARENT' => 'BASE',
			'NAME' => 'Выводить фильтр',
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
		'LINE_ELEMENT_COUNT' => [
            'PARENT' => 'NAV',
			'NAME' => 'Кол-во записей на одной странице',
			'TYPE' => 'STRING',
			'HIDDEN' => isset($templateProperties['LIST_PRODUCT_ROW_VARIANTS']) ? 'Y' : 'N',
			'DEFAULT' => '3',
        ],
        'SHOW_COLUMNS' => [
            'PARENT' => 'BASE',
            'NAME' => 'Отображаемые колонки',
            'TYPE' => 'LIST',
            'VALUES' => $rowList,
            'REFRESH' => 'N',
            'DEFAULT' => '',
            'MULTIPLE' => 'Y',
        ],

        'CACHE_TIME' => [
            'DEFAULT' => 3600
        ],
    ],
];