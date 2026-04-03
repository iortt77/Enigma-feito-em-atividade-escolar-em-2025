<?php
// create_db_respostas.php
// NÃO deixe espaços/linhas antes do <?php

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'senhas';
$lockFile = __DIR__ . '/installed.lock';

// pepper um nível acima (projeto353/pepper.txt)
$pepperFile = __DIR__ . '/../pepper.txt';

if (!file_exists($pepperFile)) {
    die("Arquivo de pepper não encontrado em $pepperFile. Crie-o antes.");
}
$pepper = trim(file_get_contents($pepperFile));
if ($pepper === '') die("Pepper vazio no arquivo $pepperFile.");

// Conecta ao MySQL
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) {
    die("Erro de conexão com MySQL: " . $conn->connect_error);
}

// Cria DB se necessário
$sql = "CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($sql)) die("Erro ao criar DB: " . $conn->error);
$conn->select_db($DB_NAME);

// Cria tabela respostas
$sql = "CREATE TABLE IF NOT EXISTS `respostas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `puzzle_id` INT UNSIGNED NOT NULL UNIQUE,
    `answer_hash` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
if (!$conn->query($sql)) die("Erro ao criar tabela: " . $conn->error);

// Respostas (texto temporário)
$respostas = [
    1 => 'simples',
    2 => 'facinho',
    3 => '12',
    4 => 'P',
    5 => 'silencio',
    6 => 'teclado',
    7 => 'dificil',
    8 => 'music',
    9 => 'tanakh',
    10 => 'mâtu ešru'
];

// Usa ARGON2ID se disponível, senão DEFAULT
$algo = PASSWORD_DEFAULT;
if (defined('PASSWORD_ARGON2ID')) $algo = PASSWORD_ARGON2ID;

foreach ($respostas as $puzzle_id => $texto) {
    // checa existência
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM respostas WHERE puzzle_id = ?");
    $stmt->bind_param('i', $puzzle_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if (intval($row['c']) === 0) {
        $toHash = $pepper . $texto;
        $hash = password_hash($toHash, $algo);
        $ins = $conn->prepare("INSERT INTO respostas (puzzle_id, answer_hash) VALUES (?, ?)");
        $ins->bind_param('is', $puzzle_id, $hash);
        $ins->execute();
        $ins->close();
        // remove texto da memória (simbólico)
        $texto = null;
    }
}

// Cria lock para não reexecutar instalação completa
file_put_contents($lockFile, date('c') . " - instalado\n");
$conn->close();

// Redireciona para aleatorio.php (ajuste caminho se necessário)
header("Location: aleatorio.php");
exit;
?>
