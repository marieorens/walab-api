@extends('layout')
    @section('page_content')
    
            <!-- MAIN CONTENT-->
            <div class="main-content">
            <div class="section">
            <div class="container-fluid">
<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="overview-wrap">
            <a href="{{ route('bilans.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>   Retour
            </a>
        </div>
    </div>
</div>

                        @if($view)
                        <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-primary text-white rounded-top">
            <strong>Bilans</strong>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
            @isset($bilan->icon)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-image"></i> Image
                            </label>
                            <img src="{{asset($bilan->icon)}}" alt="image" class="img-fluid rounded">
                        </div>
                    </div>
                @endisset

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Nom
                        </label>
                        <p class="form-control-static">{{$bilan->label}}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-currency-dollar"></i> Prix
                        </label>
                        <p class="form-control-static">{{$bilan->price}} FCFA</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label">
                            <i class="bi bi-file-earmark-text"></i> Description
                        </label>
                        <p class="form-control-static">{{$bilan->description}}</p>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{route('bilans.edit', $bilan->id)}}" class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Modifier
            </a>
        </div>
    </div>
</div>

                        @else
                        <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header bg-primary text-white rounded-top">
            <strong>Bilan</strong>
        </div>
        <div class="card-body">
            <form @if(isset($bilan))  method="PUT" action="{{route('bilans.update', $bilan->id)}}" @else method="POST" action="{{route('bilans.store')}}"}} @endif enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="label" class="form-control-label">Nom</label>
                    </div>
                    <div class="col-md-9">
                        <input type="text" id="label" require="True" name="label" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($bilan) ? $bilan->label : old('label')}}">
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
                            @foreach($laboratories as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('laboratorie_id'))
                        <span class="text-danger">{{ $errors->first('laboratorie_id') }}</span>
                    @endif
                </div> <!-- end col -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="price" class="form-control-label">Prix</label>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="number" require="True" id="price" name="price" placeholder="Prix" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($bilan) ? $bilan->price : old('price')}}">
                            <div class="input-group-append">
                                <span class="input-group-text rounded-pill">FCFA</span>
                            </div>
                        </div>
                        @if ($errors->has('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
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
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="description" class="form-control-label">Description</label>
                    </div>
                    <div class="col-md-9">
                        <textarea name="description" id="description" rows="2" placeholder="Description..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{isset($bilan) ? $bilan->description : old('description')}}</textarea>
                    </div>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            
        </div>
        <div class="card-footer">
            @if(isset($bilan))
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Enregistrer
                </button>
                <a href="{{route('bilans.show', $bilan->id)}}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-ban"></i> Annuler
                </a>
            @else
                <button type="submit" class="btn btn-primary btn-sm">
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
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
  
    @endsection