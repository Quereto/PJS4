<?php

include './controllers/Controller.php';

class ControllerGroup extends Controller {
    public function __construct($url) {
        parent::__construct($url);
        $this->_manager = new GroupManager();
        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP array
        $data = json_decode($json);
        try {
            if (isset($url[1]) && !empty(trim($url[1]))):
                $action = trim($url[1]);
                if (method_exists($this, $action)) :
                    $this->$action($data);
                else:
                    throw new Exception('Page introuvable', 404);
                endif;
            elseif ($_SERVER['REQUEST_METHOD'] == 'PUT'):
                $this->fetchAll($data);
            else:
                throw new Exception('Page introuvable', 404);
            endif;
            $this->_view->generate($this->_returnData);
        } catch (Exception $e) {
            $this->_returnData = $this->msgErr(false,$e->getCode(),$e->getMessage());
            $this->_view->generate($this->_returnData); 
        }
    }

    private function getUsers($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->groupId)) || empty(trim($data->groupId))):
            throw new Exception('Les données sont incorrectes', 422);
        endif;

        // let's get all users from group
        $users = $this->_manager->getUsers(array(
            'groupId' => trim($data->groupId)
        ));

        // if there no user found
        if (!isset($users) || !isset($users[0])):
            throw new Exception('Aucun groupe disponible', 404);
        endif;

        foreach ($users as $user):
            $usersId[] = (int) $user['userId'];
        endforeach;

        // otherwise let's return the groups
        $this->_returnData = array(
            'success' => 1,
            'status' => 200,
            'data' => $usersId
        );
    }

    private function fetchAll($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->userId)) || empty(trim($data->userId))):
            throw new Exception('Les données sont incorrectes', 422);
        endif;

        // let's get all user's groups
        $groupIds = $this->_manager->getUserGroups(array(
            'userId' => trim(($data->userId))
        ));
        
        // if there no group found
        if (!isset($groupIds) || !isset($groupIds[0])):
            throw new Exception('Aucun groupe disponible', 404);
        endif;

        foreach ($groupIds as $group):
            $groups[] = $this->_manager->getGroupsById(array(
                'id' => trim($group['groupId'])
            ))[0];
        endforeach;

        // if there no group found
        if (!isset($groups) || !isset($groups[0])):
            throw new Exception('Aucun groupe disponible', 404);
        endif;

        // otherwise let's return the groups
        $this->_returnData = array(
            'success' => 1,
            'status' => 200,
            'data' => $groups
        );
    }

    private function addUser($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->userId)) || empty(trim($data->userId)) ||
            (!isset($data->groupId)) || empty(trim($data->groupId))):
            throw new Exception('Les données sont incorrectes', 422);
        endif;

        $userId = trim($data->userId);
        $groupId = trim($data->groupId);

        $this->_manager->addUser(array(
            'groupId' => $groupId,
            'userId' => $userId
        ));

        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'message' => 'Utilisateur ajouté!'
        );
    }


    private function create($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->userId)) || empty(trim($data->userId)) ||
            (!isset($data->groupName)) || empty(trim($data->groupName))):
           throw new Exception('Les données sont incorrectes', 422);
        endif;

        $userId = trim($data->userId);
        $groupName = trim($data->groupName);

        // CREATION D'UN NOUVEAU GROUPE
        $uniqueId = rand(time(), 1000000); // CREATION D'UN ID UNIQUE POUR LE GROUPE
        $this->_manager->createGroup(array(
            'id' => $uniqueId,
            'name' => $groupName
        ));

        $data = new stdClass();
        $data->groupId = $uniqueId;
        $data->userId = $userId;

        $this->addUser($data);
    }
}


