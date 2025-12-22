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
                                <li class="breadcrumb-item active">Newsletter</li>
                            </ol>  
                        </div>
                        <h4 class="page-title"> Newsletter</h4>
                    </div>

                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <a data-bs-toggle="modal" data-bs-target="#createModal">
                            <button class="btn btn-primary">
                                <i class="zmdi zmdi-plus"></i> Ajouter
                            </button>
                        </a>
                    </div>

                    <!-- Bouton à droite -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('newslettersubscriber.index') }}">
                            <button class="btn btn-primary">
                                <i class="ri-mail-unread-line"></i> Abonnée
                            </button>
                        </a>
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
                        <!-- <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a> -->
                        <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                    </div>
                    <h5 class="header-title mb-0">Listes des Newsletter</h5>
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
                                    <th class="text-white text-center">Nom</th>
                                    <th class="text-white text-center">Sujet</th>
                                    <th class="text-white text-center">Type</th>
                                    <th class="text-white text-center">Contenu</th>
                                    <th class="text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($newletters as $item)     
                                <tr class="tr-shadow">
                                    <td  class="  text-center">{{ $item->name }}</td>
                                    <td  class=" text-center">{{ $item->subject }}</td>
                                    <td  class=" text-center">{{ $item->type }}</td>
                                    <td  class=" text-center">
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#descriptionModal{{ $item->id }}">
                                            <i class="bi bi-eye"></i> Lire 
                                        </button>
                                    </td> 
                                    <td  class="text-center">
                                        <div class="table-data-feature d-flex justify-content-center align-items-center">
                                            <!-- <a href="{{route('newslettersubscriber.index')}}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <i class="ri-mail-unread-line text-white"></i>
                                            </a> -->
                                            <!-- <a data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </a> -->
                                            <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                            <i class="bi bi-trash text-white"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal View-->
                                <!-- <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered"> 
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        
                                        <div class="card-body">
                                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="ri-mail-line"></i> Nom
                                                            </label>
                                                            <p class="form-control-static">{{$item->name}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-tag"></i> Sujet
                                                            </label>
                                                            <p class="form-control-static">{{$item->subject}}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">
                                                                <i class="bi bi-currency-dollar"></i> Type
                                                            </label>
                                                            <p class="form-control-static">{{$item->type}}</p>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <label class="form-label">
                                                                <i class="bi bi-file-earmark-text"></i> Contenu
                                                            </label>
                                                            <p class="form-control-static">{{$item->content}}</p>
                                                        </div>
                                                    </div>   
                                                </form>
                                            </div>

                                        </div>
                                        </div>
                                    </div>
                                </div> -->


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
                                            <a href="{{ route('newletter_destroy', $item->id) }}">
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
                                            <div class="modal-body">
                                                {{ $item->content }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">D'accord</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        {{ $newletters->links() }}
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
            <form method="POST" action="{{route('newletters.store')}}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="name" class="form-control-label">Nom</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="name" require="True" name="name" placeholder="Nom" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('name')}}">
                        </div>
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="subject" class="form-control-label">Sujet</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" id="subject" require="True" name="subject" placeholder="Sujet" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('subject')}}">
                        </div>
                        @if ($errors->has('subject'))
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="type" class="form-control-label">Type</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text" require="True" id="type" name="type" placeholder="Type" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{old('type')}}">
                        </div>
                        @if ($errors->has('type'))
                            <span class="text-danger">{{ $errors->first('type') }}</span>
                        @endif
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="content" class="form-control-label">Contenu</label>
                        </div>
                        <div class="col-md-9">
                            <textarea name="content" id="content" rows="3" placeholder="content..." class="form-control rounded-pill focus:ring focus:ring-opacity-50">{{old('content')}}</textarea>
                        </div>
                        @if ($errors->has('content'))
                            <span class="text-danger">{{ $errors->first('content') }}</span>
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