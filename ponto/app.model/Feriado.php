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
class Feriado extends Dao {
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

    public function isFeriado(DateTime $pData)
    {
        try{
            $cont = 0;
            // separa mes e dia
            $dia = $pData->format("d");
            $mes = $pData->format("m");
            // se o feriado for fixo, ocorre todos os anos
            // portanto é necessário considerar somente
            // dia e mês da data
            $sql =  "SELECT data, descricao, tipo, horas, fixo " .
                "FROM feriados " .
                "WHERE DAY(data) = $dia " .
                "AND MONTH(data) = $mes " .
                "AND fixo = 1;";            
            $rs = parent::obterRecordSet($sql);
            if($rs)
            {
                $cont = 1;
            }
            // se o feriado for facultativo, ocorre somente
            // em data específica portanto é necessário
            // considerar a data inteira
            else
            {
                $sql = "SELECT data, descricao, tipo, horas, fixo " .
                    "FROM feriados " .
                    "WHERE data = '" . $pData->format('Y-m-d') . "' "  .
                    "AND fixo = 0;";                
                $rs = parent::obterRecordSet($sql);
                if($rs)
                {
                    $cont = 1;
                }
            }
            // prepara retorno
            if($cont == 1)
            {
                $this->setData($rs[0]["data"]);
                $this->setDescricao($rs[0]["descricao"]);
                $this->setTipo($rs[0]["tipo"]);
                $this->setHoras($rs[0]["horas"]);
                $this->setFixo($rs[0]["fixo"]);       
            }
            return $cont;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }
}
?>
