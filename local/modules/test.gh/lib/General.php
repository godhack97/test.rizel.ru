<?php
namespace Gh;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Iblock\ElementTable;


class General
{
    public static function getCount($site_id)
    {
        \Bitrix\Main\Loader::IncludeModule('iblock');
        $ibid = \Bitrix\Main\Config\Option::get("test.gh", "count_elems_ib_id_".$site_id); 
        $db = \Bitrix\Iblock\ElementTable::GetList([ 'filter'=>['IBLOCK_ID'=>$ibid], 'select'=>['ID'] ]);
        return count($db->FetchAll());
    }
}