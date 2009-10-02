<?php

/**
 * Description of Resumo
 *
 * @author 80058442553
 */
class Resumo extends Dao {
    protected $totHorNormais;
    protected $totHorExtras50;
    protected $totHorExtras100;
    protected $totHorTrabalhadas;
    protected $totHorATrabalhar;
    protected $saldoHoras;

    public function getTotHorNormais() {
        return number_format($this->totHorNormais,2);
    }

    public function setTotHorNormais($totHorNormais) {
        $this->totHorTrabalhadas = $totHorNormais;
        $this->totHorNormais = $totHorNormais;
    }

    public function getTotHorExtras50() {
        return number_format($this->totHorExtras50,2);
    }

    public function setTotHorExtras50($totHorExtras50) {
        // calcula o total
        $this->totHorTrabalhadas += $totHorExtras50;
        //
        $this->totHorExtras50 = $totHorExtras50;
    }

    public function getTotHorExtras100() {
        return number_format($this->totHorExtras100,2);
    }

    public function setTotHorExtras100($totHorExtras100) {
        // calcula o total
        $this->totHorTrabalhadas += $totHorExtras100;
        //
        $this->totHorExtras100 = $totHorExtras100;
    }

    public function getTotHorTrabalhadas() {
        return number_format($this->totHorTrabalhadas,2);
    }

    /*
    public function setTotHorTrabalhadas($totHorTrabalhadas) {
        $this->totHorTrabalhadas = number_format($totHorTrabalhadas,2);
    }
     */

    public function getTotHorATrabalhar() {
        return number_format($this->totHorATrabalhar,2);
    }

    public function setTotHorATrabalhar($totHorATrabalhar) {
        $this->totHorATrabalhar = $totHorATrabalhar;
    }

    public function getSaldoHoras() {
        return number_format($this->saldoHoras,2);
    }

    public function setSaldoHoras($saldoHoras) {
        $this->saldoHoras = $saldoHoras;
    }

    public function getResumo($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        // Foi necessï¿½rio criar outro objeto, pois todo objeto em php
        // ï¿½ passado por referï¿½ncia e isso estava interferindo no resultado
        // de outras funï¿½ï¿½es
        $oInicio = new DateTime($pInicio->format("Y-m-d"));
        $oFim = new DateTime($pFim->format("Y-m-d"));

        $sql = "SELECT SUM(h_50) * 1.5 as Hex50, SUM(h_100) * 2 as Hex100, SUM(total) - (SUM(h_50) + SUM(h_100)) as Normais  "
               . "FROM hor_frequencia "
               . "WHERE cod_prof_funcao = $pCodProfFuncao "
               . "AND data BETWEEN '" . $oInicio->format('Y-m-d')
               . "' AND '" . $oFim->format('Y-m-d') . "';";
        parent::conectar();
        $rs = parent::obterRecordSet($sql);

        $oResumo = new Resumo();
        $oResumo->setTotHorNormais($rs[0]["Normais"]);
        $oResumo->setTotHorExtras50($rs[0]["Hex50"]);
        $oResumo->setTotHorExtras100($rs[0]["Hex100"]);
        $oResumo->setTotHorATrabalhar($this->getHorATrabalhar($pCodProfFuncao, $oInicio, $oFim));

        // calcula saldo
        $saldoHoras = ($rs[0]["Normais"] + $rs[0]["Hex50"] + $rs[0]["Hex100"]) - $oResumo->getTotHorATrabalhar();
        $oResumo->setSaldoHoras($saldoHoras);
        parent::desconectar();
        return $oResumo;
    }

    public function getHorATrabalhar($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        // checa se jï¿½ existe registro na tabela de horas a trabalhar
        $sql = "SELECT horas "
            . "FROM hor_a_trabalhar "
            . "WHERE cod_prof_funcao = $pCodProfFuncao "
            . " AND periodo_ini = '". $pInicio->format("Y-m-d") . "';";
        parent::conectar();
        $cont = parent::executarScalar($sql);
        if($cont > 0)
        {
            return $cont;
        }
        parent::desconectar();

        // checa se o profissional ï¿½ horista,
        // horistas nï¿½o possuem limite de horas a trabalhar
        $oProf = new Profissional($pCodProfFuncao);
        if($oProf->getHorista())
        {
            return 0;
        }

        // obtem a carga horï¿½ria do profissional
        $varCargaHoraria = $oProf->getCargaHoraria();

        // calcula a quantidade de horas a
        // trabalhar no perï¿½odo entre duas datas
        //$oTipoDia = new Data();

        while($pInicio <= $pFim)
        {
            $varTipoDia = Data::getTipoDia($pInicio);
            if(!($varTipoDia == "Sábado" || $varTipoDia == "Domingo"))
            {
                if($varCargaHoraria == 8.5)
                {
                    if($varTipoDia == "Sexta-Feira")
                    {
                        $varTotal += 8;
                    }
                    else
                    {
                        $varTotal += $varCargaHoraria;
                    }
                }
                else
                {
                    $varTotal += $varCargaHoraria;
                }
            }
            else
            {
                // Trata feriado
                // Há feriados que só desconta um turno, por exemplo
                $oFeriado = new Feriado;
                if($oFeriado->isFeriado($pInicio))
                {
                    $varDesconto += $oFeriado->getHoras();

                    // Se o feriado cair dia de sábado
                    // Desconta 2h da carga horária referente a este dia
                    if(Data::getDiaSemana($pInicio) == "Sábado")
                    {
                        $varDesconto += 2;
                    }
                }
            }
            // Incrementa dia
            $pInicio->modify("+1 day");
        }
        $varTotal -= $varDesconto;
        return $varTotal;
    }

}
?>
