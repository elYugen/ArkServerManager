@extends('base')
@section('title', 'Configuration')
@section('styles')
<link rel="stylesheet" href="//cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css">
<style>
    /* Main content area */
    .main-content {
        margin-left: 250px;
        transition: margin-left 0.3s ease-in-out;
        min-height: 100vh;
        padding-left: 0;
    }
    
    .main-content .container {
        max-width: 95%;
    }

    .config-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .alert {
        border-radius: 8px;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .config-card {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        h2 {
            font-size: 1.5rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 1rem;
        }
    }
</style>
@endsection

@section('content')
@include('navbar')

<!-- Main content area -->
<div class="main-content">
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="bi bi-gear-fill me-2"></i>Configuration du Serveur ARK
                </h2>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(!isset($config))
                    {{-- Formulaire de création si aucune config n'existe --}}
                    <div class="config-card">
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Aucune configuration n'a été trouvée. Veuillez créer votre première configuration de serveur ARK.
                        </div>

                        <form action="{{ route('configuration.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ip" class="form-label">
                                        <i class="bi bi-hdd-network me-1"></i>Adresse IP du Serveur
                                    </label>
                                    <input 
                                        type="text" 
                                        class="form-control @error('ip') is-invalid @enderror" 
                                        id="ip" 
                                        name="ip" 
                                        value="{{ old('ip') }}"
                                        placeholder="Ex: 192.168.1.100"
                                        required
                                    >
                                    @error('ip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">L'adresse IP de votre serveur ARK</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="port" class="form-label">
                                        <i class="bi bi-door-open me-1"></i>Port du Serveur
                                    </label>
                                    <input 
                                        type="number" 
                                        class="form-control @error('port') is-invalid @enderror" 
                                        id="port" 
                                        name="port" 
                                        value="{{ old('port', '27015') }}"
                                        placeholder="Ex: 27015"
                                        min="1"
                                        max="65535"
                                        required
                                    >
                                    @error('port')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Port de connexion (1-65535)</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key-fill me-1"></i>Mot de Passe (optionnel)
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    value="{{ old('password') }}"
                                    placeholder="Laissez vide si aucun mot de passe"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mot de passe de connexion au serveur (si nécessaire)</small>
                            </div>

                            <div class="mb-4">
                                <label for="shop_json_path" class="form-label">
                                    <i class="bi bi-folder2-open me-1"></i>Chemin du Fichier Shop JSON
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('shop_json_path') is-invalid @enderror" 
                                    id="shop_json_path" 
                                    name="shop_json_path" 
                                    value="{{ old('shop_json_path') }}"
                                    placeholder="Ex: /path/to/shop.json"
                                    required
                                >
                                @error('shop_json_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Chemin complet vers le fichier JSON de configuration du shop</small>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-plus-circle me-2"></i>Créer la Configuration
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Affichage de la config existante --}}
                    <div class="config-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0">
                                <i class="bi bi-server me-2"></i>Configuration du Serveur
                            </h4>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="bi bi-pencil-square me-1"></i>Modifier
                                </button>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-hdd-network text-primary me-2 fs-4"></i>
                                        <strong>Adresse IP</strong>
                                    </div>
                                    <p class="mb-0 fs-5">{{ $config->ip }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-door-open text-primary me-2 fs-4"></i>
                                        <strong>Port</strong>
                                    </div>
                                    <p class="mb-0 fs-5">{{ $config->port }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-key-fill text-primary me-2 fs-4"></i>
                                        <strong>Mot de passe</strong>
                                    </div>
                                    <p class="mb-0 fs-5">{{ $config->password ? '••••••••' : 'Non défini' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-folder2-open text-primary me-2 fs-4"></i>
                                        <strong>Chemin Shop JSON</strong>
                                    </div>
                                    <p class="mb-0 text-truncate" title="{{ $config->shop_json_path }}">{{ $config->shop_json_path }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-clock-history me-1"></i>
                                Dernière mise à jour: {{ $config->updated_at->format('d/m/Y à H:i') }}
                            </small>
                            <button type="button" class="btn btn-outline-success" onclick="testConnection()">
                                <i class="bi bi-wifi me-2"></i>Tester la Connexion
                            </button>
                        </div>
                    </div>

                    {{-- Modal de modification --}}
                    <div class="modal fade" id="editModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="bi bi-pencil-square me-2"></i>Modifier la Configuration
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('configuration.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="edit_ip" class="form-label">
                                                    <i class="bi bi-hdd-network me-1"></i>Adresse IP
                                                </label>
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    id="edit_ip" 
                                                    name="ip" 
                                                    value="{{ $config->ip }}"
                                                    required
                                                >
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="edit_port" class="form-label">
                                                    <i class="bi bi-door-open me-1"></i>Port
                                                </label>
                                                <input 
                                                    type="number" 
                                                    class="form-control" 
                                                    id="edit_port" 
                                                    name="port" 
                                                    value="{{ $config->port }}"
                                                    min="1"
                                                    max="65535"
                                                    required
                                                >
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_password" class="form-label">
                                                <i class="bi bi-key-fill me-1"></i>Mot de Passe
                                            </label>
                                            <input 
                                                type="password" 
                                                class="form-control" 
                                                id="edit_password" 
                                                name="password" 
                                                value="{{ $config->password }}"
                                                placeholder="Laissez vide si inchangé"
                                            >
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_shop_json_path" class="form-label">
                                                <i class="bi bi-folder2-open me-1"></i>Chemin Shop JSON
                                            </label>
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="edit_shop_json_path" 
                                                name="shop_json_path" 
                                                value="{{ $config->shop_json_path }}"
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal de suppression --}}
                    <div class="modal fade" id="deleteModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la Suppression
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer cette configuration ?</p>
                                    <p class="text-danger mb-0"><strong>Cette action est irréversible.</strong></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="{{ route('configuration.destroy', $config->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash me-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('footer')

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="//cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function testConnection() {
    // Vous pouvez implémenter ici un test de connexion AJAX
    alert('Fonctionnalité de test de connexion à implémenter');
}
</script>

@endsection