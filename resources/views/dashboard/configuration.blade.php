@extends('base')
@section('title', 'Configuration')
@section('styles')
<link rel="stylesheet" href="//cdn.datatables.net/2.3.5/css/dataTables.dataTables.min.css">
<style>
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
        margin-bottom: 2rem;
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

    .section-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }

        .config-card {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('content')
@include('navbar')

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
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(!isset($config))
                    {{-- Formulaire de création --}}
                    <div class="config-card">
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Aucune configuration trouvée. Créez votre première configuration.
                        </div>

                        <form action="{{ route('configuration.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ip" class="form-label">
                                        <i class="bi bi-hdd-network me-1"></i>Adresse IP
                                    </label>
                                    <input type="text" class="form-control" id="ip" name="ip" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="port" class="form-label">
                                        <i class="bi bi-door-open me-1"></i>Port RCON
                                    </label>
                                    <input type="number" class="form-control" id="port" name="port" value="27015" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key-fill me-1"></i>Mot de passe RCON
                                </label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <div class="mb-4">
                                <label for="shop_json_path" class="form-label">
                                    <i class="bi bi-folder2-open me-1"></i>Chemin Shop JSON
                                </label>
                                <input type="text" class="form-control" id="shop_json_path" name="shop_json_path" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-plus-circle me-2"></i>Créer la Configuration
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Configuration Serveur --}}
                    <div class="config-card">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="mb-0"><i class="bi bi-server me-2"></i>Configuration Serveur</h4>
                            <div>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editServerModal">
                                    <i class="bi bi-pencil"></i> Modifier
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"><strong>IP:</strong> {{ $config->ip }}</div>
                            <div class="col-md-3"><strong>Port:</strong> {{ $config->port }}</div>
                            <div class="col-md-3"><strong>Password:</strong> {{ $config->password ? '••••••••' : 'Non défini' }}</div>
                            <div class="col-md-3"><strong>Fichier JSON:</strong> {{ basename($config->shop_json_path) }}</div>
                        </div>
                    </div>

                    {{-- Configuration Shop --}}
                    <div class="config-card">
                        <h4 class="mb-4"><i class="bi bi-cart-fill me-2"></i>Configuration ArkShop</h4>
                        
                        <div id="shopConfigLoader" class="text-center py-5">
                            <div class="spinner-border text-primary"></div>
                            <p class="mt-3 text-muted">Chargement...</p>
                        </div>

                        <div id="shopConfigContent" style="display: none;">
                            <form id="shopConfigForm">
                                @csrf
                                
                                {{-- MySQL --}}
                                <div class="section-title">
                                    <i class="bi bi-database me-2"></i>Configuration MySQL
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-2">
                                        <label class="form-label">Activer MySQL</label>
                                        <select class="form-select" name="Mysql[UseMysql]" id="mysql_use">
                                            <option value="true">Oui</option>
                                            <option value="false">Non</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Host</label>
                                        <input type="text" class="form-control" name="Mysql[MysqlHost]" id="mysql_host">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Port</label>
                                        <input type="number" class="form-control" name="Mysql[MysqlPort]" id="mysql_port">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">User</label>
                                        <input type="text" class="form-control" name="Mysql[MysqlUser]" id="mysql_user">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="Mysql[MysqlPass]" id="mysql_pass">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Database</label>
                                        <input type="text" class="form-control" name="Mysql[MysqlDB]" id="mysql_db">
                                    </div>
                                </div>

                                {{-- General --}}
                                <div class="section-title">
                                    <i class="bi bi-gear me-2"></i>Paramètres Généraux
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label">Intervalle de récompense (min)</label>
                                        <input type="number" class="form-control" name="General[TimedPointsReward][Interval]" id="reward_interval">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Articles par page</label>
                                        <input type="number" class="form-control" name="General[ItemsPerPage]" id="items_per_page">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Temps d'affichage (sec)</label>
                                        <input type="number" step="0.1" class="form-control" name="General[ShopDisplayTime]" id="shop_display_time">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Taille du texte</label>
                                        <input type="number" step="0.1" class="form-control" name="General[ShopTextSize]" id="shop_text_size">
                                    </div>
                                </div>

                                {{-- Messages --}}
                                <div class="section-title">
                                    <i class="bi bi-chat-left-text me-2"></i>Messages du Shop
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Expéditeur</label>
                                        <input type="text" class="form-control" name="Messages[Sender]" id="msg_sender">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Article acheté</label>
                                        <input type="text" class="form-control" name="Messages[BoughtItem]" id="msg_bought_item">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Dino acheté</label>
                                        <input type="text" class="form-control" name="Messages[BoughtDino]" id="msg_bought_dino">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Points reçus</label>
                                        <input type="text" class="form-control" name="Messages[ReceivedPoints]" id="msg_received_points">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Pas assez de points</label>
                                        <input type="text" class="form-control" name="Messages[NoPoints]" id="msg_no_points">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kit acheté</label>
                                        <input type="text" class="form-control" name="Messages[BoughtKit]" id="msg_bought_kit">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-save me-2"></i>Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div id="shopConfigError" class="alert alert-danger" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span id="shopConfigErrorMessage"></span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Server --}}
<div class="modal fade" id="editServerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier Configuration Serveur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('configuration.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">IP</label>
                        <input type="text" class="form-control" name="ip" value="{{ $config->ip ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Port</label>
                        <input type="number" class="form-control" name="port" value="{{ $config->port ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" value="{{ $config->password ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chemin JSON</label>
                        <input type="text" class="form-control" name="shop_json_path" value="{{ $config->shop_json_path ?? '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('footer')
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
@if(isset($config))
$(document).ready(function() {
    loadShopConfig();
});

function loadShopConfig() {
    const loader = $('#shopConfigLoader');
    const content = $('#shopConfigContent');
    const error = $('#shopConfigError');
    
    loader.show();
    content.hide();
    error.hide();
    
    $.get('{{ route("configuration.shop") }}', function(data) {
        console.log('Config loaded:', data);
        
        // MySQL
        $('#mysql_use').val(data.Mysql?.UseMysql ? 'true' : 'false');
        $('#mysql_host').val(data.Mysql?.MysqlHost || '');
        $('#mysql_port').val(data.Mysql?.MysqlPort || '');
        $('#mysql_user').val(data.Mysql?.MysqlUser || '');
        $('#mysql_pass').val(data.Mysql?.MysqlPass || '');
        $('#mysql_db').val(data.Mysql?.MysqlDB || '');
        
        // General
        $('#reward_interval').val(data.General?.TimedPointsReward?.Interval || '');
        $('#items_per_page').val(data.General?.ItemsPerPage || '');
        $('#shop_display_time').val(data.General?.ShopDisplayTime || '');
        $('#shop_text_size').val(data.General?.ShopTextSize || '');
        
        // Messages
        $('#msg_sender').val(data.Messages?.Sender || '');
        $('#msg_bought_item').val(data.Messages?.BoughtItem || '');
        $('#msg_bought_dino').val(data.Messages?.BoughtDino || '');
        $('#msg_received_points').val(data.Messages?.ReceivedPoints || '');
        $('#msg_no_points').val(data.Messages?.NoPoints || '');
        $('#msg_bought_kit').val(data.Messages?.BoughtKit || '');
        
        loader.hide();
        content.show();
    }).fail(function() {
        loader.hide();
        error.show();
        $('#shopConfigErrorMessage').text('Impossible de charger la configuration');
    });
}

$('#shopConfigForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = $(this).serializeArray();
    const config = {};
    
    formData.forEach(item => {
        if (item.name === '_token') return;
        
        const keys = item.name.match(/\[([^\]]+)\]/g).map(k => k.slice(1, -1));
        const topLevel = item.name.split('[')[0];
        
        if (!config[topLevel]) config[topLevel] = {};
        
        let current = config[topLevel];
        for (let i = 0; i < keys.length - 1; i++) {
            if (!current[keys[i]]) current[keys[i]] = {};
            current = current[keys[i]];
        }
        
        let value = item.value;
        if (value === 'true') value = true;
        else if (value === 'false') value = false;
        else if (!isNaN(value) && value !== '') value = parseFloat(value);
        
        current[keys[keys.length - 1]] = value;
    });
    
    $.ajax({
        url: '{{ route("configuration.shop.update") }}',
        method: 'POST',
        data: JSON.stringify(config),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            alert('✅ Configuration sauvegardée avec succès !');
        },
        error: function(xhr) {
            alert('❌ Erreur lors de la sauvegarde: ' + (xhr.responseJSON?.error || 'Erreur inconnue'));
        }
    });
});
@endif
</script>
@endsection