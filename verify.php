<?php

session_start();
$conn = new mysqli('localhost', 'gabriel', '', 'sistema_login');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

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
        echo "Código inválido!";
    }
}
?>

<h2>Verificação de 2 Fatores</h2>
<form method="POST">
    Código: <input type="text" name="codigo" required><br>
    <button type="submit">Verificar</button>
</form>
