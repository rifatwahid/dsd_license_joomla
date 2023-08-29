<?php

class JshoppingModelUserGroupsFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_usergroups';

    public function getDefaultUsergroupId()
    {
        return $this->select(['usergroup_id'], ["`usergroup_is_default`= '1'"], '', false)->usergroup_id ?: null;
    }

    public function getAll()
    {
        $lang = JSFactory::getLang();
        $columnsNames = [
            '*',
            "`{$lang->get('name')}` as name",
            "`{$lang->get('description')}` as description"
        ];

        $userGroups = $this->select($columnsNames);

        if (!empty($userGroups)) {
            foreach($userGroups as $k => $usergroup) {
                if (empty($usergroup->name)) {
                    $userGroups[$k]->name = $usergroup->usergroup_name;
                }
            }
        }

        return $userGroups;
    }

    public function getUserDiscount(int $userId)
    {
        $db = \JFactory::getDBO(); 
        $query = "SELECT usergroup.usergroup_discount FROM `#__jshopping_usergroups` AS usergroup
                  INNER JOIN `#__jshopping_users` AS users ON users.usergroup_id = usergroup.usergroup_id
                  WHERE users.user_id = '{$db->escape($userId)}' ";
        $db->setQuery($query);

        return floatval($db->loadResult());
    }
}
