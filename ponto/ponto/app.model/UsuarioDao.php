<?php
require_once('Model.php');

class UsuarioDao extends Dao
{            
    public function __construct(){                
        if(!parent::conectar()){
          echo "Erro ao conectar com a base de dados!";
          exit();
        }          
    }

    public function  __destruct() {
        parent::desconectar();
    }
    
    public function incluir($pUsuario){          
        $this->inicializarTransacao();
        $sql = "INSERT INTO sistemas_pwd (idGrupo, nomeUsuario, login, senha, codSistema) VALUES ("
          . $pUsuario->getGrupo() . ",'" 
          . $pUsuario->getNome() . "','"
          . $pUsuario->getLogin() . "','"
          . $pUsuario->getSenha() . "'," 
          . 2 . ");";                   
        $this->executar($sql);
        $this->finalizarTransacao();   
        $this->desconectar();
    }
    
}
?>

