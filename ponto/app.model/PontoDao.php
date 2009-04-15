<?php

class PontoDao extends Dao
{
    //put your code here
    function  __construct() {
        parent::conectar();
    }

    function  __destruct() {
        parent::desconectar();
    }

    function incluir(Ponto $pPonto, Profissional $pProf)
    {
        
    }

    function getPonto(Ponto $pPonto, Profissional $pProf)
    {
        $sql = "SELECT entrada, almoco, retorno, saida
                FROM hor_requencia
                WHERE data = '" . $pPonto->getData() .  "'
                AND cod_prof_funcao = " .  $pProf->getCodProfFuncao();
        $rs = parent::obterRecordSet($sql);
        return $rs;
    }
}
?>
