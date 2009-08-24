<?php


class OcorrenciaDao extends Dao
{

    public function  __construct()
    {
        //$this->bd = new Dao();
        parent::conectar();
    }

    public function  __destruct()
    {
        parent::desconectar();
    }

    public function getList()
    {
        $sql = "SELECT cod_ocorrencia as codigo, descricao
                FROM hor_tipo_ocorrencia
                ORDER BY cod_ocorrencia;";
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
        return $oOcorrencia;
    }
    public function Add(Ocorrencia $pOcorrencia, Profissional $pProfissional)
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
