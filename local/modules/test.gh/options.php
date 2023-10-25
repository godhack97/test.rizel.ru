<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request['mid'] != '' ? $request['mid'] : $request['id']);

Loader::includeModule($module_id);

$aTabs = [
    [
        'DIV' => 'edit',
        'TAB' => 'Основные настройки',
        'TITLE' => 'Курсы валют',
        'OPTIONS' => [
            'Курсы',
            [
                'AUD',
                'AUD',
                'N',
                ['checkbox'],
            ],
            [
                'GBP', // name
                'GBP',
                'N',
                ['checkbox'],
            ],
            [
                'BYR',
                'BYR',
                'N',
                ['checkbox'],
            ],
            [
                'DKK',
                'DKK',
                'N',
                ['checkbox'],
            ],
            [
                'USD',
                'USD',
                'N',
                ['checkbox'],
            ],
            [
                'EUR',
                'EUR',
                'N',
                ['checkbox'],
            ],
            [
                'ISK',
                'ISK',
                'N',
                ['checkbox'],
            ],
        ]
    ]
];

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab['OPTIONS'] as $arOption) {
            if (!is_array($arOption)) {
                continue;
            }

            if ($request['apply']) {
                $optionValue = $request->getPost($arOption[0]);

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);
            }

            if ($request['default']) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id . '&lang=' . LANG);
}

$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs
);

$tabControl->Begin();
?>

<form action='<? echo ($APPLICATION->GetCurPage()); ?>?mid=<? echo ($module_id); ?>&lang=<? echo (LANG); ?>' method='post'>
    <?
    foreach ($aTabs as $aTab) {
        if ($aTab['OPTIONS']) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
        }
    }

    $tabControl->Buttons();
    echo (bitrix_sessid_post());
    ?>

    <input class='adm-btn-save' type='submit' name='apply' value='Применить' />
    <input type='submit' name='default' value='По умолчанию' />
</form>
<?
$tabControl->End();
