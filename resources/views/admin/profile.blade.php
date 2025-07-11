@extends('layouts.admin')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">

        {{-- Carte Profil --}}
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="avatar-container mb-4">
                        <img src="{{ asset('images/profil.jpg') }}" class="avatar-img" alt="Photo de profil">
                    </div>
                    <h3 class="card-title mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-4">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">
                            <i class="fas fa-user-shield me-2"></i>Administrateur
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Carte Modifier Profil --}}
        <div class="col-lg-8 col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Modifier le profil
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nom complet</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="name" value="{{ $user->name }}" 
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ $user->email }}" 
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Carte Mot de passe --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-lock me-2"></i>Changer le mot de passe
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.updatePassword') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Mot de passe actuel</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" name="current_password" 
                                       class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" 
                                       class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimum 8 caractères</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <input type="password" name="password_confirmation" 
                                       class="form-control" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-sync-alt me-2"></i>Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    :root {
        --primary-color: #4a148c;
        --primary-light: #f3e5f5;
    }
    
    body {
        background-color: #f8f9fa;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .avatar-container {
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--primary-light);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: var(--primary-light);
        color: var(--primary-color);
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    
    .form-label {
        font-weight: 500;
        color: #495057;
    }
    
    .input-group-text {
        background-color: var(--primary-light);
        color: var(--primary-color);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        padding: 10px 20px;
    }
    
    .btn-warning {
        background-color: #ff9800;
        border: none;
        padding: 10px 20px;
    }
    
    .toggle-password {
        cursor: pointer;
    }
</style>

<script>
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.parentNode.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });
</script>
@endsection