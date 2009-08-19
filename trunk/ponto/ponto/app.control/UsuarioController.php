<?php

require_once("./checaLogin.php");

class UsuarioController extends Controller {

    private $view;
    private $model;

    public function __construct(){
        // instanciamos os objetos
        $this->model = new UsuarioModel();
        $this->view = new UsuarioView();
    }

    public function incluir(){
      //Configura usu�rio
      $user = new UsuarioVO;
      $user->setGrupo(1);
      $user->setLogin('teste login');
      $user->setNome('teste nome');
      $user->setSenha('123456');  

      //insere no bd
      $model = new UsuarioModel();
      $model->incluir($user);
    }      
}    
?>
