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

    function  __construct($pCodProfFuncao = -1)
    {
        try{
            if($pCodProfFuncao == -1){
                return false;
            }
            $sql = "SELECT pf.codigo, p.nome, pia.matricula, pms.codigo as horista, ptt.tipo as carga, f.nome as funcao "
                . "FROM profs p INNER JOIN profs_funcoes pf "
                . "ON (p.codigo = pf.cod_prof) "
                . "INNER JOIN profs_info_adm pia ON (pia.cod_prof_funcao = pf.codigo) "
                . "INNER JOIN profs_modo_salario pms ON (pia.cod_modo_salario = pms.codigo) "
                . "INNER JOIN profs_tipo_turno ptt ON (ptt.codigo = pia.cod_tipo_turno) "
                . "INNER JOIN funcoes f ON (f.codigo = pf.cod_funcao) "
                . "WHERE pf.codigo = $pCodProfFuncao;";
            $rs = parent::obterRecordSet($sql);
            $this->setCodProfFuncao($rs[0]["codigo"]);
            $this->setMatricula($rs[0]["matricula"]);
            $this->setNome($rs[0]["nome"]);
            $this->setHorista($rs[0]["horista"]);
            $this->setCargaHoraria($rs[0]["carga"]);
            $this->setFuncao($rs[0]["funcao"]);
            return true;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }

    public function getHorasTrabalharNoPeriodo(Periodo $pPeriodo) {
        // checa se o profissional ï¿½ horista,
        // horistas nï¿½o possuem limite de horas a trabalhar
        if($this->getHorista())
        {
            return 0;
        }
        
        //Prepara períodos
        $pInicio = new DateTime($pPeriodo->getInicial());
        $pFim = new DateTime($pPeriodo->getFinal());
            
        // checa se jï¿½ existe registro na tabela de horas a trabalhar
        $sql = "SELECT horas "
        . "FROM hor_a_trabalhar "
        . "WHERE cod_prof_funcao =  ".$this->getCodProfFuncao()
        . " AND periodo_ini = '". $pInicio->format("Y-m-d") . "';";
        $cont = parent::executarScalar($sql);
        if($cont > 0)
        {
            return $cont;
        }
            
        // obtem a carga horï¿½ria do profissional, converte para numero
        $varCargaHoraria = $this->getCargaHoraria();
            
        // calcula a quantidade de horas a
        // trabalhar no perï¿½odo entre duas datas
        //$oTipoDia = new Data();
        while($pInicio <= $pFim)
        {
            $varTipoDia = Data::getTipoDia($pInicio);
            if(!($varTipoDia == "Sábado" || $varTipoDia == "Domingo"))
            {
                if($varCargaHoraria == 8.5)
                {
                    if($varTipoDia == "Sexta-Feira")
                    {
                        $varTotal += 8;
                    }
                    else
                    {
                        $varTotal += $varCargaHoraria;
                    }
                }
                else
                {
                    $varTotal += $varCargaHoraria;
                }
            }
            else
            {
                // Trata feriado
                // Há feriados que só desconta um turno, por exemplo
                $oFeriado = new Feriado;
                if($oFeriado->isFeriado($pInicio))
                {
                    $varDesconto += $oFeriado->getHoras();                        
                    // Se o feriado cair dia de sábado
                    // Desconta 2h da carga horária referente a este dia
                    if(Data::getDiaSemana($pInicio) == "Sábado")
                    {
                        $varDesconto += 2;
                    }
                }
            }
            // Incrementa dia
            $pInicio->modify("+1 day");
        }
        $varTotal -= $varDesconto;
        return number_format($varTotal,2);
    }
}
?>
