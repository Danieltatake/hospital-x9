<?php
require 'config.php';
// NÃO coloque autenticação forte aqui — exemplo simples: crie usuário admin real se for produção.
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = intval($_POST['id']);
    if($_POST['action'] === 'confirm') {
        $pdo->prepare("UPDATE appointments SET status='confirmed' WHERE id = ?")->execute([$id]);
    } elseif($_POST['action'] === 'cancel') {
        $pdo->prepare("UPDATE appointments SET status='cancelled' WHERE id = ?")->execute([$id]);
    }
    header('Location: admin.php'); exit;
}
$rows = $pdo->query("SELECT a.*, u.name as user_name, d.name as doctor_name FROM appointments a JOIN users u ON a.user_id=u.id JOIN doctors d ON a.doctor_id=d.id ORDER BY a.created_at DESC")->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><title>Admin — Agendamentos</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4">
  <h3>Admin — Agendamentos</h3>
  <a href="index.php">Voltar ao site</a>
  <table class="table mt-3">
    <thead><tr><th>ID</th><th>Paciente</th><th>Médico</th><th>Data</th><th>Hora</th><th>Status</th><th>Ações</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?=$r['id']?></td>
          <td><?=htmlspecialchars($r['user_name'])?></td>
          <td><?=htmlspecialchars($r['doctor_name'])?></td>
          <td><?=$r['appointment_date']?></td>
          <td><?=substr($r['appointment_time'],0,5)?></td>
          <td><?=$r['status']?></td>
          <td>
            <form method="post" style="display:inline">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <button name="action" value="confirm" class="btn btn-sm btn-success">Confirmar</button>
            </form>
            <form method="post" style="display:inline">
              <input type="hidden" name="id" value="<?=$r['id']?>">
              <button name="action" value="cancel" class="btn btn-sm btn-danger">Cancelar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
