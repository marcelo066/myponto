<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Apropriacao
 *
 * @author 80058442553
 */
class Apropriacao {
    //put your code here
    private $id;
    private $codProfFuncao;
    private $data;
    private $cc;
    private $valor;

    public function getCodProfFuncao() {
        return $this->codProfFuncao;
    }

    public function setCodProfFuncao($codProfFuncao) {
        $this->codProfFuncao = $codProfFuncao;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getCc() {
        return $this->cc;
    }

    public function setCc($cc) {
        $this->cc = $cc;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

}
?>
