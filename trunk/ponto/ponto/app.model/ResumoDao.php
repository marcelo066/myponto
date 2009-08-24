<?php

class ResumoDao extends Dao
{
    public function  __construct()
    {
        parent::conectar();
    }

    public function  __destruct()
    {
        parent::desconectar();
    }

    public function getResumo($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        // Foi necess�rio criar outro objeto, pois todo objeto em php
        // � passado por refer�ncia e isso estava interferindo no resultado
        // de outras fun��es
        $oInicio = new DateTime($pInicio->format("Y-m-d"));
        $oFim = new DateTime($pFim->format("Y-m-d"));

        $sql = "SELECT SUM(h_50) * 1.5 as Hex50, SUM(h_100) * 2 as Hex100, SUM(total) - (SUM(h_50) + SUM(h_100)) as Normais  "
               . "FROM hor_frequencia "
               . "WHERE cod_prof_funcao = $pCodProfFuncao "
               . "AND data BETWEEN '" . $oInicio->format('Y-m-d')
               . "' AND '" . $oFim->format('Y-m-d') . "';";

        $rs = parent::obterRecordSet($sql);

        $oResumo = new Resumo();
        $oResumo->setTotHorNormais($rs[0]["Normais"]);
        $oResumo->setTotHorExtras50($rs[0]["Hex50"]);
        $oResumo->setTotHorExtras100($rs[0]["Hex100"]);
        $oResumo->setTotHorATrabalhar($this->getHorATrabalhar($pCodProfFuncao, $oInicio, $oFim));

        // calcula saldo
        $saldoHoras = ($rs[0]["Normais"] + $rs[0]["Hex50"] + $rs[0]["Hex100"]) - $oResumo->getTotHorATrabalhar();
        $oResumo->setSaldoHoras($saldoHoras);

        return $oResumo;
    }

    public function getHorATrabalhar($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {              
        // checa se j� existe registro na tabela de horas a trabalhar
        $sql = "SELECT horas "
            . "FROM hor_a_trabalhar "
            . "WHERE cod_prof_funcao = $pCodProfFuncao "
            . " AND periodo_ini = '". $pInicio->format("Y-m-d") . "';";

        $cont = parent::executarScalar($sql);
        if($cont > 0)
        {
            return $cont;
        }

        // checa se o profissional � horista,
        // horistas n�o possuem limite de horas a trabalhar
        $oProf = new Profissional($pCodProfFuncao);
        if($oProf->getHorista())
        {
            return 0;
        }

        // obtem a carga hor�ria do profissional
        $varCargaHoraria = $oProf->getCargaHoraria();

        // calcula a quantidade de horas a
        // trabalhar no per�odo entre duas datas
        //$oTipoDia = new Data();
        
        while($pInicio <= $pFim)
        {            
            $varTipoDia = Data::getTipoDia($pInicio);
                        
            if(!($varTipoDia == "S�bado" || $varTipoDia == "Domingo" || $varTipoDia == "Feriado"))
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
                if($varTipoDia == "Feriado")
                {
                    // H� feriados que s� desconta um turno, por exemplo
                    $feriados = new FeriadoDao();
                    $feriado = $feriados->getFeriado($pInicio);                  
                    $varDesconto += $feriado->getHoras();

                    // Se o feriado cair dia de s�bado
                    // Desconta 2h da carga hor�ria referente a este dia
                    if(Data::getDiaSemana($pInicio) == "S�bado")
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
