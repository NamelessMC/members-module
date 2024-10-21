<?php

namespace NamelessMC\Members\Lifecycle;

use NamelessMC\Framework\ModuleLifecycle\Hook;

class Disable extends Hook
{
    public function execute(): void
    {
        dd("Members module disabled!");
    }
}