<?php

use NamelessMC\Framework\Extend;

return [
    (new Extend\Language(__DIR__ . '/language')),
    (new Extend\Pages)
        ->register('/members', 'members.members', \NamelessMC\Members\Pages\Members::class),
    (new Extend\PanelPages)
        ->register('/', 'members.member_lists', \NamelessMC\Members\Pages\Panel\Index::class)
        ->register('/settings', 'members.members', \NamelessMC\Members\Pages\Panel\Settings::class),
    (new Extend\Queries)
        ->register('/member_list', \NamelessMC\Members\Queries\MemberList::class),
    (new Extend\Permissions)->register([
        'admincp.members' => 'members.member_lists',
    ]),
    (new Extend\DebugInfo)->register(\NamelessMC\Members\DebugInfo\Provider::class)
];
