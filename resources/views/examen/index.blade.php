@extends('layout')
    @section('page_content')

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Walab</a></li>
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboards</a></li>
                                <li class="breadcrumb-item"><a href="{{route('laboratories.index')}}">Laboratoires</a></li>
                                <li class="breadcrumb-item active">Examen</li>
                            </ol>  
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('laboratories.index') }}" class="btn btn-secondary btn-sm me-3">
                                <i class="ri-arrow-left-line me-1"></i> Retour
                            </a>
                            <h4 class="page-title mb-0">Examens @if(isset($laboratorie)) - <span class="text-primary">{{ $laboratorie->name }}</span> @endif</h4>
                        </div>

                    </div>

                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="table-data__tool">
                        <div class="table-data__tool-right">
                        @if($user_auth->role_id == 4)
                            <a data-bs-toggle="modal" data-bs-target="#createModal">
                                <button class="btn btn-primary mb-3">
                                    <i class="zmdi zmdi-plus"></i> Ajouter
                                </button>
                            </a>
                        @endif
                        </div>
                    </div>

                    <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                        <input type="text" hidden value="examens" id="tablesearch">
                        <input type="search" class="form-control rounded me-2" placeholder="Tape ici..." id="searchInput" name="query">
                        <button id="searchButton" class="btn btn-primary pr-4 rounded">
                            <span class="ri-search-line"></span>
                        </button>
                    </div>
                </div>
            </div>



<div class="row">
    <div class="col-lg-12">
        <!-- Todo-->
        <div class="card">
            <div class="card-body p-0">
                <div class="p-3">  
                    <div class="card-widgets">
                        <a href="{{route('exporter.examen')}}"><i class="mdi mdi-microsoft-excel" style="color: #008000;"></i></a>
                        <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                    </div>
                    <h5 class="header-title mb-0">Listes des Examens</h5>
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
                            <thead class="bg-primary">
                                <tr>
                                    <!-- <th class="text-white text-center">Image</th> -->
                                    <th class="text-white text-center">Nom</th>
                                    <th class="text-white text-center">Prix</th>
                                    <th class="text-white text-center">Description</th>
                                    <th class="text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($examens as $item)     
                                <tr class="tr-shadow">
                                    <!-- <td  class="  text-center"><img src="{{ asset($item->icon) }}" class="rounded shadow" alt="image_examen" style="width: 50px; height: 50px;"></td> -->
                                    <td  class=" text-center">{{ $item->label }}</td>
                                    <td  class=" text-center">{{ $item->price }} FCFA</td>
                                    <td  class=" text-center">
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal{{ $item->id }}">
                                            <i class="bi bi-eye"></i> Lire 
                                        </button>
                                    </td> 
                                    <td  class="text-center">
                                        <div class="table-data-feature d-flex justify-content-center align-items-center">
                                            <a data-bs-toggle="modal" data-bs-target="#viewModal{{ $item->id }}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <i class="bi bi-eye text-white"></i>
                                            </a>
                                            <a data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </a>
                                            <!-- <a data-bs-toggle="modal" data-bs-target="#confirmBloquerModal{{ $item->id }}" class="btn {{ !$item->isactive ? 'btn-success' : 'btn-danger' }} btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title={{($item->isactive) ? "désactivé" : "activé"}} >
                                                <i class="mdi mdi-lock-open text-white"></i>
                                            </a> -->
                                            @if($user_auth->role_id == 4)
                                            <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="bi bi-trash text-white"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal View-->
                                <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered"> 
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        
                                        <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                    @isset($item->icon)
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">
                                                                    <i class="bi bi-image"></i> Image
                                                                </label>
                                                                <img src="{{asset($item->icon)}}" alt="image" class="img-fluid rounded">
                                                            </div>
                                                        </div>
                                                    @endisset

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-tag"></i> Nom
                                                            </label>
                                                            <p class="form-control-static">{{$item->label}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-currency-dollar"></i> Prix
                                                            </label>
                                                            <p class="form-control-static">{{$item->price}} FCFA</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <label class="form-label">
                                                                <i class="bi bi-file-earmark-text"></i> Description
                                                            </label>
                                                            <div style="
                                                                white-space: pre-wrap;
                                                                font-family: 'Arial', sans-serif; /* Choisissez une police qui vous convient */
                                                                font-size: 16px; /* Taille de la police */
                                                                line-height: 1.5; /* Hauteur de ligne pour une meilleure lisibilité */
                                                                color: #333; /* Couleur du texte */
                                                                background-color: #f9f9f9; /* Couleur de fond légère */
                                                                padding: 15px; /* Espacement intérieur */
                                                                border-radius: 8px; /* Coins arrondis pour un look moderne */
                                                                box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Ombre légère pour donner de la profondeur */
                                                            ">
                                                                <p class="form-control-static">{!! nl2br(e($item->description)) !!}</p>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Modifier-->
                                <div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" aria-labelledby="updateModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered"> 
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{route('examen_update', $item->id)}}" enctype="multipart/form-data" class="form-horizontal">
                                                    @csrf
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <label for="label" class="form-control-label">Nom</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" id="label" require="True" name="label" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($item) ? $item->label : old('label')}}" required>
                                                        </div>
                                                        @if ($errors->has('label'))
                                                            <span class="text-danger">{{ $errors->first('label') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <label for="laboratorie_id" class="form-control-label">Laboratoire</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select require="True" name="laboratorie_id" id="laboratorie_id" class="form-control rounded-pill focus:ring focus:ring-opacity-50" data-toggle="select2">
                                                                <option>Select</option>
                                                                    <option selected value="{{$item->laboratorie->id}}">{{$item->laboratorie->name}}</option>
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('laboratorie_id'))
                                                            <span class="text-danger">{{ $errors->first('laboratorie_id') }}</span>
                                                        @endif
                                                    </div> 
                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <label for="price" class="form-control-label">Prix</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="input-group">
                                                                <input type="number" require="True" id="price" name="price" placeholder="Prix" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($item) ? $item->price : old('price')}}" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text rounded-pill">FCFA</span>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('price'))
                                                                <span class="text-danger">{{ $errors->first('price') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <label for="icon" class="form-control-label">Image</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="custom-file">
                                                                <input type="file" id="icon" name="icon" class="custom-file-input" required>
                                                            </div>
                                                        </div>
                                                        @if ($errors->has('icon'))
                                                            <span class="text-danger">{{ $errors->first('icon') }}</span>
                                                        @endif
                                                    </div> -->

                                                    <div class="row mb-3">
                                                        <div class="col-md-3">
                                                            <label for="description" class="form-control-label">Description</label>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{isset($item) ? $item->description : old('description')}}</textarea>
                                                        </div>
                                                        @if ($errors->has('description'))
                                                            <span class="text-danger">{{ $errors->first('description') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Suprression-->
                                <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cet élément ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ route('examen_destroy', $item->id) }}">
                                                <button type="button" class="btn btn-danger">Confirmer</button>
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for Description -->
                                <div class="modal fade" id="descriptionModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class=" text-primary modal-title" id="descriptionModalLabel{{ $item->id }}">Description</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div style="
                                                white-space: pre-wrap;
                                                font-family: 'Arial', sans-serif; /* Choisissez une police qui vous convient */
                                                font-size: 16px; /* Taille de la police */
                                                line-height: 1.5; /* Hauteur de ligne pour une meilleure lisibilité */
                                                color: #333; /* Couleur du texte */
                                                background-color: #f9f9f9; /* Couleur de fond légère */
                                                padding: 15px; /* Espacement intérieur */
                                                border-radius: 8px; /* Coins arrondis pour un look moderne */
                                                box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Ombre légère pour donner de la profondeur */
                                            ">
                                                {!! nl2br(e($item->description)) !!}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">D'accord</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Bloquer-->
                                <div class="modal fade" id="confirmBloquerModal{{ $item->id }}" tabindex="-1" aria-labelledby="confirmBloquerModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if($item->isactive)
                                                Êtes-vous sûr de vouloir le désactivé ?
                                            @else
                                                Êtes-vous sûr de vouloir l'activé?
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <a href="{{ url('/examen/active', $item) }}">
                                                <button type="button" class="btn btn-danger">Confirmer</button>
                                            </a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        {{ $examens->links() }}
                    </div>        
                </div>
            </div>                           
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<!-- Modal create-->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Examen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" action="{{route('examens.store')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="label" class="form-control-label">Nom</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="label" require="True" name="label" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('label')}}" required>
                        </div>
                        @if ($errors->has('label'))
                            <span class="text-danger">{{ $errors->first('label') }}</span>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="laboratorie_id" class="form-control-label">Laboratoire</label>
                        </div>
                        <div class="col-md-9">
                            <select require="True" name="laboratorie_id" id="laboratorie_id" class="form-control rounded-pill focus:ring focus:ring-opacity-50" data-toggle="select2">
                                <option>Select</option>
                                    <option selected value="{{$laboratorie->id}}">{{$laboratorie->name}}</option>
                            </select>
                        </div>
                        @if ($errors->has('laboratorie_id'))
                            <span class="text-danger">{{ $errors->first('laboratorie_id') }}</span>
                        @endif
                    </div> 
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="price" class="form-control-label">Prix</label>
                        </div>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="number" require="True" id="price" name="price" placeholder="Prix" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('price')}}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text rounded-pill">FCFA</span>
                                </div>
                            </div>
                            @if ($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="icon" class="form-control-label">Image</label>
                        </div>
                        <div class="col-md-9">
                            <div class="custom-file">
                                <input type="file" id="icon" name="icon" class="custom-file-input">
                            </div>
                        </div>
                        @if ($errors->has('icon'))
                            <span class="text-danger">{{ $errors->first('icon') }}</span>
                        @endif
                    </div> -->

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="description" class="form-control-label">Description</label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{old('description')}}</textarea>
                        </div>
                        @if ($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Include Bootstrap JS and jQuery -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


            
        </div>
        <!-- End Content-->

    @endsection