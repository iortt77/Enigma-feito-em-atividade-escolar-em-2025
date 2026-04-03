<?php
// check_answer.php
header('Content-Type: application/json; charset=utf-8');

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'senhas';

// pepper um nível acima (projeto353/pepper.txt)
$pepperFile = __DIR__ . '/../pepper.txt';

if (!file_exists($pepperFile)) {
    echo json_encode(['ok' => false, 'message' => 'Configuração faltando (pepper).']);
    exit;
}
$pepper = trim(file_get_contents($pepperFile));

$puzzle_id = isset($_POST['puzzle_id']) ? intval($_POST['puzzle_id']) : 0;
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

if ($puzzle_id <= 0 || $answer === '') {
    echo json_encode(['ok' => false, 'message' => 'Dados inválidos.']);
    exit;
}

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    echo json_encode(['ok' => false, 'message' => 'Erro ao conectar ao banco.']);
    exit;
}

$stmt = $conn->prepare("SELECT answer_hash FROM respostas WHERE puzzle_id = ?");
$stmt->bind_param('i', $puzzle_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $hash = $row['answer_hash'];
    if (password_verify($pepper . $answer, $hash)) {
        echo json_encode(['ok' => true, 'message' => 'Resposta correta!']);
    } else {
        echo json_encode(['ok' => false, 'message' => 'Resposta incorreta.']);
    }
} else {
    echo json_encode(['ok' => false, 'message' => 'Puzzle não encontrado.']);
}
$stmt->close();
$conn->close();
