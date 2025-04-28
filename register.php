<?php
// register.php
$conn = new mysqli('localhost', 'gabriel', '', 'sistema_login');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (email, senha) VALUES (?, ?)");
    $stmt->bind_param('ss', $email, $senha);
    if ($stmt->execute()) {
        echo "Cadastro realizado! <a href='index.php'>Faça login</a>";
    } else {
        echo "Erro ao cadastrar.";
    }
}
?>

<h2>Cadastro</h2>
<form method="POST">
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Cadastrar</button>
</form>