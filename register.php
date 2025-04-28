<?php
$conn = new mysqli('localhost', 'root', '', 'sistema_login');

// Variável para mostrar mensagens
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
    $stmt->bind_param('ss', $email, $senha);
    if ($stmt->execute()) {
        $mensagem = "Cadastro realizado! <a href='index.php'>Faça login</a>";
    } else {
        $mensagem = "Erro ao cadastrar. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Cadastro</h2>

    <!-- Mostrar mensagem se existir -->
    <?php if (!empty($mensagem)) : ?>
        <div class="message"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Seu email" required><br>
        <input type="password" name="senha" placeholder="Sua senha" required><br>
        <button type="submit">Cadastrar</button>
    </form>

    <a href="index.php">Já tem conta? Faça login</a>
</div>

</body>
</html>
