<?php
abstract class Dao {

  private $conn;

/**
 * Conecta com a base de dados
 * @return <type>
 */      
  protected function conectar(){
    try{
        $this->conn = new PDO('mysql:unix_socket=/tmp/mysql.sock;host=127.0.0.1;port=3306;dbname=new_ufc','root','');
        return true;
    }catch(PDOException $e){
        throw new Exception($e->getMessage());
    }
  } 

  protected function desconectar(){
      try{
        $this->conn = null;
        return true;
      }catch(PDOException $e){
        throw new Exception($e->getMessage());
      }
  } 
  
  protected function obterRecordSet($sql){
      try{
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $ret = $stm->fetchAll();
        return $ret;

      }catch(PDOException $e){
        throw new Exception($e->getMessage());
      }
  }
  
  protected function executar($sql){
      try{
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        return $stm->rowCount();
      }catch(PDOException $e){
        throw new Exception($e->getMessage());
      }
  }
  
  protected function executarScalar($sql){
      try{
        $stm = $this->conn->prepare($sql);
        $stm->execute();
        $row = $stm->fetchColumn();
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