<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Auth' }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <style>
        /* Base Styles */
.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.floating-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    animation: float 15s infinite linear;
}

.shape:nth-child(1) {
    width: 300px;
    height: 300px;
    top: -50px;
    left: -50px;
    animation-delay: 0s;
}

.shape:nth-child(2) {
    width: 200px;
    height: 200px;
    bottom: 100px;
    right: -50px;
    animation-delay: 3s;
}

.shape:nth-child(3) {
    width: 150px;
    height: 150px;
    top: 30%;
    right: 20%;
    animation-delay: 6s;
}

@keyframes float {
    0% {
        transform: translate(0, 0) rotate(0deg);
    }
    50% {
        transform: translate(50px, 50px) rotate(180deg);
    }
    100% {
        transform: translate(0, 0) rotate(360deg);
    }
}

/* Login Container */
.login-container {
    width: 100%;
    max-width: 420px;
    animation: fadeIn 0.5s ease-out;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        transform: translateY(20px);
        opacity: 0;
    }
    to { 
        transform: translateY(0);
        opacity: 1;
    }
}

.slide-up {
    animation: slideUp 0.5s ease-out;
}

/* Logo & Header */
.logo-container {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    margin-bottom: 1.5rem;
}

.auth-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.95rem;
}

/* Input Styles */
.input-enhanced {
    position: relative;
}

.input-enhanced label {
    display: block;
    margin-bottom: 0.5rem;
    color: white;
    font-weight: 500;
    font-size: 0.95rem;
}

.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: rgba(255, 255, 255, 0.7);
}

flux-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

flux-input:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.4);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

flux-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

/* Checkbox */
.checkbox-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.checkbox-label {
    color: white;
    font-size: 0.9rem;
    cursor: pointer;
}

.checkbox-enhanced {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.1);
    cursor: pointer;
    transition: all 0.2s ease;
}

.checkbox-enhanced:checked {
    background: white;
    border-color: white;
}

/* Button */
.btn-primary {
    width: 100%;
    padding: 12px 24px;
    background: white;
    color: #667eea;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Loading Animation */
.loading {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(102, 126, 234, 0.3);
    border-radius: 50%;
    border-top-color: #667eea;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Links */
.forgot-password {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
    text-decoration: none;
    transition: color 0.2s ease;
}

.forgot-password:hover {
    color: white;
    text-decoration: underline;
}

/* Messages */
.error-message {
    color: #ff6b6b;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.success-message {
    color: #51cf66;
    font-size: 0.9rem;
    padding: 0.75rem;
    background: rgba(81, 207, 102, 0.1);
    border-radius: 6px;
    margin-bottom: 1rem;
    text-align: center;
}
</style>
</head>
<body>


    {{ $slot }}
    @livewireScripts
</body>
</html>