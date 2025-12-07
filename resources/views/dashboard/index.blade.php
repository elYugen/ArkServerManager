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
        <!-- Bouton pour ouvrir la modal d'envoi de commande -->
        <button id="sendCommandBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rconModal">
            <i class="bi bi-terminal"></i> Envoyer une commande RCON
        </button>

        <!-- Modal Bootstrap pour envoyer la commande -->
        <div class="modal fade" id="rconModal" tabindex="-1" aria-labelledby="rconModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rconModalLabel">Envoyer une commande RCON</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="rconCommandInput" class="form-control" placeholder="Entrez la commande RCON">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="confirmSendCommand">Envoyer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Bootstrap pour afficher le résultat -->
        <div class="modal fade" id="rconResultModal" tabindex="-1" aria-labelledby="rconResultModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rconResultModalLabel">Résultat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <pre id="rconResult" style="white-space: pre-wrap; word-wrap: break-word;"></pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h2>Joueurs connectés</h2>
            <div class="table-responsive">
                <table id="playersTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>EosId</th>
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
                <h5 class="modal-title" id="kickModalLabel">Confirmer l’expulsion</h5>
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

    });

    let currentKickSteamID = null;

    // Chargement des joueurs
    fetch('http://127.0.0.1:8000/players')
        .then(res => res.json())
        .then(data => {
            let raw = data.players_raw;

            if (!raw || raw.toLowerCase().includes("no players")) {
                table.clear().draw();
                table.row.add(["-", "Aucun joueur en ligne", "-", "-"]).draw();
                return;
            }

            let lines = raw
                .replace(/\u0000/g, '')
                .trim()
                .split("\n")
                .filter(line => line.trim().length > 0);

            let players = [];

            lines.forEach(line => {
                let match = line.match(/^\d+\.\s*(.*?),\s*(.*)$/);
                if (match) {
                    players.push({
                        name: match[1],
                        steamid: match[2]
                    });
                }
            });

            table.clear();

            players.forEach((p, i) => {
                table.row.add([
                    i + 1,
                    p.name,
                    p.steamid,
                    `<button class="btn btn-sm btn-danger kickBtn" 
                        data-name="${p.name}" 
                        data-steamid="${p.steamid}">
                        <i class="bi bi-hammer"></i>
                    </button>`
                ]);
            });

            table.draw();
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