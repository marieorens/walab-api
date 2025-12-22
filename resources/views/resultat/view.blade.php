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
            <strong>Résultat</strong>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code_commande" class="form-label">
                            <i class="bi bi-barcode"></i> Code Commande
                        </label>
                        <p class="form-control-static">{{ $resultat->code_commande }}</p>
                    </div>

                    <div class="col-md-6">
                        <label for="fichier" class="form-label">
                            <i class="bi bi-file-earmark-text"></i> Fichier
                        </label>
                        <iframe src="{{ asset($resultat->pdf_url) }}" width="100%" height="600px" class="rounded shadow-sm"></iframe>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('resultats.edit', $resultat->id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-pencil-alt"></i> Modifier
            </a>
        </div>
    </div>
</div>

                        @else
                        <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-primary text-white rounded-top">
            <strong>Résultat</strong>
        </div>
        <div class="card-body">
            <form @if(isset($resultat)) method="PUT" action="{{route('resultats.update', $resultat->id)}}" @else method="POST" action="{{route('resultats.store')}}" @endif enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code_commande" class="form-label">
                            <i class="bi bi-barcode"></i> Code Commande
                        </label>
                        <input type="text" disabled  id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($resultat) ? $resultat->code_commande : ''}}">
         
                    </div>

                    <div class="col-md-6">
                        <label for="pdf_url" class="form-label">
                            <i class="bi bi-file-earmark-text"></i> Fichier
                        </label>
                        <div class="input-group">
                            <input type="file" require="True" id="pdf_url" name="pdf_url" class="form-control-file">
                           
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer text-end">
            @if(isset($resultat))
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Enregistrer
                </button>
                <a href="{{route('resultats.show', $resultat->id)}}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-ban"></i> Annuler
                </a>
            @else
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Ajouter
                </button>
                <a href="{{route('commandes.index')}}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-ban"></i> Annuler
                </a>   
            @endif
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