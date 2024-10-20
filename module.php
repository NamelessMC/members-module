<?php

use NamelessMC\Framework\Extend;

return [
    (new Extend\Language(__DIR__ . '/language')),
    (new Extend\FrontendPages)
        ->templateDirectory(__DIR__ . '/views')
        ->register('/', 'members/members', \NamelessMC\Members\Pages\Members::class, true),
    (new Extend\PanelPages)
        ->register('/', 'members/member_lists', \NamelessMC\Members\Pages\Panel\Index::class)
        ->register('/settings', 'members/members', \NamelessMC\Members\Pages\Panel\Settings::class),
    (new Extend\Queries)
        ->register('/member_list', \NamelessMC\Members\Queries\MemberList::class),
    (new Extend\Permissions)
        ->register([
            'staffcp' => [
                'admincp.members' => 'members/member_lists',
            ],
        ]),
    (new Extend\DebugInfo)
        ->provide(\NamelessMC\Members\DebugInfo\Provider::class),
    (new Extend\Events)
        ->listen(UserRegisteredEvent::class, \NamelessMC\Members\Listeners\UserRegisteredListener::class),
    (new Extend\Container)
        ->singleton(\NamelessMC\Members\MemberListManager::class),
    // TODO: assets? see if anything in AssetTree can be extracted here
];
