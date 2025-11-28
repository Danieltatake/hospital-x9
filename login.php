<?php
// login.php
require 'config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id,name,password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if($u && password_verify($pass, $u['password'])) {
        $_SESSION['user_id'] = $u['id'];
        $_SESSION['user_name'] = $u['name'];
        header('Location: index.php'); exit;
    } else {
        $error = "Credenciais inválidas.";
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Login — Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto" style="max-width:420px;">
    <div class="card-body">
      <h5 class="card-title">Entrar</h5>
      <?php if(!empty($_GET['registered'])): ?>
        <div class="alert alert-success">Registro realizado! Faça login.</div>
      <?php endif; ?>
      <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Senha</label><input name="password" type="password" class="form-control" required></div>
        <button class="btn btn-primary w-100">Entrar</button>
      </form>
      <hr>
      <a href="register.php">Criar nova conta</a>
    </div>
  </div>
</div>
</body>
</html>
