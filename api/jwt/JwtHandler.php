<?php
// INCLURE LE JWT
require_once __DIR__.'/src/JWT.php';

use \Firebase\JWT\JWT;

class JwtHandler
{
    public function __construct() {
        // DEFINITION DU FUSEA HORAIRE PAR DEFAUT
        date_default_timezone_set('Europe/Paris');
        $this->_issuedAt = time();

        // DURABILITE DU TOKEN (1h)
        $this->_expire = $this->_issuedAt + 24*3600;

        // DEFINITION DU CODE SECRET
        $this->_jwt_secret = 'this_is_my_secrect';
    }

    /**
     * ENCODAGE DU TOKEN
     */
    public function _jwt_encode_data($iss, $data) {
        $token = array(
            // AJOUT DE L'IDENTIFIANT DU TOKEN (QUI EMET LE TOKEN)
            'iss' => $iss,
            'aud' => $iss,
            // AJOUT DE L'HORODATAGE ACTUEL AU TOKEN, POUR IDENTIFIER 
            // QUAND LE TOKEN A ETE EMIS
            'iat' => $this->_issuedAt,
            // L'EXPIRATION DU TOKEN
            'exp' => $this->_expire,
            // PAYLOAD
            'data' => $data
        );
        try {
            $encode = JWT::encode($token, $this->_jwt_secret);
            return $encode;
        } catch (Exception $e) {
            return $this->_msg(0, $e->getMessage());
        }
    }

    // DECODAGE DU TOKEN
    public function _jwt_decode_data($jwt_token) {
        try {
            $decode = JWT::decode($jwt_token, $this->_jwt_secret, array('HS256'));
            return $this->_msg(1, $decode->data);
        } catch (Exception $e) {
            return $this->_msg(0, $e->getMessage());
        }
    }

    // MESSAGE
    protected function _msg($success, $data){
        $msg = array('auth' => $success);
        return $msg += ($success === 1) 
        ? array('data' => $data) 
        : array('message' => $data);
    }

    protected $_jwt_secret;
    protected $_expire;
    protected $_issuedAt;
}