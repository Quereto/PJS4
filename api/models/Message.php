<?php 

class Message implements JsonSerializable {

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
    public function setIncoming_msg_id($incoming_msg_id) {
        $this->incoming_msg_id = (int) $incoming_msg_id;
    }
    public function setOutgoing_msg_id	($outgoing_msg_id	) {
        $this->outgoing_msg_id	 = (int) $outgoing_msg_id	;
    }
    public function setMsg($msg) {
        $this->msg = (string) $msg;
    }
    public function setCreatedAt($createdAt) {
        $this->createdAt = (string) $createdAt;
    }
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = (string) $updatedAt;
    }

    //GETTERS
    public function id() {
        return $this->id;
    }
    public function incoming_msg_id() {
        return $this->incoming_msg_id;
    }
    public function outgoing_msg_id	() {
        return $this->outgoing_msg_id	;
    }
    public function msg() {
        return $this->msg;
    }
    public function createdAt() {
        return $this->createdAt;
    }
    public function updatedAt() {
        return $this->updatedAt;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    private $id;
    private $incoming_msg_id;
    private $outgoing_msg_id;
    private $msg;
    private $createdAt;
    private $updatedAt;
}


