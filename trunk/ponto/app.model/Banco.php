<?php
/**
 * Description of BancoHoras
 *
 * @author 80058442553
 */
final class Banco extends Dao
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



    function getBanco($pCodProfFuncao)
    {
        try{
            // horista não possui banco de horas
            $oProf = new Profissional();
            $oProf->getProfissional($pCodProfFuncao);
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
            parent::conectar();
            $rs = parent::obterRecordSet($sql);

            //$varAcumulado = 0;
            $ret = Array();
            foreach($rs as $row)
            {

                $oPeriodo = new Periodo($row["periodo_ini"]);
                $vPeriodo = "de " . $oPeriodo->getInicio() . " a " . $oPeriodo->getFim();
                $oBanco = new Banco;
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
            parent::desconectar();
            return $ret;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }

    }

}
?>
