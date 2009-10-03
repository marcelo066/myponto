<?php
/**
 * Description of Frequencia
 *
 * @author 80058442553
 */
class Frequencia {
    private $ano;
    private $mes;
    private $matricula;
    private $nome;
    private $funcao;
    private $registro; // um objeto
    private $totHorNormais;
    private $totHorExtras50;
    private $totHorExtras100;
    private $totHorTrabalhadas;
    private $totHorATrabalhar;
    private $saldoHoras;

    public function getAno() {
        return $this->ano;
    }

    public function getMes() {
        return $this->mes;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getFuncao() {
        return $this->funcao;
    }

    public function getRegistro() {
        return $this->registro;
    }

    public function getTotHorNormais(){
        return $this->totHorNormais;
    }

    public function getTotHorExtras50(){
        return $this->totHorExtras50;
    }

    public function getTotHorExtras100(){
        return $this->totHorExtras100;
    }

    public function getTotHorTrabalhadas(){
        return $this->totHorTrabalhadas;
    }

    public function getTotHorATrabalhar(){
        return $this->totHorATrabalhar;
    }

    public function getSaldoHoras(){
        return $this->saldoHoras;
    }

    public function   __construct(Profissional $pProf, Periodo $pPeriodo)
    {
        try{
            $this->nome = $pProf->getNome();
            $this->matricula = $pProf->getMatricula();
            $this->funcao = $pProf->getFuncao();
            $this->totHorATrabalhar = $pProf->getHorasTrabalharNoPeriodo($pPeriodo);

            // instancia objetos que comp�e a frequencia
            $oRegistro = new Registro();
            $this->registro = $oRegistro->getByRange($pProf, $pPeriodo);
            $oRegistro->getTotaisTrabalhados($pProf, $pPeriodo);
            $this->totHorNormais = number_format($oRegistro->getTotal() - $oRegistro->getExtra100() - $oRegistro->getExtra50(),2);
            $this->totHorExtras50 = $oRegistro->getExtra50();
            $this->totHorExtras100 = $oRegistro->getExtra100();
            $this->totHorTrabalhadas = number_format($oRegistro->getTotal(),2);
            $this->saldoHoras = $this->totHorTrabalhadas - $this->totHorATrabalhar;
            
            // la la la la la la la
            $vData = new DateTime($pPeriodo->getData());
            $this->ano = $vData->format("Y");
            $this->mes = $vData->format("m");

            return true;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }

}
?>
