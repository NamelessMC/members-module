<?php

namespace NamelessMC\Members\Queries;

use NamelessMC\Members\MemberListManager;

class MemberList extends Query {

    private Cache $cache;
    private MemberListManager $memberListManager;

    public function __construct(
        Cache $cache,
        MemberListManager $memberListManager
    ) {
        $this->cache = $cache;
        $this->memberListManager = $memberListManager;

        $this->cache->setCache('member_list_queries');
    }

    public function handle(): void {
        $list = $this->data['list'];
        $overview = $this->data['overview'] ?? false;
        $page = $this->data['page'] ?? 1;

        $this->json($this->cache->fetch($this->cacheKey($list, $page, $overview), 60, function () use ($list, $overview, $page) {
            if (str_starts_with($list, 'group_')) {
                $members = $this->memberListManager->getList((int) substr($list, 6), true)->getMembers(false, $page);
            } else {
                $members = $this->memberListManager->getList($list)->getMembers($overview, $page);
            }

            return $members;
        }));
    }

    private function cacheKey(string $list, int $page, bool $overview): string {
        return ($list . '_page_' . $page) . ($overview ? '_overview' : '') . (Settings::get('member_list_hide_banned', false, 'Members') ? '_hide_banned' : '');
    }
}

