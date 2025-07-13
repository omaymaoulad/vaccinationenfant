<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <style>
     /* ===== AUTH LOGIN STYLES ===== */
/* Fichier CSS séparé pour le login Blade avec Flux UI */

/* Reset & Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Background Principal */
.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

/* Formes Flottantes Animées */
.floating-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(8px);
    animation: float 20s infinite linear;
}

.shape:nth-child(1) {
    width: 300px;
    height: 300px;
    top: -150px;
    left: -150px;
    animation-delay: 0s;
}

.shape:nth-child(2) {
    width: 200px;
    height: 200px;
    bottom: -100px;
    right: -100px;
    animation-delay: 7s;
}

.shape:nth-child(3) {
    width: 150px;
    height: 150px;
    top: 20%;
    right: 15%;
    animation-delay: 14s;
}

@keyframes float {
    0% {
        transform: translate(0, 0) rotate(0deg);
    }
    25% {
        transform: translate(30px, -30px) rotate(90deg);
    }
    50% {
        transform: translate(60px, 30px) rotate(180deg);
    }
    75% {
        transform: translate(-30px, 60px) rotate(270deg);
    }
    100% {
        transform: translate(0, 0) rotate(360deg);
    }
}

/* Conteneur Principal du Login */
.login-container {
    width: 100%;
    max-width: 420px;
    position: relative;
    z-index: 10;
}

/* Animations d'Entrée */
.fade-in {
    animation: fadeIn 0.8s ease-out;
}

.slide-up {
    animation: slideUp 0.6s ease-out 0.2s both;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Logo et Header */
.logo-container {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin: 0 auto 1.5rem;
    transition: all 0.3s ease;
}

.logo-container:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.logo-container svg {
    color: white;
}

.auth-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
    text-align: center;
}

.auth-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    text-align: center;
    line-height: 1.5;
}

/* Effet de Verre */
.glass-effect {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 20px;
    box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.2),
        0 2px 16px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.18);
    position: relative;
    overflow: hidden;
}

.glass-effect::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
}

/* Formulaire et Inputs */
.input-enhanced {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.input-enhanced label {
    color: white;
    font-weight: 500;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.input-enhanced .relative {
    position: relative;
}

/* Icônes dans les Inputs */
.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: rgba(255, 255, 255, 0.6);
    z-index: 2;
    pointer-events: none;
}

/* Style pour les Inputs Flux */
.input-enhanced flux-input,
.input-enhanced input[type="email"],
.input-enhanced input[type="password"] {
    width: 100% !important;
    padding: 14px 16px 14px 44px !important;
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-radius: 12px !important;
    color: white !important;
    font-size: 1rem !important;
    transition: all 0.3s ease !important;
    outline: none !important;
}

.input-enhanced flux-input:focus,
.input-enhanced input[type="email"]:focus,
.input-enhanced input[type="password"]:focus {
    border-color: rgba(255, 255, 255, 0.4) !important;
    background: rgba(255, 255, 255, 0.15) !important;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1) !important;
}

.input-enhanced flux-input::placeholder,
.input-enhanced input::placeholder {
    color: rgba(255, 255, 255, 0.5) !important;
}

/* Lien "Forgot Password" */
.forgot-password {
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 0.875rem !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
}

.forgot-password:hover {
    color: white !important;
    text-decoration: underline !important;
}

/* Checkbox Container */
.checkbox-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Style pour Flux Checkbox */
.checkbox-enhanced {
    width: 20px !important;
    height: 20px !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 4px !important;
    background: rgba(255, 255, 255, 0.1) !important;
    transition: all 0.2s ease !important;
}

.checkbox-enhanced:checked {
    background: white !important;
    border-color: white !important;
}

.checkbox-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    cursor: pointer;
    user-select: none;
}

/* Bouton Principal - Ciblage correct pour Flux UI */
flux-button[variant="primary"],
flux-button.btn-primary,
.btn-primary,
button[type="submit"] {
    width: 100% !important;
    padding: 14px 24px !important;
    background: white !important;
    color: #2642bb !important;
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    position: relative !important;
    overflow: hidden !important;
    min-height: 48px !important;
    box-sizing: border-box !important;
}

/* Effets hover et états du bouton */
flux-button[variant="primary"]::before,
flux-button.btn-primary::before,
.btn-primary::before,
button[type="submit"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

flux-button[variant="primary"]:hover::before,
flux-button.btn-primary:hover::before,
.btn-primary:hover::before,
button[type="submit"]:hover::before {
    left: 100%;
}

flux-button[variant="primary"]:hover,
flux-button.btn-primary:hover,
.btn-primary:hover,
button[type="submit"]:hover {
    background: rgba(255, 255, 255, 0.95) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important;
}

flux-button[variant="primary"]:active,
flux-button.btn-primary:active,
.btn-primary:active,
button[type="submit"]:active {
    transform: translateY(0) !important;
}

flux-button[variant="primary"]:disabled,
flux-button.btn-primary:disabled,
.btn-primary:disabled,
button[type="submit"]:disabled {
    opacity: 0.7 !important;
    cursor: not-allowed !important;
    transform: none !important;
}

/* Animation de Loading */
.loading {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid rgba(102, 126, 234, 0.3);
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Messages d'Erreur */
.error-message {
    color: #ff6b6b;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    padding: 0.5rem;
    background: rgba(255, 107, 107, 0.1);
    border-radius: 8px;
    border-left: 3px solid #ff6b6b;
}

/* Messages de Succès */
.success-message {
    color: #51cf66;
    font-size: 0.9rem;
    padding: 0.75rem;
    background: rgba(81, 207, 102, 0.1);
    border-radius: 8px;
    margin-bottom: 1rem;
    text-align: center;
    border-left: 3px solid #51cf66;
}

/* Classes Utilitaires Tailwind Override */
.min-h-screen {
    min-height: 100vh;
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-center {
    justify-content: center;
}

.justify-end {
    justify-content: flex-end;
}

.justify-between {
    justify-content: space-between;
}

.flex-col {
    flex-direction: column;
}

.gap-6 {
    gap: 1.5rem;
}

.gap-2 {
    gap: 0.5rem;
}

.px-4 {
    padding-left: 1rem;
    padding-right: 1rem;
}

.mb-8 {
    margin-bottom: 2rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.text-center {
    text-align: center;
}

.relative {
    position: relative;
}

.z-10 {
    z-index: 10;
}

.w-8 {
    width: 2rem;
}

.h-8 {
    height: 2rem;
}

.w-full {
    width: 100%;
}

.p-8 {
    padding: 2rem;
}

/* Responsive Design */
@media (max-width: 640px) {
    .login-container {
        margin: 0 1rem;
    }
    
    .glass-effect {
        padding: 1.5rem;
    }
    
    .auth-header h1 {
        font-size: 1.5rem;
    }
    
    .logo-container {
        width: 60px;
        height: 60px;
    }
    
    .input-enhanced flux-input,
    .input-enhanced input {
        padding: 12px 16px 12px 40px !important;
    }
    
    .btn-primary {
        padding: 12px 20px !important;
    }
}

/* Correction pour les composants Flux spécifiques */
flux-input {
    display: block !important;
    width: 100% !important;
}

flux-button {
    display: inline-flex !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

flux-checkbox {
    display: inline-block !important;
}

flux-link {
    display: inline !important;
}

/* Forcer la largeur du conteneur du bouton */
.flex.items-center.justify-end {
    width: 100%;
}

.flex.items-center.justify-end flux-button {
    width: 100% !important;
}

/* Wire Loading States */
[wire\:loading] {
    display: none;
}

[wire\:loading\.block] {
    display: block;
}

[wire\:loading\.flex] {
    display: flex;
}

[wire\:loading\.remove] {
    display: none;
}

/* Focus States pour l'accessibilité */
.btn-primary:focus,
.checkbox-enhanced:focus,
flux-input:focus,
input:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5);
    outline-offset: 2px;
}
</style>
</head>
<body>


    {{ $slot }}
    @livewireScripts
</body>
</html>