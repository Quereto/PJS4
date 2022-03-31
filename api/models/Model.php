<?php

/**
 * Le Model de la base de donnée.
 * 
 * PHP version 5
 * 
 * @category Authentication
 * @package  controllers
 * @author   Georgy Guei <gettien98@gmail.com>
 */
abstract class Model {

    private static $_db;

    /**
     * Etablit la connexion à la base de donnée
     * 
     * @throws PDOException  Echec de connexion
     */
    private static function setDB() {
        $hostname = "localhost";	
        $base= "chatapp";
        $loginDB= "root";	
        $passDB="root";

        try {
            // DSN (Data Source Name)pour se connecter à MySQL
            $dsn = "mysql:server=$hostname ; dbname=$base";
            
            self::$_db = new PDO ($dsn, $loginDB, $passDB,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            // Le dernier argument sert à ce que toutes les chaines de caractères 
            // en entrée et sortie de MySql soit dans le codage UTF-8
            
            // On active le mode d'affichage des erreurs, et le lancement d'exception en cas d'erreur
            self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connexion au DSN: ".$dsn." OK! </br>";
        } catch(PDOException $e) {
            throw new Exception('Echec de connexion : '.$e->getMessage(), 500);
        }
    }

    /**
     * Récupère la connexion à la base de donnée
     * 
     * @return PDO $_db une instance de PDO
     */
    protected function getDB() {
        if(self::$_db === null):
            self::setDB();
        endif;
        return self::$_db;
    }

    /**
     * Recupère les données d'une table
     * 
     * @param string $table le nom de la table
     * @param array  $condition la condition de la reqête
     * @param string $separator Opérateur logique
     * 
     * @return array Un tableau qui contient le résultat de la requête
     * 
     * @throws Exception Echec de select
     */
    protected function get($table, $obj, $condition=array(), $extra=null, $separator='AND') {
        try {
            // CONNEXION A LA BASE DE DONNEE
            $this->getDB();
            // REQUETE SQL
            $sql = 'SELECT * FROM '.'`'.$table.'`';
            if (count($condition) > 0):
                $sql .= ' WHERE ';
                foreach ($condition as $field => $value):
                    $sql .= $field.'=:'.$field.' '.$separator.' ';
                endforeach;
                // SUPPRESSION DE la VIRGULE DE FIN 
                $sql = substr_replace(trim($sql), '', ($separator) == 'AND' ? -3 : -2);
                $sql .= isset($extra) ? $extra : 'ORDER BY id ASC';
            endif;

            // PREPARATION DE LA REQUETE SQL
            $query = self::$_db->prepare($sql);
            if (count($condition) > 0) :
                foreach ($condition as $field => $value):
                    $query->bindValue(':'.$field, $value);
                endforeach;
            endif;

            // EXECUTION DE LA REQUETE SQL
            $query->execute();

            // RECUPERATION DES DONNEES
            if ($query->rowCount()):
                while ($res = $query->fetch(PDO::FETCH_ASSOC)):
                    $data[] = isset($obj) ? new $obj($res) : $res;
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

    /**
     * Inserer les données dans une table
     * 
     * @param string $table le nom de la table
     * @param array  $data les données à inserer
     * 
     * @throws Exception Echec de insert
     */
    protected function post($table, $data) {
        try {
            // CONNEXION A LA BASE DE DONNEE
            $this->getDB();
            
            // REQUETE SQL
            $sql = 'INSERT INTO '.'`'.$table.'`'.'(';

            if (count($data) > 0):
                $keys = array_keys($data);
            else:
                throw new Exception("Error Processing Request");
            endif;

            foreach ($keys as $param):
                $sql .= '`'.$param.'`'.', ';
            endforeach;
            // SUPPRESSION DE la VIRGULE DE FIN 
            $sql = substr_replace(trim($sql), '', -1);
            $sql .= ') VALUES(';
            
            // PARAMETRE DES DONNEES
            foreach ($keys as $param):
                $sql .= ':'.$param.', ';
            endforeach;
            // SUPPRESSION DE la VIRGULE DE FIN 
            $sql = substr_replace(trim($sql), '', -1);
            $sql .= ')';

            // PREPARATION DE LA REQUETE
            $query = self::$_db->prepare($sql);
            foreach ($data as $param => $value):
                $query->bindValue(':'.$param, $value);
            endforeach;

            // EXECUSSION DE LA REQUETE
            $query->execute();
        } catch (Exception $e) {
            throw new Exception('Echec de INSERT : '.$e->getMessage(), 500);
        }
    }

    /**
     * Modifier les données présents dans une table
     * 
     * @param string $table le nom de la table
     * @param array  $data les données à modifier
     * @param array  $condition la condition de la reqête
     * @param string $separator Opérateur logique
     * 
     * @throws Exception Echec de put
     */
    protected function put($table, $data, $condition=array(), $separator='AND') {
        try {    
            // CONNEXION A LA BASE DE DONNEE
            $this->getDB();

            // REQUETE SQL
            $sql = 'UPDATE '.'`'.$table.'`';
            
            $sql .= ' SET ';
            if (count($data) > 0):
                foreach ($data as $field => $value):
                    $sql .= $field.'=:'.$field.', ';
                endforeach;
                // SUPPRESSION DE la VIRGULE DE FIN 
                $sql = substr_replace(trim($sql), '', -1);
            else:
                throw new Exception("Error Processing Request");
            endif;

            // TRAITEMENT DE LA CONTITION
            if (count($condition) > 0):
                $sql .= ' WHERE ';
                foreach ($condition as $field => $value):
                    $sql .= $field.'=:'.$field.' '.$separator.' ';
                endforeach;
                // SUPPRESSION DE la VIRGULE DE FIN 
                $sql = substr_replace(trim($sql), '', ($separator) == 'AND' ? -3 : -2);
            endif;

            // PREPARATION DE LA REQUETE
            $query = self::$_db->prepare($sql);

            foreach ($data as $field => $value):
                $query->bindValue(':'.$field, $value);
            endforeach;
            foreach ($condition as $field => $value):
                $query->bindValue(':'.$field, $value);
            endforeach;

            // EXECUSSION DE LA REQUETE
            $query->execute();
        } catch (Exception $e) {
            throw new Exception('Echec de UPDATE : '.$e->getMessage(), 500);
        }
    }

    /**
     * Supprimer les données presents dans une table
     * 
     * @param string $table le nom de la table
     * @param array  $condition la condition de la reqête
     * @param string $separator Opérateur logique
     * 
     * @throws Exception Echec de select
     */
    protected function delete($table, $condition, $separator='AND') {
        try {
            // CONNEXION A LA BASE DE DONNEE
            $this->getDB();

            // REQUET SQL
            $sql = 'DELETE FROM '.'`'.$table.'`';

            // TRAITEMENT DE LA CONTITION
            if (count($condition) > 0):
                $sql .= ' WHERE ';
                foreach ($condition as $field => $value):
                    $sql .= $field.'=:'.$field.' '.$separator.' ';
                endforeach;
                // SUPPRESSION DE la VIRGULE DE FIN 
                $sql = substr_replace(trim($sql), '', ($separator) == 'AND' ? -3 : -2);
            else:
                throw new Exception("Error Processing Request");
            endif;

            // PREPARATION DE LA REQUETE
            $query = self::$_db->prepare($sql);

            foreach ($condition as $field => $value):
                $query->bindValue(':'.$field, $value);
            endforeach;

            // EXECUSSION DE LA REQUETE
            $query->execute();
        } catch (Exception $e) {
            throw new Exception('Echec de DELETE : '.$e->getMessage(), 500);
        }
    }

    
}
