<?php

use NamelessMC\Framework\Extend;

return [
    (new Extend\Language(__DIR__ . '/language')),
    // TODO could these be more generic? with `registerFrontend` and `registerPanel`?
    (new Extend\FrontendPages)
        ->templateDirectory(__DIR__ . '/views')
        // TODO, don't require defining page name here and in the class
        ->register('/', 'members', 'members/members', \NamelessMC\Members\Pages\Members::class, true),
    (new Extend\PanelPages)
        ->register('/', 'member_lists', 'members/member_lists', \NamelessMC\Members\Pages\Panel\Index::class, 'admincp.members')
        ->register('/settings', 'members_settings', 'members/member_list_settings', \NamelessMC\Members\Pages\Panel\Settings::class, 'admincp.members'),
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
