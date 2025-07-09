<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_tipo'] !== 'comprador' && $_SESSION['user_tipo'] !== 'ambos')) {
    header('Location: login_cadastro.php');
    exit;
}
require_once 'conexao.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

// Exemplo: quantidade de pedidos fictício.
$pedidos = 8;
$enderecos = 2;

$stmt = $pdo->prepare("SELECT * FROM produtos ORDER BY criado_em DESC LIMIT 6");
$stmt->execute();
$produtos_destaque = $stmt->fetchAll();

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login_cadastro.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Comprador - MarketPlace Brasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0ea5e9;
            
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
            
            --gradient-primary: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            --gradient-secondary: linear-gradient(135deg, #64748b 0%, #475569 100%);
            --gradient-bg: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
            --border-radius-xl: 1rem;
            
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--gradient-bg);
            color: var(--neutral-800);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            background: white;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
        }

        /* Header */
        .header {
            background: var(--gradient-primary);
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.06)"/></svg>');
            pointer-events: none;
        }

        .user-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .user-avatar {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            transition: var(--transition);
        }

        .user-avatar:hover {
            transform: scale(1.05);
            background: rgba(255, 255, 255, 0.3);
        }

        .user-info {
            flex: 1;
        }

        .user-info h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: white;
        }

        .user-info p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            font-weight: 400;
        }

        .user-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary {
            background: white;
            color: var(--primary-color);
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--neutral-50);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Main Content */
        .main-content {
            padding: 2.5rem;
        }

        /* Dashboard Cards */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .dash-card {
            background: white;
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .dash-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .dash-card:hover::before {
            transform: scaleX(1);
        }

        .dash-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .dash-card .icon {
            width: 3.5rem;
            height: 3.5rem;
            margin: 0 auto 1rem;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            transition: var(--transition);
        }

        .dash-card:hover .icon {
            transform: scale(1.1) rotate(5deg);
        }

        .dash-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.5rem;
        }

        .dash-card .count {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .dash-card .desc {
            color: var(--neutral-500);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Search */
        .search-section {
            margin-bottom: 3rem;
        }

        .search-container {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }

        .search-box {
            width: 100%;
            padding: 1rem 1.5rem;
            padding-right: 4rem;
            border: 2px solid var(--neutral-200);
            border-radius: var(--border-radius-lg);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .search-box:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gradient-primary);
            border: none;
            border-radius: var(--border-radius);
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-btn:hover {
            transform: translateY(-50%) scale(1.05);
        }

        /* Products Section */
        .produtos-section {
            margin-top: 3rem;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--neutral-800);
            margin-bottom: 2rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 4rem;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .produto-card {
            background: white;
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            transition: var(--transition);
            cursor: pointer;
        }

        .produto-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-color);
        }

        .produto-img-wrap {
            width: 100%;
            height: 200px;
            background: var(--neutral-50);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .produto-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .produto-card:hover .produto-img-wrap img {
            transform: scale(1.05);
        }

        .produto-placeholder {
            font-size: 3rem;
            color: var(--neutral-300);
        }

        .produto-info {
            padding: 1.5rem;
        }

        .produto-info h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .produto-preco {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-color);
            margin-bottom: 0.75rem;
        }

        .produto-desc {
            color: var(--neutral-500);
            font-size: 0.875rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .produto-detalhes {
            background: var(--neutral-50);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .produto-detalhes-list {
            list-style: none;
            font-size: 0.8rem;
            color: var(--neutral-600);
            line-height: 1.6;
        }

        .produto-detalhes-list li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .produto-detalhes-list li:last-child {
            margin-bottom: 0;
        }

        .produto-detalhes-list strong {
            color: var(--neutral-700);
        }

        .produto-btn {
            width: 100%;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .produto-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .produto-btn:hover::before {
            left: 100%;
        }

        .produto-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Sidebar Carrinho */
        .carrinho-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .carrinho-backdrop.active {
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar-carrinho {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            max-width: 90vw;
            height: 100vh;
            background: white;
            box-shadow: var(--shadow-xl);
            z-index: 1100;
            display: flex;
            flex-direction: column;
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-carrinho.active {
            right: 0;
        }

        .sidebar-carrinho-header {
            padding: 2rem;
            background: var(--gradient-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-carrinho-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-carrinho-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .sidebar-carrinho-close:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-carrinho-content {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .carrinho-vazio {
            text-align: center;
            color: var(--neutral-400);
            padding: 3rem 0;
        }

        .carrinho-vazio i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--neutral-300);
        }

        .carrinho-lista {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .carrinho-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            background: white;
            position: relative;
        }

        .carrinho-item-img {
            width: 60px;
            height: 60px;
            border-radius: var(--border-radius);
            object-fit: cover;
            background: var(--neutral-100);
        }

        .carrinho-item-info {
            flex: 1;
        }

        .carrinho-item-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.25rem;
        }

        .carrinho-item-details {
            font-size: 0.8rem;
            color: var(--neutral-500);
            margin-bottom: 0.5rem;
        }

        .carrinho-item-preco {
            font-size: 1rem;
            font-weight: 600;
            color: var(--success-color);
            margin-bottom: 0.5rem;
        }

        .carrinho-item-qtd {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .qtd-btn {
            width: 2rem;
            height: 2rem;
            border: 1px solid var(--neutral-300);
            background: white;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            color: var(--neutral-600);
        }

        .qtd-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .carrinho-item-remove {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            color: var(--danger-color);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .carrinho-item-remove:hover {
            background: var(--neutral-100);
        }

        .sidebar-carrinho-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--neutral-200);
            background: var(--neutral-50);
        }

        .carrinho-resumo {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .carrinho-resumo:last-of-type {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--neutral-800);
            padding-top: 0.75rem;
            border-top: 1px solid var(--neutral-200);
        }

        .carrinho-resumo .label {
            color: var(--neutral-600);
        }

        .carrinho-resumo .valor {
            color: var(--success-color);
            font-weight: 600;
        }

        .carrinho-btn-finalizar {
            width: 100%;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 1rem;
        }

        .carrinho-btn-finalizar:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                border-radius: var(--border-radius);
            }

            .header {
                padding: 1.5rem;
            }

            .user-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .user-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .main-content {
                padding: 1.5rem;
            }

            .dashboard {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .dash-card {
                padding: 1.5rem;
            }

            .produtos-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .sidebar-carrinho {
                width: 100vw;
                right: -100vw;
            }
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .container {
            animation: slideInUp 0.6s ease-out;
        }

        .dash-card {
            animation: fadeIn 0.6s ease-out;
        }

        .produto-card {
            animation: fadeIn 0.6s ease-out;
        }

        /* Hover Effects */
        .dash-card:hover {
            --shadow-color: rgba(37, 99, 235, 0.15);
            box-shadow: 0 10px 25px -5px var(--shadow-color), 0 8px 10px -6px var(--shadow-color);
        }

        .produto-card:hover {
            --shadow-color: rgba(37, 99, 235, 0.15);
            box-shadow: 0 20px 25px -5px var(--shadow-color), 0 8px 10px -6px var(--shadow-color);
        }

        /* Focus States */
        .btn:focus,
        .search-box:focus,
        .produto-btn:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Garantir que o formulário não interfira no layout dos botões */
.user-actions form {
    display: inline-block;
    margin: 0;
    padding: 0;
}

/* Garantir que o botão dentro do form tenha o mesmo tamanho */
.user-actions form .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
    min-width: auto;
}

/* Garantir que todos os botões tenham a mesma altura */
.user-actions .btn {
    height: 44px; /* Altura fixa para todos os botões */
    box-sizing: border-box;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="user-header">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <h2>Olá, <?= htmlspecialchars($user_data['nome']) ?>!</h2>
                    <p><?= htmlspecialchars($user_data['email']) ?></p>
                </div>
                <div class="user-actions">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar para Home
                    </a>
                    <a href="javascript:void(0)" class="btn btn-primary" id="abrir-carrinho-btn">
                        <i class="fas fa-shopping-cart"></i> Carrinho
                    </a>
                    <form method="post" style="display:inline;">
                        <button class="btn btn-secondary" type="submit" name="logout">
                            <i class="fas fa-sign-out-alt"></i> Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="dashboard">
                <div class="dash-card">
                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="count"><?= $enderecos ?></div>
                    <h3>Endereços</h3>
                    <div class="desc">Gerencie seus endereços de entrega</div>
                </div>
                <div class="dash-card">
                    <div class="icon"><i class="fas fa-star"></i></div>
                    <h3>Avaliações</h3>
                    <div class="desc">Avalie produtos e vendedores</div>
                </div>
                <div class="dash-card">
                    <div class="icon"><i class="fas fa-id-card"></i></div>
                    <h3>Meus Dados</h3>
                    <div class="desc">Atualize seu perfil, email e senha</div>
                </div>
                <div class="dash-card">
                    <div class="icon"><i class="fas fa-headset"></i></div>
                    <h3>Central de Ajuda</h3>
                    <div class="desc">Dúvidas? Acesse nossos canais de atendimento</div>
                </div>
            </div>

            <div class="search-section">
                <div class="search-container">
                    <input type="text" class="search-box" placeholder="Buscar produtos, marcas, lojas...">
                    <button class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="produtos-section">
                <h2 class="section-title"></h2>
                <h2 class="section-title">Produtos em Destaque</h2>
                <div class="produtos-grid">
                    <?php if (!empty($produtos_destaque)): ?>
                        <?php foreach ($produtos_destaque as $produto): ?>
                            <div class="produto-card">
                                <div class="produto-img-wrap">
                                    <?php if (!empty($produto['imagem'])): ?>
                                        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                    <?php else: ?>
                                        <i class="fas fa-image produto-placeholder"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="produto-info">
                                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                                    <div class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                                    <div class="produto-desc">
                                        <?= htmlspecialchars(substr($produto['descricao'], 0, 100)) ?>
                                        <?= strlen($produto['descricao']) > 100 ? '...' : '' ?>
                                    </div>
                                    <div class="produto-detalhes">
                                        <ul class="produto-detalhes-list">
                                            <li><span>Categoria:</span> <strong><?= htmlspecialchars($produto['categoria']) ?></strong></li>
                                            <li><span>Estoque:</span> <strong><?= htmlspecialchars($produto['estoque']) ?> disponível</strong></li>
                                            <li><span>Vendedor:</span> <strong>ID: <?= htmlspecialchars($produto['vendedor_id'] ?? 'N/A') ?></strong></li>
                                            <li><span>Publicado em:</span> <strong><?= date('d/m/Y', strtotime($produto['criado_em'])) ?></strong></li>
                                        </ul>
                                    </div>
                                    <button class="produto-btn" onclick="adicionarAoCarrinho(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>)">
                                        <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="produto-card">
                            <div class="produto-info">
                                <h3>Nenhum produto encontrado</h3>
                                <div class="produto-desc">Não há produtos cadastrados no momento.</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Carrinho -->
    <div class="carrinho-backdrop" id="carrinho-backdrop" onclick="fecharCarrinho()"></div>
    <div class="sidebar-carrinho" id="sidebar-carrinho">
        <div class="sidebar-carrinho-header">
            <h2><i class="fas fa-shopping-cart"></i> Meu Carrinho</h2>
            <button class="sidebar-carrinho-close" onclick="fecharCarrinho()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sidebar-carrinho-content">
            <div class="carrinho-vazio" id="carrinho-vazio">
                <i class="fas fa-shopping-cart"></i>
                <h3>Carrinho vazio</h3>
                <p>Adicione produtos ao seu carrinho para começar a comprar</p>
            </div>
            <div class="carrinho-lista" id="carrinho-lista" style="display: none;">
                <!-- Itens do carrinho serão inseridos aqui via JavaScript -->
            </div>
        </div>
        <div class="sidebar-carrinho-footer" id="carrinho-footer" style="display: none;">
            <div class="carrinho-resumo">
                <span class="label">Subtotal:</span>
                <span class="valor" id="carrinho-subtotal">R$ 0,00</span>
            </div>
            <div class="carrinho-resumo">
                <span class="label">Frete:</span>
                <span class="valor" id="carrinho-frete">R$ 15,00</span>
            </div>
            <div class="carrinho-resumo">
                <span class="label">Total:</span>
                <span class="valor" id="carrinho-total">R$ 15,00</span>
            </div>
            <button class="carrinho-btn-finalizar" onclick="finalizarCompra()">
                <i class="fas fa-credit-card"></i> Finalizar Compra
            </button>
        </div>
    </div>

    <script>
        // Carrinho de compras
        let carrinho = [];
        let totalCarrinho = 0;
        const freteFixo = 15.00;

        // Função para adicionar produto ao carrinho
        function adicionarAoCarrinho(id, nome, preco) {
            const itemExistente = carrinho.find(item => item.id === id);
            
            if (itemExistente) {
                itemExistente.quantidade += 1;
                itemExistente.total = itemExistente.preco * itemExistente.quantidade;
            } else {
                carrinho.push({
                    id: id,
                    nome: nome,
                    preco: preco,
                    quantidade: 1,
                    total: preco
                });
            }
            
            atualizarCarrinho();
            mostrarNotificacao('Produto adicionado ao carrinho!', 'success');
        }

        // Função para remover produto do carrinho
        function removerDoCarrinho(id) {
            carrinho = carrinho.filter(item => item.id !== id);
            atualizarCarrinho();
            mostrarNotificacao('Produto removido do carrinho!', 'info');
        }

        // Função para alterar quantidade
        function alterarQuantidade(id, operacao) {
            const item = carrinho.find(item => item.id === id);
            
            if (item) {
                if (operacao === 'aumentar') {
                    item.quantidade += 1;
                } else if (operacao === 'diminuir' && item.quantidade > 1) {
                    item.quantidade -= 1;
                }
                
                item.total = item.preco * item.quantidade;
                atualizarCarrinho();
            }
        }

        // Função para atualizar o carrinho
        function atualizarCarrinho() {
            const carrinhoVazio = document.getElementById('carrinho-vazio');
            const carrinhoLista = document.getElementById('carrinho-lista');
            const carrinhoFooter = document.getElementById('carrinho-footer');
            
            if (carrinho.length === 0) {
                carrinhoVazio.style.display = 'block';
                carrinhoLista.style.display = 'none';
                carrinhoFooter.style.display = 'none';
                return;
            }
            
            carrinhoVazio.style.display = 'none';
            carrinhoLista.style.display = 'block';
            carrinhoFooter.style.display = 'block';
            
            // Construir lista de itens
            let html = '';
            totalCarrinho = 0;
            
            carrinho.forEach(item => {
                totalCarrinho += item.total;
                html += `
                    <div class="carrinho-item">
                        <div class="carrinho-item-img">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="carrinho-item-info">
                            <div class="carrinho-item-title">${item.nome}</div>
                            <div class="carrinho-item-preco">R$ ${item.preco.toFixed(2).replace('.', ',')}</div>
                            <div class="carrinho-item-qtd">
                                <button class="qtd-btn" onclick="alterarQuantidade(${item.id}, 'diminuir')">-</button>
                                <span>${item.quantidade}</span>
                                <button class="qtd-btn" onclick="alterarQuantidade(${item.id}, 'aumentar')">+</button>
                            </div>
                        </div>
                        <button class="carrinho-item-remove" onclick="removerDoCarrinho(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            
            carrinhoLista.innerHTML = html;
            
            // Atualizar valores
            document.getElementById('carrinho-subtotal').textContent = `R$ ${totalCarrinho.toFixed(2).replace('.', ',')}`;
            document.getElementById('carrinho-frete').textContent = `R$ ${freteFixo.toFixed(2).replace('.', ',')}`;
            document.getElementById('carrinho-total').textContent = `R$ ${(totalCarrinho + freteFixo).toFixed(2).replace('.', ',')}`;
        }

        // Função para abrir carrinho
        function abrirCarrinho() {
            document.getElementById('carrinho-backdrop').classList.add('active');
            document.getElementById('sidebar-carrinho').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Função para fechar carrinho
        function fecharCarrinho() {
            document.getElementById('carrinho-backdrop').classList.remove('active');
            document.getElementById('sidebar-carrinho').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Função para finalizar compra
        function finalizarCompra() {
            if (carrinho.length === 0) {
                mostrarNotificacao('Adicione produtos ao carrinho antes de finalizar a compra!', 'warning');
                return;
            }
            
            mostrarNotificacao('Redirecionando para finalização da compra...', 'info');
            
            // Aqui você pode redirecionar para uma página de checkout
            setTimeout(() => {
                window.location.href = 'checkout.php';
                mostrarNotificacao('Funcionalidade de checkout em desenvolvimento!', 'info');
            }, 1500);
        }

        // Função para mostrar notificações
        function mostrarNotificacao(mensagem, tipo) {
            const notificacao = document.createElement('div');
            notificacao.className = `notificacao notificacao-${tipo}`;
            notificacao.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${tipo === 'success' ? 'var(--success-color)' : 
                            tipo === 'warning' ? 'var(--warning-color)' : 
                            tipo === 'error' ? 'var(--danger-color)' : 'var(--info-color)'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-lg);
                z-index: 9999;
                animation: slideInRight 0.3s ease-out;
                max-width: 300px;
                font-weight: 500;
            `;
            
            notificacao.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-${tipo === 'success' ? 'check-circle' : 
                                      tipo === 'warning' ? 'exclamation-triangle' : 
                                      tipo === 'error' ? 'times-circle' : 'info-circle'}"></i>
                    <span>${mensagem}</span>
                </div>
            `;
            
            document.body.appendChild(notificacao);
            
            setTimeout(() => {
                notificacao.remove();
            }, 3000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Botão abrir carrinho
            document.getElementById('abrir-carrinho-btn').addEventListener('click', abrirCarrinho);
            
            // Busca
            const searchBox = document.querySelector('.search-box');
            const searchBtn = document.querySelector('.search-btn');
            
            searchBtn.addEventListener('click', function() {
                const termo = searchBox.value.trim();
                if (termo) {
                    mostrarNotificacao(`Buscando por: "${termo}"`, 'info');
                    // Aqui você pode implementar a lógica de busca
                    // window.location.href = `busca.php?q=${encodeURIComponent(termo)}`;
                }
            });
            
            searchBox.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchBtn.click();
                }
            });
            
            // Cards do dashboard
            const dashCards = document.querySelectorAll('.dash-card');
            dashCards.forEach(card => {
                card.addEventListener('click', function() {
                    const titulo = this.querySelector('h3').textContent;
                    mostrarNotificacao(`Acessando: ${titulo}`, 'info');
                    // Aqui você pode implementar a navegação para cada seção
                });
            });
        });

        // Animação de entrada dos cards
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = Math.random() * 0.3 + 's';
                    entry.target.classList.add('visible');
                }
            });
        });

        document.querySelectorAll('.produto-card, .dash-card').forEach(card => {
            observer.observe(card);
        });

        // Adicionar estilos para animações
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            .notificacao {
                animation: slideInRight 0.3s ease-out;
            }
            
            .produto-card, .dash-card {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.6s ease-out;
            }
            
            .produto-card.visible, .dash-card.visible {
                opacity: 1;
                transform: translateY(0);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>