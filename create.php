<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tasks = json_decode(file_get_contents('./db/dados.json'), true);
    
    // Verifica se o arquivo JSON está vazio ou não existe
    if (!$tasks) {
        $tasks = [];
    }
    
    // Adiciona a nova tarefa ao array de tarefas
    $date = date('Y-m-d H:i:s');
    $codTarefa = date('dmyHis');
    $codId = date('His');
    $tasks[] = [
        'id'=> $codId,
        'tarefa' => $codTarefa,
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'status' => $_POST['status'],
        "date_inclusion" => $date,
        "last_update"   => ""
    ];
    
    // Grava o array atualizado no arquivo JSON
    file_put_contents('./db/dados.json', json_encode($tasks, JSON_PRETTY_PRINT));
    
    // Redireciona para a página principal
    header('Location: index.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Nova Tarefa</title>
    <link rel="stylesheet" href="./_lib/css/style.css">

</head>
<body>
    <h1>Adicionar Nova Tarefa</h1>
    <form method="POST">
        <label for="title"><b>Título:</b></label>
        <input type="text" id="title" name="title" required>
        
        <label for="description"><b>Descrição:</b></label>
        <textarea id="description" name="description" rows="25" cols="50" required></textarea>
        
        <label for="status"><b>Status:</b></label>
        <select id="status" name="status" required>
        <option value="">Selecione</option>
            <option value="Concluída">Concluída</option>
            <option value="Pendente">Pendente</option>
            <option value="Em Revisão">Em Revisão</option>
            <option value="Aguardando Aprovação">Aguardando Aprovação</option>
            <option value="Em Andamento">Em Andamento</option>
            <option value="Cancelada">Cancelada</option>
            <option value="Prioritária">Prioritária</option>
        </select>
        
        <div class="button-container">
            <button type="submit" class="save-button">Salvar</button>
            <button type="button" class="cancel-button" onclick="window.location.href='index.php';">Cancelar</button>
        </div>
    </form>
</body>
</html>


