<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_tipo'] !== 'comprador' && $_SESSION['user_tipo'] !== 'ambos')) {
    header('Location: login_cadastro.php');
    exit;
}

// Conexão com banco
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

// Exemplo: quantidade de pedidos fictício. Você pode trocar isso para buscar do banco real.
$pedidos = 8;
$enderecos = 2;

// Produtos em destaque (6 últimos cadastrados)
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ... todo seu CSS igual ... */
        .carrinho-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(34, 34, 34, 0.30);
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s;
        }
        .carrinho-backdrop.active {
            opacity: 1;
            pointer-events: auto;
        }
        .sidebar-carrinho {
            position: fixed;
            top: 0;
            right: -440px;
            width: 420px;
            max-width: 97vw;
            height: 100vh;
            background: #fff;
            box-shadow: -2px 0 24px rgba(102,126,234,0.17);
            z-index: 2100;
            display: flex;
            flex-direction: column;
            transition: right 0.33s cubic-bezier(.55,.13,.43,1.13);
        }
        .sidebar-carrinho.active { right: 0; }
        .sidebar-carrinho-header {
            padding: 20px 28px 12px 28px;
            border-bottom: 1.5px solid #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(90deg, #667eea10 0%, #764ba215 100%);
        }
        .sidebar-carrinho-header h2 {
            margin: 0;
            font-size: 22px;
            color: #667eea;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-carrinho-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #888;
            cursor: pointer;
            transition: color 0.18s;
            padding: 4px;
        }
        .sidebar-carrinho-close:hover { color: #f44336; }
        .sidebar-carrinho-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 28px 0 28px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .carrinho-vazio {
            text-align: center;
            color: #bbb;
            padding: 60px 0 30px 0;
            font-size: 17px;
        }
        .carrinho-lista {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .carrinho-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f3f3f3;
            position: relative;
        }
        .carrinho-item:last-child { border-bottom: none; }
        .carrinho-item-img {
            width: 68px;
            height: 68px;
            border-radius: 8px;
            object-fit: cover;
            background: #f6f6fa;
            border: 1.5px solid #eee;
        }
        .carrinho-item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .carrinho-item-title {
            font-size: 16px;
            font-weight: 600;
            color: #2e2e53;
        }
        .carrinho-item-details {
            font-size: 13px;
            color: #888;
        }
        .carrinho-item-preco {
            font-size: 15px;
            color: #764ba2;
            font-weight: 700;
        }
        .carrinho-item-qtd {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-top: 2px;
        }
        .qtd-btn {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1.5px solid #e0e0e0;
            background: #f6f7fb;
            color: #667eea;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.18s, border 0.18s;
        }
        .qtd-btn:hover {
            background: #eceaff;
            border-color: #667eea;
        }
        .carrinho-item-remove {
            position: absolute;
            top: 4px;
            right: 0;
            background: none;
            border: none;
            color: #f44336;
            font-size: 16px;
            cursor: pointer;
            padding: 4px;
            transition: color 0.15s;
        }
        .carrinho-item-remove:hover { color: #b80000; }
        .sidebar-carrinho-footer {
            padding: 22px 28px 22px 28px;
            border-top: 1.5px solid #f1f1f1;
            background: #fff;
            box-shadow: 0 -2px 16px rgba(102,126,234,0.07);
        }
        .carrinho-resumo {
            font-size: 16px;
            margin-bottom: 13px;
        }
        .carrinho-resumo .label {
            color: #777; font-weight: 500;
        }
        .carrinho-resumo .valor {
            color: #667eea;
            font-weight: bold;
            float: right;
        }
        .carrinho-btn-finalizar {
            width: 100%;
            padding: 14px 0;
            border-radius: 10px;
            border: none;
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #fff;
            font-size: 19px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 5px 18px rgba(102,126,234,0.10);
            margin-top: 4px;
            transition: background 0.19s, box-shadow 0.14s;
        }
        .carrinho-btn-finalizar:hover {
            background: linear-gradient(45deg, #564bb1, #6d479a);
            box-shadow: 0 8px 30px rgba(102,126,234,0.13);
        }
        .carrinho-link-resumo {
            color: #667eea;
            font-size: 13px;
            text-decoration: underline;
            display: inline-block;
            margin-bottom: 10px;
            cursor: pointer;
        }
        @media (max-width: 600px) {
            .sidebar-carrinho, .sidebar-carrinho-header, .sidebar-carrinho-content, .sidebar-carrinho-footer {
                padding-left: 10px;
                padding-right: 10px;
            }
            .sidebar-carrinho { width: 98vw; max-width: none; }
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
                <!-- Carrinho botão ao lado do sair -->
                <a href="javascript:void(0)" class="voltar-btn" id="abrir-carrinho-btn" style="margin-left:10px; background: linear-gradient(45deg, #13ce66, #45a247);">
                    <i class="fas fa-shopping-cart"></i> Carrinho
                </a>
            </div>
        </div>
        <div class="user-header-divider"></div>
        <div class="dashboard">
            <!-- ... (dashboard igual) ... -->
        </div>
        <!-- Bloco de busca abaixo das 4 caixas -->
        <div class="search-container">
            <input type="text" class="search-box" placeholder="Buscar produtos, marcas, lojas...">
            <button class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <!-- Produtos em Destaque -->
        <div style="margin-top:48px;">
            <h2 style="color:#667eea;text-align:center;font-size:28px;margin-bottom:28px;font-weight:700;">Produtos em Destaque</h2>
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
                                <button class="produto-btn">Comprar Agora</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="color:#aaa;text-align:center;width:100%;">Nenhum produto em destaque ainda.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar do Carrinho de Compras -->
    <div class="carrinho-backdrop" id="carrinho-backdrop"></div>
    <aside class="sidebar-carrinho" id="sidebar-carrinho" aria-label="Carrinho de compras" tabindex="-1">
        <div class="sidebar-carrinho-header">
            <h2><i class="fas fa-shopping-cart"></i> Seu Carrinho</h2>
            <button class="sidebar-carrinho-close" id="carrinho-fechar" aria-label="Fechar carrinho">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="sidebar-carrinho-content">
            <div class="carrinho-vazio" id="carrinho-vazio" style="display:none;">
                <i class="fas fa-shopping-basket fa-2x" style="color:#bbb;margin-bottom:8px;"></i><br>
                Seu carrinho está vazio.<br>
                Adicione produtos para visualizar aqui.
            </div>
            <div class="carrinho-lista" id="carrinho-lista">
                <!-- Itens do carrinho serão renderizados aqui via JS -->
            </div>
        </div>
        <div class="sidebar-carrinho-footer">
            <div class="carrinho-resumo">
                <span class="label">Subtotal</span>
                <span class="valor" id="carrinho-subtotal">R$ 0,00</span>
            </div>
            <div class="carrinho-resumo" style="font-size:15px;">
                <span class="label">Frete estimado</span>
                <span class="valor" id="carrinho-frete">R$ 0,00</span>
            </div>
            <div class="carrinho-resumo" style="font-size:18px;font-weight:700;">
                <span class="label">Total</span>
                <span class="valor" id="carrinho-total">R$ 0,00</span>
            </div>
            <button class="carrinho-btn-finalizar" id="btn-finalizar-carrinho">
                <i class="fas fa-credit-card"></i> Finalizar Compra
            </button>
        </div>
    </aside>
    <script>
        // Search functionality
        const searchBtn = document.querySelector('.search-btn');
        const searchBox = document.querySelector('.search-box');
        searchBtn.addEventListener('click', function() {
            const searchTerm = searchBox.value.trim();
            if (searchTerm) {
                alert(`Buscando por: ${searchTerm}`);
                // Aqui você implementaria a busca real (redirecionar, AJAX, etc)
            }
        });
        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') { searchBtn.click(); }
        });

        // Botão "Comprar Agora" (Adiciona ao carrinho)
        document.querySelectorAll('.produto-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const card = btn.closest('.produto-card');
                const titulo = card.querySelector('h3').textContent;
                const preco = Number(card.querySelector('.produto-preco').textContent.replace(/[^\d,]/g,'').replace(',','.'));
                let img = card.querySelector('img')?.src || '';
                if (!img) {
                    let ic = card.querySelector('.produto-img-wrap i');
                    if (ic) img = `https://via.placeholder.com/68x68?text=${ic.className.replace('fas fa-','').toUpperCase()}`;
                }
                adicionarAoCarrinho({
                    titulo,
                    preco,
                    img,
                    variante: '', // Pode ser adicionado suporte a variantes
                    vendedor: '', // Pode ser adicionado suporte ao nome do vendedor
                    qtd: 1
                });
            });
        });

        // Carrinho JS igual ao do index.php
        function formatPrice(valor) {
            return "R$ " + valor.toFixed(2).replace('.', ',');
        }

        let carrinho = JSON.parse(localStorage.getItem('carrinhoMPBR')) || [];

        function renderCarrinho() {
            const carrinhoLista = document.getElementById('carrinho-lista');
            const carrinhoVazio = document.getElementById('carrinho-vazio');
            carrinhoLista.innerHTML = '';
            if (!carrinho || carrinho.length === 0) {
                carrinhoVazio.style.display = 'block';
                return;
            }
            carrinhoVazio.style.display = 'none';
            carrinho.forEach((item, idx) => {
                let div = document.createElement('div');
                div.className = 'carrinho-item';
                div.innerHTML = `
                    <img src="${item.img || 'https://via.placeholder.com/68x68?text=IMG'}" class="carrinho-item-img" alt="Imagem do produto">
                    <div class="carrinho-item-info">
                        <span class="carrinho-item-title">${item.titulo}</span>
                        <span class="carrinho-item-details">
                            ${item.variante ? 'Variante: ' + item.variante + ' | ' : ''} 
                            ${item.vendedor ? 'Vendedor: ' + item.vendedor : ''}
                        </span>
                        <span class="carrinho-item-preco">${formatPrice(item.preco)} x ${item.qtd}</span>
                        <div class="carrinho-item-qtd">
                            <button class="qtd-btn" onclick="alterarQtdCarrinho(${idx}, -1)" aria-label="Diminuir quantidade">-</button>
                            <span>${item.qtd}</span>
                            <button class="qtd-btn" onclick="alterarQtdCarrinho(${idx}, 1)" aria-label="Aumentar quantidade">+</button>
                        </div>
                    </div>
                    <button class="carrinho-item-remove" onclick="removerItemCarrinho(${idx})" aria-label="Remover item">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                carrinhoLista.appendChild(div);
            });
            atualizarResumoCarrinho();
        }

        function atualizarResumoCarrinho() {
            let subtotal = 0;
            carrinho.forEach(item => subtotal += item.preco * item.qtd);
            let frete = subtotal > 300 ? 0 : (subtotal > 0 ? 24.90 : 0);
            let total = subtotal + frete;
            document.getElementById('carrinho-subtotal').textContent = formatPrice(subtotal);
            document.getElementById('carrinho-frete').textContent = formatPrice(frete);
            document.getElementById('carrinho-total').textContent = formatPrice(total);
        }

        function adicionarAoCarrinho(produto) {
            let idx = carrinho.findIndex(item => item.titulo === produto.titulo && item.variante === produto.variante);
            if (idx > -1) {
                carrinho[idx].qtd += produto.qtd || 1;
            } else {
                carrinho.push({...produto, qtd: produto.qtd || 1});
            }
            localStorage.setItem('carrinhoMPBR', JSON.stringify(carrinho));
            renderCarrinho();
            abrirCarrinho();
        }

        function alterarQtdCarrinho(idx, delta) {
            if (!carrinho[idx]) return;
            carrinho[idx].qtd += delta;
            if (carrinho[idx].qtd < 1) carrinho[idx].qtd = 1;
            localStorage.setItem('carrinhoMPBR', JSON.stringify(carrinho));
            renderCarrinho();
        }

        function removerItemCarrinho(idx) {
            if (!carrinho[idx]) return;
            carrinho.splice(idx, 1);
            localStorage.setItem('carrinhoMPBR', JSON.stringify(carrinho));
            renderCarrinho();
        }

        function abrirCarrinho() {
            document.getElementById('carrinho-backdrop').classList.add('active');
            document.getElementById('sidebar-carrinho').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function fecharCarrinho() {
            document.getElementById('carrinho-backdrop').classList.remove('active');
            document.getElementById('sidebar-carrinho').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Botão carrinho do painel comprador
            const abrirCarrinhoBtn = document.getElementById('abrir-carrinho-btn');
            if (abrirCarrinhoBtn) {
                abrirCarrinhoBtn.onclick = abrirCarrinho;
            }
            document.getElementById('carrinho-fechar').onclick = fecharCarrinho;
            document.getElementById('carrinho-backdrop').onclick = fecharCarrinho;
            document.getElementById('btn-finalizar-carrinho').onclick = function() {
                if (!carrinho || carrinho.length === 0) {
                    alert('Adicione produtos ao carrinho antes de finalizar a compra.');
                    return;
                }
                alert('Checkout não implementado.\nSimulação: ' + JSON.stringify(carrinho, null, 2));
                fecharCarrinho();
            };
            renderCarrinho();
        });

        // Permitir alterar quantidade/remover via HTML onclick
        window.alterarQtdCarrinho = alterarQtdCarrinho;
        window.removerItemCarrinho = removerItemCarrinho;
    </script>
</body>
</html>