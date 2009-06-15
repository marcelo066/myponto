<?php
/**
 * Description of Frequencia
 *
 * @author ivan
 * @property data 
 */
class Registro {
    private $data;
    private $diaDaSemana;
    private $entradaManha;
    private $saidaManha;
    private $entradaTarde;
    private $saidaTarde;
    private $entradaNoite;
    private $saidaNoite;
    private $total;
    private $extra50;
    private $extra100;
    private $ocorrencias;
    public function getData (){
        //return $this->data->format('d/m/Y');
        return $this->data;
    }
    public function setData ($pData){        
        $this->data = new DateTime($pData);
    }
    public function getDiaDaSemana (){
        return $this->diaDaSemana;
    }
    public function setDiaDaSemana ($pDiaDaSemana){
        $this->diaDaSemana = $pDiaDaSemana;
    }
    public function getEntradaManha (){
        return $this->entradaManha;
    }
    public function setEntradaManha ($pEntradaManha){
        $this->entradaManha = $pEntradaManha;
    }
    public function getSaidaManha (){
        return $this->saidaManha;
    }
    public function setSaidaManha ($pSaidaManha){
        $this->saidaManha = $pSaidaManha;
    }
    public function getEntradaTarde (){
        return $this->entradaTarde;
    }
    public function setEntradaTarde ($pEntradaTarde){
        $this->entradaTarde = $pEntradaTarde;
    }
    public function getSaidaTarde (){
        return $this->saidaTarde;
    }
    public function setSaidaTarde ($pSaidaTarde){
        $this->saidaTarde = $pSaidaTarde;
    }
    public function getEntradaNoite (){
        return $this->entradaNoite;
    }
    public function setEntradaNoite ($pEntradaNoite){
        $this->entradaNoite = $pEntradaNoite;
    }
    public function getSaidaNoite (){
        return $this->saidaNoite;
    }
    public function setSaidaNoite ($pSaidaNoite){
        $this->saidaNoite = $pSaidaNoite;
    }
    public function getTotal (){
        return $this->total;
    }
    public function setTotal ($pTotal){
        $this->total = number_format($pTotal,2,',','.');
    }
    public function getExtra50 (){
        return $this->extra50;
    }
    public function setExtra50 ($pExtra50){
        $this->extra50 = number_format($pExtra50,2,',','.');
    }
    public function getExtra100 (){
        return $this->extra100;
    }
    public function setExtra100 ($pExtra100){
        $this->extra100 = number_format($pExtra100,2,',','.');
    }
    public function addOcorrencia(Ocorrencia $pOcorrencia)
    {
        $this->ocorrencias[] = $pOcorrencia;
    }
    public function getOcorrencia()
    {
        return $this->ocorrencias;
    }
}
?>
