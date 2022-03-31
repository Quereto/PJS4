<?php

/**
 * User Manager, permet de gérer les requêtes sur la table User.
 * 
 * PHP version 5
 * 
 * @category Managment
 * @package  models
 * @author   Georgy Guei <gettien98@gmail.com>
 */
class UserManager extends Model {
    /**
     * Récupère les données d'un ou plusieurs utilisateur(s)
     * 
     * @param array $condition La condition de la requête
     * 
     * @return array Un tableau qui contient le résultat de la requête
     * 
     * @uses get
     */
    public function getUser($condition=array()) {
        return $this->get('users', 'User', $condition);
    }

    /**
     * Ajoute d'un nouvel utilisateur
     * 
     * @param array $data Les données du nouvel utilisateur
     * 
     * @uses post
     */
    public function addUser($data) {
        $this->post('users', $data);
    }

    /**
     * Modifie les données d'un utilisateur
     * 
     * @param array $data      Les nouvelles données à insérer
     * @param array $condition La condition de la requête
     * 
     * @uses put
     */
    public function setUser($data, $condition) {
        $this->put('users', $data, $condition);
    }

    /**
     * Suppression d'un utilisateur
     * 
     * @param array $condition La condition de la requête
     * 
     * @uses delete
     */
    public function removeUser($condition) {
        $this->delete('users', $condition);
    }

    /**
     * Rechercher un utilisateur à partir des lettres de son nom.
     * 
     * @param string $searchTerm Le terme de la recherche
     * 
     * @return array Un tableau qui contient le résultat de la requête
     */
    public function searchUser($searchTerm, $id) {
        $obj = 'User';
        try {
            // CONNEXION A LA BASE DE DONNEE
            $db = $this->getDB();
            // REQUETE SQL
            $sql = 'SELECT * FROM `users` WHERE LOWER(`name`) LIKE LOWER(:searchTerm) AND `id`<>:id ';
            // PREPARATION DE LA REQUETE SQL
            $query = $db->prepare($sql);
            $query->bindValue(':searchTerm', '%'.$searchTerm.'%');
            $query->bindValue(':id', $id);
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