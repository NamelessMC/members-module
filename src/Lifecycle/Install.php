<?php

namespace NamelessMC\Members\Lifecycle;

use NamelessMC\Framework\ModuleLifecycle\Hook;

class Install extends Hook
{
    public function execute(): void
    {
        dd('Installing Members module...');
    }
}