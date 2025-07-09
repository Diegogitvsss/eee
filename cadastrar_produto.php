<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['user_tipo'] !== 'vendedor' && $_SESSION['user_tipo'] !== 'ambos')) {
    header('Location: login_cadastro.php');
    exit;
}
require_once 'conexao.php';

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
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-light: #3b82f6;
    --primary-lighter: #dbeafe;
    --primary-alpha: rgba(37, 99, 235, 0.1);
    --secondary: #0f172a;
    --secondary-light: #334155;
    --secondary-lighter: #64748b;
    --accent: #f59e0b;
    --background: #f8fafc;
    --surface: #ffffff;
    --surface-elevated: #fefefe;
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-muted: #64748b;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --success: #10b981;
    --success-light: #d1fae5;
    --danger: #ef4444;
    --danger-light: #fef2f2;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --radius: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: var(--background);
    color: var(--text-primary);
    line-height: 1.6;
    min-height: 100vh;
    padding: 2rem 1rem;
}

.main-container {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    gap: 2rem;
    grid-template-columns: 1fr;
}

.header {
    background: var(--surface);
    border-radius: var(--radius-xl);
    padding: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 3.5rem;
    height: 3.5rem;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: var(--shadow);
}

.header-text h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.header-text p {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.form-container {
    background: var(--surface);
    border-radius: var(--radius-xl);
    padding: 2.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.msg {
    padding: 1rem 1.25rem;
    border-radius: var(--radius-lg);
    margin-bottom: 2rem;
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid transparent;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.msg.success {
    background: var(--success-light);
    color: var(--success);
    border-color: var(--success);
}

.msg.error {
    background: var(--danger-light);
    color: var(--danger);
    border-color: var(--danger);
}

.form-grid {
    display: grid;
    gap: 2rem;
}

.form-section {
    background: var(--surface-elevated);
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border-light);
}

.form-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-section h3 i {
    color: var(--primary);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.label-required::after {
    content: '*';
    color: var(--danger);
    font-weight: 700;
}

input, select, textarea {
    padding: 0.875rem 1rem;
    border-radius: var(--radius);
    border: 1px solid var(--border);
    font-size: 0.9375rem;
    outline: none;
    font-family: inherit;
    background: var(--surface);
    transition: var(--transition);
    color: var(--text-primary);
}

input:focus, select:focus, textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-alpha);
}

input::placeholder, textarea::placeholder {
    color: var(--text-muted);
}

textarea {
    min-height: 5rem;
    resize: vertical;
}

select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1rem;
    padding-right: 2.5rem;
    cursor: pointer;
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    cursor: pointer;
    transition: var(--transition);
}

.checkbox-item:hover {
    border-color: var(--primary);
    background: var(--primary-lighter);
}

.checkbox-item input[type="checkbox"] {
    width: 1.125rem;
    height: 1.125rem;
    accent-color: var(--primary);
    margin: 0;
    cursor: pointer;
}

.checkbox-item label {
    font-weight: 400;
    color: var(--text-secondary);
    cursor: pointer;
    margin: 0;
}

.file-input-container {
    position: relative;
}

.file-input {
    padding: 2rem;
    border: 2px dashed var(--border);
    border-radius: var(--radius-lg);
    background: var(--background);
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
}

.file-input:hover {
    border-color: var(--primary);
    background: var(--primary-lighter);
}

.file-input i {
    font-size: 2rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.file-input-text {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.image-preview {
    margin-top: 1rem;
    max-width: 200px;
    max-height: 200px;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    display: none;
    object-fit: cover;
    box-shadow: var(--shadow);
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
    margin-top: 2rem;
}

.btn {
    padding: 0.875rem 1.5rem;
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    border: none;
    outline: none;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    box-shadow: var(--shadow);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-lg);
}

.btn-secondary {
    background: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border);
}

.btn-secondary:hover {
    background: var(--background);
    border-color: var(--primary);
    color: var(--primary);
}

.input-group {
    position: relative;
}

.input-group .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.875rem;
}

.input-group input {
    padding-left: 2.5rem;
}

.price-input {
    position: relative;
}

.price-input::before {
    content: 'R$';
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-weight: 600;
    font-size: 0.875rem;
    z-index: 1;
}

.price-input input {
    padding-left: 2.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding: 1rem;
    }
    
    .main-container {
        gap: 1rem;
    }
    
    .header,
    .form-container {
        padding: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .header-text h1 {
        font-size: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .checkbox-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.main-container {
    animation: fadeIn 0.6s ease-out;
}

/* Form validation styles */
.form-group.error input,
.form-group.error select,
.form-group.error textarea {
    border-color: var(--danger);
    background: var(--danger-light);
}

.form-group.success input,
.form-group.success select,
.form-group.success textarea {
    border-color: var(--success);
}

.error-message {
    color: var(--danger);
    font-size: 0.75rem;
    margin-top: 0.25rem;
    display: none;
}

.form-group.error .error-message {
    display: block;
}

/* Loading state */
.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-primary:disabled::after {
    content: '';
    position: absolute;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    right: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="header-text">
                    <h1>Cadastro de Produto</h1>
                    <p>Preencha as informações para adicionar um novo produto ao marketplace</p>
                </div>
            </div>
        </div>

        <div class="form-container">
            <?php if ($msg): ?>
                <div class="msg <?= $msg_type ?>">
                    <i class="fas fa-<?= $msg_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                    <span><?= htmlspecialchars($msg) ?></span>
                </div>
            <?php endif; ?>
            
            <form class="form-grid" method="POST" enctype="multipart/form-data">
                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Informações Básicas</h3>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="nome" class="label-required">Nome do Produto</label>
                            <input type="text" name="nome" id="nome" maxlength="120" required placeholder="Digite o nome do produto">
                            <div class="error-message">Nome do produto é obrigatório</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="descricao" class="label-required">Descrição</label>
                            <textarea name="descricao" id="descricao" required placeholder="Descreva o produto, suas características e diferenciais..."></textarea>
                            <div class="error-message">Descrição é obrigatória</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="categoria" class="label-required">Categoria</label>
                            <select name="categoria" id="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Eletrônicos">Eletrônicos</option>
                                <option value="Moda">Moda</option>
                                <option value="Casa & Jardim">Casa & Jardim</option>
                                <option value="Esportes">Esportes</option>
                                <option value="Automotivo">Automotivo</option>
                                <option value="Bebês">Bebês</option>
                                <option value="Outros">Outros</option>
                            </select>
                            <div class="error-message">Categoria é obrigatória</div>
                        </div>
                        <div class="form-group">
                            <label for="garantia">Garantia</label>
                            <input type="text" name="garantia" id="garantia" maxlength="100" placeholder="Ex: 12 meses">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-dollar-sign"></i> Preço e Estoque</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="preco" class="label-required">Preço</label>
                            <div class="price-input">
                                <input type="number" name="preco" id="preco" min="0.01" step="0.01" required placeholder="0,00">
                            </div>
                            <div class="error-message">Preço é obrigatório</div>
                        </div>
                        <div class="form-group">
                            <label for="estoque" class="label-required">Quantidade em Estoque</label>
                            <input type="number" name="estoque" id="estoque" min="1" step="1" required placeholder="0">
                            <div class="error-message">Quantidade em estoque é obrigatória</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-credit-card"></i> Métodos de Pagamento</h3>
                    <div class="checkbox-grid">
                        <div class="checkbox-item">
                            <input type="checkbox" name="pagamento[]" value="Cartão de Crédito" id="cartao">
                            <label for="cartao">Cartão de Crédito</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="pagamento[]" value="PIX" id="pix">
                            <label for="pix">PIX</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="pagamento[]" value="Boleto Bancário" id="boleto">
                            <label for="boleto">Boleto Bancário</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="pagamento[]" value="Débito Online" id="debito">
                            <label for="debito">Débito Online</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" name="pagamento[]" value="Marketplace Pay" id="marketplace">
                            <label for="marketplace">Marketplace Pay</label>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-shipping-fast"></i> Envio e Logística</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="peso" class="label-required">Peso (Kg)</label>
                            <input type="number" name="peso" id="peso" min="0" step="0.01" required placeholder="0,00">
                            <div class="error-message">Peso é obrigatório</div>
                        </div>
                        <div class="form-group">
                            <label for="dimensoes" class="label-required">Dimensões (cm)</label>
                            <input type="text" name="dimensoes" id="dimensoes" maxlength="40" placeholder="Ex: 30x20x10" required>
                            <div class="error-message">Dimensões são obrigatórias</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="envio" class="label-required">Método de Envio</label>
                            <select name="envio" id="envio" required>
                                <option value="">Selecione o método de envio</option>
                                <option value="Correios">Correios</option>
                                <option value="Transportadora">Transportadora</option>
                                <option value="Retirada em Mãos">Retirada em Mãos</option>
                                <option value="Outro">Outro</option>
                            </select>
                            <div class="error-message">Método de envio é obrigatório</div>
                        </div>
                        <div class="form-group">
                            <label for="rastreamento">Código de Rastreamento</label>
                            <input type="text" name="rastreamento" id="rastreamento" maxlength="100" placeholder="Ex: AA123456789BR">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3><i class="fas fa-image"></i> Imagem do Produto</h3>
                    <div class="form-group">
                        <label for="imagem">Foto do Produto</label>
                        <div class="file-input-container">
                            <input type="file" name="imagem" id="imagem" accept="image/*" style="display: none;" onchange="previewImagem(event)">
                            <div class="file-input" onclick="document.getElementById('imagem').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div class="file-input-text">
                                    <strong>Clique para selecionar</strong> ou arraste uma imagem aqui
                                    <br><small>PNG, JPG, JPEG até 5MB</small>
                                </div>
                            </div>
                        </div>
                        <img src="#" alt="Prévia da imagem" class="image-preview" id="img-preview">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="painel_vendedor.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Voltar ao Painel
                    </a>
                    <button type="submit" name="cadastrar_produto" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Cadastrar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImagem(event) {
            const input = event.target;
            const preview = document.getElementById('img-preview');
            const fileInput = document.querySelector('.file-input');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    fileInput.innerHTML = `
                        <i class="fas fa-check-circle" style="color: var(--success);"></i>
                        <div class="file-input-text">
                            <strong>Imagem selecionada</strong>
                            <br><small>${input.files[0].name}</small>
                        </div>
                    `;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "#";
                preview.style.display = 'none';
            }
        }

        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        validateField(this);
                    }
                });
            });
            
            form.addEventListener('submit', function(e) {
                let isValid = true;
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
        
        function validateField(field) {
            const formGroup = field.closest('.form-group');
            
            if (field.value.trim() === '') {
                formGroup.classList.add('error');
                formGroup.classList.remove('success');
                return false;
            } else {
                formGroup.classList.remove('error');
                formGroup.classList.add('success');
                return true;
            }
        }
    </script>
</body>
</html>