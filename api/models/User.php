<?php 

class User implements JsonSerializable {

    // CONSTRUCTEUR
    public function __construct($data) {
        $this->hydrate($data);
    }

    // HYDRATATION
    private function hydrate($data) {
        foreach ($data as $key => $value):
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)):
                $this->$method($value);
            endif;
        endforeach;
    }

    // SETTERS
    public function setId($id) {
        $this->id = (int) $id;
    }
    public function setName($name) {
        $this->name = (string) $name;
    }
    public function setEmail($email) {
        $this->email = (string) $email;
    }
    public function setPassword($password) {
        $this->password = (string) $password;
    }
    public function setImg($img) {
        $this->img = isset($img) ? (string) $img : null;
    }
    public function setStatus($status) {
        $this->status = (int) $status;
    }



    //GETTERS
    public function id() {
        return $this->id;
    }
    public function name() {
        return $this->name;
    }
    public function email() {
        return $this->email;
    }
    public function password() {
        return $this->password;
    }
    public function img() {
        return $this->img;
    }
    public function status() {
        return $this->status;
    }

    public function jsonSerialize()
    {
        $json = get_object_vars($this);
        foreach ($json as $key => $value):
            if ($key == 'password'):
                unset($json[$key]);
            endif;
        endforeach;
        return $json;
    }

    private $id;
    private $password;
    private $name;
    private $email;
    private $img;
    private $status;
}


