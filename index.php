<?php

session_start();
$conn = new mysqli('localhost', 'gabriel', '', 'sistema_login');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];

        // Gerar código de 2FA
        $codigo = rand(100000, 999999);
        $conn->query("UPDATE usuarios SET codigo_2fa = '$codigo', validado_2fa = 0 WHERE id = " . $usuario['id']);

        // Enviar e-mail com PHPMailer
        require 'vendor/autoload.php'; // Depois te ensino como instalar
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.seuservidor.com'; // Exemplo: smtp.gmail.com
        $mail->SMTPAuth = true;
        $mail->Username = 'seuemail@gmail.com';
        $mail->Password = 'suasenha';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('seuemail@gmail.com', 'Sistema Login');
        $mail->addAddress($email);
        $mail->Subject = 'Seu código 2FA';
        $mail->Body = "Seu código de verificação é: $codigo";

        if (!$mail->send()) {
            echo 'Erro ao enviar código. Tente novamente.';
            exit;
        }

        header('Location: verify.php');
        exit;
    } else {
        echo "Email ou senha inválidos!";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    Email: <input type="email" name="email" required><br>
    Senha: <input type="password" name="senha" required><br>
    <button type="submit">Entrar</button>
</form>

<p><a href="register.php">Cadastrar</a></p>

