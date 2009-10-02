<?php

class Ocorrencia extends Dao {
    private $codigo;
    private $descricao;

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getAll()
    {
        $sql = "SELECT cod_ocorrencia as codigo, descricao
                FROM hor_tipo_ocorrencia
                ORDER BY cod_ocorrencia;";
        parent::conectar();
        $rs = parent::obterRecordSet($sql);
        $oOcorrencia = Array();
        foreach($rs as $row)
        {
            $vOcorrencia = new Ocorrencia;
            $vOcorrencia->setCodigo($row["codigo"]);
            $vOcorrencia->setDescricao($row["descricao"]);
            $oOcorrencia[] = $vOcorrencia;
            $vOcorrencia = null;
        }
        parent::desconectar();
        return $oOcorrencia;
    }
    public function Insert(Ocorrencia $pOcorrencia, Profissional $pProfissional)
    {

    }
    /**
     * M�todo getMotivo
     * retorna o motivo pelo qual n�o h� registro de frequencia para uma data
     * pode ser final de semana, feriado,
     * ou alguma outra ocorrencia como
     * atestado, licen�a etc, etc.
     * @param String $pData a data que n�o se encontra na frequ�ncia
     * @param Long $pCodProfFuncao o c�digo da fun��o do profissional
     * @return String uma string contendo o motivo
     */
    public function getMotivo($pData, $pCodProfFuncao)
    {

    }

}
?>
