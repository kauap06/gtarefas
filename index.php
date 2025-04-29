<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/conexao.php';
require_once __DIR__ . '/includes/funcoes.php';

// --- Roteamento e Controle de Acesso ---
$pagina = $_GET['pagina'] ?? 'home'; // Define 'home' como padrão se 'pagina' não existir

// Páginas públicas (não exigem login)
$paginasPublicas = ['login', 'registro'];

// Verifica se o usuário está logado
$usuarioLogado = isset($_SESSION['usuario_id']);

// Se o usuário NÃO está logado e tenta acessar uma página NÃO pública
if (!$usuarioLogado && !in_array($pagina, $paginasPublicas)) {
     setMensagemErro("Você precisa fazer login para acessar esta página.");
     redirecionar('login'); // Redireciona para o login
}

// Se o usuário ESTÁ logado e tenta acessar login/registro, redireciona para home
if ($usuarioLogado && in_array($pagina, $paginasPublicas)) {
    redirecionar('home');
}


require_once __DIR__ . '/includes/header.php'; // Cabeçalho depois do controle de acesso

// Define as páginas permitidas no sistema
$paginasPermitidas = [
    'home',
    'cadastro_funcionario',
    'cadastro_tarefa',
    'gerenciar_tarefas',
    'login', // Adicionada
    'registro', // Adicionada
    'logout' // Adicionaremos esta
];

$caminhoPagina = __DIR__ . "/pages/{$pagina}.php";

if (in_array($pagina, $paginasPermitidas) && file_exists($caminhoPagina)) {
    require_once $caminhoPagina;
} else if ($pagina === 'logout') { // Lógica de Logout direto no index
     // Destruir todas as variáveis de sessão.
    $_SESSION = array();

    // Se é desejado destruir a sessão completamente, apague também o cookie de sessão.
    // Nota: Isso destruirá a sessão, e não apenas os dados de sessão!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destrói a sessão.
    session_destroy();

    // Redireciona para a página de login com mensagem
     session_start(); // Inicia nova sessão para a mensagem
     setMensagemSucesso("Você saiu do sistema.");
     header("Location: index.php?pagina=login"); // Usar header aqui pois redirecionar() pode não funcionar após session_destroy()
     exit();

} else {
     echo "<div class='alert alert-danger'>Página não encontrada!</div>";
}

require_once __DIR__ . '/includes/footer.php';
?>
