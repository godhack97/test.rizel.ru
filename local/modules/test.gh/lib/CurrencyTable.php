<?
namespace GH;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\FloatField;

class CurrencyTable extends DataManager
{
    public static function getTableName()
    {
        return 'currency_list';
    }

    public static function getMap()
    {
        return [
            (new IntegerField('ID', [
                'primary' => true,
				'autocomplete' => true,
            ])),
            (new StringField('CODE')),
            (new DatetimeField('DATE')),
            (new FloatField('COURSE')),
        ];
    }

    public static function getRows(): ?array
    {
        foreach (self::getMap() as $row) {
            $res[$row->getName()] = $row->getName();
        }

        return $res;
    }
}