<?php
require 'config.php';
$doctors = $pdo->query("SELECT id,name,specialty FROM doctors ORDER BY name")->fetchAll();

// processar envio do agendamento
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $doctor_id = intval($_POST['doctor_id']);
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    // validações simples
    $stmt = $pdo->prepare("INSERT INTO appointments (user_id,doctor_id,appointment_date,appointment_time) VALUES (?,?,?,?)");
    $stmt->execute([$_SESSION['user_id'],$doctor_id,$date,$time]);
    $success = "Agendamento solicitado com sucesso. Acompanhe em seu painel.";
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Reservas — Hospital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f6f7fb}
    .hero{background:white;padding:30px;border-radius:8px;box-shadow:0 6px 20px rgba(0,0,0,0.04)}
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="#">Hospital • Reservas</a>
    <div>
      <?php if(!empty($_SESSION['user_name'])): ?>
        <span class="me-2">Olá, <?=htmlspecialchars($_SESSION['user_name'])?></span>
        <a class="btn btn-outline-secondary btn-sm" href="dashboard.php">Meu painel</a>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">Sair</a>
      <?php else: ?>
        <a class="btn btn-primary btn-sm" href="login.php">Entrar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="hero">
    <div class="row">
      <div class="col-md-6">
        <h3>Agende sua consulta</h3>
        <p>Escolha o médico, data e horário. Você receberá confirmação no painel.</p>

        <?php if(!empty($success)): ?>
          <div class="alert alert-success"><?=$success?></div>
        <?php endif; ?>

        <?php if(empty($_SESSION['user_id'])): ?>
          <div class="alert alert-info">Você precisa <a href="login.php">entrar</a> para agendar.</div>
        <?php else: ?>
          <form method="post" id="bookingForm">
            <div class="mb-3">
              <label class="form-label">Médico</label>
              <select name="doctor_id" class="form-select" required>
                <?php foreach($doctors as $d): ?>
                  <option value="<?=$d['id']?>"><?=htmlspecialchars($d['name'])?> — <?=htmlspecialchars($d['specialty'])?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="row g-2">
              <div class="col-md-6 mb-3">
                <label class="form-label">Data</label>
                <input type="date" name="appointment_date" class="form-control" min="<?=date('Y-m-d')?>" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Horário</label>
                <input type="time" name="appointment_time" class="form-control" required>
              </div>
            </div>
            <button class="btn btn-success">Solicitar agendamento</button>
          </form>
        <?php endif; ?>
      </div>

      <div class="col-md-6">
        <h5>Médicos Disponíveis</h5>
        <ul class="list-group">
          <?php foreach($doctors as $d): ?>
            <li class="list-group-item">
              <strong><?=htmlspecialchars($d['name'])?></strong><br>
              <small><?=htmlspecialchars($d['specialty'])?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
</body>
</html>
