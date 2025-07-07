<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_tipo'] !== 'vendedor' && $_SESSION['user_tipo'] !== 'ambos')) {
    header('Location: login_cadastro.php');
    exit;
}

// Conexão ao banco
$host = 'localhost';
$db   = 'marketplace';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Erro ao conectar no banco: ' . $e->getMessage());
}

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
$pedidos = 0; // Coloque consulta real se implementar pedidos
$avaliacoes = 0; // Coloque consulta real se implementar avaliações
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel do Vendedor - MarketPlace Brasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 980px;
            margin: 40px auto;
            background: white;
            border-radius: 18px;
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.14);
            padding: 32px 24px 48px 24px;
        }
        .user-header {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-bottom: 28px;
        }
        .user-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
            font-weight: bold;
        }
        .user-info {
            flex: 1;
        }
        .user-info h2 {
            margin: 0 0 5px 0;
            color: #667eea;
            font-size: 26px;
            font-weight: bold;
        }
        .user-info p {
            color: #777;
            font-size: 15px;
            margin: 0;
        }
        .logout-btn, .voltar-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 11px 24px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-left: 10px;
        }
        .logout-btn:hover, .voltar-btn:hover {
            opacity: 0.9;
        }
        .user-actions {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .user-header-divider {
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            opacity: 0.13;
            border-radius: 2px;
            margin-bottom: 20px;
            margin-top: -8px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 26px;
        }
        .dash-card {
            background: #f7f8fa;
            border-radius: 14px;
            padding: 34px 18px 22px 18px;
            box-shadow: 0 8px 22px rgba(102, 126, 234, 0.07);
            text-align: center;
            transition: box-shadow 0.2s, transform 0.15s;
            cursor: pointer;
            border: 2px solid transparent;
            position: relative;
            min-height: 160px;
        }
        .dash-card:hover {
            box-shadow: 0 18px 36px rgba(102, 126, 234, 0.15);
            border-color: #667eea;
            transform: translateY(-6px) scale(1.025);
        }
        .dash-card .icon {
            font-size: 40px;
            color: #667eea;
            margin-bottom: 16px;
        }
        .dash-card h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
            color: #222;
        }
        .dash-card .count {
            font-size: 22px;
            color: #764ba2;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .dash-card .desc {
            color: #555;
            font-size: 15px;
        }
        .produtos-lista {
            margin-top: 40px;
        }
        .produtos-lista h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .produtos-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .produtos-table th, .produtos-table td {
            padding: 10px 8px;
            border: 1px solid #eee;
            text-align: left;
        }
        .produtos-table th {
            background: #f4f7fe;
            color: #667eea;
        }
        .produtos-table td img {
            max-width: 55px;
            max-height: 55px;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .add-produto-btn {
            display: inline-block;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-radius: 8px;
            padding: 11px 28px;
            font-size: 17px;
            font-weight: bold;
            text-decoration: none;
            margin-bottom: 22px;
            margin-top: 10px;
            box-shadow: 0 4px 16px rgba(102,126,234,0.08);
            transition: background .18s, box-shadow .16s;
        }
        .add-produto-btn:hover {
            background: linear-gradient(45deg, #564bb1, #6d479a);
            box-shadow: 0 8px 30px rgba(102,126,234,0.13);
        }
        @media (max-width: 700px) {
            .container { padding: 10px 2vw 30px 2vw; }
            .dashboard { gap: 14px; }
            .dash-card { min-height: 120px; }
            .user-header { flex-direction: column; align-items: flex-start; }
            .user-actions { margin-top: 12px; }
            .logout-btn, .voltar-btn { margin-left: 0; margin-top: 8px; width: 100%; }
            .produtos-table th, .produtos-table td { font-size: 11px; padding: 5px 2px;}
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-header">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h2>Olá, <?= htmlspecialchars($user_data['nome']) ?>!</h2>
                <p><?= htmlspecialchars($user_data['email']) ?></p>
            </div>
            <div class="user-actions">
                <form method="post" style="display:inline;">
                    <button class="logout-btn" type="submit" name="logout"><i class="fas fa-sign-out-alt"></i> Sair</button>
                </form>
                <a href="index.php" class="voltar-btn" style="text-decoration:none; display:inline-block;">
                    <i class="fas fa-arrow-left"></i> Voltar para Home
                </a>
            </div>
        </div>
        <div class="user-header-divider"></div>
        <div class="dashboard">
            <div class="dash-card" onclick="window.location='cadastrar_produto.php';">
                <div class="icon"><i class="fas fa-plus"></i></div>
                <h3>Cadastrar Produto</h3>
                <div class="desc">Adicione novos produtos para vender no marketplace.</div>
            </div>
            <div class="dash-card" onclick="window.location='meus_pedidos.php';">
                <div class="icon"><i class="fas fa-box-open"></i></div>
                <div class="count"><?= $pedidos ?></div>
                <h3>Compra de Clientes</h3>
                <div class="desc">Veja compras realizadas pelos clientes.</div>
            </div>
            <div class="dash-card" onclick="window.location='minhas_avaliacoes.php';">
                <div class="icon"><i class="fas fa-star"></i></div>
                <div class="count"><?= $avaliacoes ?></div>
                <h3>Minhas Avaliações</h3>
                <div class="desc">Veja como os clientes avaliam seus produtos.</div>
            </div>
            <div class="dash-card" onclick="window.location='meus_dados.php';">
                <div class="icon"><i class="fas fa-id-card"></i></div>
                <h3>Meus Dados</h3>
                <div class="desc">Atualize nome, email, senha, dados bancários, endereço comercial.</div>
            </div>
            <div class="dash-card" onclick="window.location='central_ajuda.php';">
                <div class="icon"><i class="fas fa-headset"></i></div>
                <h3>Central de Ajuda</h3>
                <div class="desc">Dúvidas? Acesse nossos canais de atendimento.</div>
            </div>
        </div>

        <div class="produtos-lista">
            <table class="produtos-table">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Categoria</th>
                        <th>Métodos de Pagamento</th>
                        <th>Rastreamento</th>
                        <th>Envio</th>
                        <th>Peso</th>
                        <th>Dimensões</th>
                        <th>Garantia</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_vendedor = ?");
                $stmt->execute([$user_id]);
                $produtos = $stmt->fetchAll();
                if (is_array($produtos) && count($produtos) > 0):
                    foreach ($produtos as $produto):
                ?>
                    <tr>
                        <td>
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do produto">
                            <?php else: ?>
                                <span style="color:#aaa;">Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= (int)$produto['estoque'] ?></td>
                        <td><?= htmlspecialchars($produto['categoria']) ?></td>
                        <td><?= htmlspecialchars($produto['metodo_pagamento']) ?></td>
                        <td><?= htmlspecialchars($produto['rastreamento']) ?></td>
                        <td><?= htmlspecialchars($produto['envio']) ?></td>
                        <td><?= number_format($produto['peso'], 2, ',', '.') ?> kg</td>
                        <td><?= htmlspecialchars($produto['dimensoes']) ?></td>
                        <td><?= htmlspecialchars($produto['garantia']) ?></td>
                    </tr>
                <?php
                    endforeach;
                else:
                ?>
                    <tr>
                        <td colspan="11" style="text-align:center;color:#aaa;">Nenhum produto cadastrado ainda.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>