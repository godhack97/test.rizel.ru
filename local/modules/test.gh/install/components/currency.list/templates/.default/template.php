<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
if ('Y' === $arParams['USE_FILTER']) {

    $APPLICATION->IncludeComponent(
        "test.gh:currency.filter",
        ".default",
        array(
            "COMPONENT_TEMPLATE" => ".default",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
        ),
        false
    );
}
?>

<table>
    <colgroup>
        <col span="1" style="background:Khaki">
        <col style="background-color:LightCyan">
        <col span="1" style="background:Khaki">
        <col style="background-color:LightCyan">
    </colgroup>
    <tr>
        <?foreach ($arParams['SHOW_COLUMNS'] as $key => $value) {?>
            <th><?= $value ?></th>
        <?}?>
    </tr>
    <?foreach ($arResult['ITEMS'] as $key => $arItem) {?>
        <tr>
            <?foreach ($arItem as $columnName => $value) {?>
                <td><?=$value?></td>
            <?}?>
        </tr>
    <?}?>
</table>
<br>
<br>
<?
$APPLICATION->IncludeComponent(
    'bitrix:main.pagenavigation',
    '',
    array(
        'NAV_OBJECT' => $arResult['NAV'],
        'SEF_MODE' => 'N',
    ),
    false
);
?>
