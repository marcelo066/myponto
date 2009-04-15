<?php
/**
 * PHP Template.
 */
class Usuario{
    
    //Atributos
    private $codigo;
    private $nome;
    private $login;
    private $senha;
    private $grupo;
    
    //Métodos
    function getCodigo(){
        return $this->codigo;
    }
    
    function getNome(){
        return $this->nome;        
    }

    function setNome($pNome){
        $this->nome = $pNome;
    }   
    
    function getLogin(){
        return $this->login;        
    }

    function setLogin($pLogin){
        $this->login = $pLogin;
    }

    function getSenha(){
        return $this->senha;        
    }

    function setSenha($pSenha){
        $this->senha = $pSenha;
    }

    function getGrupo(){
        return $this->grupo;        
    }

    function setGrupo($pGrupo){
        $this->grupo = $pGrupo;
    }    
}
?>
