<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_tipo'] !== 'vendedor' && $_SESSION['user_tipo'] !== 'ambos')) {
    header('Location: login_cadastro.php');
    exit;
}

// Banco de dados
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

$msg = '';
$msg_type = '';

// Cadastro do produto
if (isset($_POST['cadastrar_produto'])) {
    $nome         = trim($_POST['nome']);
    $descricao    = trim($_POST['descricao']);
    $preco        = floatval($_POST['preco']);
    $categoria    = trim($_POST['categoria']);
    $estoque      = intval($_POST['estoque']);
    $pagamento    = isset($_POST['pagamento']) ? implode(',', $_POST['pagamento']) : '';
    $rastreamento = trim($_POST['rastreamento']);
    $peso         = floatval($_POST['peso']);
    $dimensoes    = trim($_POST['dimensoes']);
    $envio        = trim($_POST['envio']);
    $garantia     = trim($_POST['garantia']);
    $user_id      = $_SESSION['user_id'];

    // Upload de imagem (simples)
    $imagem = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $img_tmp  = $_FILES['imagem']['tmp_name'];
        $img_name = uniqid() . '_' . basename($_FILES['imagem']['name']);
        $dest     = 'uploads/' . $img_name;
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        if (move_uploaded_file($img_tmp, $dest)) {
            $imagem = $dest;
        }
    }

    // Inserir produto
    $stmt = $pdo->prepare('
        INSERT INTO produtos 
        (nome, descricao, preco, categoria, estoque, metodo_pagamento, rastreamento, imagem, id_vendedor, peso, dimensoes, envio, garantia)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $ok = $stmt->execute([
        $nome, $descricao, $preco, $categoria, $estoque, $pagamento, $rastreamento, $imagem, $user_id, $peso, $dimensoes, $envio, $garantia
    ]);
    if ($ok) {
        $msg = "Produto cadastrado com sucesso!";
        $msg_type = "success";
    } else {
        $msg = "Erro ao cadastrar produto!";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - MarketPlace Brasil</title>
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
            max-width: 570px;
            margin: 40px auto;
            background: white;
            border-radius: 18px;
            box-shadow: 0 12px 40px rgba(102, 126, 234, 0.14);
            padding: 36px 24px 44px 24px;
        }
        h2 {
            color: #667eea;
            text-align: center;
            margin-bottom: 30px;
        }
        .msg {
            padding: 12px 18px;
            border-radius: 8px;
            text-align: center;
            font-size: 15px;
            margin-bottom: 18px;
            font-weight: 500;
        }
        .msg.success { background: #e3ffe3; color: #13ce66; border: 1px solid #a3ffb4;}
        .msg.error { background: #fff2f1; color: #f44336; border: 1px solid #ffbaba;}
        form { display: flex; flex-direction: column; gap: 18px;}
        label { font-weight: 600; color: #667eea; margin-bottom: 2px;}
        input, select, textarea {
            padding: 11px 12px;
            border-radius: 8px;
            border: 1.5px solid #e0e0e0;
            font-size: 15px;
            outline: none;
            font-family: inherit;
            background: #f7f8fa;
            transition: border .2s;
        }
        input:focus, select:focus, textarea:focus { border-color: #764ba2;}
        textarea { min-height: 68px; resize: vertical;}
        .row { display: flex; gap: 16px;}
        .row > * { flex: 1;}
        .checkboxes { display: flex; flex-wrap: wrap; gap: 10px;}
        .checkboxes label { font-weight: 400; color: #555;}
        .imagem-previa {
            margin-top: 4px;
            max-width: 140px;
            max-height: 120px;
            border-radius: 5px;
            border: 1px solid #eee;
            display: none;
        }
        .submit-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-weight: bold;
            font-size: 18px;
            margin-top: 14px;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(102,126,234,0.08);
            transition: background .18s, box-shadow .16s;
        }
        .submit-btn:hover {
            background: linear-gradient(45deg, #564bb1, #6d479a);
            box-shadow: 0 8px 30px rgba(102,126,234,0.13);
        }
        .voltar-link {
            display: inline-block;
            margin-top: 18px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }
        .voltar-link:hover { color: #764ba2; text-decoration: underline;}
        @media (max-width: 700px) {
            .container { padding: 2vw 2vw 30px 2vw;}
            .row { flex-direction: column;}
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-box"></i> Cadastro de Produto</h2>
        <?php if ($msg): ?>
            <div class="msg <?= $msg_type ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <label for="nome">Nome do Produto</label>
            <input type="text" name="nome" id="nome" maxlength="120" required>

            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" required placeholder="Detalhe o produto, características e diferenciais..."></textarea>

            <div class="row">
                <div>
                    <label for="preco">Preço (R$)</label>
                    <input type="number" name="preco" id="preco" min="0.01" step="0.01" required>
                </div>
                <div>
                    <label for="estoque">Estoque</label>
                    <input type="number" name="estoque" id="estoque" min="1" step="1" required>
                </div>
            </div>
            
            <label for="categoria">Categoria</label>
            <select name="categoria" id="categoria" required>
                <option value="">Selecione</option>
                <option value="Eletrônicos">Eletrônicos</option>
                <option value="Moda">Moda</option>
                <option value="Casa & Jardim">Casa & Jardim</option>
                <option value="Esportes">Esportes</option>
                <option value="Automotivo">Automotivo</option>
                <option value="Bebês">Bebês</option>
                <option value="Outros">Outros</option>
            </select>

            <label>Métodos de Pagamento Aceitos</label>
            <div class="checkboxes">
                <label><input type="checkbox" name="pagamento[]" value="Cartão de Crédito"> Cartão de Crédito</label>
                <label><input type="checkbox" name="pagamento[]" value="PIX"> PIX</label>
                <label><input type="checkbox" name="pagamento[]" value="Boleto Bancário"> Boleto Bancário</label>
                <label><input type="checkbox" name="pagamento[]" value="Débito Online"> Débito Online</label>
                <label><input type="checkbox" name="pagamento[]" value="Marketplace Pay"> Marketplace Pay</label>
            </div>

            <label for="rastreamento">Código de Rastreamento (opcional)</label>
            <input type="text" name="rastreamento" id="rastreamento" maxlength="100" placeholder="Ex: AA123456789BR">

            <div class="row">
                <div>
                    <label for="peso">Peso (Kg)</label>
                    <input type="number" name="peso" id="peso" min="0" step="0.01" required>
                </div>
                <div>
                    <label for="dimensoes">Dimensões (cm - C x L x A)</label>
                    <input type="text" name="dimensoes" id="dimensoes" maxlength="40" placeholder="Ex: 30x20x10" required>
                </div>
            </div>

            <label for="envio">Método de Envio</label>
            <select name="envio" id="envio" required>
                <option value="">Selecione</option>
                <option value="Correios">Correios</option>
                <option value="Transportadora">Transportadora</option>
                <option value="Retirada em Mãos">Retirada em Mãos</option>
                <option value="Outro">Outro</option>
            </select>

            <label for="garantia">Garantia (opcional)</label>
            <input type="text" name="garantia" id="garantia" maxlength="100" placeholder="Ex: 12 meses">

            <label for="imagem">Foto do Produto</label>
            <input type="file" name="imagem" id="imagem" accept="image/*" onchange="previewImagem(event)">
            <img src="#" alt="Prévia da imagem" class="imagem-previa" id="img-preview">

            <button class="submit-btn" type="submit" name="cadastrar_produto"><i class="fas fa-plus"></i> Cadastrar Produto</button>
        </form>
        <a href="painel_vendedor.php" class="voltar-link"><i class="fas fa-arrow-left"></i> Voltar ao painel</a>
    </div>
    <script>
        function previewImagem(event) {
            const input = event.target;
            const preview = document.getElementById('img-preview');
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "#";
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>