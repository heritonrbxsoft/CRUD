<?php

// Carregar as tarefas do arquivo JSON
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

// Verificar se o índice foi encontrado
if ($taskIndex !== null) {
    // Remover a tarefa com o índice encontrado
    unset($tasks[$taskIndex]);

    // Reindexar o array para corrigir índices numéricos contínuos
    $tasks = array_values($tasks);

    // Salvar as tarefas atualizadas de volta no arquivo JSON
    file_put_contents('./db/dados.json', json_encode($tasks, JSON_PRETTY_PRINT));

    // Redirecionar para a página principal
    header('Location: index.php');
    exit();
} else {
    die('Tarefa não encontrada.');
}
