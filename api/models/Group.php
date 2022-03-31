<?php 

class Group implements JsonSerializable {

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
    public function setImg($img) {
        $this->img = (string) $img;
    }


    //GETTERS
    public function id() {
        return $this->id;
    }
    public function name() {
        return $this->name;
    }
    public function img() {
        return $this->img;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    private $id;
    private $name;
    private $img;
}


