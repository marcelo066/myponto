<?php
/**
 * Description of Profissional
 *
 * @author 80058442553
 */
final class Profissional
{    
    private $codProfFuncao;
    private $matricula;
    private $nome;
    private $horista;
    private $cargaHoraria;
    private $funcao;

    public function getFuncao() {
        return $this->funcao;
    }

    public function setFuncao($funcao) {
        $this->funcao = $funcao;
    }
        
    public function getCodProfFuncao() {
        return $this->codProfFuncao;
    }
        
    public function setCodProfFuncao($codProfFuncao) {
        $this->codProfFuncao = $codProfFuncao;
    }
        
    public function getMatricula() {
        return $this->matricula;
    }
        
    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }
        
    public function getNome() {
        return $this->nome;
    }
        
    public function setNome($nome) {
        $this->nome = $nome;
    }        
       
    public function getHorista() {
        return $this->horista;
    }
        
    public function setHorista($horista) {
        $this->horista = $horista;
    }
        
    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }
        
    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }        
}
?>
