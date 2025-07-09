<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_tipo'] !== 'vendedor' && $_SESSION['user_tipo'] !== 'ambos')) {
    header('Location: login_cadastro.php');
    exit;
}
require_once 'conexao.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();
if (!$user_data) {
    session_destroy();
    header('Location: login_cadastro.php');
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login_cadastro.php');
    exit;
}

$produtosQtd = $pdo->query("SELECT COUNT(*) FROM produtos WHERE id_vendedor = $user_id")->fetchColumn();
$pedidos = 12; // Exemplo
$avaliacoes = 4; // Exemplo
$vendas = 8; // Exemplo

// Buscar produtos recentes
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_vendedor = ? ORDER BY criado_em DESC LIMIT 6");
$stmt->execute([$user_id]);
$produtos_recentes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Vendedor - MarketPlace Brasil</title>
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            text-align: center;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .stat-card .icon {
            width: 2.5rem;
            height: 2.5rem;
            margin: 0 auto 1rem;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            transition: var(--transition);
        }

        .stat-card:hover .icon {
            transform: scale(1.1);
        }

        .stat-card .count {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-card .label {
            font-size: 0.875rem;
            color: var(--neutral-500);
            font-weight: 500;
        }

        /* Products Section */
        .produtos-section {
            margin-top: 3rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--neutral-800);
            margin-bottom: 2rem;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 1.5rem;
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
        }

        .produto-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
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

        .produto-estoque {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .produto-estoque.in-stock {
            background: rgba(5, 150, 105, 0.1);
            color: var(--success-color);
        }

        .produto-estoque.low-stock {
            background: rgba(217, 119, 6, 0.1);
            color: var(--warning-color);
        }

        .produto-estoque.out-of-stock {
            background: rgba(220, 38, 38, 0.1);
            color: var(--danger-color);
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

        .produto-actions {
            display: flex;
            gap: 0.5rem;
        }

        .produto-btn {
            flex: 1;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
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

        .produto-btn-secondary {
            background: var(--neutral-100);
            color: var(--neutral-700);
            border: 1px solid var(--neutral-200);
        }

        .produto-btn-secondary:hover {
            background: var(--neutral-200);
            color: var(--neutral-800);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--neutral-400);
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--neutral-300);
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--neutral-600);
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 2rem;
            color: var(--neutral-500);
        }

        .empty-state .btn {
            background: var(--gradient-primary);
            color: white;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
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

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dash-card {
                padding: 1.5rem;
            }

            .produtos-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
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
                    <i class="fas fa-store"></i>
                </div>
                <div class="user-info">
                    <h2>Olá, <?= htmlspecialchars($user_data['nome']) ?>!</h2>
                    <p><?= htmlspecialchars($user_data['email']) ?></p>
                </div>
                <div class="user-actions">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar para Home
                    </a>
                    <a href="cadastrar_produto.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Produto
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
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-box"></i></div>
                    <div class="count"><?= $produtosQtd ?></div>
                    <div class="label">Produtos</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="count"><?= $pedidos ?></div>
                    <div class="label">Pedidos</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-star"></i></div>
                    <div class="count"><?= $avaliacoes ?></div>
                    <div class="label">Avaliações</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-chart-line"></i></div>
                    <div class="count"><?= $vendas ?></div>
                    <div class="label">Vendas</div>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard">
                <div class="dash-card" onclick="window.location='minhas_avaliacoes.php';">
                    <div class="icon"><i class="fas fa-star"></i></div>
                    <h3>Minhas Avaliações</h3>
                    <div class="desc">Veja como os clientes avaliam seus produtos</div>
                </div>

                <div class="dash-card" onclick="window.location='relatorios.php';">
                    <div class="icon"><i class="fas fa-chart-bar"></i></div>
                    <h3>Relatórios</h3>
                    <div class="desc">Acompanhe suas vendas e performance</div>
                </div>

                <div class="dash-card" onclick="window.location='meus_dados.php';">
                    <div class="icon"><i class="fas fa-id-card"></i></div>
                    <h3>Meus Dados</h3>
                    <div class="desc">Atualize suas informações pessoais e comerciais</div>
                </div>

                <div class="dash-card" onclick="window.location='central_ajuda.php';">
                    <div class="icon"><i class="fas fa-headset"></i></div>
                    <h3>Central de Ajuda</h3>
                    <div class="desc">Dúvidas? Acesse nossos canais de atendimento</div>
                </div>
            </div>

            <!-- Products Section -->
            <div class="produtos-section">
                <h2 class="section-title">
                    <i class="fas fa-box"></i>
                    Produtos Recentes
                </h2>

                <?php if (is_array($produtos_recentes) && count($produtos_recentes) > 0): ?>
                    <div class="produtos-grid">
                        <?php foreach ($produtos_recentes as $produto): 
                            $estoque = (int)$produto['estoque'];
                            $stockClass = $estoque > 10 ? 'in-stock' : ($estoque > 0 ? 'low-stock' : 'out-of-stock');
                            $stockIcon = $estoque > 10 ? 'fas fa-check-circle' : ($estoque > 0 ? 'fas fa-exclamation-circle' : 'fas fa-exclamation-triangle');
                        ?>
                            <div class="produto-card">
                                <div class="produto-img-wrap">
                                    <?php if (!empty($produto['imagem'])): ?>
                                        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                                    <?php else: ?>
                                        <div class="produto-placeholder">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="produto-info">
                                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                                    <div class="produto-preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
                                    <div class="produto-estoque <?= $stockClass ?>">
                                        <i class="<?= $stockIcon ?>"></i>
                                        <?= $estoque ?> em estoque
                                    </div>
                                    <div class="produto-detalhes">
                                        <ul class="produto-detalhes-list">
                                            <li><strong>Categoria:</strong> <?= htmlspecialchars($produto['categoria']) ?></li>
                                            <li><strong>SKU:</strong> <?= isset($produto['sku']) ? htmlspecialchars($produto['sku']) : 'N/A' ?></li>
                                            <li><strong>Criado em:</strong> <?= date('d/m/Y', strtotime($produto['criado_em'])) ?></li>
                                        </ul>
                                    </div>
                                    <div class="produto-actions">
                                        <button class="produto-btn" onclick="window.location='editar_produto.php?id=<?= $produto['id'] ?>';">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="produto-btn produto-btn-secondary" onclick="window.location='excluir_produto.php?id=<?= $produto['id'] ?>';">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open icon"></i>
                        <h3>Nenhum produto cadastrado</h3>
                        <p>Você ainda não cadastrou nenhum produto. Clique no botão abaixo para adicionar um novo produto.</p>
                        <a href="cadastrar_produto.php" class="btn">Cadastrar Produto</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>