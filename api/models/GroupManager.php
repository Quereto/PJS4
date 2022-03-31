<?php

class GroupManager extends Model {


    public function addUser($data) {
        $this->post('usergroup', $data);
    }

    public function createGroup($data) {
        $this->post('groups', $data);
    }


    public function getUserGroups($condition=array()) {
        return $this->get('usergroup', null, $condition, '');
    }

    public function getGroupsById($condition=array()) {
        return $this->get('groups', 'Group', $condition);
    }

    public function getUsers($condition=array()) {
        return $this->get('usergroup', null, $condition, '');
    }

}