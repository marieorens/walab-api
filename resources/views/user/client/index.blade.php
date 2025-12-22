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
                                    <li class="breadcrumb-item active">Clients</li>
                                </ol>
                            </div>
                            <h4 class="page-title ">Clients</h4>
                        </div>

                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="table-data__tool">
                            <div class="table-data__tool-right">
                                <a data-bs-toggle="modal" data-bs-target="#createModal">
                                    <button class="btn btn-primary mb-3">
                                        <i class="zmdi zmdi-plus"></i> Ajouter
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                            <input type="text" hidden value="users_client" id="tablesearch">
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
                                        <a href="{{route('exporter.client')}}"><i class="mdi mdi-microsoft-excel" style="color: #008000;"></i></a>
                                        <!-- <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a> -->
                                        <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                                        <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                    </div>
                                    <h5 class="header-title mb-0">Listes des Clients</h5>
                                    <!-- <a href="{{ route('exporter.client') }}" class="btn btn-primary">Exporter en Excel</a> -->
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
                                        <table class="table table-nowrap table-hover mb-0">
                                            <thead class="bg-primary">
                                                <tr>  
                                                    <!-- <th class="text-white text-center">#</th> -->
                                                    <th class="text-white text-center">Profile</th>
                                                    <th class="text-white text-center">Nom</th>
                                                    <th class="text-white text-center">Prenom</th>
                                                    <th class="text-white text-center">Email</th>
                                                    <th class="text-white text-center">Téléphone</th>
                                                    <!-- <th class="text-white text-center">Pays</th> -->
                                                    <th class="text-white text-center">Ville</th>
                                                    <th class="text-white text-center">Addresse</th>
                                                    <th class="text-white text-center">Action </th>
                                                </tr>
                                            </thead>
                                            <tbody id="table-body">
                                                @foreach($clients as $i => $item)
                                                    <tr>
                                                        <!-- <td class="text-center">{{$i + 1}}</td> -->
                                                        <td class="text-center">
                                                            <div class="mb-3">
                                                                <img id="imagePreview" src="{{asset($item->url_profil) }}" alt="profile" class="avatar-sm rounded-circle shadow border border-primary">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{$item->firstname}}</td>
                                                        <td class="text-center">{{$item->lastname}}</td>
                                                        <td class="text-center">{{$item->email}}</td>
                                                        <td class="text-center">{{$item->phone}}</td>
                                                        <!-- <td class="text-center">{{$item->country}}</td> -->
                                                        <td class="text-center">{{$item->city}}</td>
                                                        <td class="text-center">{{$item->adress}}</td>
                                                        <td class="text-center">
                                                            <div class="table-data-feature">
                                                                <a href="{{ url('/user/account', $item) }}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                                    <i class="bi bi-eye text-white"></i>
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                                    <i class="bi bi-pencil-square text-white"></i>
                                                                </a>
                                                                <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                                    <i class="bi bi-trash text-white"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                    <!-- Modal Modifier-->
                                    <div class="modal fade" id="updateModal{{ $item->id }}" tabindex="-1" aria-labelledby="updateModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered"> 
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{route('client_update', $item->id)}}" enctype="multipart/form-data" class="form-horizontal">
                                                            @csrf
                                                            
                                                            <div class="card-body px-4">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="firstname" class="form-label">Nom</label>
                                                                        <input type="text" id="firstname" name="firstname" placeholder="Nom" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->firstname :  old('firstname')}}" required>
                                                                        @if ($errors->has('firstname'))
                                                                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="lastname" class="form-label">Prénom</label>
                                                                        <input type="text" id="lastname" name="lastname" placeholder="Prénom" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->lastname : old('lastname')}}" required>
                                                                        @if ($errors->has('lastname'))
                                                                            <span class="text-danger">{{ $errors->first('lastname') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" id="email" name="email" placeholder="Email" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->email : old('email')}}" required>
                                                                        @if ($errors->has('email'))
                                                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="city" class="form-label">Ville</label>
                                                                        <input type="text" id="city" name="city" placeholder="Ville" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->city :  old('city')}}" required>
                                                                        @if ($errors->has('city'))
                                                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="phone" class="form-label">Téléphone</label>
                                                                        <input type="text" id="phone" name="phone" placeholder="phone" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->phone : old('phone')}}" required>
                                                                        @if ($errors->has('phone'))
                                                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    @if(!isset($item))
                                                                        <div class="col-md-6">
                                                                            <label for="password" class="form-label">Mot de passe</label>
                                                                            <input type="password" id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->phone :  old('password')}}" required>
                                                                            @if ($errors->has('password'))
                                                                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                    @isset($item)
                                                                        <input type="password" hidden id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->phone :  old('password')}}" required>
                                                                    @endisset
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="role_id" class="form-label">Rôle</label>
                                                                        <select name="role_id" id="role_id" class="form-control rounded-pill focus-ring" required>
                                                                            <option value="" disabled>Sélectionner un rôle</option>
                                                                            @foreach($roles as $item_rol)
                                                                                @if($item_rol->id == 2)
                                                                                    <option value="{{$item_rol->id}}">🎯 Agent</option>
                                                                                @elseif($item_rol->id == 3)
                                                                                    <option value="{{$item_rol->id}}" selected>👤 Client</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('role_id'))
                                                                            <span class="text-danger">{{ $errors->first('role_id') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="adress" class="form-label">Adresse</label>
                                                                        <input type="text" id="adress" name="adress" placeholder="Adresse" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->adress : old('adress')}}" required>
                                                                        @if ($errors->has('adress'))
                                                                            <span class="text-danger">{{ $errors->first('adress') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="gender" class="form-label">Genre</label>
                                                                        <select name="gender" id="gender" class="form-control rounded-pill focus-ring">
                                                                            <option value="" disabled selected>Sélectionner le genre</option>
                                                                                <option value="male" {{isset($item) && $item->gender == "male" ? 'selected' : ''}}>Homme</option>
                                                                                <option value="female" {{isset($item) && $item->gender == "female" ? 'selected' : ''}}>Femme</option>
                                                                        </select>
                                                                        @if ($errors->has('gender'))
                                                                            <span class="text-danger">{{ $errors->first('gender') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="date_naissance" class="form-label">Date de naissance</label>
                                                                        <input type="date" id="date_naissance" name="date_naissance" placeholder="date naissancee" class="form-control rounded-pill focus-ring" value="{{isset($item) ? $item->date_naissance :  old('date_naissance')}}" required>
                                                                        @if ($errors->has('date_naissance'))
                                                                            <span class="text-danger">{{ $errors->first('date_naissance') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="d-flex flex-column align-items-center mb-3">
                                                                        @isset($item)
                                                                            <div class="mb-3">
                                                                                <img id="imagePreview" src="{{ asset($item->url_profil) }}" alt="" class="avatar-lg rounded-circle shadow border border-primary">
                                                                            </div>
                                                                        @endisset
                                                                        <div>
                                                                            <input type="file" id="url_profil" name="url_profil" class="form-control-file">
                                                                        </div>
                                                                        @if ($errors->has('url_profil'))
                                                                            <span class="text-danger">{{ $errors->first('url_profil') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div>
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
                                                <a href="{{ route('clients_destroy', $item->id) }}">
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
                                        {{ $clients->links() }}
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
                <h5 class="modal-title" id="exampleModalLabel">Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form method="POST" action="{{route('clients.store')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    
                <div class="card-body px-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="firstname" class="form-label">Nom</label>
                            <input type="text" id="firstname" name="firstname" placeholder="Nom" class="form-control rounded-pill focus-ring" value="{{old('firstname')}}">
                            @if ($errors->has('firstname'))
                                <span class="text-danger">{{ $errors->first('firstname') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="lastname" class="form-label">Prénom</label>
                            <input type="text" id="lastname" name="lastname" placeholder="Prénom" class="form-control rounded-pill focus-ring" value="{{old('lastname')}}">
                            @if ($errors->has('lastname'))
                                <span class="text-danger">{{ $errors->first('lastname') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" class="form-control rounded-pill focus-ring" value="{{old('email')}}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" id="city" name="city" placeholder="Ville" class="form-control rounded-pill focus-ring" value="{{old('city')}}">
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" id="phone" name="phone" placeholder="phone" class="form-control rounded-pill focus-ring" value="{{old('phone')}}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        @if(!isset($client))
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" value="{{old('password')}}">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        @endif
                        @isset($client)
                            <input type="password" hidden id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" value="{{old('password')}}">
                        @endisset
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="role_id" class="form-label">Rôle</label>
                            <select name="role_id" id="role_id" class="form-control rounded-pill focus-ring">
                                <option value="" disabled selected>Sélectionner un rôle</option>
                                @foreach($roles as $item)
                                    @if($item->id == 3)
                                        <option value="{{$item->id}}" selected>{{$item->label}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if ($errors->has('role_id'))
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="adress" class="form-label">Adresse</label>
                            <input type="text" id="adress" name="adress" placeholder="Adresse" class="form-control rounded-pill focus-ring" value="{{old('adress')}}">
                            @if ($errors->has('adress'))
                                <span class="text-danger">{{ $errors->first('adress') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Genre</label>
                            <select name="gender" id="gender" class="form-control rounded-pill focus-ring">
                                <option value="" disabled selected>Sélectionner le genre</option>
                                    <option value="male" {{isset($agent) && $agent->gender == "male" ? 'selected' : ''}}>Homme</option>
                                    <option value="female" {{isset($agent) && $agent->gender == "female" ? 'selected' : ''}}>Femme</option>
                            </select>
                            @if ($errors->has('gender'))
                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" id="date_naissance" name="date_naissance" placeholder="date naissancee" class="form-control rounded-pill focus-ring" value="{{old('date_naissance')}}">
                            @if ($errors->has('date_naissance'))
                                <span class="text-danger">{{ $errors->first('date_naissance') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="d-flex flex-column align-items-center mb-3">
                            @isset($client)
                                <div class="mb-3">
                                    <img id="imagePreview" src="{{ asset($agent->url_profil) }}" alt="" class="avatar-lg rounded-circle shadow border border-primary">
                                </div>
                            @endisset
                            <div>
                                <input type="file" id="url_profil" name="url_profil" class="form-control-file">
                            </div>
                            @if ($errors->has('url_profil'))
                                <span class="text-danger">{{ $errors->first('url_profil') }}</span>
                            @endif
                        </div>
                    </div>

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
                
            </div>
        <!-- End Content-->

    @endsection