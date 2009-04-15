<?php
/**
 * Description of BancoHoras
 *
 * @author 80058442553
 */
final class Banco
{
    //put your code here
    private $periodo;
    private $horHex50;
    private $horHex100;
    private $horNormais;
    private $horTrabalhadas;
    private $horATrabalhar;
    private $horSaldo;
    private $horPagas;
    private $horAcumuladas;

    public function getPeriodo() {
        return $this->periodo;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }

    public function getHorhex50() {
        return  number_format($this->horHex50,2,',','.');
    }

    public function setHorhex50($horHex50) {
        $this->horHex50 = $horHex50;
    }

    public function getHorhex100() {
        return  number_format($this->horHex100,2,',','.');
    }

    public function setHorhex100($horHex100) {
        $this->horHex100 = $horHex100;
    }

    public function getHornormais() {
        return  number_format($this->horNormais,2,',','.');
    }

    public function setHornormais($horNormais) {
        $this->horNormais = $horNormais;
    }

    public function getHortrabalhadas() {
        return  number_format($this->horTrabalhadas,2,',','.');
    }

    public function setHortrabalhadas($horTrabalhadas) {
        $this->horTrabalhadas = $horTrabalhadas;
    }

    public function getHoratrabalhar() {
        return  number_format($this->horATrabalhar,2,',','.');
    }

    public function setHoratrabalhar($horATrabalhar) {
        $this->horATrabalhar = $horATrabalhar;
    }

    public function getHorsaldo() {
        return  number_format($this->horSaldo,2,',','.');
    }

    public function setHorsaldo($horSaldo) {
        $this->horSaldo = $horSaldo;
    }

    public function getHorpagas() {
        return  number_format($this->horPagas,2,',','.');
    }

    public function setHorpagas($horPagas) {
        $this->horPagas = $horPagas;
    }

    public function getHoracumuladas() {
        return  number_format($this->horAcumuladas,2,',','.');
    }

    public function setHoracumuladas($horAcumuladas) {
        $this->horAcumuladas = $horAcumuladas;
    }
}
?>
