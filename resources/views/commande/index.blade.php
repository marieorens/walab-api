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
                                <li class="breadcrumb-item active">Commandes</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Commandes</h4>
                    </div>

                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="table-data__tool">
                        <!-- <div class="table-data__tool-right">
                            <a href="{{route('bilans.create')}}">
                                <button class="btn btn-primary mb-3">
                                    <i class="zmdi zmdi-plus"></i> Ajouter Resultat
                                </button>
                            </a>
                        </div> -->
                    </div>

                    <div class="ms-auto input-group rounded p-2 bg-light mb-3" style="width:30%">
                        <input type="text" hidden value="commandes" id="tablesearch">
                        <input type="search" class="form-control rounded me-2" placeholder="Tape ici..." id="searchInput" name="query">
                        <button id="searchButton" class="btn btn-primary pr-4 rounded">
                            <span class="ri-search-line"></span>
                        </button>
                    </div>
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
                                    <a href="{{route('exporter.commande')}}"><i class="mdi mdi-microsoft-excel" style="color: #008000;"></i></a>
                                    <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                                    <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                </div>
                                <h5 class="header-title mb-0">Listes des Commandes</h5>
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
                                    <table class="table table-nowrap table-hover mb-2 table-striped">
                                        <thead class="bg-primary">
                                        <tr>
                                    <th class="text-white text-center">Code</th>
                                    <th class="text-wrap text-white text-center">Type</th>
                                    <th class="text-wrap text-white text-center">Addresse</th>
                                    <th class="text-white text-center">Status</th>
                                    <th class="text-center text-white">Client</th>
                                    <th class="text-center text-white">Agent</th>
                                    <th class="text-center text-white">Date de prélevement</th>
                                    <th class="text-center text-white">Code PDF</th>
                                    <th class="text-center text-white">Action</th>  
                                </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        @foreach($commandes as $item)
                                        <tr class="tr-shadow">
                                            <td  class=" text-center">{{$item->code}}</td>
                                            <td  class=" text-center">{{$item->type}}</td>
                                            <td  class=" text-center">{{$item->adress}}</td>
                                            <td  class=" text-primary text-center">{{$item->statut}}</td>
                                            <td class="text-center">@isset($item->client_id) {{$item->client->firstname}} {{$item->client->lastname}} @endisset</td>
                                            <td class=" text-wrap text-center @if(!isset($item->agent_id)) text-primary @endif">@isset($item->agent_id){{$item->agent->firstname}} {{$item->agent->lastname}}  @endisset @if(!isset($item->agent_id))  <span>En attente d'assignation</span>  @endif</td>
                                            <td class="text-center">{{$item->date_prelevement}}
                                            </td>
                                            <td class="text-center">{{isset($item->resultat->pdf_password) ? $item->resultat->pdf_password : ""}}
                                            </td>

                                            <td class="text-center">
                                                <div class="table-data-feature d-flex justify-content-center align-items-center">
                                                    @if(isset($item->resultat->pdf_url))
                                                        <a href="{{ asset($item->resultat->pdf_url) }}" download="resultat_{{$item->code}}"
                                                            class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Voir Résultat">
                                                            <i class="bi bi-arrow-bar-down text-white"></i>
                                                        </a>
                                                    @endif
                                                    @if(isset($item->agent_id))
                                                        <a data-bs-toggle="modal" data-bs-target="#resultatcreate{{ $item->id }}"
                                                            class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="Créer Resultat">
                                                            <i class="bi bi-arrow-bar-up text-white"></i>
                                                        </a>        
                                                    @endif
                                                    
                                                    <a data-bs-toggle="modal" data-bs-target="#assigner{{ $item->id }}" 
                                                        class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" title="Assigner Agent">
                                                        <i class="bi bi-person-badge text-white"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('commande.details', $item->id) }}"
                                                        class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" title="Détails">
                                                        <i class="bi bi-eye text-white"></i>
                                                    </a>
                                                    
                                                </div>
                                            </td>

                                            
                                        </tr>


                                        <!-- Modal Resultat view-->
                                        <!-- <div class="modal fade" id="resultatview{{ $item->id }}" tabindex="-1" aria-labelledby="resultatviewLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"> 
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Résultat de la commande</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="code_commande" class="form-label">
                                                                        <i class="bi bi-barcode"></i> Code Commande
                                                                    </label>
                                                                    <p class="form-control-static">{{ $item->code }}</p>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="fichier" class="form-label">
                                                                        <i class="bi bi-file-earmark-text"></i> Fichier
                                                                    </label>
                                                                    @if($item->statut == "Terminer" && $item->resultat && $item->resultat->pdf_url)
                                                                        <iframe src="{{ asset($item->resultat->pdf_url) }}" style="width: 100%; height: 500px;" frameborder="0" allowfullscreen></iframe>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- Modal Resultat create-->
                                        <div class="modal fade" id="resultatcreate{{ $item->id }}" tabindex="-1" aria-labelledby="resultatcreateLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"> 
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Résultat de la commande</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{route('resultats.store')}}" enctype="multipart/form-data" class="form-horizontal" id="myForm1">
                                                            @csrf
                                                            <div class="row mb-3">
                                                                <!-- <div class="col-md-6"> -->
                                                                    <!-- <label for="code_commande" class="form-label">
                                                                        <i class="bi bi-barcode"></i> Code Commande
                                                                    </label> -->
                                                                    <input hidden type="text" require="True" id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill focus:ring focus:ring-opacity-50" value="{{isset($item) ? $item->code : ''}}">
                                                                <!-- </div> -->
                                                                <!-- @if ($errors->has('code_commande'))
                                                                    <span class="text-danger">{{ $errors->first('code_commande') }}</span>
                                                                @endif -->

                                                                <div class="col-md-6">
                                                                    <label for="pdf_url" class="form-label">
                                                                        <i class="bi bi-file-earmark-text"></i> Fichier
                                                                    </label>
                                                                    <div class="input-group">
                                                                        <input type="file" require="True" id="pdf_url" name="pdf_url" class="form-control-file" style="width: 100%;">
                                                                    </div>
                                                                </div>
                                                                @if ($errors->has('pdf_url'))
                                                                    <span class="text-danger">{{ $errors->first('pdf_url') }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                <button id="myButton1" type="submit" class="btn btn-primary">
                                                                    Enregistrer
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal Assigner-->
                                        <div class="modal fade" id="assigner{{ $item->id }}" tabindex="-1" aria-labelledby="assignerLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"> 
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Assigner la commande à un Agent</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form @if(isset($item)) method="PUT" action="{{ url('/commandes/update/assigne') }}" @else method="POST" action="{{ url('/commandes/update/assigne') }}" @endif enctype="multipart/form-data" class="form-horizontal" id="myForm">
                                                            @csrf
                                                            <div class="card-header bg-primary text-white rounded-top">
                                                                <strong>Assigner</strong>
                                                            </div>
                                                            <div class="card shadow-sm rounded-lg">
                                                                <div class="card-body">
                                                                    <div class="row mb-3">
                                                                        <!-- <div class="col-md-3">
                                                                            <label for="code_commande" class="form-label">Code Commande</label>
                                                                        </div> -->
                                                                        <div class="col-md-9">
                                                                            <input hidden type="text" id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill shadow-sm focus-ring" value="{{ isset($item) ? $item->code : '' }}">
                                                                            <input type="text" id="id" name="id" placeholder="id" hidden class="form-control rounded-pill" value="{{ isset($item) ? $item->id : '' }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-3">
                                                                            <label for="agent_id" class="form-label">Agent</label>
                                                                        </div>
                                                                        <div class="col-md-9">
                                                                            <select name="agent_id" require="True" id="agent_id" class="form-select rounded-pill shadow-sm focus-ring">
                                                                                <option disabled selected>Select Agent</option>
                                                                                @foreach($agents as $item_ag)
                                                                                    <option value="{{ $item_ag->id }}">{{ $item_ag->firstname }} {{ $item_ag->lastname }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                    <button id="myButton" type="submit" class="btn btn-primary">
                                                                        Enregistrer
                                                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal View-->
                                        <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered"> 
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modification</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                
                                                <div class="card-body">
                                                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" >
                                                        <div class="row mb-3">
                    
                                                            <div class="col-md-6">
                                                                <label for="code" class="form-label">
                                                                    <i class="bi bi-barcode"></i> Code
                                                                </label>
                                                            <p class="form-control-static">@isset($item) {{$item->code}} @endisset</p>
                                                            </div>

                                                            @isset($item->agent_id)
                                                                <div class="col-md-6">
                                                                    <label for="agent" class="form-label">
                                                                        <i class="bi bi-person-check"></i> Agent
                                                                    </label>
                                                                    <p class="form-control-static">{{ $item->agent->firstname }} {{ $item->agent->lastname }}</p>
                                                                </div>
                                                            @endisset
                                                        </div>

                                                            <div class="row mb-3">
                                                            @isset($item->client_id) 
                                                            <div class="col-md-6">
                                                                        <label for="client" class="form-label">
                                                                            <i class="bi bi-person-fill"></i> Client
                                                                        </label>
                                                                        <p class="form-control-static">{{ $item->client->firstname }} {{ $item->client->lastname }}</p>
                                                                    </div>
                                                                @endisset

                                                                @isset($item)
                                                                    <div class="col-md-6">
                                                                        <label for="type" class="form-label">
                                                                            <i class="bi bi-file-earmark-text"></i> Type
                                                                        </label>
                                                                        <p class="form-control-static">{{ $item->type }}</p>
                                                                    </div>
                                                                @endisset
                                                            </div>

                                                            <div class="row mb-3">
                                                                @isset($item->examen_id)
                                                                    <div class="col-md-6">
                                                                        <label for="examen" class="form-label">
                                                                            <i class="bi bi-file-text"></i> Examen
                                                                        </label>
                                                                        <p class="form-control-static">{{ $item->examen->label }}</p>
                                                                    </div>
                                                                @endisset

                                                                @isset($item->type_bilan_id)
                                                                    <div class="col-md-6">
                                                                        <label for="type_bilan" class="form-label">
                                                                            <i class="bi bi-file-earmark-medical"></i> Type de bilan
                                                                        </label>
                                                                        <p class="form-control-static">{{ $item->type_bilan->label }}</p>
                                                                    </div>
                                                                @endisset
                                                            </div>
                                                            @isset($item)
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="statut" class="form-label">
                                                                            <i class="bi bi-check-circle"></i> Statut
                                                                        </label>
                                                                        <p class="form-control-static">{{ $item->statut }}</p>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="adress" class="form-label">
                                                                            <i class="bi bi-house-door"></i> Adresse
                                                                        </label>
                                                                        <p class="form-control-static">{{ $item->adress }}</p>
                                                                    </div>
                                                                </div>
                                                            @endisset
                                                        </form>
                                                    </div>

                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Pagination Links -->
                                    {{ $commandes->links() }}
                                </div>        
                            </div>
                        </div>                           
                    </div> <!-- end card-->

                </div> <!-- end col-->
            </div>

            
        </div>
        <!-- End Content-->

    @endsection
