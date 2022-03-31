<?php

class View
{
    // CONSTRUCTEUR
    public function __construct() {}
    // GENERER ET AFFICHER LA VUE
    public function generate($data) {
        http_response_code($data['status']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); 
    }

}