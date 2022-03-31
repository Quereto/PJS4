<?php

include __DIR__.'/Controller.php';

/**
 * Controlleur Messages, permet d'ajouter et récuperer des messages.
 * 
 * PHP version 5
 * 
 * @category Authentication
 * @package  controllers
 * @author   Georgy Guei <gettien98@gmail.com>
 */
class ControllerMessages extends Controller
{
    /**
     * Constructeur
     * 
     * @param array $url L'URL de la page
     * 
     * @throws Exception
     * 
     * @uses 
     * @uses msgErr
     */
    public function __construct($url) {
        try {
            // PRENDRE LES DONNEES BRUTES DE LA REQUETE
            $json = file_get_contents('php://input');
            // LES CONVERTIR EN TABLEAU PHP
            $data = json_decode($json);

            parent::__construct();
            $this->_manager = new MessageManager();
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $this->add($data);
                    break;
                case 'PUT':
                    $this->fetch($data);
                    //$this->set($data);
                    break;
                case 'DELETE':
                    $this->remove($data);
                    break;
                default:
                    throw new Exception('Page Introuvable', 404);
                    break;
            }
        } catch (Exception $e) {
            $this->_returnData = $this->msgErr(false,$e->getCode(),$e->getMessage());
            $this->_view->generate($this->_returnData);
        }
    }

    private function remove($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->messageId)) || empty(trim($data->messageId))):
           throw new Exception('Les données sont incorrectes', 422);
        endif;

        $messageId = trim($data->messageId);

        $this->_manager->deleteMessage(
            array('id' => $messageId)
        );

        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'message' => 'message sumprimé!'
        );
        $this->_view->generate($this->_returnData);
    }

    private function set($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->messageId)) || empty(trim($data->messageId)) ||
            (!isset($data->newMessage)) || empty(trim($data->newMessage))):
           throw new Exception('Les données sont incorrectes', 422);
        endif;

        $messageId = trim($data->messageId);
        $newMessage = trim($data->newMessage);

        $this->_manager->setMessage(
            array('msg' => htmlspecialchars($newMessage)),
            array('id' => $messageId)
        );

        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'message' => 'message modifié!'
        );
        $this->_view->generate($this->_returnData);
    }


    private function fetch($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->incoming_msg_id)) || empty(trim($data->incoming_msg_id)) ||
            (!isset($data->outgoing_msg_id)) || empty(trim($data->outgoing_msg_id))):
           throw new Exception('Les données sont incorrectes', 422);
        endif;

        $incoming_msg_id = trim($data->incoming_msg_id);
        $outgoing_msg_id = trim($data->outgoing_msg_id);

        if (isset($data->all) && $data->all==true):
            $res = $this->_manager->getMessage($incoming_msg_id, $outgoing_msg_id);
        else:
            $res = $this->_manager->getMessage($incoming_msg_id, $outgoing_msg_id, true);
        endif;
        if (!isset($res) || empty($res)):
            throw new Exception('Aucun message disponible', 404);
        endif;
        
        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'data' => $res
        );
        $this->_view->generate($this->_returnData);
    }

    private function add($data) {
        // RECHERCHE DE CHAMPS VIDES
        if ((!isset($data->incoming_msg_id)) || empty(trim($data->incoming_msg_id)) ||
            (!isset($data->outgoing_msg_id)) || empty(trim($data->outgoing_msg_id)) ||
            (!isset($data->msg)) || empty(trim($data->msg))):
           throw new Exception('Les données sont incorrectes', 422);
        endif;

        $incoming_msg_id = trim($data->incoming_msg_id);
        $outgoing_msg_id = trim($data->outgoing_msg_id);
        $msg = trim($data->msg);
    
        $res = $this->_manager->addMessage(array(
            'incoming_msg_id' => $incoming_msg_id,
            'outgoing_msg_id' => $outgoing_msg_id,
            'msg' => $msg
        ));

        // ENVOIE DES DONNEES 
        $this->_returnData = array(
            'success' => true,
            'status' => 200,
            'message' => 'Message ajouté!',
        );
        $this->_view->generate($this->_returnData);
    }
}