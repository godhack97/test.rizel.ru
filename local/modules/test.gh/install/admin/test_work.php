<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
CModule::IncludeModule('main');
if (!($USER->CanDoOperation('fileman_admin_files') || $USER->CanDoOperation('fileman_edit_existent_files')))
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if(
    $REQUEST_METHOD == "POST" // проверка метода вызова страницы
    &&
    ($save!="" || $apply!="") // проверка нажатия кнопок "Сохранить" и "Применить"
){}
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

    global $APPLICATION;
    // список сайтов
    $arSite  = Bitrix\Main\SiteTable::GetList(['select'=>['LID','SITE_NAME']])->fetchAll();
    // табы для мультисайтовости
    foreach($arSite as $site){
            $aTabs[] = ["DIV" => "edit_".$site['LID'], "TAB" => (($bEdit) ? $site['SITE_NAME'] :$site['SITE_NAME'] ), "ICON" => "fileman", "TITLE" => (($bEdit) ? "" : "выберите инфоблок")];
    
    if(isset($_POST['count_elems_ib_id_'.$site['LID']])){
        \Bitrix\Main\Config\Option::set("test.gh", "count_elems_ib_id_".$site['LID'], $_POST['count_elems_ib_id_'.$site['LID']]);
    }

    }

?>

<form enctype="multipart/form-data" method="POST" action="test_work.php" name="gh_setting">
    <?
    $tabControl = new CAdminTabControl("tabControl", $aTabs);
    $tabControl->Begin();?>
    <?/* START */?>

    <?foreach($arSite as $site){?>
        <?$tabControl->BeginNextTab();?>
        <?
        /* выборка инфоблоков на сайте */
        \Bitrix\Main\Loader::IncludeModule('iblock');
        $res = \Bitrix\Iblock\IblockTable::GetList([
            'filter' => ['LID'=>$site['LID']],
            'order'  => [ 'NAME'=>'DESC'],
            'select' => ['NAME','ID']
        ]);
        ?>
        <tr class="heading"><td colspan="4">Выбор инфоблока для подсчёта элементов</td></tr>
        <tr> <!-- Выбрать инфоблок -->
            <td width="10%" class="adm-detail-content-cell-l">Инфоблок</td>
            <td width="40%" class="adm-detail-content-cell-r">
                <select autocomplete="off" name="count_elems_ib_id_<?=$site['LID']?>">
                    <?while($re = $res->fetch()){?>
                        <option <?if($re['ID'] == Bitrix\Main\Config\Option::get("test.gh", "count_elems_ib_id_".$site['LID'])):?>selected="true"<?endif;?>  value="<?=$re['ID']?>" label="<?=$re['NAME']?>">
                    <?}?>
                </select>
            </td>
            <td width="10%" class="adm-detail-content-cell-r" style="text-align: center;">
                <?$APPLICATION->IncludeComponent('test.gh:countElement','',['SITE'=>$site['LID']],null,[])?>
            </td>
            <td width="40%" class="adm-detail-content-cell-r" style="text-align: center;"></td>
        </tr>


    <?}?>

    <?/* END */?>

    <?
    $tabControl->EndTab();
    $tabControl->Buttons(
        [
            "disabled" => $only_read,
            "back_url" => (strlen($back_url)>0 && strpos($back_url, "/bitrix/admin/fileman_file_edit.php")!==0) ? htmlspecialcharsbx($back_url) : "/bitrix/admin/fileman_admin.php?".$addUrl."&site=".Urlencode($site)."&path=".UrlEncode($arParsedPath["PREV"])
        ]
    );
    $tabControl->End();
    ?>
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>