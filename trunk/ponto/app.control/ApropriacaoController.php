<?php

require_once("./checaLogin.php");

class ApropriacaoController extends Controller {

    private $view;
    private $model;

    public function __construct(){
        // instanciamos os objetos
        $this->model = new ApropriacaoDao();
        $this->view = new View('apropriar.html');
    }

    public function show()
    {
        try
        {
            // carrega a tabela com apropriações
            $oProf = Sessao::getObject("oProf");
            $pAprop = new Apropriacao;
            $pAprop->setCodProfFuncao($oProf->getCodProfFuncao());
            $pAprop->setData(date("Y-m-d"));
            $oAprop = $this->model->getAll($pAprop);
            if($oAprop)
            {
                foreach($oAprop as $vAprop)
                {
                    if($bol){
                        $bol = false;
                        $cor = "#ffffff";
                    } else {
                        $bol = true;
                        $cor = "#f4f4f3";
                    }
                    $this->view->setValue("COR", $cor);
                    $this->view->setValue("CC", $vAprop->getCc());
                    $this->view->setValue("VALOR", $vAprop->getValor());
                    $this->view->setValue("LINK", "?_task=Apropriacao&_action=excluir&_token=".$vAprop->getId());
                    $this->view->parseBlock("BLOCK_APROPRIACAO", true);
                }
            }

            /*
            // checa quanto tem para apropriar
            $oRegistro = new RegistroDao;
            $oRegistro->add($oProf, $pPonto);
            */
            $msg = "Apropriado: " . $this->model->getTotalApropriado($pAprop) .
                "<br>Saldo: " . $this->model->getSaldoApropriar($pAprop);
            $this->view->setValue("MSG", $msg);
            
            // passa funções Ajax para obter a descrição do centro de custo
            $func = 'function checkCC() {
                if($F("txtCC").length == 4) {
                    var url = "?_task=Cc&_action=getCC";
                    var params = "cc=" + $F("txtCC");
                    var ajax = new Ajax.Updater(
                        {success: "desccc"}, url,
                        {method: "get", parameters: params, onFailure: reportError});
                    }
                };

                function reportError(request) {
                    $F("txtCC") = "Error";
                }';
            $this->view->addFile("TOPO", "topo.html");
            $this->view->addFile("FOOTER", "rodape.html");
            $this->view->setValue("FUNCOES", $func);
            $this->view->show();
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function add()
    {
        try{
            $oCc = new CcDao;
            if(!isset($_POST["txtCC"]) && !is_numeric($_POST["txtCC"]) || !$oCc->existe($_POST["txtCC"]))
            {
                $this->view->setValue("MSG", "Centro de custo inválido ou inexistente. Informe um centro de custo válido.");
            }
            elseif(!isset($_POST["txtValor"]) && !is_numeric($_POST["txtValor"]) || !$_POST["txtValor"] > 0)
            {
                $this->view->setValue("MSG", "Valor inválido. Informe um valor numérico maior que zero.");
            }
            else
            {
                $oProf = Sessao::getObject("oProf");
                $oAprop = new Apropriacao;
                $oAprop->setCc($_POST["txtCC"]);
                $oAprop->setCodProfFuncao($oProf->getCodProfFuncao());
                $oAprop->setData(date("Y-m-d"));
                $oAprop->setValor($_POST["txtValor"]);
                $oApropD = new ApropriacaoDao;
                $oApropD->add($oAprop);
            }
            $this->show();
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function excluir()
    {
        try
        {
            if(isset($_GET["_token"]) && is_numeric($_GET["_token"]))
            {
                $oProf = Sessao::getObject("oProf");
                $oAprop = new Apropriacao;
                $oApropD = new ApropriacaoDao;
                $oAprop = $oApropD->getById($_GET["_token"]);
                // só pode excluir apropriação do próprio usuario
                if($oAprop)
                {
                    if($oAprop->getCodProfFuncao() == $oProf->getCodProfFuncao()){
                        $oApropD->excluir($oAprop);
                    }else{
                        $this->view->setValue("MSG", "Erro não foi possivel excluir");
                    }
                }else{
                    $this->view->setValue("MSG", "Erro não foi possivel excluir");
                }                
            }else{
                $this->view->setValue("MSG", "Erro não foi possivel excluir");
            }
            $this->show();
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

}    
?>
