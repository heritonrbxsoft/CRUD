<?php
// Verifica se o arquivo JSON existe, se não existir, cria-o com permissões adequadas
$file = './db/dados.json';
if (!file_exists($file)) {
    file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
    chmod($file, 0777); // Define as permissões para leitura e gravação
} else {
    // Verifica as permissões atuais
    $permissions = fileperms($file);

    // Verifica se as permissões são diferentes de 0777
    if (($permissions & 0x1FF) !== 0777) { // 0x1FF é uma máscara para as permissões 0777
        chmod($file, 0777); // Define as permissões para leitura e gravação
    }
}

// Lê o conteúdo do arquivo JSON
$tasks = json_decode(file_get_contents('./db/dados.json'), true);


// Filtros
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$dateInclusionFilter = isset($_GET['date_inclusion']) ? $_GET['date_inclusion'] : '';

// Ordenação
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'date_inclusion'; // Campo de ordenação (data de inclusão por padrão)
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc'; // Ordem de ordenação (ascendente por padrão)

// Filtra as tarefas com base nos critérios fornecidos
$filteredTasks = array_filter($tasks, function($task) use ($statusFilter, $dateInclusionFilter) {
    $statusMatch = empty($statusFilter) || $task['status'] === $statusFilter;

    // Se o filtro de data for fornecido, converta-o para o formato 'd/m/Y'
    if (!empty($dateInclusionFilter)) {
        $taskDate = new DateTime($task['date_inclusion']);
        $dateMatch = $taskDate && $taskDate->format('Y-m-d') === $dateInclusionFilter;
    } else {
        $dateMatch = true;
    }

    return $statusMatch && $dateMatch;
});

// Ordena as tarefas
usort($filteredTasks, function($a, $b) use ($sortField, $sortOrder) {
    if ($sortField === 'date_inclusion') {
        $dateA = new DateTime($a['date_inclusion']);
        $dateB = new DateTime($b['date_inclusion']);
        $comparison = $dateA <=> $dateB;
    } else {
        $comparison = strcmp($a[$sortField], $b[$sortField]);
    }

    return $sortOrder === 'asc' ? $comparison : -$comparison;
});

// Configurações de Paginação
$itemsPerPage = 5; // Número de itens por página
$totalItems = count($filteredTasks); // Total de itens após o filtro
$totalPages = ceil($totalItems / $itemsPerPage); // Total de páginas

// Página Atual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Garante que a página mínima é 1
$page = min($page, $totalPages); // Garante que a página não exceda o total de páginas

// Índice de Início e Fim para a página atual
$startIndex = ($page - 1) * $itemsPerPage;
$endIndex = min($startIndex + $itemsPerPage, $totalItems);

// Pega as tarefas da página atual
$pagedTasks = array_slice($filteredTasks, $startIndex, $itemsPerPage);

?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="./_lib/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function toggleFilters() {
            const filters = document.getElementById('filters');
            if (filters.classList.contains('active')) {
                filters.classList.remove('active');
            } else {
                filters.classList.add('active');
            }
        }
    </script>
</head>
<body>
    <h1>Lista de Tarefas</h1>
    
    <!-- Botão de Filtro -->
    <div class="filter-container">
        <button type="button" onclick="toggleFilters()">Filtro</button>
        
        <!-- Campos de filtro -->
        <div id="filters" class="filters">
            <form method="GET" action="">
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="">Selecione</option> 
                        <option value="Concluída" <?= $statusFilter === 'Concluída' ? 'selected' : '' ?>>Concluída</option>
                        <option value="Pendente" <?= $statusFilter === 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="Em Revisão" <?= $statusFilter === 'Em Revisão' ? 'selected' : '' ?>>Em Revisão</option>
                        <option value="Aguardando Aprovação" <?= $statusFilter === 'Aguardando Aprovação' ? 'selected' : '' ?>>Aguardando Aprovação</option>
                        <option value="Em Andamento" <?= $statusFilter === 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
                        <option value="Cancelada" <?= $statusFilter === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        <option value="Prioritária" <?= $statusFilter === 'Prioritária' ? 'selected' : '' ?>>Prioritária</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_inclusion">Data de Inclusão:</label>
                    <input type="date" name="date_inclusion" id="date_inclusion" value="<?= htmlspecialchars($dateInclusionFilter) ?>">
                </div>

                <button type="submit" class="search-button">Pesquisar</button>
            </form>
        </div>
    </div>

    <div class="actions-container" style="text-align: right; margin-right: 50px;">
    <a href="create.php" class="icon-link">
        <img src="./_lib/img/new.svg" alt="Criar Nova Tarefa" class="icon_new">
    </a>
    <!-- Adicione outros elementos aqui, se necessário -->
    </div>

    <table>
        <tr>
            <th>Numero</th>
            <th></th>
            <th>Título</th>
            <th>Descrição</th>
            <th>
                <a href="?page=<?= $page ?>&sort=status&order=<?= ($sortField === 'status' && $sortOrder === 'asc') ? 'desc' : 'asc' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?>"
                class="<?= $sortField === 'status' ? 'active' : '' ?>"><b>Status<b></a>
            </th>
            <th>
                <a href="?page=<?= $page ?>&sort=date_inclusion&order=<?= ($sortField === 'date_inclusion' && $sortOrder === 'asc') ? 'desc' : 'asc' ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?>"
                class="<?= $sortField === 'date_inclusion' ? 'active' : '' ?>"><b>Data Inc</b></a>
            </th>
            <th></th>
        </tr>
        <?php foreach ($pagedTasks as $index => $task): ?>
            <tr>
            <td class="truncated-text"><?= htmlspecialchars($task['tarefa']) ?></td>
                <td>
                    <a href="view.php?id=<?= $task['id'] ?>" class="action-link view-link"><img src="./_lib/img/documento.png" alt="" class="icon_view"></a>
                </td>
                <td class="truncated-text"><?= htmlspecialchars($task['title']) ?></td>
                <td class="truncated-text"><?= htmlspecialchars($task['description']) ?></td>
                <td><?= htmlspecialchars($task['status']) ?></td>
                <td>
                    <?php
                        $taskDate = new DateTime($task['date_inclusion']);
                        echo htmlspecialchars($taskDate->format('d/m/Y'));
                    ?>
                </td>

                <td>
                    <a href="update.php?id=<?= $task['id'] ?>" class="action-link update-link">Editar</a>
                    <a href="delete.php?id=<?= $task['id'] ?>" class="action-link delete-link" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>


    <!-- Navegação de Paginação -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1<?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?><?= isset($_GET['order']) ? '&order=' . urlencode($_GET['order']) : '' ?>"><<</a>
            <a href="?page=<?= $page - 1 ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?><?= isset($_GET['order']) ? '&order=' . urlencode($_GET['order']) : '' ?>">◄</a>
        <?php else: ?>
            <span class="disabled"><<</span>
            <span class="disabled">◄</span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?><?= isset($_GET['order']) ? '&order=' . urlencode($_GET['order']) : '' ?>" class="<?= $i === $page ? 'current' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?><?= isset($_GET['order']) ? '&order=' . urlencode($_GET['order']) : '' ?>">►</a>
            <a href="?page=<?= $totalPages ?><?= isset($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '' ?><?= isset($_GET['date_inclusion']) ? '&date_inclusion=' . urlencode($_GET['date_inclusion']) : '' ?><?= isset($_GET['sort']) ? '&sort=' . urlencode($_GET['sort']) : '' ?><?= isset($_GET['order']) ? '&order=' . urlencode($_GET['order']) : '' ?>">>></a>
        <?php else: ?>
            <span class="disabled">►</span>
            <span class="disabled">>></span>
        <?php endif; ?>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p>&copy; 2024 Heriton Vieira. Todos os direitos reservados.</p>
        <ul class="social-links">
            <li><a href="https://br.linkedin.com/in/heriton-vieira" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
            <li><a href="https://github.com/seuusuario" target="_blank" title="GitHub"><i class="fab fa-github"></i></a></li>
            <li><a href="https://www.instagram.com/heriton_vieira/" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a></li>
            <!-- Adicione mais ícones conforme necessário -->
        </ul>
    </div>

    <!-- Inclua Font Awesome no final do body se não estiver no head -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
