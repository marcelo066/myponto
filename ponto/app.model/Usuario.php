<?php
/**
 * PHP Template.
 */
class Usuario extends Dao{

    //Atributos
    private $codigo;
    private $nome;
    private $login;
    private $senha;
    private $grupo;

    //M�todos
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
        $this->senha = $this->encrypt($pSenha);
    }

    function getGrupo(){
        return $this->grupo;
    }

    function setGrupo($pGrupo){
        $this->grupo = $pGrupo;
    }

    public function login()
    {
        $sql = "SELECT pf.codigo
            FROM profs_pwd pp
            INNER JOIN profs_funcoes pf ON (pp.cod_prof_Funcao = pf.codigo)
            INNER JOIN profs p ON (pf.cod_prof = p.codigo)
            INNER JOIN profs_info_adm pia ON (pia.cod_prof_funcao = pf.codigo)
            WHERE p.interno = 1
                AND pp.cod_sistema = 2
                AND pia.matricula = '" . $this->getLogin() . "'
                AND pp.pwd = '" . $this->getSenha() . "';";

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

    public function incluir($pUsuario)
    {
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
