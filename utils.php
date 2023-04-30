<?php
    function get_db_config() {

        $jsonString = file_get_contents('../config/db.json');
        $config = json_decode($jsonString, true);
        
        if ($config === null) {
            die('Erreur lors de la lecture du fichier JSON');
        }

        $dsn = $config['credentials']['dsn'];
        $user = $config['credentials']['user'];
        $password = $config['credentials']['password'];

        return [$dsn, $user, $password];
    }
?>