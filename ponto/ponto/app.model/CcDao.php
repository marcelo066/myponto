<?php

class CcDao extends Dao
{
    function  __construct()
    {
        parent::conectar();
    }

    function  __destruct()
    {
        parent::desconectar();
    }

    function getCc($cc)
    {
        // retorna banco de horas
        $sql = "SELECT cc, nome FROM cc WHERE cc = " . $cc;
        $rs = parent::obterRecordSet($sql);
        return $rs[0]["nome"];
    }

    function existe($cc)
    {
        $sql = "SELECT COUNT(*) FROM cc WHERE cc = $cc;";
        $rs = parent::executarScalar($sql);
        return $rs > 0;
    }
}
?>
