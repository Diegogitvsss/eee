<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checkout - MarketPlace Brasil</title>
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
            --gradient-success: linear-gradient(135deg, #059669 0%, #047857 100%);
            
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
            padding: 0 1rem;
        }

        /* Header */
        .header {
            background: white;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-title h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--neutral-800);
        }

        .header-title .icon {
            width: 3rem;
            height: 3rem;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .progress-bar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            background: var(--neutral-100);
            color: var(--neutral-500);
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .progress-step.active {
            background: var(--gradient-primary);
            color: white;
        }

        .progress-step.completed {
            background: var(--gradient-success);
            color: white;
        }

        .progress-divider {
            width: 2rem;
            height: 2px;
            background: var(--neutral-200);
            border-radius: 1px;
        }

        .progress-divider.completed {
            background: var(--success-color);
        }

        /* Main Content */
        .checkout-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        /* Form Section */
        .form-section {
            background: white;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .form-header {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .form-content {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--neutral-700);
        }

        .form-label.required::after {
            content: ' *';
            color: var(--danger-color);
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--neutral-200);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input.error {
            border-color: var(--danger-color);
        }

        .form-input.success {
            border-color: var(--success-color);
        }

        .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--neutral-200);
            border-radius: var(--border-radius);
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: var(--transition);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-row-3 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .success-message {
            color: var(--success-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        /* Payment Methods */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .payment-method {
            border: 2px solid var(--neutral-200);
            border-radius: var(--border-radius);
            padding: 1rem;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .payment-method:hover {
            border-color: var(--primary-color);
            background: var(--neutral-50);
        }

        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(37, 99, 235, 0.05);
        }

        .payment-method .icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .payment-method .title {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.25rem;
        }

        .payment-method .desc {
            font-size: 0.875rem;
            color: var(--neutral-500);
        }

        .payment-details {
            background: var(--neutral-50);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-top: 1rem;
            display: none;
        }

        .payment-details.active {
            display: block;
        }

        /* Order Summary */
        .order-summary {
            background: white;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .summary-header {
            background: var(--gradient-primary);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .summary-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .summary-content {
            padding: 2rem;
        }

        .order-items {
            margin-bottom: 1.5rem;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
        }

        .order-item:last-child {
            margin-bottom: 0;
        }

        .item-image {
            width: 60px;
            height: 60px;
            background: var(--neutral-100);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--neutral-400);
            font-size: 1.5rem;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: var(--neutral-800);
            margin-bottom: 0.25rem;
        }

        .item-details {
            font-size: 0.875rem;
            color: var(--neutral-500);
            margin-bottom: 0.5rem;
        }

        .item-price {
            font-weight: 600;
            color: var(--success-color);
        }

        .order-totals {
            border-top: 1px solid var(--neutral-200);
            padding-top: 1.5rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .total-row:last-child {
            margin-bottom: 0;
            padding-top: 0.75rem;
            border-top: 1px solid var(--neutral-200);
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--neutral-800);
        }

        .total-label {
            color: var(--neutral-600);
        }

        .total-value {
            font-weight: 600;
            color: var(--success-color);
        }

        .total-row:last-child .total-value {
            color: var(--neutral-800);
        }

        /* Buttons */
        .btn {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
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

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--neutral-200);
            color: var(--neutral-700);
        }

        .btn-secondary:hover {
            background: var(--neutral-300);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--gradient-success);
            color: white;
            width: 100%;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn:disabled::before {
            display: none;
        }

        /* Loading */
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
            color: var(--neutral-500);
        }

        .loading.active {
            display: block;
        }

        .spinner {
            width: 2rem;
            height: 2rem;
            border: 3px solid var(--neutral-200);
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Success Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop.active {
            display: flex;
        }

        .modal {
            background: white;
            border-radius: var(--border-radius-xl);
            box-shadow: var(--shadow-xl);
            max-width: 500px;
            width: 90%;
            overflow: hidden;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            background: var(--gradient-success);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .modal-header .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .modal-content {
            padding: 2rem;
            text-align: center;
        }

        .modal-content p {
            color: var(--neutral-600);
            margin-bottom: 1.5rem;
        }

        .order-number {
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 2rem;
            font-family: monospace;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--neutral-800);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
                padding: 0 1rem;
            }

            .checkout-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .order-summary {
                position: static;
                order: -1;
            }

            .header {
                padding: 1.5rem;
            }

            .header-title h1 {
                font-size: 1.5rem;
            }

            .progress-bar {
                flex-direction: column;
                gap: 0.5rem;
            }

            .progress-step {
                width: 100%;
                justify-content: center;
            }

            .progress-divider {
                width: 100%;
                height: 2px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-row-3 {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                grid-template-columns: 1fr;
            }

            .form-content {
                padding: 1.5rem;
            }

            .summary-content {
                padding: 1.5rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section, .order-summary {
            animation: fadeIn 0.6s ease-out;
        }

        /* Input masks and validation styles */
        .form-input.loading {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="none" stroke="%23e2e8f0" stroke-width="2"/><path d="M12 2A10 10 0 0122 12" fill="none" stroke="%232563eb" stroke-width="2"><animateTransform attributeName="transform" type="rotate" values="0 12 12;360 12 12" dur="1s" repeatCount="indefinite"/></path></svg>');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
        }

        .cep-info {
            background: var(--neutral-50);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-top: 0.5rem;
            display: none;
        }

        .cep-info.active {
            display: block;
        }

        .cep-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .cep-info-item:last-child {
            margin-bottom: 0;
        }

        .cep-info-label {
            color: var(--neutral-600);
        }

        .cep-info-value {
            font-weight: 600;
            color: var(--neutral-800);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-title">
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h1>Finalizar Compra</h1>
                </div>
                <a href="painel_comprador.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <div class="progress-bar">
                <div class="progress-step active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Carrinho</span>
                </div>
                <div class="progress-divider"></div>
                <div class="progress-step">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Entrega</span>
                </div>
                <div class="progress-divider"></div>
                <div class="progress-step">
                    <i class="fas fa-credit-card"></i>
                    <span>Pagamento</span>
                </div>
                <div class="progress-divider"></div>
                <div class="progress-step">
                    <i class="fas fa-check-circle"></i>
                    <span>Confirmação</span>
                </div>
            </div>
        </div>

        <div class="checkout-content">
            <!-- Form Section -->
            <div class="form-section">
                <div class="form-header">
                    <i class="fas fa-shipping-fast"></i>
                    <h2>Dados de Entrega</h2>
                </div>
                <div class="form-content">
                    <form id="checkout-form">
                        <div class="form-group">
                            <label class="form-label required">CEP</label>
                            <input type="text" id="cep" class="form-input" placeholder="00000-000" maxlength="9" required>
                            <div class="error-message" id="cep-error"></div>
                            <div class="cep-info" id="cep-info">
                                <div class="cep-info-item">
                                    <span class="cep-info-label">Logradouro:</span>
                                    <span class="cep-info-value" id="logradouro"></span>
                                </div>
                                <div class="cep-info-item">
                                    <span class="cep-info-label">Bairro:</span>
                                    <span class="cep-info-value" id="bairro"></span>
                                </div>
                                <div class="cep-info-item">
                                    <span class="cep-info-label">Cidade:</span>
                                    <span class="cep-info-value" id="cidade"></span>
                                </div>
                                <div class="cep-info-item">
                                    <span class="cep-info-label">Estado:</span>
                                    <span class="cep-info-value" id="estado"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Número</label>
                                <input type="text" id="numero" class="form-input" placeholder="123" required>
                                <div class="error-message" id="numero-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Complemento</label>
                                <input type="text" id="complemento" class="form-input" placeholder="Apto 45">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Ponto de Referência</label>
                            <input type="text" id="referencia" class="form-input" placeholder="Próximo ao shopping">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Nome Completo</label>
                                <input type="text" id="nome" class="form-input" placeholder="João Silva" required>
                                <div class="error-message" id="nome-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">Telefone</label>
                                <input type="tel" id="telefone" class="form-input" placeholder="(11) 99999-9999" required>
                                <div class="error-message" id="telefone-error"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="form-section" style="margin-top: 2rem;">
                <div class="form-header">
                    <i class="fas fa-credit-card"></i>
                    <h2>Forma de Pagamento</h2>
                </div>
                <div class="form-content">
                    <div class="payment-methods">
                        <div class="payment-method" data-method="credit">
                            <div class="icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="title">Cartão de Crédito</div>
                            <div class="desc">Visa, Master, Elo</div>
                        </div>
                        <div class="payment-method" data-method="debit">
                            <div class="icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <div class="title">Cartão de Débito</div>
                            <div class="desc">Débito à vista</div>
                        </div>
                        <div class="payment-method" data-method="pix">
                            <div class="icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="title">PIX</div>
                            <div class="desc">Pagamento instantâneo</div>
                        </div>
                    </div>

                    <!-- Credit Card Details -->
                    <div class="payment-details" id="credit-details">
                        <div class="form-group">
                            <label class="form-label required">Número do Cartão</label>
                            <input type="text" id="card-number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19">
                            <div class="error-message" id="card-number-error"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Validade</label>
                                <input type="text" id="card-expiry" class="form-input" placeholder="MM/AA" maxlength="5">
                                <div class="error-message" id="card-expiry-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">CVV</label>
                                <input type="text" id="card-cvv" class="form-input" placeholder="123" maxlength="4">
                                <div class="error-message" id="card-cvv-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Nome no Cartão</label>
                            <input type="text" id="card-name" class="form-input" placeholder="JOÃO SILVA">
                            <div class="error-message" id="card-name-error"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Parcelas</label>
                            <select id="installments" class="form-select">
                                <option value="1">1x - À vista</option>
                                <option value="2">2x - Sem juros</option>
                                <option value="3">3x - Sem juros</option>
                                <option value="6">6x - Sem juros</option>
                                <option value="12">12x - Com juros</option>
                                
                                