<?php

    require 'UsuarioBL.php';
    class UsuarioService {
        private $usuarioDTO;
        private $usuarioBL;

        public function __CONSTRUCT() {
            $this->usuarioDTO = new UsuarioDTO();
            $this->usuarioBL = new UsuarioBL();
        }

        public function Read($username, $password) {
            $Username = $username;
            $Password = $password;
            $this->usuarioDTO = $this->usuarioBL->Read($Username, $Password);
            echo json_encode($this->usuarioDTO, JSON_PRETTY_PRINT);
        }

        public function Auth($token){
            $Token = $token;
            if($Confirmed = $this->usuarioBL->Authorize($Token) > 0){
                echo $json = ('{"confirm":"true"}');
            }
            else{
                echo $json = ('{"confirm":"false"}');
            }
        }
    }
    
    $Obj = new UsuarioService();
    switch($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            {
                $data = json_decode(file_get_contents('php://input'), true);//ESTO ES PARA EL TOKEN
                $Obj->Auth($data['token']);
                break;
            }
        case 'POST':
            {
                $data = json_decode(file_get_contents('php://input'), true);
                $Obj->Read($data['username'], $data['password']);
                break;
            }
        case 'PUT':
            {
                Echo json_encode("PUT Realizado", JSON_PRETTY_PRINT);
                break;
            }
        case 'DELETE':
            {
                Echo json_encode("DELETE Realizado", JSON_PRETTY_PRINT);
                break;
            }
            
        default:
            Echo json_encode("[Otro metodo] Realizado", JSON_PRETTY_PRINT);
            break;
    }
    
?>