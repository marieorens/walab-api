@extends('layout')
@section('page_content')


        <!-- MAIN CONTENT-->
        <div class="main-content">
            <div class="section">
            <div class="container-fluid">
<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="overview-wrap">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>   Retour
            </a>
        </div>
    </div>
</div>

@if($view)
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Role</strong>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Nom</label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">{{ $role->label }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 col-form-label">Value</label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">{{ $role->value }}</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                        <i class="fa fa-edit"></i> Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>


@else
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8">
<form @if(isset($role)) method="POST" action="{{ route('roles.update', $role->id) }}" @else method="POST" action="{{ route('roles.store') }}" @endif enctype="multipart/form-data" class="form-horizontal">
    @csrf
    @if(isset($role))
        @method('PUT')
    @endif
    <div class="card shadow-sm ">
        <div class="card-header bg-primary text-white">
            <strong>Role</strong>
        </div>
        <div class="card-body px-4">
            <div class="mb-3 row">
                <label for="label" class="col-md-3 col-form-label">Nom</label>
                <div class="col-md-9">
                    <input type="text" id="label" name="label" placeholder="Label" class="form-control rounded-pill focus-ring" value="{{ isset($role) ? $role->label : '' }}">
                    <small class="form-text text-muted">Entrer le label</small>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="value" class="col-md-3 col-form-label">Value</label>
                <div class="col-md-9">
                    <input type="text" id="value" name="value" placeholder="Value" class="form-control rounded-pill focus-ring" value="{{ isset($role) ? $role->value : '' }}">
                    <small class="form-text text-muted">Entrer la value</small>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> @if(isset($role)) Enregistrer @else Ajouter @endif
            </button>
            <a href="{{ isset($role) ? route('roles.show', $role->id) : route('roles.index') }}" class="btn btn-secondary btn-sm">
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