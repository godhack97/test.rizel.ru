<?
AddEventHandler('main', 'OnBuildGlobalMenu', 'addMenuItemGH');
function addMenuItemGH(&$aGlobalMenu, &$aModuleMenu)
{
    global $USER;
    if ($USER->IsAdmin()) {
        $aGlobalMenu['global_menu_content']['items'][] = 
                [
         "parent_menu" => "godhack_menu_custom",
         "section" => "test_menu_gh",
         "sort"        => 1,                    // сортировка пункта меню
         "url"         => "test_work.php?lang=".LANG,  // ссылка на пункте меню
         "text"        => 'Настройки модуля',       // текст пункта меню
         "title"       => 'Настройки модуля', // текст всплывающей подсказки
         "icon"        => "gh_vk", // малая иконка
         "page_icon"   => "form_page_icon", // большая иконка
         "items_id"    => "menu_pdf_stall",  // идентификатор ветви
         "items"       => array()          // остальные уровни меню сформируем ниже.
                ];
    }
}
?> 
