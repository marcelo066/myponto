<?php
/**
 * Classe período
 * responsável por {...}
 * @author 80058442553
 */
final class Periodo extends Dao
{
    protected $data;
    protected $inicial;
    protected $final;
    protected $anterior;
    protected $totHorNormais;
    protected $totHorExtras50;
    protected $totHorExtras100;
    protected $totHorTrabalhadas;
    protected $totHorATrabalhar;
    protected $saldoHoras;
    protected $prof;
    public function getData() {
        return $this->data;
    }
    public function getInicial(){
        return $this->inicial;
    }
    public function getAnterior(){
        return $this->anterior;
    }
    public function getFinal(){
        return $this->final;
    }
    public function getTotHorNormais() {
        return number_format($this->totHorNormais,2);
    }
    private function setTotHorNormais($pValor) {
        $this->totHorTrabalhadas = $pValor;
        $this->totHorNormais = $pValor;
    }
    public function getTotHorExtras50() {
        return number_format($this->totHorExtras50,2);
    }
    private function setTotHorExtras50($pValor) {
        // calcula o total
        $this->totHorTrabalhadas += $pValor;
        $this->totHorExtras50 = $pValor;
    }
    public function getTotHorExtras100() {
        return number_format($this->totHorExtras100,2);
    }
    private function setTotHorExtras100($pValor) {
        // calcula o total
        $this->totHorTrabalhadas += $pValor;
        $this->totHorExtras100 = $pValor;
    }
    public function getTotHorTrabalhadas() {
        return number_format($this->totHorTrabalhadas,2);
    }
    private function setTotHorTrabalhadas($pValor) {
        $this->totHorTrabalhadas = number_format($pValor,2);
    }
    public function getTotHorATrabalhar() {
        return number_format($this->totHorATrabalhar,2);
    }
    private function setTotHorATrabalhar($pValor) {
        $this->totHorATrabalhar = $pValor;
    }
    public function getSaldoHoras() {
        return number_format($this->saldoHoras,2);
    }
    private function setSaldoHoras($pValor) {
        $this->saldoHoras = $pValor;
    }
   /**
     * Método getInício
     * retorna o período inicial de uma data
     * @param String $pData uma data no formato dd/mm/yyyy
     * @return String d-m-Y
     */
    private function setInicial()
    {
        $varTmp = new DateTime($this->getData());
        list($dia, $mes, $ano) = explode('/', $varTmp->format("d/m/Y"));
        if($dia < 25)
        {
            $mes = $mes - 1;
            if($mes == 0)
            {
                $mes = 12;
                $ano--;
            }
        }
        //$ret = new DateTime("25-$mes-$ano");
        $this->inicial =  "25-$mes-$ano";
    }
    /**
     * Método getAnterior
     * retorna o período anterior de uma data
     * @param String $pData = uma data no formato dd/mm/yyyy
     * @return DateTime
     */
    private function setAnterior()
    {
        $varTmp = new DateTime($this->getData());
        list($dia, $mes, $ano) = explode('/', $varTmp->format("d/m/Y"));
        $mes = $mes - 1;
        if($mes == 0){
            $mes = 12;
            $ano--;
        }
        // $ret = new DateTime("25-$mes-$ano");
        //return "25-$mes-$ano";
        $this->final = "25-$mes-$ano";
    }
    /**
     * Método getFinal
     * retorna o período final de uma data
     * @param String $pData = uma data no formato dd/mm/yyyy
     * @return DateTime
     */
    private function setFinal()
    {
        $varTmp = new DateTime($this->getData());
        list($dia, $mes, $ano) = explode('/', $varTmp->format("d/m/Y"));
        if($dia >= 25){
            $mes++;
            if($mes == 13){
                $mes = 1;
                $ano++;
            }
        }
        $ret = new DateTime("24-$mes-$ano");
        //return "24-$mes-$ano";
        $this->final = "24-$mes-$ano";
    }
    public function getHorATrabalhar(DateTime $pInicio, DateTime $pFim)
    {
        // checa se o profissional ï¿½ horista,
        // horistas nï¿½o possuem limite de horas a trabalhar
        $pProf = $this->prof;
        if($pProf->getHorista())
        {
            return 0;
        }

        // checa se jï¿½ existe registro na tabela de horas a trabalhar
        $sql = "SELECT horas "
        . "FROM hor_a_trabalhar "
        . "WHERE cod_prof_funcao =  ".$pProf->getCodProfFuncao()
        . " AND periodo_ini = '". $pInicio->format("Y-m-d") . "';";
        parent::conectar();
        $cont = parent::executarScalar($sql);
        if($cont > 0)
        {
            return $cont;
        }
        parent::desconectar();     

        // obtem a carga horï¿½ria do profissional, converte para numero
        $varCargaHoraria = $pProf->getCargaHoraria();

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
    function  __construct(Profissional $pProf) {
        try{
            $this->data = date("d-m-Y");
            $this->setInicial();
            $this->setAnterior();
            $this->setFinal();
            $this->prof = $pProf;

            $oInicio = new DateTime($this->getInicial());
            $oFim = new DateTime($this->getFinal());
            $sql = "SELECT SUM(h_50) * 1.5 as Hex50, SUM(h_100) * 2 as Hex100, SUM(total) - (SUM(h_50) + SUM(h_100)) as Normais  "
            . "FROM hor_frequencia "
            . "WHERE cod_prof_funcao =  ".$pProf->getCodProfFuncao()
            . " AND data BETWEEN '" . $oInicio->format('Y-m-d')
            . "' AND '" . $oFim->format('Y-m-d') . "';";
            parent::conectar();
            $rs = parent::obterRecordSet($sql);
            $this->setTotHorNormais($rs[0]["Normais"]);
            $this->setTotHorExtras50($rs[0]["Hex50"]);
            $this->setTotHorExtras100($rs[0]["Hex100"]);
            $this->setTotHorATrabalhar($this->getHorATrabalhar($pProf, $oInicio, $oFim));

            // calcula saldo
            $saldoHoras = ($rs[0]["Normais"] + $rs[0]["Hex50"] + $rs[0]["Hex100"]) - $this->getTotHorATrabalhar();
            $this->setSaldoHoras($saldoHoras);
            parent::desconectar();
            return true;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }
}
?>
