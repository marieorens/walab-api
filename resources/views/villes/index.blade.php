@extends('layout') {{-- CHANGE CECI SELON TON LAYOUT PRINCIPAL --}}

@section('page_content')
    <div class="container-fluid">

        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4" style="margin-top: 20px;">
            <h2 class="text-primary2 font-weight-bold">Gestion des Villes</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addVilleModal">
                + Ajouter une ville
            </button>
        </div>

        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tableau -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary2">Liste des villes disponibles</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de la ville</th>
                            <th>Statut</th>
                            <th>Date création</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($villes as $ville)
                            <tr>
                                <td>{{ $ville->id }}</td>
                                <td class="font-weight-bold">{{ $ville->nom }}</td>
                                <td>
                                    @if($ville->is_active)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-secondary">Inactivée</span>
                                    @endif
                                </td>
                                <td>{{ $ville->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <!-- Bouton Modifier -->
                                    <button class="btn btn-sm btn-info btn-edit"
                                            data-id="{{ $ville->id }}"
                                            data-nom="{{ $ville->nom }}"
                                            data-toggle="modal"
                                            data-target="#editVilleModal">
                                        <i class="fas fa-edit"></i> Modifier
                                    </button>

                                    <!-- Bouton Activer/Désactiver -->
                                    <a href="{{ route('villes.toggle', $ville->id) }}" class="btn btn-sm {{ $ville->is_active ? 'btn-warning' : 'btn-success' }}">
                                        @if($ville->is_active)
                                            <i class="fas fa-eye-slash"></i> Désactiver
                                        @else
                                            <i class="fas fa-eye"></i> Activer
                                        @endif
                                    </a>

                                    <!-- Bouton Supprimer -->
                                    <form action="{{ route('villes.destroy', $ville->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette ville ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Aucune ville enregistrée.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end">
                    {{ $villes->links() }}
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL AJOUT -->
    <div class="modal fade" id="addVilleModal" tabindex="-1" role="dialog" aria-labelledby="addVilleLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('villes.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addVilleLabel">Ajouter une nouvelle ville</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nom">Nom de la ville</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Cotonou" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL MODIFICATION -->
    <div class="modal fade" id="editVilleModal" tabindex="-1" role="dialog" aria-labelledby="editVilleLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="editVilleLabel">Modifier la ville</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nom">Nom de la ville</label>
                            <input type="text" name="nom" id="edit_nom" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-info">Mettre à jour</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts JS pour gérer la modale d'édition -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quand on clique sur un bouton "Modifier"
            const editButtons = document.querySelectorAll('.btn-edit');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nom = this.getAttribute('data-nom');

                    // Remplir le champ input
                    document.getElementById('edit_nom').value = nom;

                    // Mettre à jour l'action du formulaire avec le bon ID
                    const form = document.getElementById('editForm');
                    form.action = "/villes/" + id; // Assure-toi que l'URL correspond à ta route
                });
            });
        });
    </script>

@endsection
