<?php

require_once __DIR__.'/../jwt/JwtHandler.php';

include './controllers/Controller.php';
include './uploads/Upload.php';


/**
 * Controlleur Register, permet de s'inscrire dans l'application.
 * 
 * PHP version 5
 * 
 * @category Authentication
 * @package  controllers
 * @author   Georgy Guei <gettien98@gmail.com>
 */
class ControllerRegister extends Controller
{
    /**
     * Constructeur
     * 
     * @param array $url L'URL de la page
     * 
     * @throws Exception
     * 
     * @uses hydrate
     * @uses register
     * @uses msgErr
     */
    public function __construct($url) {
        parent::__construct();
        // Setup request to send json via POST
        try {
            // PRENDRE LES DONNEES BRUTES DE LA REQUETE
            $json = file_get_contents('php://input');
            // LES CONVERTIR EN TABLEAU PHP
            $data = json_decode($json);

            // SI LA REQUEST METHOD EST DIFFERENTE DE POST
            if ($_SERVER['REQUEST_METHOD'] != 'POST'):
                throw new Exception('Page Introuvable', 404);
            endif;
            // HYDRATATION DES DONNEES
            $this->hydrate($data);
            // L'UTILISATEUR PEUT SE CONNECTER
            $this->_data = $data;
            $this->register();

        } catch (Exception $e) {
            $this->_returnData = $this->msgErr(false,$e->getCode(),$e->getMessage());
            $this->_view->generate($this->_returnData); 
        }
    }

    /**
     * Connexion de l'utilisateur
     * 
     * @throws Exception
     */
    private function register() {
        $this->_manager = new UserManager();

        $user = $this->_manager->getUser(array(
            'email' => $this->_data->email
        ));

        // SI L'EMAIL EST DEJA UTILISE
        if (isset($user[0]) && !empty($user[0])):
            throw new Exception($this->_data->email.' - est déjà utilisé!', 422);
        endif;

        // AJOUTER UN NOUVEL UTILISATEUR
        $uniqueId = rand(time(), 1000000); // CREATION D'UN ID UNIQUE POUR L'UTILISATEUR
        $this->_manager->addUser(array(
            'id' => $uniqueId,
            'name' => htmlspecialchars(strip_tags(trim($this->_data->fname).' '.trim($this->_data->lname))),
            'email' => $this->_data->email,
            'password' => password_hash($this->_data->password, PASSWORD_DEFAULT),
            'img' => 'http://localhost/api/images/cover.png',
            'status' => 1,
        ));

        // CREATION DU TOKEN & EXPORTATION DES DONNEES
        $jwt = new JwtHandler();
        $token = $jwt->_jwt_encode_data(
            'http://localhost/php_auth_api/',
            array("user_id"=> $uniqueId)
        );

        // AJOUT DANS UN GROUPE
        $managerGroup = new GroupManager();
        $managerGroup->addUser(array(
            'groupId' => 195535117,
            'userId' => $uniqueId
        ));
        //FIN

        // ENVOIE DES DONNEES 
        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'message' => 'Inscription reussie!',
            'token' => $token
        );
        $this->_view->generate($this->_returnData);
    }

    /**
     * Vérification des données saisies par l'utilisateur.
     * 
     * @param array $data Les données de l'utilisateur
     * 
     * @throws Exception
     */
    private function hydrate($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->fname)) || empty(trim($data->fname)) ||
            (!isset($data->lname)) || empty(trim($data->lname)) ||
            (!isset($data->email)) || empty(trim($data->email)) ||
            (!isset($data->password)) || empty(trim($data->password))):
           throw new Exception('Veillez renseigner tous les champs, s\'il vous plaît!', 422);
        endif;

        $email_pattern = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";
        
        $fname = trim($data->fname);
        $lname = trim($data->lname);
        $email = trim($data->email);
        $password = trim($data->password);
        $passwordSize = 4;
        
        // VERIFICATION DE LA LONGUEUR DU NOM
        if (strlen($fname) < 3 || strlen($fname) > 8):
            throw new Exception('Votre prénom doit contenir entre 3 et 8 caractères', 422);
        elseif (strlen($lname) < 3 || strlen($lname) > 8):
            throw new Exception('Votre nom doit contenir entre 3 et 8 caractères', 422);
        elseif (!ctype_alpha($fname)):
            throw new Exception($fname.' - n\'est pas un nom valide!');
        elseif (!ctype_alpha($lname)):
            throw new Exception($lname.' - n\'est pas un nom valide!');
        // VERIFICATION DU FORMAT DE L'EMAIL
        elseif (preg_match($email_pattern, $email) == 0):
            throw new Exception($email.' - n\'est pas un email valide!', 422);
        // VERIFICATION DE LA LONGUEUR DU MOT DE PASSE
        elseif (strlen($password) < $passwordSize):
            throw new Exception('Votre mot de passe doit contenir au moins '.$passwordSize.' caractères', 422);
        endif;
    }

    private $_data;
}