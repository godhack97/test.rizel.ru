<?php

Bitrix\Main\Loader::registerAutoloadClasses(
    'test.gh',
    [
        'Gh\\General' => 'lib/General.php',
        'Gh\\CurrencyTable' => 'lib/CurrencyTable.php',
    ]
);