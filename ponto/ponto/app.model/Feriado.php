<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Feriado
 *
 * @author 80058442553
 */
class Feriado {
    private $data;
    private $descricao;
    private $tipo;
    private $horas;
    private $fixo;

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getHoras() {
        return $this->horas;
    }

    public function setHoras($horas) {
        $this->horas = $horas;
    }

    public function getFixo() {
        return $this->fixo;
    }

    public function setFixo($fixo) {
        $this->fixo = $fixo;
    }
}
?>
