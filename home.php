<?php

session_start();
$conn = new mysqli('localhost', 'root', '', 'sistema_login');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Checa se 2FA foi validado
$stmt = $conn->prepare("SELECT validado_2fa FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario || !$usuario['validado_2fa']) {
    header('Location: verify.php');
    exit;
}

echo "<h2>Bem-vindo!</h2>";
echo "<p><a href='logout.php'>Sair</a></p>";
