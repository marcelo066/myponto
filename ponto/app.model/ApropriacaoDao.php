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
        try{
            $sql = "SELECT id, cc, total
                   FROM hor_aprop
                   WHERE cod_prof_funcao = ".$pAprop->getCodProfFuncao().
                   " AND data = '".$pAprop->getData()."';";
            $rs = parent::obterRecordSet($sql);
            $vAprop = array();
            foreach($rs as $row)
            {
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
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function add(Apropriacao $pAprop)
    {
        try{
            $sql = "INSERT INTO hor_aprop (cod_prof_funcao, data, cc, total)
                    VALUES ("
                    .$pAprop->getCodProfFuncao().",'"
                    .$pAprop->getData()."',"
                    .$pAprop->getCc().",'"
                    .$pAprop->getValor()."');";
             $rs = parent::executar($sql);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function excluir(Apropriacao $pAprop)
    {
        try{
            $sql = "DELETE FROM hor_aprop
                    WHERE id = " . $pAprop->getId();
            $rs = parent::executar($sql);
            return true;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getById($pId)
    {
        try{
            $sql = "SELECT id, data, cod_prof_funcao, cc, total
                   FROM hor_aprop
                   WHERE id = $pId";
            $rs = parent::obterRecordSet($sql);
            if($rs)
            {
                $oAprop = new Apropriacao;
                $oAprop->setId($rs[0]["id"]);
                $oAprop->setCc($rs[0]["cc"]);
                $oAprop->setCodProfFuncao($rs[0]["cod_prof_funcao"]);
                $oAprop->setData($rs[0]["data"]);
                $oAprop->setValor($rs[0]["total"]);
            }
            return $oAprop;
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getTotalApropriado(Apropriacao $pAprop)
    {
        //try{
            $sql = "SELECT SUM(total)
                   FROM hor_aprop
                   WHERE cod_prof_funcao = ".$pAprop->getCodProfFuncao().
                   " AND data = '".$pAprop->getData()."';";
            $ret = parent::executarScalar($sql);
            return $ret;
        /*}catch(Exception $e){
            throw new Exception($e->getMessage());
        }*/
    }

    public function getSaldoApropriar(Apropriacao $pAprop)
    {
       // try{
                        
            $oProf = Sessao::getObject("oProf");
            $oRegistro = new Registro;
            $oRegistroD = new RegistroDao;
            $oRegistro = $oRegistroD->getByDate($oProf, $pAprop->getData());
            if($oRegistro){
                $tRegistrado = $oRegistro->getTotal();
            }else{
                $tRegistrado = 0;
            }
            $tApropriado = $this->getTotalApropriado($pAprop);
            $vSaldo = $tRegistrado - $tApropriado;
            
            return $vSaldo;

        /*}catch(Exception $e){
            throw new Exception($e->getMessage());
        }*/
    }
}
?>