<?php
/**
 * Description of Profissional
 *
 * @author 80058442553
 */
final class Profissional extends Dao
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

    public function getProfissional($pCodProfFuncao)
    {
        try{
            $sql = "SELECT pf.codigo, p.nome, pia.matricula, pms.codigo as horista, ptt.tipo as carga, f.nome as funcao "
                . "FROM profs p INNER JOIN profs_funcoes pf "
                . "ON (p.codigo = pf.cod_prof) "
                . "INNER JOIN profs_info_adm pia ON (pia.cod_prof_funcao = pf.codigo) "
                . "INNER JOIN profs_modo_salario pms ON (pia.cod_modo_salario = pms.codigo) "
                . "INNER JOIN profs_tipo_turno ptt ON (ptt.codigo = pia.cod_tipo_turno) "
                . "INNER JOIN funcoes f ON (f.codigo = pf.cod_funcao) "
                . "WHERE pf.codigo = $pCodProfFuncao;";
            parent::conectar();
            $rs = parent::obterRecordSet($sql);
            $this->setCodProfFuncao($rs[0]["codigo"]);
            $this->setMatricula($rs[0]["matricula"]);
            $this->setNome($rs[0]["nome"]);
            $this->setHorista($rs[0]["horista"]);
            $this->setCargaHoraria($rs[0]["carga"]);
            $this->setFuncao($rs[0]["funcao"]);
            parent::desconectar();
            return true;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }

}
?>
