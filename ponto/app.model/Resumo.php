<?php

/**
 * Description of Resumo
 *
 * @author 80058442553
 */
class Resumo {
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
}
?>
