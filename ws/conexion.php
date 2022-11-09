<?php
    class Conexion extends PDO{

        private $hostBd = 'myBDIntegration:3306';
        private $nombreBd = 'libreriaDB';
        private $usuarioBd = 'root';
        private $passwordBd = 'nova';

        public function __construct(){
            try{
                parent::__construct('mysql:host=' . $this->hostBd . ';dbname=' . $this->
                nombreBd . ';charset=utf8', $this->usuarioBd, $this->passwordBd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e){
                echo 'Error: ' . $e->getMessage();
                exit;
            }
        }

    }
