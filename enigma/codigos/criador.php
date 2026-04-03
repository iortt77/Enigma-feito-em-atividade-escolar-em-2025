<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Caminho de duas pastas acima
$pasta_htdocs = dirname(dirname(__DIR__)) . '/nova_pasta';

// Cria a pasta dentro do htdocs, se não existir
if (!file_exists($pasta_htdocs)) {
    mkdir($pasta_htdocs, 0777, true);
    echo "Pasta criada: $pasta_htdocs <br>";
} else {
    echo "A pasta já existe: $pasta_htdocs <br>";
}

// Conteúdo do arquivo a ser criado
$conteudo = <<<HTML
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../projeto353/codigos/style.css">
    <title>a resposta esta em linguagens antigas onde o verdadeiro fim é a morte a verdadeira morte concreta que não se disvirtua reto em seus objetivos ceifar a vida</title>
</head>
<body>
    <h1>morte direta reta ao ponto final acabou derradeiro</h1>
</body>
</html>
HTML;

$arquivo = $pasta_htdocs . '/ofimestaproximo.php';
file_put_contents($arquivo, $conteudo);

header("Location: atualizar.php");
?>
