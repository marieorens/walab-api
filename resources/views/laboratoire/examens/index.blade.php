@extends('laboratoire.layout')
@section('page_content')

<div class="container-fluid">
    <!-- page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="#">Walab</a></li>
                        <li class="breadcrumb-item"><a href="{{route('laboratoire.dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Mes Examens</li>
                    </ol>  
                </div>
                <h4 class="page-title">Mes Examens</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div class="table-data__tool">
                <div class="table-data__tool-right">
                    <a data-bs-toggle="modal" data-bs-target="#createModal">
                        <button class="btn" style="background:#667eea; color: white; mb-3;">
                            <i class="zmdi zmdi-plus"></i> Ajouter un Examen
                        </button>
                    </a>
                </div>
            </div>

            <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                <input type="search" class="form-control rounded me-2" placeholder="Rechercher..." id="searchInput">
                <button id="searchButton" class="btn pr-4 rounded" style="background:#667eea; color: white; mb-3;">
                    <span class="ri-search-line"></span>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-3">  
                        <div class="card-widgets">
                            <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false"><i class="ri-subtract-line"></i></a>
                        </div>
                        <h5 class="header-title mb-0">Liste de mes Examens</h5>
                    </div>
                    
                    @if (session('success'))
                        <div class="alert alert-success" role="alert" style="margin-left: auto; margin-right: auto; max-width: fit-content;">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert" style="margin-left: auto; margin-right: auto; max-width: fit-content;">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif
                    
                    <div id="yearly-sales-collapse" class="collapse show">
                        <div class="table-responsive table-data">
                            <table class="table table-nowrap table-hover mb-2">
                                <thead style="background:#667eea; color: white; mb-3;">
                                    <tr>
                                        <th class="text-white text-center">Image</th>
                                        <th class="text-white text-center">Titre</th>
                                        <th class="text-white text-center">Prix</th>
                                        <th class="text-white text-center">Description</th>
                                        <th class="text-white text-center">Statut</th>
                                        <th class="text-white text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($examens as $item)
                                    <tr class="tr-shadow">
                                        <td class="text-center">
                                            <img src="{{ asset($item->icon) }}" class="rounded shadow" alt="image" style="width: 50px; height: 50px;">
                                        </td>
                                        <td class="text-center">{{$item->label}}</td>
                                        <td class="text-center">{{$item->price}} FCFA</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#descModal{{$item->id}}">
                                                <i class="bi bi-eye"></i> Lire 
                                            </button>  
                                        </td>
                                        <td class="text-center">
                                            @if($item->isactive)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-warning">Inactif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="table-data-feature d-flex justify-content-center">       
                                                <button class="btn btn-warning btn-circle mx-1" data-bs-toggle="modal" data-bs-target="#editModal{{$item->id}}" title="Modifier">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                
                                                @if($item->isactive)
                                                    <a href="{{url('laboratoire/examen/desactiver', $item->id)}}" class="btn btn-secondary btn-circle mx-1" title="Désactiver">
                                                        <i class="ri-pause-circle-line"></i>
                                                    </a>
                                                @else
                                                    <a href="{{url('laboratoire/examen/activer', $item->id)}}" class="btn btn-success btn-circle mx-1" title="Activer">
                                                        <i class="ri-play-circle-line"></i>
                                                    </a>
                                                @endif
                                                
                                                <a href="{{url('laboratoire/examen/supprimer', $item->id)}}" class="btn btn-danger btn-circle mx-1" onclick="return confirm('Voulez-vous vraiment supprimer?')" title="Supprimer">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Description -->
                                    <div class="modal fade" id="descModal{{$item->id}}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Description - {{$item->label}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{$item->description}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editModal{{$item->id}}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background:#667eea;">
                                                    <h5 class="modal-title text-white">Modifier l'Examen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{route('laboratoire.examen.update', $item->id)}}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-2">
                                                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control form-control-sm" name="label" value="{{$item->label}}" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control form-control-sm" name="price" value="{{$item->price}}" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control form-control-sm" name="description" rows="2">{{$item->description}}</textarea>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">Image</label>
                                                            <input type="file" class="form-control form-control-sm" name="icon" accept="image/*">
                                                            <small class="text-muted">Laisser vide pour garder l'image actuelle</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn text-white" style="background:#667eea;">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $examens->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#667eea;">
                <h5 class="modal-title text-white">Ajouter un Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{route('laboratoire.examen.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="label" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control form-control-sm" name="price" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea class="form-control form-control-sm" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control form-control-sm" name="icon" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background:#667eea;">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
