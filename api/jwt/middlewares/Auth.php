<?php
require_once __DIR__.'/../JwtHandler.php';

class Auth extends JwtHandler
{
    public function __construct($headers) {
        parent::__construct();
        $this->_headers = $headers;
    }

    public function auth() {
        if (!array_key_exists('authorization', $this->_headers)):
            return null;
        elseif (empty(trim($this->_headers['authorization']))):
            return null;
        else:
            $token = explode(' ', trim($this->_headers['authorization']));
            if (isset($token[1]) && !empty($token[1])):
                $data = $this->_jwt_decode_data($token[1]);
                if (isset($data['auth']) && (!empty($data['data']))):
                    return $data['data']->user_id;
                endif;
            else:
                return null;
            endif;
        endif;
    }

    private $_headers;
}