<?php

/**
 * Created by IntelliJ IDEA.
 * User: Santeri Hetekivi
 * Date: 15.3.2016
 * Time: 16.41
 */
class Membership extends MySQLObject
{

    protected function INITIALIZE()
    {
        $this->FILE = __FILE__;
        $this->setTable("membership");
        $columns = array(
            new MySQLColumn("userGroup", 0, "ID", new UserGroup()),
            new MySQLColumn("user", 0, "ID", new User()),
        );
        $this->setIdNames(array("userGroup", "user"));
        $this->setColumns($columns);
    }

    /**
     * Function GetUserGroups
     * for getting user groups for given userId.
     * @param int $userId User id to find user groups for.
     * @return array|bool UserGroup objects as a array or false if failed.
     */
    public function GetUserGroups($userId)
    {
        $return = false;
        $obs = $this->GetAll(array("user" => $userId));
        if(Checker::isArray($obs, true, $this->ERROR_INFO(__FUNCTION__)))
        {
            $userGroups = array();
            foreach($obs as $ob)
            {
                $userGroup = $ob->Value("userGroup", true);
                if($this->isObject($userGroup, "UserGroup", false, __FUNCTION__))
                {
                    $userGroups[$userGroup->ID()] = $userGroup;
                }
            }
            if(count($userGroups) === count($obs)) $return = $userGroups;
        }
        return $return;
    }

    public function Rights($userId)
    {
        $return  = array();
        $userGroups = $this->UserGroups($userId);
        if(Checker::isArray($userGroups))
        {
            foreach(UserGroup::RIGHTS as $right)
            {
                $return[$right] = false;
                foreach($userGroups as $userGroup)
                {
                    if($this->isObject($userGroup, "UserGroup"))
                    {
                        $return[$right] = $userGroup->Value($right);
                    }
                }
            }
        }
        return $return;
    }

    public function CheckRight($userId, $right)
    {
        $rights = $this->Rights($userId);
        return (Checker::isArray($rights, false) && isset($rights[$right]) && $rights[$right]);
    }

}