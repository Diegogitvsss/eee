<?php
session_start();
require_once 'conexao.php';

// Buscar os produtos em destaque (exemplo: 6 últimos cadastrados)
$stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY criado_em DESC LIMIT 6");
$stmt->execute();
$produtos_destaque = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProMarket Brasil - Compre e Venda Online</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
/* Reset e Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary-color: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary-color: #64748b;
    --accent-color: #0ea5e9;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --error-color: #ef4444;
    --neutral-50: #f8fafc;
    --neutral-100: #f1f5f9;
    --neutral-200: #e2e8f0;
    --neutral-300: #cbd5e1;
    --neutral-400: #94a3b8;
    --neutral-500: #64748b;
    --neutral-600: #475569;
    --neutral-700: #334155;
    --neutral-800: #1e293b;
    --neutral-900: #0f172a;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: var(--text-primary);
    background-color: var(--neutral-50);
    font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Header */
.header {
    background: #ffffff;
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 0;
    z-index: 1000;
    border-bottom: 1px solid var(--border-color);
}

.header-top {
    background: var(--neutral-800);
    color: var(--neutral-300);
    padding: 0.5rem 0;
    font-size: 0.875rem;
}

.header-top-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-main {
    padding: 1rem 0;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.logo {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary-color);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
}

.logo i {
    font-size: 1.5rem;
}

.search-container {
    flex: 1;
    max-width: 600px;
    position: relative;
}

.search-box {
    width: 100%;
    padding: 0.75rem 3rem 0.75rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    font-size: 1rem;
    transition: all 0.2s ease;
    background: #ffffff;
    outline: none;
}

.search-box:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgb(37 99 235 / 0.1);
}

.search-btn {
    position: absolute;
    right: 0.25rem;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color);
    border: none;
    border-radius: var(--radius-md);
    width: 2.5rem;
    height: 2.5rem;
    color: white;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-50%) scale(1.05);
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    flex-shrink: 0;
}

.header-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid var(--primary-color);
    border-radius: var(--radius-lg);
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.header-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.header-btn.primary {
    background: var(--primary-color);
    color: white;
}

.header-btn.primary:hover {
    background: var(--primary-dark);
}

/* Navigation */
.nav {
    background: #ffffff;
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 0;
}

.nav-content {
    display: flex;
    justify-content: center;
    align-items: center;
}

.nav-links {
    display: flex;
    list-style: none;
    gap: 2rem;
    margin: 0;
    padding: 0;
}

.nav-links a {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-links a:hover {
    color: var(--primary-color);
    background: var(--neutral-100);
}

.nav-links a i {
    font-size: 0.875rem;
}

/* Hero Section */
.hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 4rem 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,0 1000,300 1000,1000 0,700"/></svg>');
    background-size: cover;
}

.hero .container {
    position: relative;
    z-index: 1;
}

.hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.hero-btn {
    padding: 1rem 2rem;
    border: none;
    border-radius: var(--radius-lg);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 180px;
    justify-content: center;
}

.hero-btn.primary {
    background: white;
    color: var(--primary-color);
}

.hero-btn.primary:hover {
    background: var(--neutral-100);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.hero-btn.secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 2px solid white;
    backdrop-filter: blur(10px);
}

.hero-btn.secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

/* Categories */
.categories {
    background: white;
    padding: 4rem 0;
    position: relative;
    margin-top: -2rem;
    border-radius: 2rem 2rem 0 0;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 3rem;
    color: var(--text-primary);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.category-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: 2rem;
    text-align: center;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    opacity: 0;
    transition: opacity 0.2s ease;
}

.category-card:hover::before {
    opacity: 0.05;
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-color);
}

.category-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
    color: var(--primary-color);
    position: relative;
    z-index: 1;
}

.category-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    position: relative;
    z-index: 1;
}

.category-card p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    position: relative;
    z-index: 1;
}

/* Featured Products */
.featured-products {
    background: var(--neutral-50);
    padding: 4rem 0;
}

.produtos-destaque-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.produto-card {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.produto-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-color);
}

.produto-img-wrap {
    width: 100%;
    height: 200px;
    background: var(--neutral-100);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}

.produto-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.produto-placeholder {
    font-size: 4rem;
    color: var(--neutral-300);
}

.produto-info {
    padding: 1.5rem;
}

.produto-info h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
    line-height: 1.4;
}

.produto-preco {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--success-color);
    margin-bottom: 0.75rem;
}

.produto-desc {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.produto-detalhes-list {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
    font-size: 0.8125rem;
    color: var(--text-secondary);
}

.produto-detalhes-list li {
    margin-bottom: 0.25rem;
    padding-left: 1rem;
    position: relative;
}

.produto-detalhes-list li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: var(--primary-color);
}

.produto-detalhes-list strong {
    color: var(--text-primary);
}

.produto-btn {
    width: 100%;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    padding: 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.produto-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

/* Stats Section */
.stats {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 4rem 0;
    position: relative;
    overflow: hidden;
}

.stats::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,0 1000,200 1000,1000 0,800"/></svg>');
    background-size: cover;
}

.stats .container {
    position: relative;
    z-index: 1;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 3rem;
    text-align: center;
}

.stat-item h3 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
}

.stat-item p {
    font-size: 1.125rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Footer */
.footer {
    background: var(--neutral-900);
    color: var(--neutral-300);
    padding: 3rem 0 1.5rem;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: white;
}

.footer-section ul {
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 0.5rem;
}

.footer-section ul li a {
    color: var(--neutral-400);
    text-decoration: none;
    transition: color 0.2s ease;
    font-size: 0.875rem;
}

.footer-section ul li a:hover {
    color: var(--primary-color);
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid var(--neutral-700);
    color: var(--neutral-400);
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .search-container {
        order: 2;
        width: 100%;
    }
    
    .header-actions {
        order: 1;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .nav-links {
        display: none;
    }
    
    .hero h1 {
        font-size: 2rem;
    }
    
    .hero p {
        font-size: 1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-btn {
        width: 100%;
        max-width: 280px;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .produtos-destaque-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 2rem;
    }
    
    .stat-item h3 {
        font-size: 2rem;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card,
.produto-card {
    animation: fadeInUp 0.6s ease forwards;
}

.category-card:nth-child(1) { animation-delay: 0.1s; }
.category-card:nth-child(2) { animation-delay: 0.2s; }
.category-card:nth-child(3) { animation-delay: 0.3s; }
.category-card:nth-child(4) { animation-delay: 0.4s; }
.category-card:nth-child(5) { animation-delay: 0.5s; }
.category-card:nth-child(6) { animation-delay: 0.6s; }

.produto-card:nth-child(1) { animation-delay: 0.1s; }
.produto-card:nth-child(2) { animation-delay: 0.2s; }
.produto-card:nth-child(3) { animation-delay: 0.3s; }
.produto-card:nth-child(4) { animation-delay: 0.4s; }
.produto-card:nth-child(5) { animation-delay: 0.5s; }
.produto-card:nth-child(6) { animation-delay: 0.6s; }

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus styles */
.search-box:focus,
.header-btn:focus,
.nav-links a:focus,
.hero-btn:focus,
.produto-btn:focus {
    outline: 2px solid var(--primary-color);
    outline-offset: 2px;
}

/* Utility classes */
.text-center { text-align: center; }
.text-left { text-align: left; }
.text-right { text-align: right; }
.font-bold { font-weight: 700; }
.font-semibold { font-weight: 600; }
.font-medium { font-weight: 500; }
.uppercase { text-transform: uppercase; }
.lowercase { text-transform: lowercase; }
.capitalize { text-transform: capitalize; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-top-content">
                    <span>📍 Entregamos em todo o Brasil</span>
                    <span>📞 Central de Atendimento: 0800-123-4567</span>
                </div>
            </div>
        </div>
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <i class="fas fa-store"></i> ProMarket Brasil
                    </div>
                    <div class="search-container">
                        <input type="text" class="search-box" placeholder="Buscar produtos, marcas, lojas...">
                        <button class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
<div class="header-actions">
    <?php
    if (!empty($_SESSION['user_id'])): // Agora usamos user_id e user_tipo ?>
        <?php
        if ($_SESSION['user_tipo'] === 'comprador') {
            echo '<a href="painel_comprador.php" class="header-btn primary"><i class="fas fa-user"></i> Painel Comprador</a>';
        } elseif ($_SESSION['user_tipo'] === 'vendedor') {
            echo '<a href="painel_vendedor.php" class="header-btn primary"><i class="fas fa-store"></i> Painel Vendedor</a>';
        } elseif ($_SESSION['user_tipo'] === 'ambos') {
            echo '<a href="painel_comprador.php" class="header-btn primary"><i class="fas fa-user"></i> Painel Comprador</a>';
            echo '<a href="painel_vendedor.php" class="header-btn primary"><i class="fas fa-store"></i> Painel Vendedor</a>';
        }
        ?>
    <?php else: ?>
        <a href="login_cadastro.php" class="header-btn">
            <i class="fas fa-user"></i> Entrar
        </a>
    <?php endif; ?>
</div>
                </div>
            </div>
        </div>
    </header>
    <!-- Navigation -->
    <nav class="nav">
        <div class="container">
            <div class="nav-content">
                <ul class="nav-links">
                    <li><a href="#"><i class="fas fa-home"></i> Início</a></li>
                    <li><a href="#"><i class="fas fa-tags"></i> Ofertas</a></li>
                    <li><a href="#"><i class="fas fa-star"></i> Mais Vendidos</a></li>
                    <li><a href="#"><i class="fas fa-truck"></i> Frete Grátis</a></li>
                    <li><a href="#"><i class="fas fa-mobile-alt"></i> Eletrônicos</a></li>
                    <li><a href="#"><i class="fas fa-tshirt"></i> Moda</a></li>
                    <li><a href="#"><i class="fas fa-home"></i> Casa</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Compre e Venda com Segurança</h1>
            <p>O maior marketplace do Brasil. Milhões de produtos com os melhores preços e frete grátis.</p>
            <div class="hero-buttons">
                <a href="login_cadastro.php" class="hero-btn primary" <?php if (!empty($_SESSION['usuario_logado'])) echo 'style="display:none;"'; ?>>
                    <i class="fas fa-shopping-bag"></i> Começar a Comprar
                </a>
                <a href="login_cadastro.php" class="hero-btn secondary">
                    <i class="fas fa-store"></i> Começar a Vender
                </a>
                <?php if (!empty($_SESSION['usuario_logado'])): ?>
                    <a href="painel_usuario.php" class="hero-btn secondary">
                        <i class="fas fa-user"></i> Meu Painel
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- Categories -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Categorias Populares</h2>
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h3>Eletrônicos</h3>
                    <p>Smartphones, tablets, notebooks e mais</p>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fas fa-tshirt"></i></div>
                    <h3>Moda</h3>
                    <p>Roupas, calçados e acessórios</p>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fas fa-home"></i></div>
                    <h3>Casa & Jardim</h3>
                    <p>Decoração, móveis e utilidades</p>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fas fa-dumbbell"></i></div>
                    <h3>Esportes</h3>
                    <p>Equipamentos e roupas esportivas</p>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fas fa-car"></i></div>
                    <h3>Automotivo</h3>
                    <p>Peças, acessórios e ferramentas</p>
                </div>
                <div class="category-card">
                    <div class="category-icon"><i class="fas fa-baby"></i></div>
                    <h3>Bebês</h3>
                    <p>Produtos para mamães e bebês</p>
                </div>
            </div>
        </div>
    </section>
    <section class="featured-products" style="background: #f8f9fa; padding: 60px 0;">
        <div class="container">
            <h2 class="section-title" style="text-align:center;font-size:28px;margin-bottom:28px;color:#667eea;">Produtos em Destaque</h2>
<div class="produtos-destaque-grid">
    <?php if ($produtos_destaque && count($produtos_destaque) > 0): ?>
        <?php foreach ($produtos_destaque as $produto): ?>
            <div class="produto-card">
                <div class="produto-img-wrap">
                    <?php if (!empty($produto['imagem'])): ?>
                        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <?php else: ?>
                        <i class="fas fa-box produto-placeholder"></i>
                    <?php endif; ?>
                </div>
                <div class="produto-info">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <div class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                    <div class="produto-desc"><?= mb_strimwidth(strip_tags($produto['descricao']), 0, 60, '...'); ?></div>
                                    <div class="produto-detalhes">
                                        <ul class="produto-detalhes-list">
                                            <li><span>Categoria:</span> <strong><?= htmlspecialchars($produto['categoria']) ?></strong></li>
                                            <li><span>Estoque:</span> <strong><?= htmlspecialchars($produto['estoque']) ?> disponível</strong></li>
                                            <li><span>Vendedor:</span> <strong>ID: <?= htmlspecialchars($produto['vendedor_id'] ?? 'N/A') ?></strong></li>
                                            <li><span>Publicado em:</span> <strong><?= date('d/m/Y', strtotime($produto['criado_em'])) ?></strong></li>
                                        </ul>
                                    </div>
                    <button class="produto-btn">Comprar Agora</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="color:#aaa;text-align:center;width:100%;">Nenhum produto em destaque ainda.</div>
    <?php endif; ?>
</div>
        </div>
    </section>
    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item"><h3>10M+</h3><p>Produtos Disponíveis</p></div>
                <div class="stat-item"><h3>500K+</h3><p>Lojas Parceiras</p></div>
                <div class="stat-item"><h3>50M+</h3><p>Usuários Registrados</p></div>
                <div class="stat-item"><h3>99.9%</h3><p>Satisfação do Cliente</p></div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sobre o ProMarket</h3>
                    <ul>
                        <li><a href="#">Quem Somos</a></li>
                        <li><a href="#">Trabalhe Conosco</a></li>
                        <li><a href="#">Investidores</a></li>
                        <li><a href="#">Sustentabilidade</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Atendimento</h3>
                    <ul>
                        <li><a href="#">Central de Ajuda</a></li>
                        <li><a href="#">Como Comprar</a></li>
                        <li><a href="#">Como Vender</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Pagamento</h3>
                    <ul>
                        <li><a href="#">Cartão de Crédito</a></li>
                        <li><a href="#">Boleto Bancário</a></li>
                        <li><a href="#">PIX</a></li>
                        <li><a href="#">Marketplace Pay</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Redes Sociais</h3>
                    <ul>
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i> YouTube</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 ProMarket Brasil. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
    <script>
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        // Search functionality
        const searchBtn = document.querySelector('.search-btn');
        const searchBox = document.querySelector('.search-box');
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchBox.value.trim();
            if (searchTerm) {
                alert(`Buscando por: ${searchTerm}`);
                // Aqui você implementaria a busca real
            }
        });
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') { searchBtn.click(); }
        });
        // Product cards interaction
        document.querySelectorAll('.product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productTitle = this.closest('.product-card').querySelector('.product-title').textContent;
                alert(`Produto "${productTitle}" adicionado ao carrinho!`);
            });
        });
        // Category cards interaction
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const categoryName = this.querySelector('h3').textContent;
                alert(`Navegando para categoria: ${categoryName}`);
            });
        });
        // Footer links externos
        document.querySelectorAll('.footer-section ul li a').forEach(link => {
            link.addEventListener('click', function(e) {
                if(this.getAttribute('href') === "#") {
                    e.preventDefault();
                    alert('Página ainda não implementada.');
                }
            });
        });
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                // alert('Bem-vindo ao MarketPlace Brasil! Aproveite as ofertas.');
            }, 800);
        });
    </script>
</body>
</html>