<?php

class ApropriacaoDao extends Dao
{
    
    public function  __construct()
    {
        parent::conectar();
    }

    public function __destruct()
    {
        parent::desconectar();
    }

    public function getAll(Apropriacao $pAprop)
    {
        $sql = "SELECT id, cc, total
               FROM hor_aprop
               WHERE cod_prof_funcao = ".$pAprop->getCodProfFuncao().
               " AND data = '".$pAprop->getData()."';";
        $rs = parent::obterRecordSet($sql);

        die($sql);

        $vAprop = array();
        foreach($rs as $row)
        {
            die('oxe');
            $oAprop = new Apropriacao;
            $oAprop->setId($row["id"]);
            $oAprop->setCc($row["cc"]);
            $oAprop->setCodProfFuncao($pCodProfFuncao);
            $oAprop->setData($pData);
            $oAprop->setValor($row["total"]);
            $vAprop[] = $oAprop;
            unset($oAprop);
        }
        return $vAprop;
    }

    public function incluir(Apropriacao $pAprop)
    {
        $sql = "INSERT INTO hor_aprop (cod_prof_funcao, data, cc, total)
                VALUES ("
                .$pAprop->getCodProfFuncao().",'"
                .$pAprop->getData()."',"
                .$pAprop->getCc().",'"
                .$pAprop->getValor()."');";
         $rs = parent::executar($sql);
    }

    public function excluir(Apropriacao $pAprop)
    {
        $sql = "DELETE FROM hor_aprop
                WHERE id = " . $pAprop->getId();
        $rs = parent::executar($sql);
    }

}
?>