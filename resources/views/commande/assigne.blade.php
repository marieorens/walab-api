@extends('layout')
    @section('page_content')
    

            <!-- MAIN CONTENT-->

                        <div class="main-content">
            <div class="section">
            <div class="container-fluid">
<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="overview-wrap">
            <a href="{{ route('commandes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>   Retour
            </a>
        </div>
    </div>
</div>

                        @if($view)
                            <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <strong>commande</strong>
                                        </div>
                                        <div class="card-body card-block">
                                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                @isset($commande->url_profil)
                                                    <div class="row form-group">
                                                        <div class="col col-md-3">
                                                            <label class=" form-control-label">Photo Profile</label>
                                                        </div>
                                                        <div class="col-12 col-md-9">
                                                            <img src="{{asset($commande->url_profil)}}" alt="profile">
                                                        </div>
                                                    </div>
                                                @endisset

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">NOM</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->firstname}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Prenoms</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->lastname}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Email</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->email}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Téléphone</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->phone}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Role</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->role->label}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Pays</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->country}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Addresse</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->adress}}</p>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col col-md-3">
                                                        <label class=" form-control-label">Ville</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <p class="form-control-static">{{$commande->city}}</p>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{route('commandes.edit', $commande->id)}}"><button type="bnt" class="btn btn-primary btn-sm">
                                                <i class="fa fa-dot-circle-o"></i> Modifier
                                            </button></a>
                                        </div>
                                    </div>
                            </div>
                        @else
                        
                        <div class="col-lg-8 mx-auto">
    <form @if(isset($commande)) method="PUT" action="{{ url('/commandes/update/assigne') }}" @else method="POST" action="{{ url('/commandes/update/assigne') }}" @endif enctype="multipart/form-data" class="form-horizontal">
        @csrf
        <div class="card shadow-sm rounded-lg">
            <div class="card-header bg-primary text-white rounded-top">
                <strong>Assigner une commande à un Agent</strong>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="code_commande" class="form-label">Code Commande</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill shadow-sm focus-ring" value="{{ isset($commande) ? $commande->code : '' }}">
                        <input type="text" id="id" name="id" placeholder="id" hidden class="form-control rounded-pill" value="{{ isset($commande) ? $commande->id : '' }}">
                        <small class="form-text text-muted">Entrer le code commande</small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="agent_id" class="form-label">Agent</label>
                    </div>
                    <div class="col-md-9">
                        <select name="agent_id" id="agent_id" class="form-select rounded-pill shadow-sm focus-ring">
                            <option value="" disabled selected>Select Agent</option>
                            @foreach($agents as $item)
                                <option value="{{ $item->id }}">{{ $item->firstname }} {{ $item->lastname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end ">
                @if(isset($commande))
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-dot-circle-o"></i> Enregistrer
                    </button>
                    <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-secondary">
                        <i class="fa fa-ban"></i> Annuler
                    </a>
                @else
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-dot-circle-o"></i> Ajouter
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="fa fa-ban"></i> Annuler
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

                        @endif
                        
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
  
    @endsection