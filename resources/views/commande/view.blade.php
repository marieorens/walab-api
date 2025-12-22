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

                        <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-primary text-white rounded-top">
            <strong>Commande</strong>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="row mb-3">
                    

                    <div class="col-md-6">
                        <label for="code" class="form-label">
                            <i class="bi bi-barcode"></i> Code
                        </label>
                    <p class="form-control-static">@isset($commande) $commande->code @endisset</p>
                    </div>

                     
                    @isset($commande->agent_id)
                        <div class="col-md-6">
                            <label for="agent" class="form-label">
                                <i class="bi bi-person-check"></i> Agent
                            </label>
                            <p class="form-control-static">{{ $commande->agent->firstname }} {{ $commande->agent->lastname }}</p>
                        </div>
                    @endisset
                </div>

                <div class="row mb-3">
                @isset($commande->client_id) 
                <div class="col-md-6">
                            <label for="client" class="form-label">
                                <i class="bi bi-person-fill"></i> Client
                            </label>
                            <p class="form-control-static">{{ $commande->client->firstname }} {{ $commande->client->lastname }}</p>
                        </div>
                    @endisset

                    @isset($commande)
                        <div class="col-md-6">
                            <label for="type" class="form-label">
                                <i class="bi bi-file-earmark-text"></i> Type
                            </label>
                            <p class="form-control-static">{{ $commande->type }}</p>
                        </div>
                    @endisset
                </div>

                <div class="row mb-3">
                    @isset($commande->examen_id)
                        <div class="col-md-6">
                            <label for="examen" class="form-label">
                                <i class="bi bi-file-text"></i> Examen
                            </label>
                            <p class="form-control-static">{{ $commande->examen->label }}</p>
                        </div>
                    @endisset

                    @isset($commande->type_bilan_id)
                        <div class="col-md-6">
                            <label for="type_bilan" class="form-label">
                                <i class="bi bi-file-earmark-medical"></i> Type de bilan
                            </label>
                            <p class="form-control-static">{{ $commande->type_bilan->label }}</p>
                        </div>
                    @endisset
                </div>
                @isset($commande)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="statut" class="form-label">
                                <i class="bi bi-check-circle"></i> Statut
                            </label>
                            <p class="form-control-static">{{ $commande->statut }}</p>
                        </div>

                        <div class="col-md-6">
                            <label for="adress" class="form-label">
                                <i class="bi bi-house-door"></i> Adresse
                            </label>
                            <p class="form-control-static">{{ $commande->adress }}</p>
                        </div>
                    </div>
                @endisset
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between">
            @isset($commande)
                <a href="{{ route('commandes.edit', $commande->id) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-pencil-alt"></i> Modifier
                </a>
            @endisset
        </div>
    </div>
</div>


                        @else

                        <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form @if(isset($commande)) method="POST" action="{{ route('commandes.update', $commande->id) }}" @else method="POST" action="{{ route('commandes.store') }}" @endif enctype="multipart/form-data" class="form-horizontal">
                @csrf
                @if(isset($commande))
                    @method('PUT')
                @endif
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-white">
                        <strong>Commande</strong>
                    </div>
                    <input type="text" hidden name="client_id" class="form-control rounded-pill focus-ring" value="{{$client->id}}">
                    <div class="card-body px-4">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type</label>
                                <input type="text" id="type" name="type" placeholder="Type" class="form-control rounded-pill focus-ring" value="{{ isset($commande) ? $commande->type : '' }}">
                              
                            </div>
                            <div class="col-md-6">
                                <label for="examen_id" class="form-label">Examen</label>
                                <select name="examen_id" id="examen_id" class="form-control rounded-pill focus-ring form-multi-select" data-coreui-search="true">
                                <option value="" disabled selected>Select Examen</option>
                                @foreach($examens as $item)
                                    <option value="{{ $item->id }}" {{ isset($commande) && in_array($item->id, $commande->examen_id ?? []) ? 'selected' : '' }}>{{ $item->label }}</option>
                                @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type_bilan_id" class="form-label">Type Bilan</label>
                                <select name="type_bilan_id" id="type_bilan_id" class="form-control rounded-pill focus-ring">
                                    <option value="" disabled selected>Select Type Bilan</option>
                                    @foreach($bilans as $item)
                                        <option value="{{ $item->id }}" {{ isset($commande) && $commande->type_bilan_id == $item->id ? 'selected' : '' }}>{{ $item->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="adress" class="form-label">Adresse</label>
                                <textarea name="adress" id="adress" rows="" placeholder="Adresse..." class="form-control rounded-pill focus-ring">{{ isset($commande) ? $commande->adress : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-dot-circle-o"></i> @if(isset($commande)) Enregistrer @else Ajouter @endif
                        </button>
                        <a href="{{ isset($commande) ? route('commandes.show', $commande->id) : route('home') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-ban"></i> Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
                          </div>




                        @endif
                        
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
  
    @endsection