<?php

    require_once '../../api-usuario/conexion.php';
    require '../../api-usuario/DTO/UsuarioDTO.php';
    
    class UsuarioBL {
        
        private $conn;

        public function __CONSTRUCT() {
            $this->conn = new Conexion();
        }

        public function Create($usuarioDTO) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();
            $lastInsertId = 0;
            
            try {
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatement = $connsql->prepare(
                        "INSERT INTO usuario VALUES (
                            DEFAULT,
                            :username,
                            :pass_word,
                            :image,
                            :name,
                            :email
                        )"
                    );

                    $sqlStatement->bindParam(':username', $usuarioDTO->Username);
                    $sqlStatement->bindParam(':pass_word', $usuarioDTO->Password);
                    $sqlStatement->bindParam(':image', $usuarioDTO->Image);
                    $sqlStatement->bindParam(':name', $usuarioDTO->Name);
                    $sqlStatement->bindParam(':email', $usuarioDTO->Email);
                    $sqlStatement->execute();

                    $lastInsertId = $connsql->lastInsertId();
                    $connsql->commit();
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
            }
            return $lastInsertId;
        }

        public function Read($Id) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();
            $arrayUsuario = new ArrayObject();
            $SQLQuery = "SELECT * FROM usuarios";
            $usuarioDTO = new UsuarioDTO();
            if($Id > 0)
                $SQLQuery = "SELECT * FROM usuarios WHERE id_usuario ={$Id}";

            try {
                if($connsql)
                {
                    foreach($connsql->query($SQLQuery) as $row )
                    {
                        $usuarioDTO = new UsuarioDTO(); //inicializacion de una nueva instancia 
                        $usuarioDTO->id = $row['id_usuario'];
                        $usuarioDTO->Username = $row['username'];
                        $usuarioDTO->Image = $row['image'];
                        $usuarioDTO->Name = $row['name'];
                        $usuarioDTO->Email = $row['email'];
                        $arrayUsuario->append($usuarioDTO); //tomar los datos de la columnas y mapear a propiedades del objeto DTO
                    }
                }
            } catch (PDOException $e) {
                
            }
            return $arrayUsuario;
        }

        public function Update($usuarioDTO) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();

            try {
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatement = $connsql->prepare(
                        "UPDATE usuario SET
                            username = :username,
                            password = :pass_word,
                            image = :image,
                            name = :name,
                            email = :email
                            WHERE id_usuario = :id"
                    );

                    $sqlStatement->bindParam(':username', $usuarioDTO->Username);
                    $sqlStatement->bindParam(':pass_word', $usuarioDTO->Password);
                    $sqlStatement->bindParam(':image', $usuarioDTO->Image);
                    $sqlStatement->bindParam(':name', $usuarioDTO->Name);
                    $sqlStatement->bindParam(':email', $usuarioDTO->Email);
                    $sqlStatement->bindParam(':id', $usuarioDTO->id);
                    $sqlStatement->execute();

                    $connsql->commit();
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
            }
        }

        public function Delete($usuarioDTO) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();

            try {
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatement = $connsql->prepare(
                        "DELETE FROM usuario WHERE
                            id_usuario = :id"
                    );
                    
                    $sqlStatement->bindParam(':id', $usuarioDTO->id);
                    $sqlStatement->execute();

                    $connsql->commit();
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
            }
        }

        

    }
    
?>