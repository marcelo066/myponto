<?php

/**
 * Description of Horario
 *
 * @author 80058442553
 */
class HorarioPadrao extends Dao
{
    //put your code here
    private $dia;
    private $entrada;
    private $almoco;
    private $retorno;
    private $saida;    
    private $descricao;
    private $toleranciaAntes;
    private $toleranciaDepois;
   
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getEntrada() {
        return $this->entrada;
    }

    public function setEntrada($entrada) {
        $this->entrada = $entrada;
    }

    public function getAlmoco() {
        return $this->almoco;
    }

    public function setAlmoco($almoco) {
        $this->almoco = $almoco;
    }

    public function getRetorno() {
        return $this->retorno;
    }

    public function setRetorno($retorno) {
        $this->retorno = $retorno;
    }

    public function getSaida() {
        return $this->saida;
    }

    public function setSaida($saida) {
        $this->saida = $saida;
    }

    public function getDia() {
        return $this->dia;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function getToleranciaAntes() {
        return $this->toleranciaAntes;
    }

    public function setToleranciaAntes($toleranciaAntes) {
        $this->toleranciaAntes = $toleranciaAntes;
    }

    public function getToleranciaDepois() {
        return $this->toleranciaDepois;
    }

    public function setToleranciaDepois($toleranciaDepois) {
        $this->toleranciaDepois = $toleranciaDepois;
    }

    public function getHorario($pCodProfFuncao)
    {
        $sql = "SELECT hhd.descricao, hh.dia, hh.entrada, hh.almoco, hh.retorno, hh.saida, hh.tolerancia_antes, hh.tolerancia_depois " .
                "FROM hor_horarios AS hh " .
                "INNER JOIN hor_horarios_descricao AS hhd ON hh.codigo = hhd.codigo " .
                "INNER JOIN profs_info_adm pia ON pia.cod_hor_horarios = hh.codigo " .
                "WHERE pia.cod_prof_funcao = $pCodProfFuncao;";
        parent::conectar();
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
        parent::desconectar();
        return $oHorario;
    }
}
?>
