<?php

class RegistroDao extends Dao
{
    public function  __construct() {
        parent::conectar();
    }

    public function  __destruct() {
        parent::desconectar();
    }

    public function add(Profissional $pProf, Ponto $pPonto)
    {
        // se ja tem registro, vai dar update
        // caso contrario, vai dar insert
        if(!$this->getByDate($pProf, new DateTime($pPonto->getData())))
        {
            $sql = "INSERT INTO hor_frequencia (cod_prof_funcao, data, " . $pPonto->getTurno()
                . ") VALUES ("
                . $pProf->getCodProfFuncao() . ",'"
                . $pPonto->getData(). "','"
                . $pPonto->getHora() . "');";
        }
        else
        {
            $sql = "UPDATE hor_frequencia SET " . $pPonto->getTurno() . " = '"
                . $pPonto->getHora() ."' WHERE data = '"
                . $pPonto->getData()."' AND cod_prof_funcao = "
                . $pProf->getCodProfFuncao() . ";";

        }      
        parent::executar($sql);
    }

    public function update(Profissional $pProf, Registro $pRegistro)
    {
        try
        {
            /*
            echo $pProf->getCodProfFuncao() . '<br>';
            echo $pProf->getNome() . '<br>';
            echo $pRegistro->getData()->format("d-m-Y") . '<br>';
            echo $pRegistro->getEntradaManha() . '<br>';
            echo $pRegistro->getSaidaManha() . '<br>';
            echo $pRegistro->getEntradaTarde() . '<br>';
            echo $pRegistro->getEntradaTarde() . '<br>';
            */

            // a START time value
            $start = $pRegistro->getEntradaManha();
            // an END time value
            $end   = $pRegistro->getSaidaManha();

            // what is the time difference between $end and $start?
            if( $diff =@ Data::get_time_difference($start, $end) )
            {
                
                $d = strtotime($diff['hours'].":".$diff['minutes'].":".$diff['seconds']);                
                echo strftime("%H:%M:%S",$d);
                
            }
            else
            {
                echo "Horas: Error";
            }




/*
            // lista de ocorrências
            foreach($pRegistro->getOcorrencia() as $row )
            {
                echo $row->getCodigo()  . '<br>';
            }*/
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }



    public function getByDate(Profissional $pProf, DateTime $pData)
    {
        try{
            $sql = "SELECT hf.data, DAYNAME(hf.data) as dia, hf.entrada, hf.almoco,
                        hf.retorno, hf.saida, hf.total, hf.h_50, hf.h_100
                    FROM hor_frequencia hf
                    WHERE hf.cod_prof_funcao = " . $pProf->getCodProfFuncao() . "
                    AND hf.data = '" . $pData->format("Y-m-d") . "';";          
            $row = parent::obterRecordSet($sql);
            if($row)
            {
                $oRegistro = new Registro;
                $oRegistro->setData($row[0]["data"]);
                $oRegistro->setDiaDaSemana(Data::getTipoDia(new DateTime($row[0]["data"])));
                $oRegistro->setEntradaManha($row[0]["entrada"]);
                $oRegistro->setSaidaManha($row[0]["almoco"]);
                $oRegistro->setEntradaTarde($row[0]["retorno"]);
                $oRegistro->setSaidaTarde($row[0]["saida"]);
                $oRegistro->setEntradaNoite('-');
                $oRegistro->setSaidaNoite('-');
                $oRegistro->setTotal($row[0]["total"]);
                $oRegistro->setExtra50($row[0]["h_50"]);
                $oRegistro->setExtra100($row[0]["h_100"]);
                return $oRegistro;
            }
            else
            {
                return false;
            }        
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getByRange($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        // foi necess�rio criar outro objeto, pois todo objeto em php
        // � passado por refer�ncia e isso estava interferindo no resultado
        // de outras fun��es
        $ObjInicio = new DateTime($pInicio->format("Y-m-d"));
        $ObjFim = new DateTime($pFim->format("Y-m-d"));
        $sql = "SELECT hf.data, DAYNAME(hf.data) as dia, hf.entrada, hf.almoco,
                    hf.retorno, hf.saida, hf.total, hf.h_50, hf.h_100
                    FROM hor_frequencia hf INNER JOIN profs_funcoes pf
                    ON (hf.cod_prof_funcao = pf.codigo)
                    WHERE pf.codigo = $pCodProfFuncao
                    AND hf.data BETWEEN '" . $ObjInicio->format('Y-m-d') . "'
                    AND '" . $ObjFim->format('Y-m-d') . "';";
        $row = parent::obterRecordSet($sql);
        // insere os dias de falta, finais de semana
        // feriados e ocorr�ncias na frequencia
        //**$ObjTd = new Data();
        $j = 0;
        while($ObjInicio <= $ObjFim)
        {
            $reg = new Registro();
            $reg->setData($row[$j]["data"]);
            $objData = $reg->getData();
            if($objData->format('d/m/Y') == $ObjInicio->format('d/m/Y'))
            {
                //$reg->setDiaDaSemana($row[$j][dia]);
                $reg->setDiaDaSemana(Data::getTipoDia($objData));
                $reg->setEntradaManha($row[$j]["entrada"]);
                $reg->setSaidaManha($row[$j]["almoco"]);
                $reg->setEntradaTarde($row[$j]["retorno"]);
                $reg->setSaidaTarde($row[$j]["saida"]);
                $reg->setEntradaNoite('-');
                $reg->setSaidaNoite('-');
                $reg->setTotal($row[$j]["total"]);
                $reg->setExtra50($row[$j]["h_50"]);
                $reg->setExtra100($row[$j]["h_100"]);
                if($j < count($row)-1)
                {
                    $j++;
                }
            }
            // n�o h� registro de frequencia
            // para esta data
            else
            {
                // checa o motivo da falta de registro,
                // pode ser final de semana, feriado,
                // ou alguma outra ocorrencia como
                // atestado, licen�a etc, etc.

                $reg->setData($ObjInicio->format('d-m-Y'));
                $reg->setDiaDaSemana(Data::getTipoDia($ObjInicio));
                $reg->setEntradaManha('-');
                $reg->setSaidaManha('-');
                $reg->setEntradaTarde('-');
                $reg->setSaidaTarde('-');
                $reg->setEntradaNoite('-');
                $reg->setSaidaNoite('-');
                $reg->setTotal(0);
                $reg->setExtra50(0);
                $reg->setExtra100(0);
            }
            $ObjInicio->modify("+1 day");
            $ret[] = $reg;
        }
        return $ret; 
    }
}
?>