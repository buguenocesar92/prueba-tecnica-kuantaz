<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/tests',
    ]);

    // Define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ]);

    // Skip some files
    $rectorConfig->skip([
        __DIR__.'/app/Console/Kernel.php',
        __DIR__.'/app/Exceptions/Handler.php',
        __DIR__.'/app/Http/Kernel.php',
        __DIR__.'/app/Providers/AppServiceProvider.php',
    ]);
};
