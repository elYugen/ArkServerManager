@extends('base')
@section('title', 'Dashboard')
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

    /* Responsive styles */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }

        /* Ajustements pour les modales sur mobile */
        .modal-dialog {
            margin: 0.5rem;
        }

        /* Table responsive */
        #playersTable {
            font-size: 0.875rem;
        }

        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
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

        <div class="mt-4">
            <h2>Liste des joueurs</h2>
            <div class="table-responsive">
                <table id="playersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>EosId</th>
                            <th>Points Boutique</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- content injecté par le js -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('footer')
<!-- Modal KickPlayer -->
<div class="modal fade" id="kickModal" tabindex="-1" aria-labelledby="kickModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kickModalLabel">Confirmer l'expulsion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment expulser <strong id="kickPlayerName"></strong> ?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="confirmKickBtn" class="btn btn-danger">Kick</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="//cdn.datatables.net/2.3.5/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

$(document).ready(function() {

    // UNE SEULE initialisation DataTable
    const table = $('#playersTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        }
    });

    let currentKickSteamID = null;

    // Chargement des joueurs depuis la BDD uniquement
    fetch('http://127.0.0.1:8000/player/show')
        .then(res => res.json())
        .then(data => {
            console.log('Données reçues:', data); // Pour debug
            
            // Vérifier si on a des données valides
            if (!data || !data.players || data.players.length === 0) {
                table.clear().draw();
                table.row.add(["-", "Aucun joueur dans la base", "-", "-"]).draw();
                return;
            }

            // Effacer le tableau avant de le remplir
            table.clear();

            // Ajouter chaque joueur dans le tableau
            data.players.forEach((player, index) => {
                table.row.add([
                    index + 1,
                    player.EosId || 'N/A',
                    player.Points !== undefined ? player.Points : 0,
                    `<button class="btn btn-sm btn-primary editPointsBtn" 
                        data-eosid="${player.EosId || ''}"
                        data-points="${player.Points || 0}">
                        <i class="bi bi-pencil"></i> Modifier
                    </button>`
                ]);
            });

            // Afficher le tableau mis à jour
            table.draw();
        })
        .catch(err => {
            console.error('Erreur lors du chargement:', err);
            table.clear().draw();
            table.row.add(["-", "Erreur de chargement", "-", "-"]).draw();
        });

    // OUVERTURE MODAL KICK
    $(document).on('click', '.kickBtn', function() {
        const name = $(this).data('name');
        const steamid = $(this).data('steamid');

        currentKickSteamID = steamid;
        $('#kickPlayerName').text(name);

        let modal = new bootstrap.Modal(document.getElementById('kickModal'));
        modal.show();
    });

    // CONFIRMATION DU KICK
    $('#confirmKickBtn').on('click', function() {
        if (!currentKickSteamID) return;

        fetch('/send-rcon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                command: `KickPlayer ${currentKickSteamID}`
            })
        })
        .then(res => res.json())
        .then(data => {
            alert("Commande envoyée");
            
            // Ferme la modal kick
            let kickModal = bootstrap.Modal.getInstance(document.getElementById('kickModal'));
            kickModal.hide();
            
            // Recharger la liste des joueurs après 2 secondes
            setTimeout(() => {
                location.reload();
            }, 2000);
        })
        .catch(err => alert("Erreur : " + err));

        currentKickSteamID = null;
    });

    // Envoi de la commande RCON via la modal
    $('#confirmSendCommand').on('click', function() {
        const command = $('#rconCommandInput').val();
        if (!command) return;
        
        // Ferme la modal d'envoi AVANT d'ouvrir la modal résultat
        let sendModal = bootstrap.Modal.getInstance(document.getElementById('rconModal'));
        sendModal.hide();
        
        fetch('/send-rcon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ command })
        })
        .then(res => res.json())
        .then(data => {
            setTimeout(() => {
                $('#rconResult').text(data.result || "Aucune réponse");
                let resultModal = new bootstrap.Modal(document.getElementById('rconResultModal'));
                resultModal.show();
            }, 300);
        })
        .catch(err => {
            setTimeout(() => {
                $('#rconResult').text('Erreur : ' + err);
                let resultModal = new bootstrap.Modal(document.getElementById('rconResultModal'));
                resultModal.show();
            }, 300);
        });
    });
});

</script>
@endsection