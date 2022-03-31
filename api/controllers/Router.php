<?php

require_once './views/View.php';

class Router
{
    // ROUTE REQUETE
    public function routeReq() {
        try {
            // CHARGEMENT AUTOMATIQUE DES CLASSES DE MODELS
            spl_autoload_register( function($class) {
                if (file_exists('./models/'.$class.'.php')):
                    require_once './models/'.$class.'.php';
                endif;
            }); 

            $url = '';

            if (isset($_GET['url'])) :
                // LE CONTROLLEUR EST INCLUS SELON L'ACTION DE L'UTILISATEUR
                $url = explode('/', filter_var($_GET['url'], FILTER_SANITIZE_URL));
                $controller = ucfirst(strtolower($url[0]));
                $controllerClass = 'Controller'.$controller;
                $controllerFile = './controllers/'.$controllerClass.'.php';
                if (file_exists($controllerFile)) :
                    require_once($controllerFile);
                    $this->_ctrl = new $controllerClass($url);
                else :
                    throw new Exception('Page introuvable', 404);
                endif;
            endif;
        } catch (Exception $e) {
            // GESTION DES ERREURS
            $this->_view = new View();
            $this->_view->generate(array(
                'success' => false,
                'status' => $e->getCode(),
                'message' => $e->getMessage()
            ));
        }
    }

    // LE CONTROLEUR
    private $_ctrl;
    // LA VUE
    private $_view;
}