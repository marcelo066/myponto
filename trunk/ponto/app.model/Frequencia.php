<?php
/**
 * Description of Frequencia
 *
 * @author 80058442553
 */
class Frequencia {
    public $ano;
    public $mes;
    public $profissional;   // um objeto
    public $resumo;         // um objeto
    public $registro;       // um array de objetos

    public function getFrequencia($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        try{
            // instancia objetos que comp�e a frequencia
            $reg = new Registro();

        // carrega resumo
           new Sessao();
            $res = Sessao::getObject("oPeriodo");
            $this->resumo = $res;

            // carrega registro
            $this->registro = $reg->getByRange($pCodProfFuncao, $pInicio, $pFim);

            // la la la la la la la
            $this->ano = $pInicio->format("Y");
            $this->mes = $pInicio->format("m");

            return true;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }

}
?>
