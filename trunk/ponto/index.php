<?php
/**
 * Esta página é um front-controller
 * é responsável por receber e redirecionar requisições
 * para o controller responsável
 * @author ivan
 */
function __autoload($classe)
{
    $pastas = array('app.control', 'app.lib', 'app.model', 'app.view');
    foreach ($pastas as $pasta)
    {
        if (file_exists("{$pasta}/{$classe}.php"))
        {
            include_once "{$pasta}/{$classe}.php";
        }
    }
}

try{

    new Sessao;
    if(!Sessao::getValue('logado'))
    {
        $classe = 'Usuario';
        $metodo = 'login';
    }else{

        // trata URL amigavel
        $classe = $_GET["_task"];
        $metodo = $_GET["_action"];

        // define classe padrao
        if (empty($classe)) {
            $classe = 'Usuario';
        }

        // define metodo padrao
        if (empty($metodo)) {
            $metodo = 'login';
        }
    }
    // prepara arquivo para inclusão
    $filename = "app.control/".$classe."Controller.php";

    // se arquivo não existe, lança exceção
    if(!file_exists($filename))
    {
        throw new Exception("Url inválida!");
    }

    // inclui a classe
    require_once($filename);

    // instancia a classe
    eval('$instancia = new ' . $classe . 'Controller();');

    // se método não existe, lança exceção
    if(!method_exists($instancia, $metodo))
    {
        //unset($instancia);
        throw new Exception("Url inválida!");
    }

    // executa o metodo da classe
    eval('$instancia->' . $metodo . '();');


}
catch(Exception $e)
{
    echo "Erro: " . $e->getMessage();
}

?>