<?php
    error_reporting(E_ERROR);

    function get_db_config() {

        $jsonString = file_get_contents('.\config\db_credentials.json');
        $config = json_decode($jsonString, true);
        
        if ($config === null) {
            die('Erreur lors de la lecture du fichier JSON');
        }

        $dsn = $config['credentials']['dsn'];
        $user = $config['credentials']['user'];
        $password = $config['credentials']['password'];

        if (strstr($_SERVER['DOCUMENT_ROOT'],"wamp")){
            $password = ""; //pas besoin de mdp sous WAMP
        }

        return [$dsn, $user, $password];
    }
