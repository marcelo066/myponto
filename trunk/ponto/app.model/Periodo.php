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

    public function getData() {
        return $this->data;
    }

    public function setInicial($inicial) {
        $this->inicial = $inicial;
    }

    public function setFinal($final) {
        $this->final = $final;
    }

    public function setAnterior($anterior) {
        $this->anterior = $anterior;
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

   /**
     * Método getInício
     * retorna o período inicial de uma data
     * @param String $pData uma data no formato dd/mm/yyyy
     * @return String d-m-Y
     */
    private function calcularInicial()
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
    private function calcularAnterior()
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
        $this->anterior = "25-$mes-$ano";
    }
    /**
     * Método getFinal
     * retorna o período final de uma data
     * @param String $pData = uma data no formato dd/mm/yyyy
     * @return DateTime
     */
    private function calcularFinal()
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

    function  __construct($pData=-1) {
        try{
            if($pData==-1)
            {
                $this->data = date("d-m-Y");
            }else{
                $this->data = $pData;
            }
            $this->calcularInicial();
            $this->calcularFinal();
            $this->calcularAnterior();
            return true;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }
}
?>
