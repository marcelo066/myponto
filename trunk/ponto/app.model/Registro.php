<?php
/**
 * Description of Frequencia
 *
 * @author ivan
 * @property data
 */
class Registro extends Dao {
    private $data;
    private $diaDaSemana;
    private $hora;
    private $entradaManha;
    private $saidaManha;
    private $entradaTarde;
    private $saidaTarde;
    private $entradaNoite;
    private $saidaNoite;
    private $total;
    private $extra50;
    private $extra100;
    private $ocorrencias;
    private $apropriar;
    private $campo;
    private $saldo;
    /**
        flag
        entrada manhã = 2
        saída manhã = 4
        entrada tarde = 8
        saida tarde = 16
        6	= entrada manhã + saída manhã
       18	= entrada manhã + saída tarde
       24	= entrada tarde + saída tarde
       30	= tudo

    **/
    private $flag;

    public function getData (){
        return $this->data;
    }

    public function setData ($pData){
        $this->data = $pData;
    }

    public function getDiaDaSemana (){
        return $this->diaDaSemana;
    }

    public function setDiaDaSemana ($pDiaDaSemana){
        $this->diaDaSemana = $pDiaDaSemana;
    }

    public function getHora() {
        return $this->hora;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    public function getEntradaManha (){
        return $this->entradaManha;
    }

    public function setEntradaManha ($pEntradaManha){
        if(strtotime($pEntradaManha))
        {
            $this->setFlag(2);
        }
        $this->entradaManha = $pEntradaManha;
    }

    public function getSaidaManha (){
        return $this->saidaManha;
    }

    public function setSaidaManha ($pSaidaManha){
        if(strtotime($pSaidaManha))
        {
            $this->setFlag(4);
        }
        $this->saidaManha = $pSaidaManha;
    }

    public function getEntradaTarde (){
        return $this->entradaTarde;
    }

    public function setEntradaTarde ($pEntradaTarde){
        if(strtotime($pEntradaTarde))
        {
            $this->setFlag(8);
        }
        $this->entradaTarde = $pEntradaTarde;
    }

    public function getSaidaTarde (){
        return $this->saidaTarde;
    }

    public function setSaidaTarde ($pSaidaTarde){
        if(strtotime($pSaidaTarde))
        {
            $this->setFlag(16);
        }
        $this->saidaTarde = $pSaidaTarde;
    }

    public function getEntradaNoite (){
        return $this->entradaNoite;
    }

    public function setEntradaNoite ($pEntradaNoite){
        if(strtotime($pEntradaNoite))
        {
            $this->setFlag(32);
        }
        $this->entradaNoite = $pEntradaNoite;
    }

    public function getSaidaNoite (){
        return $this->saidaNoite;
    }

    public function setSaidaNoite ($pSaidaNoite){
        if(strtotime($pSaidaNoite))
        {
            $this->setFlag(64);
        }
        $this->saidaNoite = $pSaidaNoite;
    }

    public function getTotal (){
        return $this->total;
    }

    public function setTotal ($pTotal){
        $this->total = number_format($pTotal,2);
    }

    public function getExtra50 (){
        return $this->extra50;
    }

    public function setExtra50 ($pExtra50){
        $this->extra50 = number_format($pExtra50,2);
    }

    public function getExtra100 (){
        return $this->extra100;
    }

    public function setExtra100 ($pExtra100){
        $this->extra100 = number_format($pExtra100,2);
    }

    public function addOcorrencia(Ocorrencia $pOcorrencia)
    {
        $this->ocorrencias[] = $pOcorrencia;
    }

    public function getOcorrencia()
    {
        return $this->ocorrencias;
    }

    public function getCampo() {
        return $this->campo;
    }

    private function setCampo($Campo) {
        $this->campo = $Campo;
    }

    public function getApropriar() {
        return $this->apropriar;
    }

    public function setApropriar($apropriar) {
        $this->apropriar = $apropriar;
    }

    public function getFlag() {
        return $this->flag;
    }

    public function setFlag($flag) {
        $this->flag += $flag;
        switch($flag)
        {
            case 2:
                $this->setCampo("entrada");
                break;
            case 4:
                $this->setCampo("almoco");
                break;
            case 8:
                $this->setCampo("retorno");
                break;
            case 16:
                $this->setCampo("saida");
                break;
        }
    }
   
    public function insert(Profissional $pProf, Registro $pRegistro)
    {
        try{
            // se ja tem registro, vai dar update
            // caso contrario, vai dar insert
            $pData = new DateTime($pRegistro->getData());
            if(!$this->getByDate($pProf, $pRegistro->getData()))
            {                
                $sql = "INSERT INTO hor_frequencia (cod_prof_funcao, data, " . $pRegistro->getCampo()
                . ") VALUES ("
                . $pProf->getCodProfFuncao() . ",'"
                . $pData->format("Y-m-d"). "','"
                . $pRegistro->getHora() . "');";
                parent::executar($sql);                
            }
            else
            {
                $sql = "UPDATE hor_frequencia SET {$pRegistro->getCampo()} = '
                {$pRegistro->getHora()}' WHERE data = '
                {$pData->format("Y-m-d")}' AND cod_prof_funcao = {$pProf->getCodProfFuncao()};";
                parent::executar($sql);                
            }
            // atualiza os totais
            $this->updateTotals($pProf, $pRegistro);
        }catch(Exception $e) {
            throw new Exception($e->getTraceAsString());
        }
    }

    public function update(Profissional $pProf, Registro $pRegistro)
    {
        try
        {
            echo $pProf->getCodProfFuncao() . '<br>';
            echo $pProf->getNome() . '<br>';
            echo $pRegistro->getData() . '<br>';
            echo $pRegistro->getEntradaManha() . '<br>';
            echo $pRegistro->getSaidaManha() . '<br>';
            echo $pRegistro->getEntradaTarde() . '<br>';
            echo $pRegistro->getEntradaTarde() . '<br>';

            // lista de ocorrências
            foreach($pRegistro->getOcorrencia() as $row )
            {
                echo $row->getCodigo()  . '<br>';
            }
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function updateTotals(Profissional $pProf, Registro $pRegistro)
    {
        try
        {           
            // atualiza os totais            
            $oRegistro = new Registro;
            $oRegistro = $this->getByDate($pProf, $pRegistro->getData());
            $t = 0;
            switch($oRegistro->getFlag())
            {
                case 6:  // entrada manhã + saída manhã
                    $s = $oRegistro->getEntradaManha();
                    $e = $oRegistro->getSaidaManha();
                    $t = Data::getTimeDifference($s, $e);
                    $t = Data::timeToDecimal($t);
                    break;
                case 18: // entrada manhã + saída tarde
                    $s = $oRegistro->getEntradaManha();
                    $e = $oRegistro->getSaidaTarde();
                    $t = Data::getTimeDifference($s, $e);
                    $t = Data::timeToDecimal($t);
                    break;
                case 24: // entrada tarde + saída tarde
                    $s = $oRegistro->getEntradaTarde();
                    $e = $oRegistro->getSaidaTarde();
                    $t = Data::getTimeDifference($s, $e);
                    $t = Data::timeToDecimal($t);
                    break;
                case 30: // tudo
                    $s = $oRegistro->getEntradaManha();
                    $e = $oRegistro->getSaidaManha();
                    $t1 = Data::getTimeDifference($s, $e);
                    $t1 =Data::timeToDecimal($t1);
                    $s = $oRegistro->getEntradaTarde();
                    $e = $oRegistro->getSaidaTarde();
                    $t2 = Data::getTimeDifference($s, $e);
                    $t2 =Data::timeToDecimal($t2);
                    $t = $t1 + $t2;
                    break;
            }            
            $oPeriodo = new Periodo();
            $oPeriodo->setInicial($oRegistro->getData());
            $oPeriodo->setFinal($oRegistro->getData());
            $vTrabalhar = $pProf->getHorasTrabalharNoPeriodo($oPeriodo);
            $vSaldo = $t - $vTrabalhar;
            $t100 = 0;
            $t50 = 0;
            //se tiver saldo e o dia for s�bado, � hora extra a 50%
            //se for domingo ou feriado, � hora extra a 100%
            $vData =new DateTime($oRegistro->getData());
            if($vSaldo > 0)
            {                
                switch(Data::getTipoDia($vData)){
                    case "Domingo":
                        $t100 = $vSaldo;
                        break;
                    case "Feriado":
                        $t100 = $vSaldo;
                        break;
                    default:
                        $t50 = $vSaldo;
                        break;
                    }
                }            
            $sql = "UPDATE hor_frequencia SET total = {$t} , h_50 = {$t50}, h_100 = {$t100}
            WHERE data = '{$vData->format("Y-m-d")}' AND cod_prof_funcao = {$pProf->getCodProfFuncao()};";
            parent::executar($sql);
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function getByDate(Profissional $pProf, $pData)
    {
        try{
           $pData = new DateTime($pData);
            $sql = "SELECT hf.data, DAYNAME(hf.data) as dia, hf.entrada, hf.almoco,
                        hf.retorno, hf.saida, hf.total, hf.h_50, hf.h_100
                    FROM hor_frequencia hf
                    WHERE hf.cod_prof_funcao = " . $pProf->getCodProfFuncao() . "
                    AND hf.data = '" . $pData->format("Y-m-d") . "';";
            $row = parent::obterRecordSet($sql);
            if($row)
            {
                $oData = new DateTime($row[0]["data"]);
                $oRegistro = new Registro;
                $oRegistro->setData($oData->format('d-m-Y'));
                $oRegistro->setDiaDaSemana(Data::getTipoDia($oData));
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

    public function getByRange(Profissional $pProf, Periodo $pPeriodo)
    {
        try{
            $ObjInicio = new DateTime($pPeriodo->getInicial());
            $ObjFim = new DateTime($pPeriodo->getFinal());
            $sql = "SELECT hf.data, DAYNAME(hf.data) as dia, hf.entrada, hf.almoco,
                    hf.retorno, hf.saida, hf.total, hf.h_50, hf.h_100
                    FROM hor_frequencia hf INNER JOIN profs_funcoes pf
                    ON (hf.cod_prof_funcao = pf.codigo)
                    WHERE pf.codigo = " . $pProf->getCodProfFuncao()
                    ." AND hf.data BETWEEN '" . $ObjInicio->format('Y-m-d') . "'
                    AND '" . $ObjFim->format('Y-m-d') . "';";
            $row = parent::obterRecordSet($sql);
            // insere os dias de falta, finais de semana
            // feriados e ocorr�ncias na frequencia
            //**$ObjTd = new Data();
            $j = 0;
            while($ObjInicio <= $ObjFim)
            {
                $oReg = new Registro();
                $oData = new DateTime($row[$j]["data"]);
                $oReg->setData($oData->format("d-m-Y"));
                if($oData->format('d/m/Y') == $ObjInicio->format('d/m/Y'))
                {
                    //$reg->setDiaDaSemana($row[$j][dia]);
                    $oReg->setDiaDaSemana(Data::getTipoDia($oData));
                    $oReg->setEntradaManha($row[$j]["entrada"]);
                    $oReg->setSaidaManha($row[$j]["almoco"]);
                    $oReg->setEntradaTarde($row[$j]["retorno"]);
                    $oReg->setSaidaTarde($row[$j]["saida"]);
                    $oReg->setEntradaNoite('-');
                    $oReg->setSaidaNoite('-');
                    $oReg->setTotal($row[$j]["total"]);
                    $oReg->setExtra50($row[$j]["h_50"]);
                    $oReg->setExtra100($row[$j]["h_100"]);
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
                    $oReg->setData($ObjInicio->format('d-m-Y'));
                    $oReg->setDiaDaSemana(Data::getTipoDia($ObjInicio));
                    $oReg->setEntradaManha('-');
                    $oReg->setSaidaManha('-');
                    $oReg->setEntradaTarde('-');
                    $oReg->setSaidaTarde('-');
                    $oReg->setEntradaNoite('-');
                    $oReg->setSaidaNoite('-');
                    $oReg->setTotal(0);
                    $oReg->setExtra50(0);
                    $oReg->setExtra100(0);
                }
                $ObjInicio->modify("+1 day");
                $ret[] = $oReg;
            }
            return $ret;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }

    public function getTotaisTrabalhados(Profissional $pProf, Periodo $pPeriodo)
    {
            $oInicio = new DateTime($pPeriodo->getInicial());
            $oFim = new DateTime($pPeriodo->getFinal());
            $sql = "SELECT SUM(h_50) as Hex50, SUM(h_100)  as Hex100, SUM(total) as total  "
            . "FROM hor_frequencia "
            . "WHERE cod_prof_funcao =  ".$pProf->getCodProfFuncao()
            . " AND data BETWEEN '" . $oInicio->format('Y-m-d')
            . "' AND '" . $oFim->format('Y-m-d') . "';";
            $rs = parent::obterRecordSet($sql);            
            $this->setExtra50($rs[0]["Hex50"]);
            $this->setExtra100($rs[0]["Hex100"]);
            $this->setTotal($rs[0]["total"]);
    }

    public function __construct()
    {
        $this->flag = 0;
    }
}
?>
