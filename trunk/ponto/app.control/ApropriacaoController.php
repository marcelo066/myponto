<?php

class ApropriacaoController extends Controller {

    private $view;
    private $model;

    public function __construct(){
        // instanciamos os objetos
        $this->model = new Apropriacao();
        $this->view = new View('apropriar.html');
    }

    public function show()
    {
        try{
            // carrega a tabela com apropriações
            $oProf = Sessao::getObject("oProf");
            $oPeriodo = Sessao::getObject("oPeriodo");
            $this->model->setCodProfFuncao($oProf->getCodProfFuncao());
            $this->model->setData($oPeriodo->getData());
            $oAprop = $this->model->getAll();
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
                    $this->view->setValue("LINK", "?_task=Apropriacao&_action=delete&_token=".$vAprop->getId());
                    $this->view->parseBlock("BLOCK_APROPRIACAO", true);
                }
            }
            
            // checa quanto tem para apropriar            
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
            $this->view->setValue("MSG", $e->getMessage());
        }
    }

    public function insert()
    {
        try{
            $oProf = Sessao::getObject("oProf");
            $oPeriodo = Sessao::getObject("oPeriodo");
            $this->model->setCc($_POST["txtCC"]);
            $this->model->setCodProfFuncao($oProf->getCodProfFuncao());
            $this->model->setData($oPeriodo->getData());
            $this->model->setValor($_POST["txtValor"]);

            $oCc = new Cc;
            if(!isset($_POST["txtCC"]) && !is_numeric($_POST["txtCC"]) || !$oCc->existe($_POST["txtCC"]))
            {
                $this->view->setValue("MSG", "Centro de custo inválido ou inexistente. Informe um centro de custo válido.");
            }
            elseif(!isset($_POST["txtValor"]) && !is_numeric($_POST["txtValor"]) || !$_POST["txtValor"] > 0)
            {
                $this->view->setValue("MSG", "Valor inválido. Informe um valor numérico maior que zero.");                
            }elseif($_POST["txtValor"] > $this->model->getSaldoApropriar($oAprop))
            {
                $this->view->setValue("MSG2", "Valor ({$_POST["txtValor"]}) maior que o esperado({$this->model->getSaldoApropriar($oAprop)}).");
            }
            else
            {
                $this->model->insert();
            }
            $this->show();
        }catch(Exception $e){
            $this->view->setValue("MSG", $e->getMessage());
        }
    }

    public function delete()
    {
        try
        {
            if(isset($_GET["_token"]) && is_numeric($_GET["_token"]))
            {
                $oProf = Sessao::getObject("oProf");
                $oAprop = new Apropriacao;
                $oAprop->setId($_GET["_token"]);
                $oAprop->getById();
                // só pode excluir apropriação do próprio usuario
                if($oAprop)
                {
                    if($oAprop->getCodProfFuncao() == $oProf->getCodProfFuncao()){
                        $oAprop->delete();
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
            $this->view->setValue("MSG", $e->getMessage());
        }
    }

}    
?>
