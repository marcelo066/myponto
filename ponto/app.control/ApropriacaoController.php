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

    public function show(){
        // carrega a tabela com apropriações
        $oProf = Sessao::getObject("oProf");
        $pAprop = new Apropriacao;
        $pAprop->setCodProfFuncao($oProf->getCodProfFuncao());
        $pAprop->setData(date("Y-m-d"));
        $oAprop = $this->model->getAll($pAprop);
        if($oAprop)
        {
            die('opa');
            foreach($oAprop as $vAprop)
            {
                $this->view->setValue("CC", $vAprop->getCc());
                $this->view->setValue("VALOR", $vAprop->getValor());
                $this->view->setValue("LINK", "?_task=Apropriacao&_action=excluir&_token=".$vAprop->getId());
                $this->view->parseBlock("BLOCK_APROPRIACAO", true);
            }            
        }
        
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
    }

    public function incluir()
    {
        $oProf = Sessao::getObject("oProf");        
        $oAprop = new Apropriacao;
        $oAprop->setCc($_POST["txtCC"]);
        $oAprop->setCodProfFuncao($oProf->getCodProfFuncao());
        $oAprop->setData(date("Y-m-d"));
        $oAprop->setValor($_POST["txtValor"]);

        $oApropD = new ApropriacaoDao;
        $oApropD->incluir($oAprop);

        $this->show();

    }

    public function excluir()
    {

       // validar, só pode excluir apropriação
        // do próprio usuario
        $oProf = Sessao::getObject("oProf");

        $oAprop = new Apropriacao;
        $oAprop->setId($_GET["_token"]);

        $oApropD = new ApropriacaoDao;
        $oApropD->excluir($oAprop);

        $this->show();

    }

}    
?>
