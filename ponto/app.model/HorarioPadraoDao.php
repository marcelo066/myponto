<?php

class HorarioPadraoDao extends Dao
{
    public function  __construct()
    {
        parent::conectar();
    }

    public function __destruct()
    {
        parent::desconectar();
    }

    public function getHorario($pCodProfFuncao)
    {
        $sql = "SELECT hhd.descricao, hh.dia, hh.entrada, hh.almoco, hh.retorno, hh.saida, hh.tolerancia_antes, hh.tolerancia_depois " .
                "FROM hor_horarios AS hh " .
                "INNER JOIN hor_horarios_descricao AS hhd ON hh.codigo = hhd.codigo " .
                "INNER JOIN profs_info_adm pia ON pia.cod_hor_horarios = hh.codigo " .
                "WHERE pia.cod_prof_funcao = $pCodProfFuncao;";
        $rs = parent::obterRecordSet($sql);
        $vHorario = array();
        foreach($rs as $row)
        {
            $oHorario = new HorarioPadrao();
            $oHorario->setDia($row["dia"]);
            $oHorario->setEntrada($row["entrada"]);
            $oHorario->setAlmoco($row["almoco"]);
            $oHorario->setRetorno($row["dia"]);
            $oHorario->setSaida($row["saida"]);
            $oHorario->setToleranciaAntes($row["tolerancia_antes"]);
            $oHorario->setToleranciaDepois($row["tolerancia_depois"]);
            $vHorario[] = $oHorario;
        }
        return $oHorario;
    }
    
}
?>
