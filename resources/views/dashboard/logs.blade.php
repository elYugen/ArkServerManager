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

        <div class="mt-5">
            <h2>Logs</h2>
            <div class="table-responsive">
                <table id="logsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Page</th>
                            <th>Log</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- lignes injectées par JS -->
                    </tbody>
                </table>
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

const logsTable = $('#logsTable').DataTable({
    responsive: true,
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
    }
});

// Chargement des logs
fetch('{{ route('logs.get') }}')
    .then(res => res.json())
    .then(data => {
        logsTable.clear();

        data.forEach(log => {
            logsTable.row.add([
                log.id,
                log.user ? log.user.name : "Inconnu",
                log.on_page,
                log.logs,
                new Date(log.created_at).toLocaleString()
            ]);
        });

        logsTable.draw();
    });

</script>
@endsection