<?php

/**
 * Description of Horario
 *
 * @author 80058442553
 */
class HorarioPadrao
{
    //put your code here
    private $dia;
    private $entrada;
    private $almoco;
    private $retorno;
    private $saida;    
    private $descricao;
    private $toleranciaAntes;
    private $toleranciaDepois;
   
    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getEntrada() {
        return $this->entrada;
    }

    public function setEntrada($entrada) {
        $this->entrada = $entrada;
    }

    public function getAlmoco() {
        return $this->almoco;
    }

    public function setAlmoco($almoco) {
        $this->almoco = $almoco;
    }

    public function getRetorno() {
        return $this->retorno;
    }

    public function setRetorno($retorno) {
        $this->retorno = $retorno;
    }

    public function getSaida() {
        return $this->saida;
    }

    public function setSaida($saida) {
        $this->saida = $saida;
    }

    public function getDia() {
        return $this->dia;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function getToleranciaAntes() {
        return $this->toleranciaAntes;
    }

    public function setToleranciaAntes($toleranciaAntes) {
        $this->toleranciaAntes = $toleranciaAntes;
    }

    public function getToleranciaDepois() {
        return $this->toleranciaDepois;
    }

    public function setToleranciaDepois($toleranciaDepois) {
        $this->toleranciaDepois = $toleranciaDepois;
    }
}
?>
