<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sistema_login');

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Verificar se o 2FA foi validado
$stmt = $conn->prepare("SELECT validado_2fa FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario || !$usuario['validado_2fa']) {
    header('Location: verify.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Área Logada</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Bem-vindo!</h2>
    <p>Você está logado com sucesso.</p>

    <form action="logout.php" method="POST">
        <button type="submit" class="logout-button">Sair</button>
    </form>
</div>

</body>
</html>
