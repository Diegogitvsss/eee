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
            --primary: #373fc7;
            --primary-light: #667eea;
            --secondary: #764ba2;
            --background: #f6f7fb;
            --white: #fff;
            --text: #24243e;
            --gray: #bdbdbd;
            --danger: #f44336;
            --success: #13ce66;
            --radius: 20px;
            --shadow: 0 8px 32px 0 rgba(55, 63, 199, 0.13);
            --input-radius: 12px;
            --input-bg: #f8fafd;
            --input-border: #e0e0e0;
            --icon-bg: #edeaff;
            --icon-size: 36px;
            --icon-color: #5a48d1;
        }

        html, body {
            height: 100%;
            min-height: 100vh;
        }
        body {
            min-height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            background: linear-gradient(112deg, var(--primary) 0%, var(--secondary) 100%);
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            box-sizing: border-box;
        }
        .main-card {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 430px;
            width: 100%;
            padding: 40px 36px 32px 36px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            animation: fadein 0.7s cubic-bezier(.36,.07,.19,.97);
            position: relative;
            margin: 0 auto;
        }
        @keyframes fadein {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .voltar-btn {
            position: absolute;
            left: 24px;
            top: 24px;
            background: var(--white);
            color: var(--primary);
            border: 1.5px solid var(--primary);
            border-radius: 30px;
            padding: 7px 22px 7px 13px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(102,126,234,0.08);
            transition: background 0.2s, color 0.2s;
            z-index: 10;
        }
        .voltar-btn:hover { background: var(--primary); color: var(--white);}
        .auth-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .auth-header .logo {
            font-size: 38px;
            color: var(--primary);
            margin-bottom: 7px;
        }
        .auth-header h2 {
            font-size: 25px;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 5px 0;
        }
        .auth-header p {
            color: #888;
            font-size: 13px;
            margin: 0;
        }
        .msg {
            padding: 11px 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(102,126,234,0.04);
        }
        .msg.error {
            background: #fff2f1;
            color: var(--danger);
            border: 1px solid #ffbaba;
        }
        .msg.success {
            background: #e3ffe3;
            color: var(--success);
            border: 1px solid #a3ffb4;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 17px;
            margin-bottom: 0;
        }
        .form-group {
            margin-bottom: 2px;
        }
        .form-group label {
            font-size: 14px;
            color: var(--primary);
            margin-left: 6px;
            margin-bottom: 0;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .input-wrap {
            display: flex;
            align-items: center;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: var(--input-radius);
            margin-top: 3px;
            transition: border 0.2s, background 0.2s;
        }
        .input-wrap:focus-within {
            border-color: var(--primary-light);
            background: #f4f6ff;
        }
        .input-icon,
        .select-icon {
            width: var(--icon-size);
            height: var(--icon-size);
            min-width: var(--icon-size);
            min-height: var(--icon-size);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--icon-bg);
            border-radius: 50%;
            margin-left: 8px;
            margin-right: 8px;
        }
        .input-icon i,
        .select-icon i {
            color: var(--icon-color) !important;
            font-size: 18px !important;
            margin: 0;
            padding: 0;
        }
        .form-group input,
        .form-group select {
            border: none;
            outline: none;
            background: transparent;
            font-size: 15px;
            color: var(--text);
            padding: 13px 12px 13px 0;
            height: 44px;
            width: 100%;
            border-radius: var(--input-radius);
            box-sizing: border-box;
            vertical-align: middle;
            font-family: inherit;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
        }
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            cursor: pointer;
        }
        .select-arrow {
            margin-right: 16px;
            color: #bdbdbd;
            pointer-events: none;
            font-size: 16px;
        }
        .extra-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .extra-options label {
            font-size: 13px;
            color: #888;
        }
        .extra-options .forgot {
            color: #bbb;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.18s;
        }
        .extra-options .forgot:hover { color: var(--primary);}
        .auth-btn {
            padding: 12px;
            border: none;
            border-radius: 11px;
            background: linear-gradient(120deg, var(--primary) 0%, var(--secondary) 100%);
            color: var(--white);
            font-weight: 700;
            font-size: 18px;
            margin-top: 10px;
            letter-spacing: 0.03em;
            cursor: pointer;
            transition: background 0.18s, box-shadow 0.16s;
            box-shadow: 0 4px 18px rgba(102,126,234,0.07);
        }
        .auth-btn:hover {
            background: linear-gradient(120deg, #2c329b 0%, var(--secondary) 100%);
        }
        .switch {
            text-align: center;
            margin-top: 18px;
            font-size: 15px;
            color: #555;
        }
        .switch a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }
        .switch a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        @media (max-width: 485px) {
            body {
                align-items: flex-start;
                padding-top: 30px;
            }
            .main-card { 
                padding: 16px 3vw 24px 3vw; 
                max-width: 99vw;
                margin: 0 auto;
                margin-top: 18px;
            }
            .voltar-btn { top: 8px; left: 8px; font-size: 13px; padding: 7px 13px 7px 10px;}
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