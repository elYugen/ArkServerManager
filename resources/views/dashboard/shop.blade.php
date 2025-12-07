@extends('base')
@section('title', 'Boutique')
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

    .badge-type {
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
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
                    <i class="bi bi-cart-fill me-2"></i>Gestion de la Boutique
                </h2>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Kits Section --}}
                <div class="config-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            <i class="bi bi-box-seam me-2"></i>Kits
                        </h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKitModal">
                            <i class="bi bi-plus-circle me-1"></i>Ajouter un Kit
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="kitsTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Prix</th>
                                    <th>Quantité</th>
                                    <th>Items</th>
                                    <th>Dinos</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rempli par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Shop Items Section --}}
                <div class="config-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            <i class="bi bi-bag me-2"></i>Articles de la Boutique
                        </h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                            <i class="bi bi-plus-circle me-1"></i>Ajouter un Article
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table id="itemsTable" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Prix</th>
                                    <th>Level</th>
                                    <th>Blueprint</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rempli par JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Add Kit --}}
<div class="modal fade" id="addKitModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-box-seam me-2"></i>Ajouter un Kit
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addKitForm">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du Kit *</label>
                            <input type="text" class="form-control" name="kit_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Prix (points)</label>
                            <input type="number" class="form-control" name="price" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quantité par défaut</label>
                            <input type="number" class="form-control" name="default_amount" value="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Permissions</label>
                            <input type="text" class="form-control" name="permissions" placeholder="Ex: Admins,Premiums">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Niveau Min</label>
                            <input type="number" class="form-control" name="min_level">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Niveau Max</label>
                            <input type="number" class="form-control" name="max_level">
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3"><i class="bi bi-bag me-2"></i>Items du Kit</h6>
                    <div id="kitItemsList">
                        <div class="kit-item-row row mb-2">
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Blueprint" name="items[0][blueprint]">
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" placeholder="Quantité" name="items[0][amount]" value="1">
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" placeholder="Qualité" name="items[0][quality]" value="0">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="items[0][force_blueprint]">
                                    <option value="false">Item</option>
                                    <option value="true">Blueprint</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeKitItem(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addKitItem()">
                        <i class="bi bi-plus"></i> Ajouter un Item
                    </button>

                    <hr class="mt-4">

                    <h6 class="mb-3"><i class="bi bi-dragon me-2"></i>Dinos du Kit</h6>
                    <div id="kitDinosList">
                        <div class="kit-dino-row row mb-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Blueprint Dino" name="dinos[0][blueprint]">
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" placeholder="Level" name="dinos[0][level]" value="10">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Blueprint Selle" name="dinos[0][saddle]">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeKitDino(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addKitDino()">
                        <i class="bi bi-plus"></i> Ajouter un Dino
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Créer le Kit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Add Item --}}
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-bag me-2"></i>Ajouter un Article
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addItemForm">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ID de l'article *</label>
                            <input type="text" class="form-control" name="item_id" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type *</label>
                            <select class="form-select" name="type" id="itemType" required>
                                <option value="item">Item</option>
                                <option value="dino">Dino</option>
                                <option value="beacon">Beacon</option>
                                <option value="experience">Experience</option>
                                <option value="command">Command</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Prix (points) *</label>
                            <input type="number" class="form-control" name="price" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Niveau Min</label>
                            <input type="number" class="form-control" name="min_level">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Niveau Max</label>
                            <input type="number" class="form-control" name="max_level">
                        </div>
                    </div>

                    {{-- Item Type Fields --}}
                    <div id="itemFields" class="type-fields">
                        <hr>
                        <h6>Configuration Item</h6>
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Blueprint</label>
                                <input type="text" class="form-control" name="item_blueprint">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantité</label>
                                <input type="number" class="form-control" name="item_amount" value="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Qualité</label>
                                <input type="number" class="form-control" name="item_quality" value="0">
                            </div>
                        </div>
                    </div>

                    {{-- Dino Type Fields --}}
                    <div id="dinoFields" class="type-fields" style="display:none;">
                        <hr>
                        <h6>Configuration Dino</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Blueprint Dino</label>
                                <input type="text" class="form-control" name="dino_blueprint">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Level</label>
                                <input type="number" class="form-control" name="dino_level" value="10">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="dino_gender">
                                    <option value="">Aléatoire</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="random">Random</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Blueprint Selle</label>
                                <input type="text" class="form-control" name="dino_saddle">
                            </div>
                        </div>
                    </div>

                    {{-- Beacon Type Fields --}}
                    <div id="beaconFields" class="type-fields" style="display:none;">
                        <hr>
                        <h6>Configuration Beacon</h6>
                        <div class="mb-3">
                            <label class="form-label">ClassName</label>
                            <input type="text" class="form-control" name="beacon_classname" placeholder="Ex: SupplyCrate_Level25_Double_C">
                        </div>
                    </div>

                    {{-- Experience Type Fields --}}
                    <div id="experienceFields" class="type-fields" style="display:none;">
                        <hr>
                        <h6>Configuration Experience</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Montant XP</label>
                                <input type="number" class="form-control" name="exp_amount" value="1000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Donner au Dino</label>
                                <select class="form-select" name="exp_give_to_dino">
                                    <option value="false">Non</option>
                                    <option value="true">Oui</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Command Type Fields --}}
                    <div id="commandFields" class="type-fields" style="display:none;">
                        <hr>
                        <h6>Configuration Commande</h6>
                        <div class="mb-3">
                            <label class="form-label">Commande</label>
                            <input type="text" class="form-control" name="command_text" placeholder="Ex: fly">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="command_as_admin" value="true">
                            <label class="form-check-label">Exécuter en tant qu'admin</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Créer l'Article
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Success --}}
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Succès
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Error --}}
<div class="modal fade" id="errorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Erreur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="errorMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal View Kit --}}
<div class="modal fade" id="viewKitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Détails du Kit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewKitContent">
                <!-- Contenu injecté dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal View Item --}}
<div class="modal fade" id="viewItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-eye me-2"></i>Détails de l'Article
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewItemContent">
                <!-- Contenu injecté dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Kit --}}
<div class="modal fade" id="editKitModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Modifier le Kit
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKitForm">
                <input type="hidden" id="edit_kit_name">
                <div class="modal-body">
                    <!-- Identique au form d'ajout mais pré-rempli -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_kit_description">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Prix (points)</label>
                            <input type="number" class="form-control" id="edit_kit_price">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="edit_kit_amount">
                        </div>
                    </div>
                    <div id="editKitItemsList"></div>
                    <div id="editKitDinosList"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Item --}}
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square me-2"></i>Modifier l'Article
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editItemForm">
                <input type="hidden" id="edit_item_id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_item_description">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Prix (points)</label>
                            <input type="number" class="form-control" id="edit_item_price">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Niveau Min</label>
                            <input type="number" class="form-control" id="edit_item_min_level">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Niveau Max</label>
                            <input type="number" class="form-control" id="edit_item_max_level">
                        </div>
                    </div>
                    <div id="editItemFields"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Sauvegarder
                    </button>
                </div>
            </form>
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
let shopConfig = {};
let kitItemCounter = 1;
let kitDinoCounter = 1;

$(document).ready(function() {
    loadShopData();
});

function loadShopData() {
    // Charger depuis la route configuration.shop (qui lit le JSON)
    $.get('{{ route("configuration.shop") }}', function(data) {
        shopConfig = data;
        console.log('Loaded shop config:', shopConfig);
        loadKitsTable();
        loadItemsTable();
    }).fail(function(xhr) {
        console.error('Load error:', xhr);
        showError('Impossible de charger les données du shop');
    });
}

function saveToServer(message) {
    console.log('=== SAVING TO SERVER ===');
    console.log('Route:', '{{ route("shop.save") }}');
    console.log('Full shopConfig:', JSON.stringify(shopConfig, null, 2));
    console.log('Kits count:', Object.keys(shopConfig.Kits || {}).length);
    console.log('ShopItems count:', Object.keys(shopConfig.ShopItems || {}).length);
    
    $.ajax({
        url: '{{ route("shop.save") }}',
        method: 'POST',
        data: JSON.stringify(shopConfig),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('=== SAVE SUCCESS ===');
            console.log('Response:', response);
            
            $('#successMessage').text(message);
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Fermer les modals d'ajout
            $('#addKitModal').modal('hide');
            $('#addItemModal').modal('hide');
            
            // Recharger depuis le serveur après un délai
            setTimeout(() => {
                console.log('Reloading data from server...');
                loadShopData();
            }, 500);
            
            // Reset forms
            $('#addKitForm')[0].reset();
            $('#addItemForm')[0].reset();
            kitItemCounter = 1;
            kitDinoCounter = 1;
            $('#kitItemsList .kit-item-row:not(:first)').remove();
            $('#kitDinosList .kit-dino-row:not(:first)').remove();
        },
        error: function(xhr) {
            console.error('=== SAVE ERROR ===');
            console.error('Status:', xhr.status);
            console.error('Response:', xhr.responseJSON);
            console.error('Full error:', xhr);
            showError(xhr.responseJSON?.error || 'Erreur lors de la sauvegarde');
        }
    });
}

function showError(message) {
    $('#errorMessage').text(message);
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
}

function loadKitsTable() {
    const kits = shopConfig.Kits || {};
    const kitsData = [];

    Object.entries(kits).forEach(([name, kit]) => {
        kitsData.push([
            name,
            kit.Description || '-',
            `<span class="badge bg-success">${kit.Price || 0} pts</span>`,
            kit.DefaultAmount || 0,
            kit.Items?.length || 0,
            kit.Dinos?.length || 0,
            `<div class="btn-group" role="group">
                <button class="btn btn-sm btn-info" onclick='viewKit(${JSON.stringify(name)})' title="Voir">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-primary" onclick='editKit(${JSON.stringify(name)})' title="Modifier">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick='deleteKit(${JSON.stringify(name)})' title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </div>`
        ]);
    });

    if ($.fn.DataTable.isDataTable('#kitsTable')) {
        $('#kitsTable').DataTable().destroy();
    }

    $('#kitsTable').DataTable({
        data: kitsData,
    });
}

function loadItemsTable() {
    const items = shopConfig.ShopItems || {};
    const itemsData = [];

    const typeColors = {
        'item': 'primary',
        'dino': 'success',
        'beacon': 'warning',
        'experience': 'info',
        'command': 'secondary'
    };

    Object.entries(items).forEach(([id, item]) => {
        const level = item.MinLevel && item.MaxLevel 
            ? `${item.MinLevel}-${item.MaxLevel}`
            : item.Level || '-';

        itemsData.push([
            `<code>${id}</code>`,
            `<span class="badge badge-type bg-${typeColors[item.Type] || 'secondary'}">${item.Type}</span>`,
            item.Description || '-',
            `<span class="badge bg-success">${item.Price || 0} pts</span>`,
            level,
            item.Blueprint ? `<small>${item.Blueprint.substring(0, 30)}...</small>` : '-',
            `<div class="btn-group" role="group">
                <button class="btn btn-sm btn-info" onclick='viewItem(${JSON.stringify(id)})' title="Voir">
                    <i class="bi bi-eye"></i>
                </button>
                <button class="btn btn-sm btn-primary" onclick='editItem(${JSON.stringify(id)})' title="Modifier">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick='deleteItem(${JSON.stringify(id)})' title="Supprimer">
                    <i class="bi bi-trash"></i>
                </button>
            </div>`
        ]);
    });

    if ($.fn.DataTable.isDataTable('#itemsTable')) {
        $('#itemsTable').DataTable().destroy();
    }

    $('#itemsTable').DataTable({
        data: itemsData,
    });
}

// Kit Items Management
function addKitItem() {
    const html = `
        <div class="kit-item-row row mb-2">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Blueprint" name="items[${kitItemCounter}][blueprint]">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" placeholder="Quantité" name="items[${kitItemCounter}][amount]" value="1">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" placeholder="Qualité" name="items[${kitItemCounter}][quality]" value="0">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="items[${kitItemCounter}][force_blueprint]">
                    <option value="false">Item</option>
                    <option value="true">Blueprint</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeKitItem(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    $('#kitItemsList').append(html);
    kitItemCounter++;
}

function removeKitItem(btn) {
    $(btn).closest('.kit-item-row').remove();
}

function addKitDino() {
    const html = `
        <div class="kit-dino-row row mb-2">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Blueprint Dino" name="dinos[${kitDinoCounter}][blueprint]">
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" placeholder="Level" name="dinos[${kitDinoCounter}][level]" value="10">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Blueprint Selle" name="dinos[${kitDinoCounter}][saddle]">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeKitDino(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    $('#kitDinosList').append(html);
    kitDinoCounter++;
}

function removeKitDino(btn) {
    $(btn).closest('.kit-dino-row').remove();
}

// Change item type fields
$('#itemType').on('change', function() {
    $('.type-fields').hide();
    const type = $(this).val();
    $(`#${type}Fields`).show();
});

// Submit Kit Form
$('#addKitForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const kitName = formData.get('kit_name');
    
    const kitData = {
        Description: formData.get('description') || '',
        Price: parseInt(formData.get('price')) || 0,
        DefaultAmount: parseInt(formData.get('default_amount')) || 1
    };

    if (formData.get('permissions')) {
        kitData.Permissions = formData.get('permissions');
    }
    if (formData.get('min_level')) {
        kitData.MinLevel = parseInt(formData.get('min_level'));
    }
    if (formData.get('max_level')) {
        kitData.MaxLevel = parseInt(formData.get('max_level'));
    }

    // Items
    const items = [];
    $('.kit-item-row').each(function() {
        const blueprint = $(this).find('input[name*="[blueprint]"]').val();
        if (blueprint) {
            items.push({
                Blueprint: blueprint,
                Amount: parseInt($(this).find('input[name*="[amount]"]').val()) || 1,
                Quality: parseInt($(this).find('input[name*="[quality]"]').val()) || 0,
                ForceBlueprint: $(this).find('select[name*="[force_blueprint]"]').val() === 'true'
            });
        }
    });
    if (items.length > 0) kitData.Items = items;

    // Dinos
    const dinos = [];
    $('.kit-dino-row').each(function() {
        const blueprint = $(this).find('input[name*="[blueprint]"]').val();
        if (blueprint) {
            const dino = {
                Blueprint: blueprint,
                Level: parseInt($(this).find('input[name*="[level]"]').val()) || 10
            };
            const saddle = $(this).find('input[name*="[saddle]"]').val();
            if (saddle) dino.SaddleBlueprint = saddle;
            dinos.push(dino);
        }
    });
    if (dinos.length > 0) kitData.Dinos = dinos;

    // Ajouter au config local
    if (!shopConfig.Kits) shopConfig.Kits = {};
    shopConfig.Kits[kitName] = kitData;

    // Sauvegarder via la route dédiée
    saveToServer('Kit créé avec succès');
});

// Submit Item Form
$('#addItemForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const itemId = formData.get('item_id');
    const type = formData.get('type');
    
    const itemData = {
        Type: type,
        Description: formData.get('description') || '',
        Price: parseInt(formData.get('price')) || 0
    };

    if (formData.get('min_level')) itemData.MinLevel = parseInt(formData.get('min_level'));
    if (formData.get('max_level')) itemData.MaxLevel = parseInt(formData.get('max_level'));

    // Type specific fields
    if (type === 'item') {
        const blueprint = formData.get('item_blueprint');
        if (blueprint) {
            itemData.Items = [{
                Blueprint: blueprint,
                Amount: parseInt(formData.get('item_amount')) || 1,
                Quality: parseInt(formData.get('item_quality')) || 0,
                ForceBlueprint: false
            }];
        }
    } else if (type === 'dino') {
        const blueprint = formData.get('dino_blueprint');
        if (blueprint) {
            itemData.Blueprint = blueprint;
            itemData.Level = parseInt(formData.get('dino_level')) || 10;
            if (formData.get('dino_gender')) itemData.Gender = formData.get('dino_gender');
            const saddle = formData.get('dino_saddle');
            if (saddle) itemData.SaddleBlueprint = saddle;
        }
    } else if (type === 'beacon') {
        itemData.ClassName = formData.get('beacon_classname');
    } else if (type === 'experience') {
        itemData.Amount = parseFloat(formData.get('exp_amount')) || 1000;
        itemData.GiveToDino = formData.get('exp_give_to_dino') === 'true';
    } else if (type === 'command') {
        const commandText = formData.get('command_text');
        if (commandText) {
            itemData.Items = [{
                Command: commandText,
                ExecuteAsAdmin: formData.get('command_as_admin') === 'true'
            }];
        }
    }

    if (!shopConfig.ShopItems) shopConfig.ShopItems = {};
    shopConfig.ShopItems[itemId] = itemData;

    saveShopConfig('Article créé avec succès');
});

function deleteKit(kitName) {
    if (confirm(`Voulez-vous vraiment supprimer le kit "${kitName}" ?`)) {
        delete shopConfig.Kits[kitName];
        saveShopConfig('Kit supprimé avec succès');
    }
}

function deleteItem(itemId) {
    if (confirm(`Voulez-vous vraiment supprimer l'article "${itemId}" ?`)) {
        delete shopConfig.ShopItems[itemId];
        saveShopConfig('Article supprimé avec succès');
    }
}

function saveShopConfig(message) {
    $.ajax({
        url: '{{ route("shop.save") }}',
        method: 'POST',
        data: JSON.stringify(shopConfig),
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function() {
            $('#successMessage').text(message);
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            
            // Fermer les modals d'ajout et recharger les tables
            $('#addKitModal').modal('hide');
            $('#addItemModal').modal('hide');
            loadShopData();
            
            // Reset forms
            $('#addKitForm')[0].reset();
            $('#addItemForm')[0].reset();
            kitItemCounter = 1;
            kitDinoCounter = 1;
            $('#kitItemsList .kit-item-row:not(:first)').remove();
            $('#kitDinosList .kit-dino-row:not(:first)').remove();
        },
        error: function(xhr) {
            showError(xhr.responseJSON?.error || 'Erreur lors de la sauvegarde');
        }
    });
}

function showError(message) {
    $('#errorMessage').text(message);
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
}

// View Kit
function viewKit(kitName) {
    const kit = shopConfig.Kits[kitName];
    if (!kit) return;
    
    let html = `
        <h5 class="mb-3"><strong>${kitName}</strong></h5>
        <div class="row mb-3">
            <div class="col-md-6"><strong>Description:</strong> ${kit.Description || '-'}</div>
            <div class="col-md-3"><strong>Prix:</strong> ${kit.Price || 0} pts</div>
            <div class="col-md-3"><strong>Quantité:</strong> ${kit.DefaultAmount || 1}</div>
        </div>
        ${kit.Permissions ? `<p><strong>Permissions:</strong> ${kit.Permissions}</p>` : ''}
        ${kit.MinLevel ? `<p><strong>Niveau Min:</strong> ${kit.MinLevel}</p>` : ''}
        ${kit.MaxLevel ? `<p><strong>Niveau Max:</strong> ${kit.MaxLevel}</p>` : ''}
        <hr>
    `;
    
    if (kit.Items && kit.Items.length > 0) {
        html += '<h6 class="mt-3"><i class="bi bi-bag me-2"></i>Items:</h6><ul class="list-group mb-3">';
        kit.Items.forEach(item => {
            html += `<li class="list-group-item">
                <strong>Blueprint:</strong> <code>${item.Blueprint}</code><br>
                <strong>Quantité:</strong> ${item.Amount} | <strong>Qualité:</strong> ${item.Quality} | <strong>Force Blueprint:</strong> ${item.ForceBlueprint ? 'Oui' : 'Non'}
            </li>`;
        });
        html += '</ul>';
    }
    
    if (kit.Dinos && kit.Dinos.length > 0) {
        html += '<h6 class="mt-3"><i class="bi bi-dragon me-2"></i>Dinos:</h6><ul class="list-group">';
        kit.Dinos.forEach(dino => {
            html += `<li class="list-group-item">
                <strong>Blueprint:</strong> <code>${dino.Blueprint}</code><br>
                <strong>Level:</strong> ${dino.Level || 10}
                ${dino.SaddleBlueprint ? `<br><strong>Selle:</strong> <code>${dino.SaddleBlueprint}</code>` : ''}
            </li>`;
        });
        html += '</ul>';
    }
    
    if (!kit.Items && !kit.Dinos) {
        html += '<p class="text-muted">Ce kit ne contient aucun item ou dino.</p>';
    }
    
    $('#viewKitContent').html(html);
    const modal = new bootstrap.Modal(document.getElementById('viewKitModal'));
    modal.show();
}

// View Item
function viewItem(itemId) {
    const item = shopConfig.ShopItems[itemId];
    if (!item) return;
    
    const typeColors = {
        'item': 'primary',
        'dino': 'success',
        'beacon': 'warning',
        'experience': 'info',
        'command': 'secondary'
    };
    
    let html = `
        <h5 class="mb-3"><code>${itemId}</code></h5>
        <div class="row mb-3">
            <div class="col-md-3"><strong>Type:</strong> <span class="badge bg-${typeColors[item.Type] || 'secondary'}">${item.Type}</span></div>
            <div class="col-md-9"><strong>Description:</strong> ${item.Description || '-'}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4"><strong>Prix:</strong> ${item.Price || 0} pts</div>
            <div class="col-md-4"><strong>Level Min:</strong> ${item.MinLevel || '-'}</div>
            <div class="col-md-4"><strong>Level Max:</strong> ${item.MaxLevel || '-'}</div>
        </div>
        <hr>
    `;
    
    if (item.Blueprint) {
        html += `<p><strong>Blueprint:</strong><br><code>${item.Blueprint}</code></p>`;
    }
    
    if (item.Level) {
        html += `<p><strong>Level:</strong> ${item.Level}</p>`;
    }
    
    if (item.Gender) {
        html += `<p><strong>Genre:</strong> ${item.Gender}</p>`;
    }
    
    if (item.SaddleBlueprint) {
        html += `<p><strong>Selle:</strong><br><code>${item.SaddleBlueprint}</code></p>`;
    }
    
    if (item.ClassName) {
        html += `<p><strong>ClassName:</strong> ${item.ClassName}</p>`;
    }
    
    if (item.Amount) {
        html += `<p><strong>Montant XP:</strong> ${item.Amount}</p>`;
    }
    
    if (item.GiveToDino !== undefined) {
        html += `<p><strong>Donner au Dino:</strong> ${item.GiveToDino ? 'Oui' : 'Non'}</p>`;
    }
    
    if (item.Items && item.Items.length > 0) {
        html += '<h6 class="mt-3">Items/Commandes:</h6><ul class="list-group">';
        item.Items.forEach(i => {
            if (i.Command) {
                html += `<li class="list-group-item">
                    <strong>Commande:</strong> <code>${i.Command}</code><br>
                    <strong>Exécuter en Admin:</strong> ${i.ExecuteAsAdmin ? 'Oui' : 'Non'}
                </li>`;
            } else {
                html += `<li class="list-group-item">
                    <strong>Blueprint:</strong> <code>${i.Blueprint}</code><br>
                    Quantité: ${i.Amount}, Qualité: ${i.Quality}
                </li>`;
            }
        });
        html += '</ul>';
    }
    
    $('#viewItemContent').html(html);
    const modal = new bootstrap.Modal(document.getElementById('viewItemModal'));
    modal.show();
}

// Edit Kit
function editKit(kitName) {
    const kit = shopConfig.Kits[kitName];
    if (!kit) return;
    
    $('#edit_kit_name').val(kitName);
    $('#edit_kit_description').val(kit.Description || '');
    $('#edit_kit_price').val(kit.Price || 0);
    $('#edit_kit_amount').val(kit.DefaultAmount || 1);
    
    const modal = new bootstrap.Modal(document.getElementById('editKitModal'));
    modal.show();
}

// Edit Item
function editItem(itemId) {
    const item = shopConfig.ShopItems[itemId];
    if (!item) return;
    
    $('#edit_item_id').val(itemId);
    $('#edit_item_description').val(item.Description || '');
    $('#edit_item_price').val(item.Price || 0);
    $('#edit_item_min_level').val(item.MinLevel || '');
    $('#edit_item_max_level').val(item.MaxLevel || '');
    
    const modal = new bootstrap.Modal(document.getElementById('editItemModal'));
    modal.show();
}

// Submit Edit Kit
$('#editKitForm').on('submit', function(e) {
    e.preventDefault();
    
    const kitName = $('#edit_kit_name').val();
    if (!shopConfig.Kits[kitName]) return;
    
    shopConfig.Kits[kitName].Description = $('#edit_kit_description').val();
    shopConfig.Kits[kitName].Price = parseInt($('#edit_kit_price').val()) || 0;
    shopConfig.Kits[kitName].DefaultAmount = parseInt($('#edit_kit_amount').val()) || 1;
    
    $('#editKitModal').modal('hide');
    saveToServer('Kit modifié avec succès');
});

// Submit Edit Item
$('#editItemForm').on('submit', function(e) {
    e.preventDefault();
    
    const itemId = $('#edit_item_id').val();
    if (!shopConfig.ShopItems[itemId]) return;
    
    shopConfig.ShopItems[itemId].Description = $('#edit_item_description').val();
    shopConfig.ShopItems[itemId].Price = parseInt($('#edit_item_price').val()) || 0;
    
    const minLevel = $('#edit_item_min_level').val();
    const maxLevel = $('#edit_item_max_level').val();
    
    if (minLevel) {
        shopConfig.ShopItems[itemId].MinLevel = parseInt(minLevel);
    } else {
        delete shopConfig.ShopItems[itemId].MinLevel;
    }
    
    if (maxLevel) {
        shopConfig.ShopItems[itemId].MaxLevel = parseInt(maxLevel);
    } else {
        delete shopConfig.ShopItems[itemId].MaxLevel;
    }
    
    $('#editItemModal').modal('hide');
    saveToServer('Article modifié avec succès');
});

</script>
@endsection