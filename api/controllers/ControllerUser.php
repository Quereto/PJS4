<?php

require_once __DIR__.'/../jwt/middlewares/Auth.php';

include './controllers/Controller.php';
include './uploads/Upload.php';


class ControllerUser extends Controller
{
    public function __construct($url) {
        parent::__construct();
        $this->_manager = new UserManager();
        try {
            $this->authUser();
            if (isset($url[1]) && !empty(trim($url[1]))):
                $action = trim($url[1]);
                if (method_exists('ControllerUser', $action)) :
                    $this->$action();
                else:
                    throw new Exception('Page introuvable', 404);
                endif;
            elseif ($_SERVER['REQUEST_METHOD'] == 'GET'):
                $this->setOnline(1);
                $this->getUser();
            else:
                throw new Exception('Page introuvable', 404);
            endif;
            $this->_view->generate($this->_returnData);
        } catch (Exception $e) {
            $this->_returnData = $this->msgErr(false,$e->getCode(),$e->getMessage());
            $this->_view->generate($this->_returnData); 
        }
    }

    // search contact
    private function search() {
        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP array
        $data = json_decode($json);

        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->searchTerm)) || empty(trim($data->searchTerm))):
            throw new Exception('Les données sont incorrectes', 422);
        endif;

        // let's get users with the given searchTerm
        $contacts = $this->_manager->searchUser($data->searchTerm, $this->_userId);

        // if there no contact found
        if (!isset($contacts) || empty($contacts)):
            throw new Exception('Aucun contact trouvé lié à votre terme de recherche', 404);
        endif;

        // otherwise let's return the contacts
        $this->_returnData = array(
            'success' => 1,
            'status' => 200,
            'message' => 'user is found',
            'data' => $contacts
        );
    }

    // fetch all contacts
    private function contacts() {
        // let's get all users as contacts
        $contacts = $this->_manager->getUser();

        // if there no contact found
        if (!isset($contacts) || count($contacts) < 2):
            throw new Exception('Aucun utilisateur n\'est disponible pour chatter', 404);
        endif;

        // otherwise let's return the contacts
        $this->_returnData = array(
            'success' => 1,
            'status' => 200,
            'data' => null
        );

        // let's peek only usefull data from the contact
        foreach ($contacts as $contact) {
            if ($contact->id() != $this->_userId):
                $this->_returnData['data'][] = $contact;
            endif;
        }
    }

    // let's set user status `online`
    private function setOnline($status) {
        // if user already online
        $this->_manager->setUser(
            array('status' => $status),
            array('id' => $this->_userId)
        );
    }

    // logout user
    private function logout() {
        $this->setOnline(0);
        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'message' => 'Déconnexion réussie!'
        );
    }

    // upload image profile
    private function uploadFile() {
        // let's try to extract file 
        Upload::_extract_file('file');
        // if succed we save the file `info` into a variable 
        $file = Upload::_get_file();
        // let's create a new file name using current time
        Upload::_set_file(time().'-'.$file->name);
        // let's check if file is an image
        if (!in_array($file->ext, array('png', 'jpg', 'jpeg'))):
            throw new Exception('Veuillez sélectionner un fichier image - jpeg, jpg, png', 415);
        endif;
        // let's upload the user image
        Upload::_upload_file();
    }

    // update image profile
    private function upload() {
        // let's upload file first
        $this->uploadFile();

        // old user image
        $oldImg = $this->_returnData['user']->img();

        // let's update user's data
        $this->_manager->setUser(
            array('img' => Upload::IMAGE_URL.Upload::_get_file()->name),
            array('id' => $this->_userId)
        );
        $this->_returnData['user']->setImg(Upload::IMAGE_URL.Upload::_get_file()->name);

        // let's delete user old image if there is
        $oldImg = str_replace(Upload::IMAGE_URL, '', $oldImg);
        if (isset($oldImg) && !empty(trim($oldImg))):
            Upload::_set_file($oldImg);
            Upload::_delete_file();
        endif;
    }

    // let's auth the user 
    private function authUser() {
        $jwt_auth = new Auth(getallheaders());
        $userId = $jwt_auth->auth();
        if (isset($userId)):
            $this->_userId = (int) $userId;
        else:
            throw new Exception('Unauthorized', 401);
        endif;
    }

    // fetch user data
    private function getUser() {
        $user = $this->_manager->getUser(
            array('id' => $this->_userId)
        );

        // ENVOIE DES DONNEES
        if (isset($user[0]) && !empty($user[0])) :
            $user = $user[0];
            $this->_returnData = array(
                'success' => 1,
                'status' => 200,
                'user' => $user
            );
        endif;
    }

    private $_userId;
}