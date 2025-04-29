<?php
// As funções e a conexão $pdo já estão disponíveis via index.php

// Buscar estatísticas (Exemplo de consulta direta - seguro pois não há input externo)
try {
    $totalFuncionarios = $pdo->query("SELECT COUNT(*) FROM funcionarios")->fetchColumn();
    $totalTarefas = $pdo->query("SELECT COUNT(*) FROM tarefas")->fetchColumn();
    // Busca contagem por status e retorna como ['status' => total]
    $stmtStatus = $pdo->query("SELECT status, COUNT(*) as total FROM tarefas GROUP BY status");
    $statusTarefas = $stmtStatus->fetchAll(PDO::FETCH_KEY_PAIR);
} catch (PDOException $e) {
    error_log("Erro ao buscar estatísticas na home: " . $e->getMessage());
    // Define valores padrão para evitar erros no HTML se a consulta falhar
    $totalFuncionarios = $totalTarefas = 'N/A';
    $statusTarefas = [];
    // Poderia usar setMensagemErro() aqui também
    echo "<div class='alert alert-warning'>Não foi possível carregar as estatísticas.</div>";
}
?>
<h1 class="mb-4">Bem-vindo ao Sistema de Gerenciamento de Tarefas</h1>
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-header"><i class="fas fa-users me-2"></i> Funcionários</div>
            <div class="card-body">
                <h5 class="card-title">Total: <?php echo $totalFuncionarios; ?></h5>
                <p class="card-text">Gerencie os membros da sua equipe.</p>
                <a href="index.php?pagina=cadastro_funcionario" class="btn btn-light">
                   <i class="fas fa-user-plus me-1"></i> Cadastrar Novo
                </a>
                </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success h-100">
             <div class="card-header"><i class="fas fa-tasks me-2"></i> Tarefas</div>
             <div class="card-body">
                <h5 class="card-title">Total: <?php echo $totalTarefas; ?></h5>
                <p class="card-text">Crie e acompanhe as tarefas.</p>
                 <a href="index.php?pagina=cadastro_tarefa" class="btn btn-light">
                    <i class="fas fa-plus-circle me-1"></i> Cadastrar Nova
                </a>
                 <a href="index.php?pagina=gerenciar_tarefas" class="btn btn-light ms-2">
                    <i class="fas fa-clipboard-list me-1"></i> Gerenciar
                </a>
             </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card text-dark bg-info h-100">
             <div class="card-header"><i class="fas fa-chart-pie me-2"></i> Status das Tarefas</div>
             <div class="card-body">
                 <ul class="list-unstyled mb-3">
                    <li>
                        <span class="badge bg-secondary me-2">A Fazer</span>
                        <?php // Usamos ?? (Null Coalescing) para o caso do status não existir
                        echo $statusTarefas['a_fazer'] ?? 0; ?>
                    </li>
                    <li>
                        <span class="badge bg-warning text-dark me-2">Em Andamento</span>
                         <?php echo $statusTarefas['em_andamento'] ?? 0; ?>
                    </li>
                    <li>
                        <span class="badge bg-success me-2">Concluído</span>
                         <?php echo $statusTarefas['concluido'] ?? 0; ?>
                    </li>
                </ul>
                <a href="index.php?pagina=gerenciar_tarefas" class="btn btn-light">
                    <i class="fas fa-clipboard-list me-1"></i> Gerenciar Tarefas
                </a>
            </div>
        </div>
    </div>
</div>
