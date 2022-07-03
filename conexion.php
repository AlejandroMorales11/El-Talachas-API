<?php

    class conexion {
        private $Host = "";
        private $User = "";
        private $Password = "";
        private $Database = "";
        private $Connection;

        public function __CONSTRUCT() {
            $this->Host = "localhost";
            $this->User = "root";
            $this->Password = "";
            $this->Database = "talachas";
        }

        public function OpenConnection() {
            try {
                $this->Connection = new PDO("mysql:host={$this->Host};dbname={$this->Database}", $this->User, $this->Password);
                $this->Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $this->Connection = FALSE;
            }
        }

        public function CloseConnection() {
            mysql_close($this->Connection);
        }

        public function GetConnection() {
            return $this->Connection;
        }
    }
    
?>