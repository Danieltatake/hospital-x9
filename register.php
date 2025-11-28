<?php
// register.php
require 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if(!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 6) {
        $error = "Dados inválidos. Senha precisa ter >= 6 caracteres.";
    } else {
        // checar se email existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()) {
            $error = "E-mail já cadastrado.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
            $ins->execute([$name,$email,$hash]);
            header('Location: login.php?registered=1'); exit;
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Registrar — Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width:420px;">
    <div class="card-body">
      <h5 class="card-title">Criar conta</h5>
      <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <div class="mb-3"><label class="form-label">Nome</label><input name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Senha</label><input name="password" type="password" class="form-control" required></div>
        <button class="btn btn-primary w-100">Registrar</button>
      </form>
      <hr>
      <a href="login.php">Já tenho conta — Entrar</a>
    </div>
  </div>
</div>
</body>
</html>
