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
$date = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tasks[$taskIndex] = [
        'id' => $id, // Manter o id existente
        'tarefa' => $_POST['tarefa'],
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'status' => $_POST['status'],
        "date_inclusion" => $task['date_inclusion'],
        "last_update" => $date
    ];
    file_put_contents('./db/dados.json', json_encode($tasks));
    header('Location: index.php');
    exit();
}

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
        <input type="hidden" id="tarefa" name="tarefa" value="<?= htmlspecialchars($task['tarefa']) ?>">


        <label for="title"><b>Título:</b></label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
        
        <label for="description"><b>Descrição:</b></label>
        <textarea id="description" name="description" rows="25" cols="50" required><?= htmlspecialchars($task['description']) ?></textarea>
                
        <label for="status"><b>Status:</b></label>
        <select id="status" name="status" required>
            <option value="">Selecione</option>
            <option value="Concluída" <?=  $task['status'] === 'Concluída' ? 'selected' : '' ?>>Concluída</option>
            <option value="Pendente" <?=  $task['status'] === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
            <option value="Em Revisão" <?=  $task['status'] === 'Em Revisão' ? 'selected' : '' ?>>Em Revisão</option>
            <option value="Aguardando Aprovação" <?=  $task['status'] === 'Aguardando Aprovação' ? 'selected' : '' ?>>Aguardando Aprovação</option>
            <option value="Em Andamento" <?=  $task['status'] === 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
            <option value="Cancelada" <?=  $task['status'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
            <option value="Prioritária" <?=  $task['status'] === 'Prioritária' ? 'selected' : '' ?>>Prioritária</option>
        </select>

        <label for="date_inclusion"><b>Data de Inclusão:</b></label>
        <label id="date_inclusion"><?= $dateIclud ?></label>

        <label for="last_update"><b>Ultima Atualização:</b></label>
        <label id="last_update"><?= $lastDate ?></label>


        <div class="button-container">
            <button type="submit" class="save-button">Salvar</button>
            <button type="button" class="cancel-button" onclick="window.location.href='index.php';">Cancelar</button>
        </div>
    </form>
</body>
</html>
