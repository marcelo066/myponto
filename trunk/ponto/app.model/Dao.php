<?php
abstract class Dao {

    private $conn;

/**
 * Conecta com a base de dados
 * @return <type>
 */
    private function conectar(){
        try{
            $this->conn = new PDO('mysql:unix_socket=/tmp/mysql.sock;host=127.0.0.1;port=3306;dbname=new_ufc','root','h4ck3r');
            return true;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    private function desconectar(){
        try{
            $this->conn = null;
            return true;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    protected function obterRecordSet($sql){
        try{
            $this->conectar();
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $ret = $stm->fetchAll();
            $this->desconectar();
            return $ret;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    protected function executar($sql){
        try{
            $this->conectar();
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $cont = $stm->rowCount();
            $this->desconectar();
            return cont;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    protected function executarScalar($sql){
        try{
            $this->conectar();
            $stm = $this->conn->prepare($sql);
            $stm->execute();
            $row = $stm->fetchColumn();
            $this->desconectar();
            return $row;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    protected function inicializarTransacao(){
        try{
            $this->conn->setAttribute(PDO::ATTR_AUTOCOMMIT,FALSE);
            $this->conn->beginTransaction();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    protected function finalizarTransacao(){
        try{
            $this->conn->commit();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

    protected function cancelarTransacao(){
        try{
            $this->conn->rollBack();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }

} 
?>