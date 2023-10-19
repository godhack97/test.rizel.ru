<?php
//подключаем основные классы для работы с модулем
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock as HL;

Loc::loadMessages(__FILE__);

class test_gh extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
                //подключаем версию модуля (файл будет следующим в списке)
        include __DIR__ . '/version.php';
                //присваиваем свойствам класса переменные из нашего файла
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
                //пишем название нашего модуля как и директории
        $this->MODULE_ID = 'test.gh';
        // название модуля
        $this->MODULE_NAME = Loc::getMessage('MYMODULE_MODULE_NAME');
        //описание модуля
        $this->MODULE_DESCRIPTION = Loc::getMessage('MYMODULE_MODULE_DESCRIPTION');
        //используем ли индивидуальную схему распределения прав доступа
        $this->MODULE_GROUP_RIGHTS = 'N';
        //название компании партнера предоставляющей модуль
        $this->PARTNER_NAME = Loc::getMessage('MYMODULE_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = '';//адрес сайта
    }
    //здесь мы описываем все, что делаем до инсталляции модуля, мы добавляем наш модуль в регистр и вызываем метод создания таблицы
    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        copy($_SERVER["DOCUMENT_ROOT"]."/local/modules/test.gh/install/admin/test_work.php", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/test_work.php");
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/test.gh/install/components/test.gh",$_SERVER["DOCUMENT_ROOT"]."/bitrix/components/test.gh",true,true);
        return true;
    }
        //вызываем метод удаления таблицы и удаляем модуль из регистра
    public function doUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/test_work.php");
        DeleteDirFilesEx("/bitrix/components/test.gh");
        return true;
    }
} 
