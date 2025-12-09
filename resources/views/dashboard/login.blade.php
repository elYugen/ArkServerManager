@extends('base')
@section('title', 'Connexion')
@section('styles')
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 0 15px;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .auth-header img {
            max-width: 120px;
            margin-bottom: 1rem;
            filter: brightness(0) invert(1);
        }

        .auth-header h1 {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }

        .auth-body {
            padding: 2.5rem 2rem;
        }

        .form-switch-tabs {
            display: flex;
            background: #f8f9fa;
            border-radius: 50px;
            padding: 5px;
            margin-bottom: 2rem;
        }

        .form-switch-tabs button {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .form-switch-tabs button.active {
            background: white;
            color: #667eea;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
        }

        .input-group-text {
            background: white;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-tab-content {
            display: none;
        }

        .form-tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
@endsection
@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="https://cdn2.steamgriddb.com/logo_thumb/49616ab5001dd01538f33c56818f9478.png" alt="ARK Logo">
                <h1>Panel ASA</h1>
            </div>

            <div class="auth-body">
                <!-- Switch Tabs -->
                <div class="form-switch-tabs">
                    <button type="button" class="tab-btn active" data-tab="login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Connexion
                    </button>
                    <button type="button" class="tab-btn" data-tab="register">
                        <i class="bi bi-person-plus me-2"></i>Inscription
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- formulaire connexion -->
                <div class="form-tab-content active" id="login-form">
                    <form action="{{ route('auth.login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="login-email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Adresse Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="login-email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="clau@dine.com"
                                    required
                                    autofocus
                                >
                            </div>
                            @error('email')
                                <div class="text-danger mt-2">
                                    <small><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="login-password" class="form-label">
                                <i class="bi bi-lock me-1"></i>Mot de passe
                            </label>
                            <div class="input-group position-relative">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="login-password" 
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                >
                                <i class="bi bi-eye password-toggle" onclick="togglePassword('login-password', this)"></i>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </button>
                    </form>
                </div>

                <!-- formulaire inscription -->
                <div class="form-tab-content" id="register-form">
                    <form action="{{ route('auth.register') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="register-name" class="form-label">
                                <i class="bi bi-person me-1"></i>Pseudonyme
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="register-name" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    placeholder="Claudine"
                                    required
                                >
                            </div>
                            @error('name')
                                <div class="text-danger mt-2">
                                    <small><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register-email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Adresse Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="register-email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="votre@email.com"
                                    required
                                >
                            </div>
                            @error('email')
                                <div class="text-danger mt-2">
                                    <small><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register-password" class="form-label">
                                <i class="bi bi-lock me-1"></i>Mot de passe
                            </label>
                            <div class="input-group position-relative">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="register-password" 
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                >
                                <i class="bi bi-eye password-toggle" onclick="togglePassword('register-password', this)"></i>
                            </div>
                            <small class="text-muted">Minimum 8 caractères</small>
                            @error('password')
                                <div class="text-danger mt-2">
                                    <small><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</small>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register-password-confirm" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Confirmer le mot de passe
                            </label>
                            <div class="input-group position-relative">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="register-password-confirm" 
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    required
                                >
                                <i class="bi bi-eye password-toggle" onclick="togglePassword('register-password-confirm', this)"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-person-plus me-2"></i>Créer mon compte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <small class="text-white">
                <i class="bi bi-shield-check me-1"></i>Réalisé par Yugen © 2025
            </small>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // switch entre le login et l'inscription
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // supprime les class active de chaque onglet
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.form-tab-content').forEach(c => c.classList.remove('active'));
                
                // ajoute le tag active a une class
                this.classList.add('active');
                
                // montre le formulaire correspondant
                const tabName = this.dataset.tab;
                document.getElementById(tabName + '-form').classList.add('active');
            });
        });

        // affiche le mdp
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // switch auto sur inscription si il y a des erreurs dans le formulaire
        @error('name')
            document.querySelector('[data-tab="register"]').click();
        @enderror
    </script>
@endsection