<?php

include_once("Feriado.php");
include_once("Dao.php");

class FeriadoDao extends Dao {

    //private $bd;

    public function  __construct()
    {
        //$this->bd = new Dao();
        parent::conectar();
    }

    public function  __destruct()
    {
        parent::desconectar();
    }
    
    public function getFeriado(DateTime $pData)
    {
       $cont = 0;

       // instancia feriado
       $ObjFeriado = new Feriado();

        // separa mes e dia
        $dia = $pData->format("d");
        $mes = $pData->format("m");

        // se o feriado for fixo, ocorre todos os anos
        // portanto � necess�rio considerar somente
        // dia e m�s da data
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
        // em data espec�fica portanto � necess�rio
        // considerar a data inteira
        else
        {
            $sql =  "SELECT data, descricao, tipo, horas, fixo " .
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
            $ObjFeriado->setData($rs[0]["data"]);
            $ObjFeriado->setDescricao($rs[0]["descricao"]);
            $ObjFeriado->setTipo($rs[0]["tipo"]);
            $ObjFeriado->setHoras($rs[0]["horas"]);
            $ObjFeriado->setFixo($rs[0]["fixo"]);
            return $ObjFeriado;
        }
        else
        {
            return false;
        }
    }
    
}
?>
