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
        $this->total = number_format($pTotal,2,',','.');
    }

    public function getExtra50 (){
        return $this->extra50;
    }

    public function setExtra50 ($pExtra50){
        $this->extra50 = number_format($pExtra50,2,',','.');
    }

    public function getExtra100 (){
        return $this->extra100;
    }

    public function setExtra100 ($pExtra100){
        $this->extra100 = number_format($pExtra100,2,',','.');
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
        // se ja tem registro, vai dar update
        // caso contrario, vai dar insert
        parent::conectar();
        if(!$this->getByDate($pProf, $pRegistro->getData()))
        {
            $sql = "INSERT INTO hor_frequencia (cod_prof_funcao, data, " . $pRegistro->getCampo()
            . ") VALUES ("
            . $pProf->getCodProfFuncao() . ",'"
            . $pRegistro->getData(). "','"
            . $pRegistro->getHora() . "');";
            parent::executar($sql);
        }
        else
        {
            $sql = "UPDATE hor_frequencia SET " . $pRegistro->getCampo() . " = '"
            . $pRegistro->getHora() ."' WHERE data = '"
            . $pRegistro->getData()."' AND cod_prof_funcao = "
            . $pProf->getCodProfFuncao() . ";";
            parent::executar($sql);
            // atualiza os totais
            $this->updateTotals($pProf, $pRegistro);
        }
        parent::desconectar();
    }

    public function update(Profissional $pProf, Registro $pRegistro)
    {
        try
        {
            echo $pProf->getCodProfFuncao() . '<br>';
            echo $pProf->getNome() . '<br>';
            echo $pRegistro->getData()->format("d-m-Y") . '<br>';
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

            die($pProf->getCargaHoraria());
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
            $sql = "UPDATE hor_frequencia SET total = , h_50 = , h_100 = ";
            echo $t;
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
            parent::conectar();
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
                parent::desconectar();
                return $oRegistro;
            }
            else
            {
                parent::desconectar();
                return false;
            }
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getByRange($pCodProfFuncao, DateTime $pInicio, DateTime $pFim)
    {
        try{
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
            parent::conectar();
            $row = parent::obterRecordSet($sql);
            // insere os dias de falta, finais de semana
            // feriados e ocorr�ncias na frequencia
            //**$ObjTd = new Data();
            $j = 0;
            while($ObjInicio <= $ObjFim)
            {
                $reg = new Registro();
                $reg->setData($row[$j]["data"]);
                $objData = new DateTime($reg->getData());
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
            parent::desconectar();
            return $ret;
        }catch(Exception $e){
            throw new Exception($e->getTraceAsString());
        }
    }

    public function __construct()
    {
        $this->flag = 0;
    }
}
?>
