@extends('layout')
    @section('page_content')
    
            <!-- MAIN CONTENT-->
            <div class="main-content">
            <div class="section">
            <div class="container-fluid">
<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="overview-wrap">
            <a href="{{ route('admins.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>   Retour
            </a>
        </div>
    </div>
</div>

 <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
        <form @if(isset($agent)) method="POST" action="{{route('admin_update', $agent->id)}}" @else method="POST" action="{{route('admins.store')}}" @endif enctype="multipart/form-data" class="form-horizontal">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Admin</strong>
                </div>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <strong>{{ session('success') }}</strong>
                    </div>
                @endif
                <div class="card-body px-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="firstname" class="form-label">Nom</label>
                            <input type="text" id="firstname" name="firstname" placeholder="Nom" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->firstname :  old('firstname')}}">
                            @if ($errors->has('firstname'))
                                <span class="text-danger">{{ $errors->first('firstname') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="lastname" class="form-label">Prénom</label>
                            <input type="text" id="lastname" name="lastname" placeholder="Prénom" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->lastname : old('lastname')}}">
                            @if ($errors->has('lastname'))
                                <span class="text-danger">{{ $errors->first('lastname') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" placeholder="Email" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->email : old('email')}}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" id="city" name="city" placeholder="Ville" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->city :  old('city')}}">
                            @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="text" id="phone" name="phone" placeholder="phone" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->phone : old('phone')}}">
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        @if(!isset($agent))
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->phone :  old('password')}}">
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        @endif
                        @isset($agent)
                            <input type="password" hidden id="password" name="password" placeholder="Mot de passe" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->phone :  old('password')}}">
                        @endisset
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="role_id" class="form-label">Rôle</label>
                            <select name="role_id" id="role_id" class="form-control rounded-pill focus-ring">
                                <option value="" disabled selected>Sélectionner un rôle</option>
                                @foreach($roles as $item)
                                    <option value="{{$item->id}}" {{isset($agent) && $agent->role_id == $item->id ? 'selected' : ''}}>{{$item->label}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('role_id'))
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="adress" class="form-label">Adresse</label>
                            <input type="text" id="adress" name="adress" placeholder="Adresse" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->adress : old('adress')}}">
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
                            <input type="date" id="date_naissance" name="date_naissance" placeholder="date naissancee" class="form-control rounded-pill focus-ring" value="{{isset($agent) ? $agent->date_naissance :  old('date_naissance')}}">
                            @if ($errors->has('date_naissance'))
                                <span class="text-danger">{{ $errors->first('date_naissance') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="d-flex flex-column align-items-center mb-3">
                            @isset($agent)
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

                    <!-- <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea name="bio" id="bio" rows="2" placeholder="Bio..." class="form-control rounded-pill focus-ring">{{isset($agent) ? $agent->bio : old('bio')}}</textarea>
                        </div>
                        @if ($errors->has('bio'))
                            <span class="text-danger">{{ $errors->first('bio') }}</span>
                        @endif
                    </div> -->
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-dot-circle-o"></i> @if(isset($agent)) Enregistrer @else Ajouter @endif
                    </button>
                    <a href="{{isset($agent) ? route('admins.show', $agent->id) : route('home')}}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-ban"></i> Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
                        
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
  
    @endsection