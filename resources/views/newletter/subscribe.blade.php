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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Walab</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Newsletters</a></li>
                                <li class="breadcrumb-item active">Abonnée</li>
                            </ol>  
                        </div>
                        <h4 class="page-title"> Abonnée</h4>
                    </div>

                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <!-- <div class="table-data__tool">
                        <div class="table-data__tool-right">
                            <a data-bs-toggle="modal" data-bs-target="#createModal">
                                <button class="btn btn-primary mb-3">
                                    <i class="zmdi zmdi-plus"></i> Ajouter
                                </button>
                            </a>
                        </div>
                    </div> -->

                    <!-- <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                        <input type="text" hidden value="newletters" id="tablesearch">
                        <input type="search" class="form-control rounded me-2" placeholder="Tape ici..." id="searchInput" name="query">
                        <button id="searchButton" class="btn btn-primary pr-4 rounded">
                            <span class="ri-search-line"></span>
                        </button>
                    </div> -->
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
                    <h5 class="header-title mb-0">Listes des Abonnées Newsletter</h5>
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
                                    <th class="text-white text-center">Email</th>
                                    <th class="text-white text-center">Nom</th>
                                    <th class="text-white text-center">Prenom</th>
                                    <!-- <th class="text-white text-center">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($newsletterSubscribers as $item)     
                                <tr class="tr-shadow">
                                    <td  class="  text-center">{{ $item->email }}</td>
                                    <td  class=" text-center">{{ $item->user_id ? $item->user->firstname: "" }}</td>
                                    <td  class=" text-center">{{ $item->user_id ? $item->user->lastname: "" }}</td>
                                    
                                    <!-- <td  class="text-center"> -->
                                        <!-- <div class="table-data-feature d-flex justify-content-center align-items-center"> -->
                                            <!-- <a href="{{route('newslettersubscriber.index')}}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <i class="ri-mail-unread-line text-white"></i>
                                            </a> -->
                                            <!-- <a data-bs-toggle="modal" data-bs-target="#updateModal{{ $item->id }}" class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="bi bi-pencil-square text-white"></i>
                                            </a> -->
                                            <!-- <a data-bs-toggle="modal" data-bs-target="#confirmDeleteModal{{ $item->id }}" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                <i class="bi bi-trash text-white">
                                            </a> -->
                                        <!-- </div> -->
                                    <!-- </td> -->
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
                                            <!-- <a href="{{ route('newletter_destroy', $item->id) }}">
                                                <button type="button" class="btn btn-danger">Confirmer</button>
                                            </a> -->
                                        </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal for Description -->
                               
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        {{ $newsletterSubscribers->links() }}
                    </div>        
                </div>
            </div>                           
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<!-- Include Bootstrap JS and jQuery -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


            
        </div>
        <!-- End Content-->

    @endsection