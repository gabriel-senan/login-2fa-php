<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'sistema_login');

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Variável para mensagens
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_digitado = $_POST['codigo'];

    $stmt = $conn->prepare("SELECT codigo_2fa FROM usuarios WHERE id = ?");
    $stmt->bind_param('i', $_SESSION['usuario_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && $codigo_digitado == $usuario['codigo_2fa']) {
        $conn->query("UPDATE usuarios SET validado_2fa = 1 WHERE id = " . $_SESSION['usuario_id']);
        header('Location: home.php');
        exit;
    } else {
        $mensagem = "Código inválido!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificação 2FA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Verificação de 2 Fatores</h2>

    <!-- Mostrar mensagem de erro -->
    <?php if (!empty($mensagem)) : ?>
        <div class="message"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="codigo" placeholder="Digite seu código" required><br>
        <button type="submit">Verificar</button>
    </form>

    <a href="index.php">Voltar para o Login</a>
</div>

</body>
</html>
