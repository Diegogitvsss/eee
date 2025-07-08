<?php
session_start();
$host = 'localhost';
$db   = 'marketplace';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Conexão com o banco de dados usando PDO
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

// Cadastro
if (isset($_POST['register'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'comprador';

    if ($tipo !== 'comprador' && $tipo !== 'vendedor' && $tipo !== 'ambos') {
        $msg = 'Tipo de conta inválido!';
        $msg_type = 'error';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $msg = 'E-mail já cadastrado!';
            $msg_type = 'error';
        } else {
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$nome, $email, $senha, $tipo])) {
                $msg = 'Cadastro realizado com sucesso! Faça login.';
                $msg_type = 'success';
            } else {
                $msg = 'Erro ao cadastrar!';
                $msg_type = 'error';
            }
        }
    }
}

// Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare('SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['user_tipo'] = $user['tipo'];
        // Redirecionamento conforme o tipo de conta
        if ($user['tipo'] === 'comprador') {
            header('Location: painel_comprador.php');
        } elseif ($user['tipo'] === 'vendedor') {
            header('Location: painel_vendedor.php');
        } else {
            header('Location: index.php'); // Ambos ou outro tipo
        }
        exit;
    } else {
        $msg = 'E-mail ou senha inválidos!';
        $msg_type = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Entrar ou Cadastrar - MarketPlace Brasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts & FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-light: #3b82f6;
    --primary-lighter: #dbeafe;
    --secondary: #1e293b;
    --secondary-light: #475569;
    --accent: #f59e0b;
    --background: #f8fafc;
    --surface: #ffffff;
    --text-primary: #0f172a;
    --text-secondary: #475569;
    --text-muted: #64748b;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --success: #059669;
    --success-light: #d1fae5;
    --danger: #dc2626;
    --danger-light: #fef2f2;
    --warning: #d97706;
    --warning-light: #fef3c7;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --radius-sm: 0.375rem;
    --radius: 0.5rem;
    --radius-md: 0.75rem;
    --radius-lg: 1rem;
    --radius-xl: 1.5rem;
    --transition: all 0.15s ease-in-out;
    --transition-slow: all 0.3s ease-in-out;
}

* {
    box-sizing: border-box;
}

html, body {
    height: 100%;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: var(--text-primary);
    line-height: 1.6;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    overflow-x: hidden;
}

.main-card {
    background: var(--surface);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-xl);
    max-width: 420px;
    width: 100%;
    padding: 2.5rem 2rem 2rem;
    position: relative;
    border: 1px solid var(--border-light);
    animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(2rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.voltar-btn {
    position: absolute;
    left: 1.5rem;
    top: 1.5rem;
    background: var(--surface);
    color: var(--primary);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    z-index: 10;
    box-shadow: var(--shadow-sm);
}

.voltar-btn:hover {
    background: var(--primary);
    color: var(--surface);
    border-color: var(--primary);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.voltar-btn i {
    font-size: 0.75rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-top: 1rem;
}

.auth-header .logo {
    width: 3.5rem;
    height: 3.5rem;
    background: var(--primary-lighter);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: var(--primary);
    font-size: 1.5rem;
}

.auth-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.025em;
}

.auth-header p {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0;
    font-weight: 400;
}

.msg {
    padding: 0.875rem 1rem;
    border-radius: var(--radius);
    text-align: center;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
    border: 1px solid transparent;
}

.msg.error {
    background: var(--danger-light);
    color: var(--danger);
    border-color: #fecaca;
}

.msg.success {
    background: var(--success-light);
    color: var(--success);
    border-color: #a7f3d0;
}

form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-left: 0.125rem;
}

.input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--background);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    transition: var(--transition);
    min-height: 3rem;
}

.input-wrap:focus-within {
    border-color: var(--primary);
    background: var(--surface);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.input-icon,
.select-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    margin-left: 0.75rem;
    border-radius: var(--radius);
    background: var(--primary-lighter);
    color: var(--primary);
    font-size: 1rem;
    flex-shrink: 0;
}

.form-group input,
.form-group select {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 0.9375rem;
    color: var(--text-primary);
    padding: 0.75rem 0.75rem 0.75rem 0.5rem;
    font-family: inherit;
    font-weight: 400;
}

.form-group input::placeholder {
    color: var(--text-muted);
}

.form-group select {
    appearance: none;
    cursor: pointer;
    background-image: none;
}

.select-arrow {
    margin-right: 0.75rem;
    color: var(--text-muted);
    pointer-events: none;
    font-size: 0.875rem;
}

.extra-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    z-index: 10;
    position: relative;
}

.extra-options label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
    cursor: pointer;
    font-weight: 400;
    z-index: 10;
    position: relative;
}

.extra-options input[type="checkbox"] {
    width: 1rem;
    height: 1rem;
    accent-color: var(--primary);
    border-radius: var(--radius-sm);
    z-index: 10;
    position: relative;
}

.extra-options .forgot {
    color: var(--primary);
    font-size: 0.875rem;
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
    z-index: 10;
    position: relative;
}

.extra-options .forgot:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.auth-btn {
    padding: 0.875rem 1.5rem;
    border: none;
    border-radius: var(--radius-md);
    background: var(--primary);
    color: var(--surface);
    font-weight: 600;
    font-size: 0.9375rem;
    letter-spacing: 0.025em;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 0.5rem;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}

.auth-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.auth-btn:hover::before {
    left: 100%;
}

.auth-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.auth-btn:active {
    transform: translateY(0);
    box-shadow: var(--shadow-sm);
}

.switch {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.switch a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.switch a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 640px) {
    body {
        padding: 0.5rem;
        align-items: flex-start;
        padding-top: 2rem;
    }
    
    .main-card {
        max-width: 100%;
        padding: 2rem 1.5rem 1.5rem;
        margin: 0;
    }
    
    .voltar-btn {
        left: 1rem;
        top: 1rem;
        font-size: 0.8125rem;
        padding: 0.5rem 0.875rem;
    }
    
    .auth-header h2 {
        font-size: 1.5rem;
    }
    
    .auth-header p {
        font-size: 0.8125rem;
    }
    
    .form-group input,
    .form-group select {
        font-size: 0.875rem;
    }
}

@media (max-width: 480px) {
    .main-card {
        padding: 1.5rem 1rem 1rem;
    }
    
    .auth-header {
        margin-bottom: 1.5rem;
    }
    
    .auth-header .logo {
        width: 3rem;
        height: 3rem;
        font-size: 1.25rem;
    }
    
    .voltar-btn {
        left: 0.75rem;
        top: 0.75rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
}

/* Loading animation for form submission */
.auth-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.auth-btn:disabled::after {
    content: '';
    position: absolute;
    width: 1rem;
    height: 1rem;
    border: 2px solid transparent;
    border-top: 2px solid var(--surface);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* Focus styles for accessibility */
.auth-btn:focus-visible,
.voltar-btn:focus-visible,
.switch a:focus-visible {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

/* Improved select styling */
.form-group select option {
    padding: 0.5rem;
    background: var(--surface);
    color: var(--text-primary);
}

/* Enhanced form validation styles */
.form-group input:invalid:not(:focus):not(:placeholder-shown) {
    border-color: var(--danger);
}

.form-group input:valid:not(:focus):not(:placeholder-shown) {
    border-color: var(--success);
}

.input-wrap:has(input:invalid:not(:focus):not(:placeholder-shown)) {
    border-color: var(--danger);
    background: var(--danger-light);
}

.input-wrap:has(input:valid:not(:focus):not(:placeholder-shown)) {
    border-color: var(--success);
}
    </style>
</head>
<body>
    <div class="main-card" id="login-container">
        <a class="voltar-btn" href="index.php"><i class="fas fa-arrow-left"></i>Voltar</a>
        <div class="auth-header">
            <div class="logo"><i class="fas fa-store"></i></div>
            <h2 id="form-title">Bem-vindo!</h2>
            <p id="form-desc">Acesse sua conta ou cadastre-se no MarketPlace Brasil</p>
        </div>
        <?php if (!empty($msg)): ?>
            <div class="msg <?= isset($msg_type) ? $msg_type : 'error' ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <!-- Login Form -->
        <form id="login-form" method="post" style="display:block;">
            <div class="form-group">
                <label for="login-email">E-mail</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="login-email" name="email" placeholder="Seu e-mail" required autocomplete="username">
                </div>
            </div>
            <div class="form-group">
                <label for="login-senha">Senha</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="login-senha" name="senha" placeholder="Sua senha" required autocomplete="current-password">
                </div>
            </div>
            <div class="extra-options">
                <div style="display: flex; align-items: center; gap:6px;">
                    <input type="checkbox" id="show-pw" style="accent-color: #667eea; width:15px; height:15px;">
                    <label for="show-pw" style="color:#888; font-size:13px; cursor:pointer;">Mostrar senha</label>
                </div>
                <a href="#" class="forgot" onclick="alert('Recuperação de senha não implementada.');return false;">Esqueci a senha</a>
            </div>
            <button type="submit" name="login" class="auth-btn">Entrar</button>
        </form>
        <!-- Register Form -->
        <form id="register-form" method="post" style="display:none;">
            <div class="form-group">
                <label for="reg-nome">Nome completo</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" id="reg-nome" name="nome" placeholder="Seu nome completo" required>
                </div>
            </div>
            <div class="form-group">
                <label for="reg-email">E-mail</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    <input type="email" id="reg-email" name="email" placeholder="Seu melhor e-mail" required>
                </div>
            </div>
            <div class="form-group">
                <label for="reg-senha">Senha</label>
                <div class="input-wrap">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="reg-senha" name="senha" placeholder="Crie uma senha segura" required>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:6px;">
                <label for="reg-tipo">Tipo de conta</label>
                <div class="input-wrap">
                    <span class="select-icon"><i class="fas fa-user-check"></i></span>
                    <select id="reg-tipo" name="tipo" required>
                        <option value="comprador">Comprador</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="ambos">Ambos</option>
                    </select>
                    <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                </div>
            </div>
            <button type="submit" name="register" class="auth-btn">Cadastrar</button>
        </form>
        <div class="switch" id="switch-area">
            <span id="toggle-text">Não tem uma conta? <a href="#" id="show-register">Cadastre-se</a></span>
        </div>
    </div>
    <script>
        // Alternar login/cadastro
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const formTitle = document.getElementById('form-title');
        const formDesc = document.getElementById('form-desc');
        const switchArea = document.getElementById('switch-area');
        let showRegister, showLogin;

        function setLogin() {
            loginForm.style.display = "block";
            registerForm.style.display = "none";
            formTitle.textContent = "Bem-vindo!";
            formDesc.textContent = "Acesse sua conta ou cadastre-se no MarketPlace Brasil";
            switchArea.innerHTML = 'Não tem uma conta? <a href="#" id="show-register">Cadastre-se</a>';
            showRegister = document.getElementById('show-register');
            showRegister.onclick = function(e) { e.preventDefault(); setRegister(); }
        }
        function setRegister() {
            loginForm.style.display = "none";
            registerForm.style.display = "block";
            formTitle.textContent = "Crie sua conta";
            formDesc.textContent = "Cadastre-se para aproveitar todas as ofertas do MarketPlace Brasil";
            switchArea.innerHTML = 'Já tem conta? <a href="#" id="show-login">Entrar</a>';
            showLogin = document.getElementById('show-login');
            showLogin.onclick = function(e) { e.preventDefault(); setLogin(); }
        }
        setLogin();

        // Mostrar senha
        const pwInput = document.getElementById('login-senha');
        const showPW = document.getElementById('show-pw');
        if (pwInput && showPW) {
            showPW.addEventListener('change', function() {
                pwInput.type = this.checked ? 'text' : 'password';
            });
        }
    </script>
</body>
</html>