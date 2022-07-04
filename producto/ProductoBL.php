<?php

    require_once '../../api-usuario/conexion.php';
    require '../../api-usuario/DTO/ProductoDTO.php';
    
    class ProductoBL {
        
        private $conn;

        public function __CONSTRUCT() {
            $this->conn = new Conexion();
        }

        public function Create($productoDTO) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();
            $lastInsertId = 0;
            
            try {
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatement = $connsql->prepare(
                        "INSERT INTO producto VALUES (
                            DEFAULT,
                            :name,
                            :price,
                            :image,
                            :description
                        )"
                    );

                    $sqlStatement->bindParam(':name', $productoDTO->Name);
                    $sqlStatement->bindParam(':price', $productoDTO->Price);
                    $sqlStatement->bindParam(':image', $productoDTO->Image);
                    $sqlStatement->bindParam(':description', $productoDTO->Description);
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
            $arrayProducto = new ArrayObject();
            $SQLQuery = "SELECT * FROM productos";
            $productoDTO = new ProductoDTO();
            if($Id > 0)
                $SQLQuery = "SELECT * FROM productos WHERE id_producto ={$Id}";

            try {
                if($connsql)
                {
                    foreach($connsql->query($SQLQuery) as $row )
                    {
                        $productoDTO = new ProductoDTO(); //inicializacion de una nueva instancia 
                        $productoDTO->id = $row['id_producto'];
                        $productoDTO->Name = $row['name'];
                        $productoDTO->Price = $row['price'];
                        $productoDTO->Image = $row['image'];
                        $productoDTO->Descripition = $row['description'];
                        $arrayProducto->append($productoDTO); //tomar los datos de la columnas y mapear a propiedades del objeto DTO
                    }
                }
            } catch (PDOException $e) {
                
            }
            return $arrayProducto;
        }

        public function Update($productoDTO) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();

            try {
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatement = $connsql->prepare(
                        "UPDATE producto SET
                            name = :name,
                            password = :price,
                            image = :image,
                            name = :name,
                            email = :email
                            WHERE id_producto = :id"
                    );

                    $sqlStatement->bindParam(':name', $productoDTO->Name);
                    $sqlStatement->bindParam(':price', $productoDTO->Price);
                    $sqlStatement->bindParam(':image', $productoDTO->Image);
                    $sqlStatement->bindParam(':description', $productoDTO->Description);
                    $sqlStatement->bindParam(':email', $productoDTO->Email);
                    $sqlStatement->bindParam(':id', $productoDTO->id);
                    $sqlStatement->execute();

                    $connsql->commit();
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
            }
        }

        public function Delete($productoDTO) {
            $this->conn->OpenConnection();
            $connsql = $this->conn->GetConnection();

            try {
                if($connsql) {
                    $connsql->beginTransaction();
                    $sqlStatement = $connsql->prepare(
                        "DELETE FROM producto WHERE
                            id_producto = :id"
                    );
                    
                    $sqlStatement->bindParam(':id', $productoDTO->id);
                    $sqlStatement->execute();

                    $connsql->commit();
                }
            } catch (PDOException $e) {
                $connsql->rollBack();
            }
        }

        

    }
    
?>