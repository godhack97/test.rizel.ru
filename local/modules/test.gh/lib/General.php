<?php
namespace Gh;

use GH\CurrencyTable;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Config\Option;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();


class General
{
    const MODULE_ID = 'test.gh';

    public static function getCurrencyFromCbr(): ?array
    {
        $xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . date('d/m/Y'));

        if (!empty($xml)) {
            foreach ($xml->Valute as $item) {
                $result[] = $item;
            }
        }

        return $result;
    }

    public static function clearTable(): void
    {
        foreach (CurrencyTable::getList()->fetchAll() as $key => $item) {
            CurrencyTable::delete($item['ID']);
        }
    }

    public static function currencyAgent(): string
    {
        $cur = self::getCurrencyFromCbr();

        foreach ($cur as $currencyItem) {

            if (!$currencyItem->CharCode->__toString()) {
                continue;
            }

            if ('Y' !== Option::get(self::MODULE_ID, $currencyItem->CharCode->__toString())) {
                continue;
            }

            CurrencyTable::add([
                'CODE' => $currencyItem->CharCode->__toString(),
                'DATE' =>  new DateTime(),
                'COURSE' => $currencyItem->Value
            ]);
        }

        return '\\Gh\\General::currencyAgent();';
    }
}