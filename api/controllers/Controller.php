<?php

abstract class Controller {
    public function __construct() {
        // INITIALISATION DE L'OBJET VUE
        $this->_view = new View();
    }

    /**
     * Afficher un message d'erreur.
     * 
     * @param int    $success le succès
     * @param int    $status le status
     * @param string $message le message
     * @param array  $extra les informations supplémentaires
     * 
     * @return array Un tableau qui représent les données à afficher
     */
    protected function msgErr($success, $status, $message, $extra = []) {
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ], $extra);
    }

    protected $_manager;
    protected $_returnData;
    protected $_view;
}



