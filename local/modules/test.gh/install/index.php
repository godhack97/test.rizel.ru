<?php

use Bitrix\Main\IO;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;


Loc::loadMessages(__FILE__);

class test_gh extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];

        include __DIR__ . '/install.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_ID = 'test.gh';
        $this->MODULE_NAME = Loc::getMessage('MYMODULE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MYMODULE_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('MYMODULE_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = '';
    }

    public function doInstall(): bool
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->installAdminFiles();
        $this->installComponents();
        $this->installTable();
        $this->installAgents();

        return true;
    }

    public function doUninstall(): bool
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->UninstallComponents();
        $this->UninstallAdminFiles();
        $this->UninstallTable();
        $this->unInstallAgents();

        return true;
    }

    private function installAgents(): void
    {
        \CAgent::AddAgent('\\Gh\\General::currencyAgent();', $this->MODULE_ID, 'N', 86400, '', 'Y');
    }

    private function unInstallAgents(): void
    {
        \CAgent::RemoveModuleAgents($this->MODULE_ID);
    }

    private function installTable(): void
    {
        global $DB;
        $DB->RunSQLBatch(__DIR__.'/db/mysql/install.sql');
    }

    private function UninstallTable(): void
    {
        global $DB;
        $DB->RunSQLBatch(__DIR__.'/db/mysql/uninstall.sql');
    }

    private function installAdminFiles(): void
    {
        $AdminFileOrig = new IO\File(__DIR__ . '/admin/currency_rate_gh.php');
        $AdminFilecopy = new IO\File(Application::getDocumentRoot() . '/bitrix/admin/currency_rate_gh.php');
        $AdminFilecopy->putContents($AdminFileOrig->getContents());
    }

    private function UninstallAdminFiles(): void
    {
        DeleteDirFilesEx(Application::getDocumentRoot() . '/bitrix/admin/currency_rate_gh.php');
    }

    public function installComponents(): void
    {
        CopyDirFiles(
            __DIR__ . '/components/',
            Application::getDocumentRoot() . '/bitrix/components/test.gh',
            true,
            true
        );
    }

    public function UninstallComponents(): void
    {
        IO\Directory::deleteDirectory(
            Application::getDocumentRoot() . '/bitrix/components/test.gh'
        );
    }
}
