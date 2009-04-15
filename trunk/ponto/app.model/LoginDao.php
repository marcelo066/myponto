<?php
require_once('Dao.php');

class LoginDao extends Dao
{
    public function __construct(){
        if(!parent::conectar()){
          throw new Exception("Erro ao conectar com a base de dados!");
        }
    }

    public function  __destruct() {
        parent::desconectar();
    }

    public function fazerLogin($pUsuario){
        $sql = "SELECT pf.codigo
            FROM profs_pwd pp
            INNER JOIN profs_funcoes pf ON (pp.cod_prof_Funcao = pf.codigo)
            INNER JOIN profs p ON (pf.cod_prof = p.codigo)
            INNER JOIN profs_info_adm pia ON (pia.cod_prof_funcao = pf.codigo)
            WHERE p.interno = 1
                AND pp.cod_sistema = 2
                AND pia.matricula = '" . $pUsuario->getLogin() . "'
                AND pp.pwd = '" . $this->encrypt($pUsuario->getSenha()) . "';";      

        $res = parent::obterRecordSet($sql);
        if($res)
        {
            return $res[0]['codigo'];
        }
        else
        {
            return false;
        }
    }

    private function encrypt($password)
    {
        return md5($password);
    }
   
}
?>