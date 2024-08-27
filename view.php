<?php

$tasks = json_decode(file_get_contents('./db/dados.json'), true);
$id = $_GET['id'];

// Encontrar o índice da tarefa com o id correspondente
$taskIndex = null;
foreach ($tasks as $index => $task) {
    if ($task['id'] == $id) {
        $taskIndex = $index;
        break;
    }
}

if ($taskIndex === null) {
    die('Tarefa não encontrada.');
}

$task = $tasks[$taskIndex];

$dateIclud = new DateTime($task['date_inclusion']);
$dateIclud = $dateIclud->format('d/m/Y H:i:s');

$lastDate = "";
if (!empty($task['last_update'])) {
    $lastDate = new DateTime($task['last_update']);
    $lastDate = $lastDate->format('d/m/Y H:i:s');
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="./_lib/css/style.css">

</head>
<body>
    <h1>Editar Tarefa: <?= htmlspecialchars($task['tarefa']) ?></h1>
    <form method="POST">
        <label for="title"><b>Título:</b></label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" disabled>
        
        <label for="description"><b>Descrição:</b></label>
        <textarea id="description" name="description" rows="25" cols="50" disabled><?= htmlspecialchars($task['description']) ?></textarea>
        
        <label for="status"><b>Status:</b></label>
        <label id="status"><?= htmlspecialchars($task['status']) ?></label>

        <label for="date_inclusion"><b>Data de Inclusão:</b></label>
        <label id="date_inclusion"><?= $dateIclud ?></label>

        <label for="last_update"><b>Ultima Atualização:</b></label>
        <label id="last_update"><?= $lastDate ?></label>


        <div class="button-container">
            <button type="button" class="voltar-button" onclick="window.location.href='index.php';">Voltar</button>
        </div>
    </form>
</body>
</html>
