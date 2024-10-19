<?php

use NamelessMC\Framework\Extend;

return [
    (new Extend\Language(__DIR__ . '/language')),
    (new Extend\FrontendPages)
        ->register('/', 'members/members', \NamelessMC\Members\Pages\Members::class),
    (new Extend\PanelPages)
        ->register('/', 'members/member_lists', \NamelessMC\Members\Pages\Panel\Index::class)
        ->register('/settings', 'members/members', \NamelessMC\Members\Pages\Panel\Settings::class),
    (new Extend\Queries)
        ->register('/member_list', \NamelessMC\Members\Queries\MemberList::class),
    (new Extend\Permissions)
        ->register([
            'admin' => [
                'admincp.members' => 'members/member_lists',
            ],
        ]),
    (new Extend\DebugInfo)
        ->register(\NamelessMC\Members\DebugInfo\Provider::class),
    (new Extend\Events)
        ->listen(UserRegisteredEvent::class, \NamelessMC\Members\Listeners\UserRegisteredListener::class),
    (new Extend\Container)
        ->singleton(\NamelessMC\Members\MemberListManager::class),
];
