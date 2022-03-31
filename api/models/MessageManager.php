<?php

/**
 * Message Manager, permet de gérer les requêtes sur la table messages.
 * 
 * PHP version 5
 * 
 * @category Managment
 * @package  models
 * @author   Georgy Guei <gettien98@gmail.com>
 */
class MessageManager extends Model {

    /**
     * Récupère les données d'un ou plusieurs utilisateur(s)
     * 
     * @param array $condition La condition de la requête
     * 
     * @return array Un tableau qui contient le résultat de la requête
     * 
     * @uses get
     */
    // public function getMessage($condition=array(), $orderDESC=false) {
        // return $this->get('messages', $condition, $orderDESC);
    // }

    /**
     * Ajoute d'un nouvel utilisateur
     * 
     * @param array $data Les données du nouvel utilisateur
     * 
     * @uses post
     */
    public function addMessage($data) {
        $this->post('messages', $data);
    }

    public function setMessage($data, $condition) {
        $this->put('messages', $data, $condition);
    }

    public function deleteMessage($condition) {
        $this->delete('messages', $condition, 'GROUP BY outgoing_msg_id ORDER BY id DESC');
    }

    public function getMessage($incoming_msg_id, $outgoing_msg_id, $first=false) {
        $obj = 'Message';
        try {
            // CONNEXION A LA BASE DE DONNEE
            $db = $this->getDB();
            // REQUETE SQL
            $sql = 'SELECT * FROM `messages` WHERE (outgoing_msg_id=:outgoing_msg_id AND incoming_msg_id=:incoming_msg_id)
                    OR (outgoing_msg_id=:incoming_msg_id AND incoming_msg_id=:outgoing_msg_id) ORDER BY id DESC';
            $sql .= $first ? ' LIMIT 1' : '';
            // PREPARATION DE LA REQUETE SQL
            $query = $db->prepare($sql);
            $query->bindValue(':incoming_msg_id', $incoming_msg_id);
            $query->bindValue(':outgoing_msg_id', $outgoing_msg_id);
            // EXECUTION DE LA REQUETE SQL
            $query->execute();
            // RECUPERATION DES DONNEES
            if ($query->rowCount()):
                while ($res = $query->fetch(PDO::FETCH_ASSOC)):
                    $data[] = new $obj($res);
                endwhile;
                $query->closeCursor();
                return $data;
            else:
                return null;
            endif;
        } catch (PDOException $e) {
            throw new Exception('Echec de SELECT : '.$e->getMessage(), 500);
        }
    }

}




