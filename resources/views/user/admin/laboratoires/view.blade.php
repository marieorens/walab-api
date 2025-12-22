@extends('layout')
    @section('page_content')
    
            <!-- MAIN CONTENT-->
            <div class="main-content">
            <div class="section">
            <div class="container-fluid">
<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="overview-wrap">
            <a href="{{ route('laboratories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>   Retour
            </a>
        </div>
    </div>
</div>

                        @if($view)
                        <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header text-white rounded-top" style="background:#667eea;">
            <strong>Laboratoire</strong>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Nom
                        </label>
                        <p class="form-control-static">{{$laboratoire->name}}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-currency-dollar"></i> Addresse
                        </label>
                        <p class="form-control-static">{{$laboratoire->address}}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-file-earmark-text"></i> Description
                        </label>
                        <p class="form-control-static">{{$laboratoire->description}}</p>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{route('laboratories.edit', $laboratoire->id)}}" class="btn btn-sm text-white" style="background:#667eea;">
                <i class="fa fa-dot-circle-o"></i> Modifier
            </a>
        </div>
    </div>
</div>

                        @else
                        <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header text-white rounded-top" style="background:#667eea;">
            <strong>Laboratoire</strong>
        </div>
        <div class="card-body">
            <form @if(isset($laboratoire))  method="PUT" action="{{route('laboratories.update', $laboratoire->id)}}" @else method="POST" action="{{route('laboratories.store')}}"}} @endif enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="name" class="form-control-label">Nom</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="name" require="True" name="name" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($laboratoire) ? $laboratoire->name : ''}}">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="address" class="form-control-label">Addresse</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="text" id="address" require="True" name="address" placeholder="address" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($laboratoire) ? $laboratoire->address : ''}}">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="description" class="form-control-label">Description</label>
                    </div>
                    <div class="col-md-9">
                        <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{isset($laboratoire) ? $laboratoire->description : ''}}</textarea>
                    </div>
                </div>
             </div>
                <div class="card-footer">
                    @if(isset($laboratoire))
                        <button type="submit" class="btn btn-sm text-white" style="background:#667eea;">
                            <i class="fa fa-dot-circle-o"></i> Enregistrer
                        </button>
                        <a href="{{route('laboratories.show', $laboratoire->id)}}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-ban"></i> Annuler
                        </a>
                    @else
                        <button type="submit" class="btn btn-sm text-white" style="background:#667eea;">
                            <i class="fa fa-dot-circle-o"></i> Ajouter
                        </button>
                        <a href="{{route('home')}}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-ban"></i> Annuler
                        </a>
                    @endif
                </div>
            </form>
    </div>
                        </div>



                        @endif
                        
                    </div>
                </div>
            </div>
            
  
    @endsection