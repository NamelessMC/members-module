<?php

namespace NamelessMC\Members\Pages\Panel;

class Settings extends PanelPage {

    private Smarty $smarty;
    private Cache $cache;
    private Language $coreLanguage;
    private Language $membersLanguage;

    public function __construct(
        Smarty $smarty,
        Cache $cache,
        Language $coreLanguage,
        Language $membersLanguage,
    ) {
        $this->smarty = $smarty;
        $this->cache = $cache;
        $this->coreLanguage = $coreLanguage;
        $this->membersLanguage = $membersLanguage;
    }

    public function render() {
        // if (!$user->handlePanelPageLoad('admincp.members')) {
        //     require_once(ROOT_PATH . '/403.php');
        //     die();
        // }

        // const PAGE = 'panel';
        // const PARENT_PAGE = 'members';
        // const PANEL_PAGE = 'members_settings';

        // $page_title = $this->membersLanguage->get('members', 'members');
        // require_once(ROOT_PATH . '/core/templates/backend_init.php');

        if (Input::exists()) {
            if (Token::check()) {
                Settings::set('member_list_viewable_groups', json_encode(Input::get('groups')), 'Members');
                Settings::set('member_list_hide_banned', Input::get('hide_banned_users'), 'Members');

                // Update link location
                if (isset($_POST['link_location'])) {
                    switch ($_POST['link_location']) {
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                            $location = $_POST['link_location'];
                            break;
                        default:
                            $location = 1;
                    }
                } else {
                    $location = 1;
                }

                // Update Link location cache
                $this->cache->setCache('nav_location');
                $this->cache->store('members_location', $location);

                Session::flash('admin_members_settings', $this->membersLanguage->get('members', 'settings_updated_successfully'));
            } else {
                // Invalid token
                Session::flash('admin_members_settings_error', $this->coreLanguage->get('general', 'invalid_token'));
            }
            Redirect::to(URL::build('/panel/members/settings'));
        }

        // Retrieve Link Location from cache
        $this->cache->setCache('nav_location');
        $link_location = $this->cache->retrieve('members_location');

        // Load modules + template
        // Module::loadPage($user, $pages, $cache, $smarty, [$navigation, $cc_nav, $staffcp_nav], $widgets, $template);

        if (Session::exists('admin_members_settings')) {
            $this->smarty->assign([
                'SUCCESS' => Session::flash('admin_members_settings'),
                'SUCCESS_TITLE' => $this->coreLanguage->get('general', 'success')
            ]);
        }

        if (Session::exists('admin_members_settings_error')) {
            $this->smarty->assign([
                'ERROR' => Session::flash('admin_members_settings_error'),
                'ERRORS_TITLE' => $this->coreLanguage->get('general', 'success')
            ]);
        }

        $group_array = [];
        foreach (Group::all() as $group) {
            $group_array[] = [
                'id' => $group->id,
                'name' => Output::getClean($group->name),
            ];
        }

        $this->smarty->assign([
            'PARENT_PAGE' => PARENT_PAGE,
            'DASHBOARD' => $this->coreLanguage->get('admin', 'dashboard'),
            'MEMBERS' => $this->membersLanguage->get('members', 'members'),
            'SETTINGS' => $this->coreLanguage->get('admin', 'settings'),
            'LINK_LOCATION' => $this->coreLanguage->get('admin', 'page_link_location'),
            'LINK_LOCATION_VALUE' => $link_location,
            'LINK_NAVBAR' => $this->coreLanguage->get('admin', 'page_link_navbar'),
            'LINK_MORE' => $this->coreLanguage->get('admin', 'page_link_more'),
            'LINK_FOOTER' => $this->coreLanguage->get('admin', 'page_link_footer'),
            'LINK_NONE' => $this->coreLanguage->get('admin', 'page_link_none'),
            'HIDE_BANNED_USERS' => $this->membersLanguage->get('members', 'member_list_hide_banned_users'),
            'HIDE_BANNED_USERS_VALUE' => Settings::get('member_list_hide_banned', false, 'Members'),
            'GROUPS' => $this->membersLanguage->get('members', 'viewable_groups'),
            'GROUPS_ARRAY' => $group_array,
            'GROUPS_VALUE' => json_decode(Settings::get('member_list_viewable_groups', '{}', 'Members'), true) ?: [],
            'NO_ITEM_SELECTED' => $this->coreLanguage->get('admin', 'no_item_selected'),
            'PAGE' => PANEL_PAGE,
            'TOKEN' => Token::get(),
            'SUBMIT' => $this->coreLanguage->get('general', 'submit'),
        ]);

        // $template->onPageLoad();

        // require(ROOT_PATH . '/core/templates/panel_navbar.php');

        // Display template
        // $template->displayTemplate('members/members_settings.tpl', $smarty);
    }
}
