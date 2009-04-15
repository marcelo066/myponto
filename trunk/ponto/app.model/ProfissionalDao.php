<?php

class ProfissionalDao extends Dao
{   
    public function  __construct() {
        parent::conectar();
    }

    public function  __destruct() {
        parent::desconectar();
    }

    public function getProfissional($pCodProfFuncao)
    {        
        $sql = "SELECT pf.codigo, p.nome, pia.matricula, pms.codigo as horista, ptt.tipo as carga, f.nome as funcao "
            . "FROM profs p INNER JOIN profs_funcoes pf "
            . "ON (p.codigo = pf.cod_prof) "
            . "INNER JOIN profs_info_adm pia ON (pia.cod_prof_funcao = pf.codigo) "
            . "INNER JOIN profs_modo_salario pms ON (pia.cod_modo_salario = pms.codigo) "
            . "INNER JOIN profs_tipo_turno ptt ON (ptt.codigo = pia.cod_tipo_turno) "
            . "INNER JOIN funcoes f ON (f.codigo = pf.cod_funcao) "
            . "WHERE pf.codigo = $pCodProfFuncao;";
        $rs = parent::obterRecordSet($sql);
        $oProf = new Profissional();
        $oProf->setCodProfFuncao($rs[0]["codigo"]);
        $oProf->setMatricula($rs[0]["matricula"]);
        $oProf->setNome($rs[0]["nome"]);
        $oProf->setHorista($rs[0]["horista"]);
        $oProf->setCargaHoraria($rs[0]["carga"]);
        $oProf->setFuncao($rs[0]["funcao"]);
        $this->desconectar();
        return $oProf;
    }   

}
?>
