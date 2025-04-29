// script.js (Pode adicionar interatividade aqui no futuro, se necessário)

document.addEventListener('DOMContentLoaded', function() {
    console.log('Sistema de Gerenciamento de Tarefas carregado.');

    // Exemplo: Adicionar uma classe 'ativa' ao link do menu atual
    // (Já feito no PHP com a classe 'active', mas aqui seria uma alternativa JS)
    // const currentPage = new URLSearchParams(window.location.search).get('pagina') || 'home';
    // const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    // navLinks.forEach(link => {
    //     if (link.getAttribute('href').includes(`pagina=${currentPage}`)) {
    //         link.classList.add('active-js'); // Use uma classe diferente para não conflitar
    //     }
    // });

    // Adicionar tooltips do Bootstrap (se usar)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

});