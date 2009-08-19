<?php

class BancoDao extends Dao
{
    function  __construct()
    {
        parent::conectar();
    }

    function  __destruct()
    {
        parent::desconectar();
    }

    function get($pCodProfFuncao)
    {
        // horista não possui banco de horas
        $oProfD = new ProfissionalDao();
        $oProf = $oProfD->getProfissional($pCodProfFuncao);
        if($oProf->getHorista())
        {
            return false;
        }

        // retorna banco de horas
        $sql = "SELECT hb.periodo_ini, hb.hex_50, hb.hex_100,
                    hb.total_trab as normais,
                    (hb.total_trab + hb.hex_50 + hb.hex_100) as total_trab,
                    IFNULL(hp.horas,0) as horas_pagas, IFNULL(ht.horas,'0') as horas_a_trabalhar
               FROM hor_banco as hb 
               LEFT JOIN hor_pagas as hp 
               ON (hb.cod_prof_funcao = hp.cod_prof_funcao AND hb.periodo_ini = hp.periodo_ini) 
               LEFT JOIN hor_a_trabalhar as ht 
               ON (hb.cod_prof_funcao = ht.cod_prof_funcao AND hb.periodo_ini = ht.periodo_ini) 
               WHERE hb.cod_prof_funcao = " . $pCodProfFuncao .
               " ORDER BY hb.periodo_ini ASC;";
        $rs = parent::obterRecordSet($sql);
        
        //$varAcumulado = 0;
        $ret = Array();
        foreach($rs as $row)
        {
            $oPeriodo = new Periodo($row["periodo_ini"]);
            $vPeriodo = "de " . $oPeriodo->getInicio() . " a " . $oPeriodo->getFim();
            $oBanco = new Banco();
            $oBanco->setPeriodo($vPeriodo);
            $oBanco->setHorhex50($row["hex_50"]);
            $oBanco->setHorhex100($row["hex_100"]);
            $oBanco->setHornormais($row["normais"]);
            $oBanco->setHortrabalhadas($row["total_trab"]);
            $oBanco->setHoratrabalhar($row["horas_a_trabalhar"]);
            $oBanco->setHorpagas($row["horas_pagas"]);
            $oBanco->setHorsaldo($row["total_trab"] - $row["horas_a_trabalhar"]);
            $varAcumulado += $row["total_trab"] - $row["horas_a_trabalhar"] - $row["horas_pagas"];
            $oBanco->setHoracumuladas($varAcumulado);            
            $ret[] = $oBanco;
        }
        return $ret;
    }
}
?>
