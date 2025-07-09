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
    
    --border-radius: 8px;
    --border-radius-lg: 12px;
    --border-radius-xl: 16px;
    
    --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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
    line-height: 1.5;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Header */
.header {
    background: white;
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    padding: 1.5rem 2rem;
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
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--neutral-800);
}

.header-title .icon {
    width: 2.5rem;
    height: 2.5rem;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
}

.progress-bar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.progress-step {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    background: var(--neutral-100);
    color: var(--neutral-500);
    font-size: 0.8125rem;
    font-weight: 500;
    transition: var(--transition);
    white-space: nowrap;
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
    flex: 1;
    height: 2px;
    background: var(--neutral-200);
    border-radius: 1px;
    min-width: 20px;
    max-width: 40px;
}

.progress-divider.completed {
    background: var(--success-color);
}

/* Main Content */
.checkout-content {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.5rem;
}

/* Form Sections */
.form-section {
    background: white;
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    display: none;
    margin-bottom: 1.5rem;
}

.form-section.active {
    display: block;
    animation: fadeIn 0.4s ease-out;
}

.form-header {
    background: var(--gradient-primary);
    color: white;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-header h2 {
    font-size: 1.15rem;
    font-weight: 600;
}

.form-header .icon {
    font-size: 1.25rem;
}

.form-content {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--neutral-700);
    font-size: 0.9375rem;
}

.form-label.required::after {
    content: ' *';
    color: var(--danger-color);
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    font-size: 0.9375rem;
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
    padding: 0.75rem 1rem;
    border: 1px solid var(--neutral-300);
    border-radius: var(--border-radius);
    font-size: 0.9375rem;
    background: white;
    cursor: pointer;
    transition: var(--transition);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px;
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

.error-message {
    color: var(--danger-color);
    font-size: 0.8125rem;
    margin-top: 0.25rem;
    display: none;
}

.success-message {
    color: var(--success-color);
    font-size: 0.8125rem;
    margin-top: 0.25rem;
    display: none;
}

/* Payment Methods */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1.25rem;
}

.payment-method {
    border: 1px solid var(--neutral-300);
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
    box-shadow: 0 0 0 1px var(--primary-color);
}

.payment-method .icon {
    font-size: 1.75rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.payment-method .title {
    font-weight: 600;
    color: var(--neutral-800);
    margin-bottom: 0.25rem;
    font-size: 0.9375rem;
}

.payment-method .desc {
    font-size: 0.8125rem;
    color: var(--neutral-500);
}

.payment-details {
    background: var(--neutral-50);
    border-radius: var(--border-radius);
    padding: 1.25rem;
    margin-top: 1rem;
    display: none;
    border: 1px solid var(--neutral-200);
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
    margin-bottom: 1.5rem;
    display: none;
}

.order-summary.active {
    display: block;
}

.summary-header {
    background: var(--gradient-primary);
    color: white;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.summary-header h3 {
    font-size: 1.15rem;
    font-weight: 600;
}

.summary-header .icon {
    font-size: 1.25rem;
}

.summary-content {
    padding: 1.5rem;
}

.order-items {
    margin-bottom: 1.25rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    margin-bottom: 0.75rem;
}

.order-item:last-child {
    margin-bottom: 0;
}

.item-image {
    width: 50px;
    height: 50px;
    background: var(--neutral-100);
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--neutral-400);
    font-size: 1.25rem;
    flex-shrink: 0;
}

.item-info {
    flex: 1;
    min-width: 0;
}

.item-name {
    font-weight: 600;
    color: var(--neutral-800);
    margin-bottom: 0.25rem;
    font-size: 0.9375rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-details {
    font-size: 0.8125rem;
    color: var(--neutral-500);
    margin-bottom: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.item-price {
    font-weight: 600;
    color: var(--success-color);
    font-size: 0.9375rem;
}

.order-totals {
    border-top: 1px solid var(--neutral-200);
    padding-top: 1.25rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.total-row:last-child {
    margin-bottom: 0;
    padding-top: 0.75rem;
    border-top: 1px solid var(--neutral-200);
    font-weight: 700;
    font-size: 1.0625rem;
    color: var(--neutral-800);
}

.total-label {
    color: var(--neutral-600);
    font-size: 0.9375rem;
}

.total-value {
    font-weight: 600;
    color: var(--success-color);
    font-size: 0.9375rem;
}

.total-row:last-child .total-value {
    color: var(--neutral-800);
    font-size: 1.0625rem;
}

/* Buttons */
.btn {
    padding: 0.6875rem 1.25rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 0.9375rem;
    cursor: pointer;
    transition: var(--transition-fast);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
    height: 42px;
    min-width: 120px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
}

.btn-primary:hover {
    background: var(--primary-dark);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
}

.btn-secondary {
    background: white;
    color: var(--primary-color);
    border: 1px solid var(--neutral-300);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
    background: var(--neutral-50);
    border-color: var(--primary-color);
    color: var(--primary-dark);
}

.btn-success {
    background: var(--gradient-success);
    color: white;
    box-shadow: 0 2px 4px rgba(5, 150, 105, 0.1);
}

.btn-success:hover {
    background: #047857;
    box-shadow: 0 4px 8px rgba(5, 150, 105, 0.2);
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

/* Navigation */
.checkout-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 1.5rem;
    gap: 1rem;
}

.checkout-navigation .btn {
    flex: 1;
    max-width: calc(50% - 0.5rem);
}

#finalizar-compra-btn {
    flex: 1 0 100%;
    max-width: 100%;
    padding: 0.75rem;
}

/* Icons */
.btn i {
    font-size: 0.875em;
    transition: transform 0.2s ease;
}

.btn:hover i {
    transform: translateX(2px);
}

/* Loading */
.loading {
    display: none;
    text-align: center;
    padding: 1.5rem;
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

/* CEP Info */
.cep-info {
    background: var(--neutral-50);
    border-radius: var(--border-radius);
    padding: 0.75rem;
    margin-top: 0.5rem;
    display: none;
    border: 1px solid var(--neutral-200);
    font-size: 0.875rem;
}

.cep-info.active {
    display: block;
}

.cep-info-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.375rem;
}

.cep-info-item:last-child {
    margin-bottom: 0;
}

.cep-info-label {
    color: var(--neutral-600);
}

.cep-info-value {
    font-weight: 500;
    color: var(--neutral-800);
}

/* Coupon Section */
.coupon-section {
    margin: 1.25rem 0;
    padding: 1rem;
    background: var(--neutral-50);
    border-radius: var(--border-radius);
    border: 1px solid var(--neutral-200);
}

.coupon-input {
    display: flex;
    gap: 0.5rem;
}

.coupon-input .form-input {
    flex: 1;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
}

.coupon-input .btn {
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    height: auto;
}

/* Modals */
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-backdrop.active {
    display: flex;
}

.modal {
    background: white;
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-xl);
    max-width: 500px;
    width: 100%;
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
}

.modal-header {
    background: var(--gradient-success);
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.modal-header .icon {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
}

.modal-header h2 {
    font-size: 1.375rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.modal-header p {
    font-size: 0.9375rem;
}

.modal-content {
    padding: 1.5rem;
    text-align: center;
}

.modal-content p {
    color: var(--neutral-600);
    margin-bottom: 1.25rem;
    font-size: 0.9375rem;
}

.order-number {
    background: var(--neutral-50);
    border: 1px solid var(--neutral-200);
    border-radius: var(--border-radius);
    padding: 0.875rem;
    margin-bottom: 1.5rem;
    font-family: monospace;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--neutral-800);
}

.modal-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.modal-actions .btn {
    flex: 1;
    max-width: 180px;
}

/* PIX Modal */
.pix-info {
    text-align: center;
    padding: 1.5rem;
}

.pix-icon {
    font-size: 3.5rem;
    color: var(--primary-color);
    margin-bottom: 0.75rem;
}

.pix-info h3 {
    font-size: 1.25rem;
    margin-bottom: 0.75rem;
    color: var(--neutral-800);
}

.pix-info p {
    color: var(--neutral-600);
    margin-bottom: 1.5rem;
    font-size: 0.9375rem;
}

.pix-benefits {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.benefit {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--success-color);
    justify-content: center;
    font-size: 0.9375rem;
}

.benefit i {
    font-size: 1rem;
}

.pix-qr-code {
    margin-bottom: 1.25rem;
}

.qr-placeholder {
    width: 180px;
    height: 180px;
    border: 2px dashed var(--neutral-300);
    border-radius: var(--border-radius);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: var(--neutral-500);
}

.qr-placeholder i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.pix-code {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.pix-code .form-input {
    flex: 1;
    font-family: monospace;
    font-size: 0.8125rem;
    padding: 0.625rem 0.75rem;
}

.pix-timer {
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--warning-color);
    font-weight: 600;
    font-size: 0.9375rem;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    body {
        padding: 15px;
    }
    
    .header {
        padding: 1.25rem;
    }
    
    .header-title h1 {
        font-size: 1.5rem;
    }
    
    .progress-bar {
        gap: 0.5rem;
    }
    
    .progress-step {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .form-content, .summary-content {
        padding: 1.25rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .payment-methods {
        grid-template-columns: 1fr;
    }
    
    .checkout-navigation {
        flex-direction: column;
    }
    
    .checkout-navigation .btn {
        max-width: 100%;
    }
    
    .modal-actions {
        flex-direction: column;
    }
    
    .modal-actions .btn {
        max-width: 100%;
    }
    
    .item-image {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .item-name, .item-details {
        white-space: normal;
    }
}

@media (max-width: 480px) {
    .header {
        padding: 1rem;
    }
    
    .header-title h1 {
        font-size: 1.25rem;
    }
    
    .header-title .icon {
        width: 2rem;
        height: 2rem;
        font-size: 0.9rem;
    }
    
    .form-header, .summary-header {
        padding: 1rem;
    }
    
    .form-header h2, .summary-header h3 {
        font-size: 1.05rem;
    }
    
    .btn {
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        height: 40px;
    }
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
                <div class="progress-step active" id="step-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Carrinho</span>
                </div>
                <div class="progress-divider" id="divider-1"></div>
                <div class="progress-step" id="step-shipping">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Entrega</span>
                </div>
                <div class="progress-divider" id="divider-2"></div>
                <div class="progress-step" id="step-payment">
                    <i class="fas fa-credit-card"></i>
                    <span>Pagamento</span>
                </div>
                <div class="progress-divider" id="divider-3"></div>
                <div class="progress-step" id="step-confirmation">
                    <i class="fas fa-check-circle"></i>
                    <span>Confirmação</span>
                </div>
            </div>
        </div>

        <div class="checkout-content">
            <!-- Shipping Section -->
            <div class="form-section active" id="shipping-section">
                <div class="form-header">
                    <i class="fas fa-shipping-fast"></i>
                    <h2>Dados de Entrega</h2>
                </div>
                <div class="form-content">
                    <form id="shipping-form">
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

                        <div class="checkout-navigation">
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='painel_comprador.php'">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </button>
                            <button type="button" class="btn btn-primary" id="next-to-payment">
                                Continuar para Pagamento <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="form-section" id="payment-section">
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
                                <option value="18">18x - Com juros</option>
                                <option value="24">24x - Com juros</option>
                            </select>
                        </div>
                    </div>

                    <!-- Debit Card Details -->
                    <div class="payment-details" id="debit-details">
                        <div class="form-group">
                            <label class="form-label required">Número do Cartão</label>
                            <input type="text" id="debit-number" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19">
                            <div class="error-message" id="debit-number-error"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">Validade</label>
                                <input type="text" id="debit-expiry" class="form-input" placeholder="MM/AA" maxlength="5">
                                <div class="error-message" id="debit-expiry-error"></div>
                            </div>
                            <div class="form-group">
                                <label class="form-label required">CVV</label>
                                <input type="text" id="debit-cvv" class="form-input" placeholder="123" maxlength="4">
                                <div class="error-message" id="debit-cvv-error"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Nome no Cartão</label>
                            <input type="text" id="debit-name" class="form-input" placeholder="JOÃO SILVA">
                            <div class="error-message" id="debit-name-error"></div>
                        </div>
                    </div>

                    <!-- PIX Details -->
                    <div class="payment-details" id="pix-details">
                        <div class="pix-info">
                            <div class="pix-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <h3>Pagamento via PIX</h3>
                            <p>Após finalizar o pedido, você receberá um QR Code para pagamento instantâneo via PIX.</p>
                            <div class="pix-benefits">
                                <div class="benefit">
                                    <i class="fas fa-check"></i>
                                    <span>Pagamento instantâneo</span>
                                </div>
                                <div class="benefit">
                                    <i class="fas fa-check"></i>
                                    <span>Sem taxas adicionais</span>
                                </div>
                                <div class="benefit">
                                    <i class="fas fa-check"></i>
                                    <span>Disponível 24/7</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-navigation">
                        <button type="button" class="btn btn-secondary" id="back-to-shipping">
                            <i class="fas fa-arrow-left"></i> Voltar para Entrega
                        </button>
                        <button type="button" class="btn btn-primary" id="next-to-review">
                            Revisar Pedido <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="form-section" id="review-section">
                <div class="form-header">
                    <i class="fas fa-check-circle"></i>
                    <h2>Revisar Pedido</h2>
                </div>
                <div class="form-content">
                    <div class="order-summary active">
                        <div class="summary-header">
                            <i class="fas fa-receipt"></i>
                            <h3>Resumo do Pedido</h3>
                        </div>
                        <div class="summary-content">
                            <div class="order-items">
                                <div class="order-item">
                                    <div class="item-image">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    <div class="item-info">
                                        <div class="item-name">Notebook Gamer RGB</div>
                                        <div class="item-details">Cor: Preto | Tamanho: 15.6"</div>
                                        <div class="item-price">R$ 2.499,90</div>
                                    </div>
                                </div>
                                <div class="order-item">
                                    <div class="item-image">
                                        <i class="fas fa-mouse"></i>
                                    </div>
                                    <div class="item-info">
                                        <div class="item-name">Mouse Gamer</div>
                                        <div class="item-details">Cor: RGB | DPI: 12000</div>
                                        <div class="item-price">R$ 149,90</div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-totals">
                                <div class="total-row">
                                    <span class="total-label">Subtotal:</span>
                                    <span class="total-value">R$ 2.649,80</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">Frete:</span>
                                    <span class="total-value">R$ 29,90</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">Desconto:</span>
                                    <span class="total-value">-R$ 50,00</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">Total:</span>
                                    <span class="total-value">R$ 2.629,70</span>
                                </div>
                            </div>

                            <div class="coupon-section">
                                <div class="form-group">
                                    <label class="form-label">Cupom de Desconto</label>
                                    <div class="coupon-input">
                                        <input type="text" id="coupon" class="form-input" placeholder="Digite seu cupom">
                                        <button type="button" class="btn btn-secondary" onclick="applyCoupon()">
                                            <i class="fas fa-tag"></i> Aplicar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-navigation">
                        <button type="button" class="btn btn-secondary" id="back-to-payment">
                            <i class="fas fa-arrow-left"></i> Voltar para Pagamento
                        </button>
                        <button type="button" class="btn btn-success" onclick="processCheckout()">
                            <i class="fas fa-lock"></i> Finalizar Compra
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Processando seu pedido...</p>
        </div>

        <!-- Success Modal -->
        <div class="modal-backdrop" id="success-modal">
            <div class="modal">
                <div class="modal-header">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Pedido Realizado!</h2>
                    <p>Sua compra foi processada com sucesso</p>
                </div>
                <div class="modal-content">
                    <p>Você receberá um e-mail com os detalhes do pedido e informações de entrega.</p>
                    <div class="order-number">
                        Pedido #<span id="order-id">MB-2024-001234</span>
                    </div>
                    <div class="modal-actions">
                        <button class="btn btn-primary" onclick="redirectToOrders()">
                            <i class="fas fa-list"></i> Ver Meus Pedidos
                        </button>
                        <button class="btn btn-secondary" onclick="continueShopping()">
                            <i class="fas fa-shopping-cart"></i> Continuar Comprando
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PIX Modal -->
        <div class="modal-backdrop" id="pix-modal">
            <div class="modal">
                <div class="modal-header">
                    <div class="icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h2>Pagamento via PIX</h2>
                    <p>Escaneie o QR Code ou copie o código</p>
                </div>
                <div class="modal-content">
                    <div class="pix-qr-code">
                        <div class="qr-placeholder">
                            <i class="fas fa-qrcode"></i>
                            <p>QR Code do PIX</p>
                        </div>
                    </div>
                    <div class="pix-code">
                        <input type="text" class="form-input" value="00020126580014br.gov.bcb.pix..." readonly>
                        <button class="btn btn-secondary" onclick="copyPixCode()">
                            <i class="fas fa-copy"></i> Copiar
                        </button>
                    </div>
                    <div class="pix-timer">
                        <p>Tempo restante: <span id="pix-timer">15:00</span></p>
                    </div>
                    <div class="modal-actions">
                        <button class="btn btn-success" onclick="checkPixPayment()">
                            <i class="fas fa-check"></i> Verificar Pagamento
                        </button>
                        <button class="btn btn-secondary" onclick="closePix()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Checkout Controller
        class CheckoutController {
            constructor() {
                this.currentStep = 'shipping';
                this.selectedPaymentMethod = null;
                this.pixTimer = null;
                this.pixTimeLeft = 900; // 15 minutos
                this.orderData = {};
                
                this.initElements();
                this.setupEventListeners();
                this.maskInputs();
                this.updateProgress(1); // Start at shipping step
            }
            
            initElements() {
                this.shippingForm = document.getElementById('shipping-form');
                this.cepInput = document.getElementById('cep');
                this.paymentMethods = document.querySelectorAll('.payment-method');
                this.paymentDetails = document.querySelectorAll('.payment-details');
                this.progressSteps = document.querySelectorAll('.progress-step');
                this.progressDividers = document.querySelectorAll('.progress-divider');
                
                // Navigation buttons
                this.nextToPaymentBtn = document.getElementById('next-to-payment');
                this.backToShippingBtn = document.getElementById('back-to-shipping');
                this.nextToReviewBtn = document.getElementById('next-to-review');
                this.backToPaymentBtn = document.getElementById('back-to-payment');
                
                // Sections
                this.shippingSection = document.getElementById('shipping-section');
                this.paymentSection = document.getElementById('payment-section');
                this.reviewSection = document.getElementById('review-section');
            }
            
            setupEventListeners() {
                // CEP lookup
                this.cepInput.addEventListener('blur', () => this.lookupCEP());
                this.cepInput.addEventListener('input', () => this.maskCEP());
                
                // Payment method selection
                this.paymentMethods.forEach(method => {
                    method.addEventListener('click', () => {
                        this.selectPaymentMethod(method.dataset.method);
                    });
                });
                
                // Navigation buttons
                this.nextToPaymentBtn.addEventListener('click', () => this.goToPayment());
                this.backToShippingBtn.addEventListener('click', () => this.goToShipping());
                this.nextToReviewBtn.addEventListener('click', () => this.goToReview());
                this.backToPaymentBtn.addEventListener('click', () => this.goToPayment());
                
                // Card number formatting
                ['card-number', 'debit-number'].forEach(id => {
                    const input = document.getElementById(id);
                    if (input) {
                        input.addEventListener('input', (e) => this.formatCardNumber(e));
                    }
                });
                
                // Expiry date formatting
                ['card-expiry', 'debit-expiry'].forEach(id => {
                    const input = document.getElementById(id);
                    if (input) {
                        input.addEventListener('input', (e) => this.formatExpiryDate(e));
                    }
                });
                
                // Phone formatting
                document.getElementById('telefone').addEventListener('input', (e) => this.formatPhone(e));
            }
            
            goToShipping() {
                this.currentStep = 'shipping';
                this.shippingSection.classList.add('active');
                this.paymentSection.classList.remove('active');
                this.reviewSection.classList.remove('active');
                this.updateProgress(1);
            }
            
            goToPayment() {
                if (this.currentStep === 'shipping') {
                    if (!this.validateShippingForm()) {
                        return;
                    }
                }
                
                this.currentStep = 'payment';
                this.shippingSection.classList.remove('active');
                this.paymentSection.classList.add('active');
                this.reviewSection.classList.remove('active');
                this.updateProgress(2);
            }
            
            goToReview() {
                if (!this.validatePaymentForm()) {
                    return;
                }
                
                this.currentStep = 'review';
                this.shippingSection.classList.remove('active');
                this.paymentSection.classList.remove('active');
                this.reviewSection.classList.add('active');
                this.updateProgress(3);
            }
            
            maskInputs() {
                // CEP mask
                this.cepInput.addEventListener('input', (e) => {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
                    e.target.value = value;
                });
            }
            
            maskCEP(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            }
            
            formatCardNumber(e) {
                let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formattedValue;
            }
            
            formatExpiryDate(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/^(\d{2})(\d)/, '$1/$2');
                e.target.value = value;
            }
            
            formatPhone(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
                e.target.value = value;
            }
            
            lookupCEP() {
                const cep = this.cepInput.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    this.cepInput.classList.add('loading');
                    
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.erro) {
                                this.showError('cep-error', 'CEP não encontrado');
                                this.cepInput.classList.add('error');
                            } else {
                                this.fillAddressData(data);
                                this.cepInput.classList.remove('error');
                                this.cepInput.classList.add('success');
                                this.showCEPInfo(data);
                            }
                        })
                        .catch(error => {
                            this.showError('cep-error', 'Erro ao buscar CEP');
                            this.cepInput.classList.add('error');
                        })
                        .finally(() => {
                            this.cepInput.classList.remove('loading');
                        });
                }
            }
            
            fillAddressData(data) {
                document.getElementById('logradouro').textContent = data.logradouro;
                document.getElementById('bairro').textContent = data.bairro;
                document.getElementById('cidade').textContent = data.localidade;
                document.getElementById('estado').textContent = data.uf;
            }
            
            showCEPInfo(data) {
                const cepInfo = document.getElementById('cep-info');
                cepInfo.classList.add('active');
            }
            
            selectPaymentMethod(method) {
                this.selectedPaymentMethod = method;
                
                // Update UI
                this.paymentMethods.forEach(pm => pm.classList.remove('selected'));
                document.querySelector(`[data-method="${method}"]`).classList.add('selected');
                
                // Show/hide payment details
                this.paymentDetails.forEach(pd => pd.classList.remove('active'));
                document.getElementById(`${method}-details`).classList.add('active');
            }
            
            updateProgress(step) {
                this.progressSteps.forEach((stepEl, index) => {
                    if (index < step) {
                        stepEl.classList.add('completed');
                        stepEl.classList.remove('active');
                    } else if (index === step) {
                        stepEl.classList.add('active');
                        stepEl.classList.remove('completed');
                    } else {
                        stepEl.classList.remove('active', 'completed');
                    }
                });
                
                this.progressDividers.forEach((divider, index) => {
                    if (index < step) {
                        divider.classList.add('completed');
                    } else {
                        divider.classList.remove('completed');
                    }
                });
            }
            
            validateShippingForm() {
                let isValid = true;
                
                // Required fields
                const requiredFields = ['cep', 'numero', 'nome', 'telefone'];
                requiredFields.forEach(field => {
                    const input = document.getElementById(field);
                    if (!input.value.trim()) {
                        this.showError(`${field}-error`, 'Este campo é obrigatório');
                        input.classList.add('error');
                        isValid = false;
                    } else {
                        input.classList.remove('error');
                        this.hideError(`${field}-error`);
                    }
                });
                
                // Validate CEP
                if (document.getElementById('cep').value.replace(/\D/g, '').length !== 8) {
                    this.showError('cep-error', 'CEP inválido');
                    document.getElementById('cep').classList.add('error');
                    isValid = false;
                }
                
                // Validate phone
                const phone = document.getElementById('telefone').value.replace(/\D/g, '');
                if (phone.length < 10 || phone.length > 11) {
                    this.showError('telefone-error', 'Telefone inválido');
                    document.getElementById('telefone').classList.add('error');
                    isValid = false;
                }
                
                return isValid;
            }
            
            validatePaymentForm() {
                let isValid = true;
                
                // Payment method validation
                if (!this.selectedPaymentMethod) {
                    this.showToast('Selecione uma forma de pagamento', 'warning');
                    isValid = false;
                }
                
                // Card validation for credit/debit
                if (this.selectedPaymentMethod === 'credit' || this.selectedPaymentMethod === 'debit') {
                    const prefix = this.selectedPaymentMethod === 'credit' ? 'card' : 'debit';
                    const cardFields = [`${prefix}-number`, `${prefix}-expiry`, `${prefix}-cvv`, `${prefix}-name`];
                    
                    cardFields.forEach(field => {
                        const input = document.getElementById(field);
                        if (!input.value.trim()) {
                            this.showError(`${field}-error`, 'Este campo é obrigatório');
                            input.classList.add('error');
                            isValid = false;
                        } else {
                            input.classList.remove('error');
                            this.hideError(`${field}-error`);
                        }
                    });
                    
                    // Validate card number
                    const cardNumber = document.getElementById(`${prefix}-number`).value.replace(/\s+/g, '');
                    if (cardNumber.length < 16) {
                        this.showError(`${prefix}-number-error`, 'Número do cartão inválido');
                        document.getElementById(`${prefix}-number`).classList.add('error');
                        isValid = false;
                    }
                    
                    // Validate expiry date
                    const expiry = document.getElementById(`${prefix}-expiry`).value;
                    if (!expiry.match(/^\d{2}\/\d{2}$/)) {
                        this.showError(`${prefix}-expiry-error`, 'Data inválida (MM/AA)');
                        document.getElementById(`${prefix}-expiry`).classList.add('error');
                        isValid = false;
                    }
                    
                    // Validate CVV
                    const cvv = document.getElementById(`${prefix}-cvv`).value;
                    if (cvv.length < 3 || cvv.length > 4) {
                        this.showError(`${prefix}-cvv-error`, 'CVV inválido');
                        document.getElementById(`${prefix}-cvv`).classList.add('error');
                        isValid = false;
                    }
                }
                
                return isValid;
            }
            
            collectOrderData() {
                this.orderData = {
                    shipping: {
                        cep: document.getElementById('cep').value,
                        numero: document.getElementById('numero').value,
                        complemento: document.getElementById('complemento').value,
                        referencia: document.getElementById('referencia').value,
                        nome: document.getElementById('nome').value,
                        telefone: document.getElementById('telefone').value,
                        logradouro: document.getElementById('logradouro').textContent,
                        bairro: document.getElementById('bairro').textContent,
                        cidade: document.getElementById('cidade').textContent,
                        estado: document.getElementById('estado').textContent
                    },
                    payment: {
                        method: this.selectedPaymentMethod
                    },
                    items: [
                        {
                            name: "Notebook Gamer RGB",
                            details: "Cor: Preto | Tamanho: 15.6\"",
                            price: 2499.90
                        },
                        {
                            name: "Mouse Gamer",
                            details: "Cor: RGB | DPI: 12000",
                            price: 149.90
                        }
                    ],
                    totals: {
                        subtotal: 2649.80,
                        shipping: 29.90,
                        discount: 50.00,
                        total: 2629.70
                    }
                };
                
                if (this.selectedPaymentMethod === 'credit') {
                    this.orderData.payment.card = {
                        number: document.getElementById('card-number').value.replace(/\s+/g, ''),
                        expiry: document.getElementById('card-expiry').value,
                        cvv: document.getElementById('card-cvv').value,
                        name: document.getElementById('card-name').value,
                        installments: document.getElementById('installments').value
                    };
                } else if (this.selectedPaymentMethod === 'debit') {
                    this.orderData.payment.card = {
                        number: document.getElementById('debit-number').value.replace(/\s+/g, ''),
                        expiry: document.getElementById('debit-expiry').value,
                        cvv: document.getElementById('debit-cvv').value,
                        name: document.getElementById('debit-name').value
                    };
                }
            }
            
            processCheckout() {
                this.collectOrderData();
                document.getElementById('loading').classList.add('active');
                this.updateProgress(4);
                
                // Simulate API call
                setTimeout(() => {
                    document.getElementById('loading').classList.remove('active');
                    
                    if (this.selectedPaymentMethod === 'pix') {
                        this.showPixModal();
                    } else {
                        this.showSuccessModal();
                    }
                }, 2000);
            }
            
            showSuccessModal() {
                const orderNumber = 'MB-' + new Date().getFullYear() + '-' + Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
                document.getElementById('order-id').textContent = orderNumber;
                document.getElementById('success-modal').classList.add('active');
            }
            
            showPixModal() {
                document.getElementById('pix-modal').classList.add('active');
                this.startPixTimer();
            }
            
            startPixTimer() {
                this.pixTimeLeft = 900; // Reset timer
                
                this.pixTimer = setInterval(() => {
                    this.pixTimeLeft--;
                    const minutes = Math.floor(this.pixTimeLeft / 60);
                    const seconds = this.pixTimeLeft % 60;
                    document.getElementById('pix-timer').textContent = 
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    
                    if (this.pixTimeLeft <= 0) {
                        clearInterval(this.pixTimer);
                        this.closePix();
                        this.showToast('Tempo para pagamento PIX expirado', 'error');
                    }
                }, 1000);
            }
            
            copyPixCode() {
                const pixCode = document.querySelector('.pix-code input');
                pixCode.select();
                document.execCommand('copy');
                
                // Show feedback
                const btn = document.querySelector('.pix-code button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                }, 2000);
                
                this.showToast('Código PIX copiado para a área de transferência', 'success');
            }
            
            checkPixPayment() {
                // Simulate payment check
                setTimeout(() => {
                    clearInterval(this.pixTimer);
                    this.closePix();
                    this.showSuccessModal();
                }, 1000);
            }
            
            closePix() {
                if (this.pixTimer) {
                    clearInterval(this.pixTimer);
                }
                document.getElementById('pix-modal').classList.remove('active');
            }
            
            applyCoupon() {
                const couponInput = document.getElementById('coupon');
                const couponValue = couponInput.value.trim();
                
                if (couponValue) {
                    // Simulate coupon validation
                    setTimeout(() => {
                        this.showToast('Cupom aplicado com sucesso!', 'success');
                        couponInput.value = '';
                    }, 500);
                } else {
                    this.showToast('Digite um cupom válido', 'warning');
                }
            }
            
            redirectToOrders() {
                window.location.href = 'meus-pedidos.php';
            }
            
            continueShopping() {
                window.location.href = 'painel_comprador.php';
            }
            
            showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            
            hideError(elementId) {
                const errorElement = document.getElementById(elementId);
                errorElement.style.display = 'none';
            }
            
            showToast(message, type = 'info') {
                // Remove existing toasts
                const existingToasts = document.querySelectorAll('.toast');
                existingToasts.forEach(toast => toast.remove());
                
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = `
                    <div class="toast-content">
                        <i class="fas ${this.getToastIcon(type)}"></i>
                        <span>${message}</span>
                    </div>
                    <button class="toast-close" onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.add('show');
                }, 10);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 5000);
            }
            
            getToastIcon(type) {
                const icons = {
                    'success': 'fa-check-circle',
                    'error': 'fa-exclamation-circle',
                    'warning': 'fa-exclamation-triangle',
                    'info': 'fa-info-circle'
                };
                return icons[type] || 'fa-info-circle';
            }
        }

        // Initialize the controller when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            const checkout = new CheckoutController();
            
            // Make functions available globally
            window.processCheckout = () => checkout.processCheckout();
            window.applyCoupon = () => checkout.applyCoupon();
            window.redirectToOrders = () => checkout.redirectToOrders();
            window.continueShopping = () => checkout.continueShopping();
            window.copyPixCode = () => checkout.copyPixCode();
            window.checkPixPayment = () => checkout.checkPixPayment();
            window.closePix = () => checkout.closePix();
        });

        // Toast styles
        const toastStyles = document.createElement('style');
        toastStyles.textContent = `
            .toast {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: white;
                border-radius: var(--border-radius);
                box-shadow: var(--shadow-lg);
                padding: 1rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                max-width: 400px;
                transform: translateY(100px);
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 10000;
            }
            
            .toast.show {
                transform: translateY(0);
                opacity: 1;
            }
            
            .toast-content {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            
            .toast i {
                font-size: 1.25rem;
            }
            
            .toast-success {
                border-left: 4px solid var(--success-color);
            }
            
            .toast-success i {
                color: var(--success-color);
            }
            
            .toast-error {
                border-left: 4px solid var(--danger-color);
            }
            
            .toast-error i {
                color: var(--danger-color);
            }
            
            .toast-warning {
                border-left: 4px solid var(--warning-color);
            }
            
            .toast-warning i {
                color: var(--warning-color);
            }
            
            .toast-info {
                border-left: 4px solid var(--info-color);
            }
            
            .toast-info i {
                color: var(--info-color);
            }
            
            .toast-close {
                background: none;
                border: none;
                color: var(--neutral-400);
                cursor: pointer;
                padding: 0.25rem;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .toast-close:hover {
                background: var(--neutral-100);
            }
        `;
        document.head.appendChild(toastStyles);
    </script>
</body>
</html>