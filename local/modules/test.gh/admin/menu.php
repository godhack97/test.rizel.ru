<?
AddEventHandler('main', 'OnBuildGlobalMenu', 'addMenuItemGH');

function addMenuItemGH(&$aGlobalMenu, &$aModuleMenu): void
{
    global $USER;
    if ($USER->IsAdmin()) {
        $aGlobalMenu['global_menu_services']['items'][] = [
            "parent_menu" => "global_menu_services",
            "section" => "test_menu_gh",
            "sort"        => 1,
            "url"         => "currency_rate_gh.php?lang=".LANG,
            "text"        => 'Курсы валют',
            "title"       => 'Курсы валют',
            "page_icon"   => "form_page_icon",
            "items_id"    => "test_menu_gh",
            "items"       => array()
        ];
    }
}
?>
