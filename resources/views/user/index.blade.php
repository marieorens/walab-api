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
                                    <li class="breadcrumb-item active">Bienvenue!</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Bienvenue!</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

  
    <style>
    
    </style>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<div class="container my-4">
    <div class="row align-items-stretch">
        <div class="col-xxl-3 col-sm-6 mb-4">
            <div class="card widget-flat text-bg-pink shadow-sm h-90">
                <div class="card-body card-icon d-flex align-items-center justify-content-between h-90">
                    <div>
                        <h6 class="text-uppercase mt-0" title="Clients">Clients</h6>
                        <h2 class="my-2">{{$count_client}}</h2>
                    </div>
                    <i class="bi bi-person fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6 mb-4">
            <div class="card widget-flat text-bg-purple shadow-sm h-90">
                <div class="card-body card-icon d-flex align-items-center justify-content-between h-90">
                    <div>
                        <h6 class="text-uppercase mt-0" title="Agents">Agents</h6>
                        <h2 class="my-2">{{$count_agent}}</h2>
                    </div>
                    <i class="bi bi-person-badge fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6 mb-4">
            <div class="card widget-flat text-bg-info shadow-sm h-90">
                <div class="card-body card-icon d-flex align-items-center justify-content-between h-90">
                    <div>
                        <h6 class="text-uppercase mt-0" title="Commandes">Commandes</h6>
                        <h2 class="my-2">{{$count_commande}}</h2>
                    </div>
                    <i class="bi bi-receipt fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-xxl-3 col-sm-6 mb-4">
            <div class="card widget-flat text-bg-primary shadow-sm h-90">
                <div class="card-body card-icon d-flex align-items-center justify-content-between h-90">
                    <div>
                        <h6 class="text-uppercase mt-0" title="Résultats">Résultats</h6>
                        <h2 class="my-2">{{$count_resultat}}</h2>
                    </div>
                    <i class="bi bi-graph-up fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>



                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-widgets">
                                    <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a>
                                    <a data-bs-toggle="collapse" href="#weeklysales-collapse" role="button" aria-expanded="false" aria-controls="weeklysales-collapse"><i class="ri-subtract-line"></i></a>
                                    <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                </div>
                                <h5 class="header-title mb-0">Evolution des commandes</h5>

                                <div id="weeklysales-collapse" class="collapse pt-3 show">
                                    <div dir="ltr">
                                        <div id="commande-chart" class="apex-charts" data-colors="#3bc0c3,#1a2942,#d1d7d973"></div>
                                    </div>

                                    <div class="row text-center"> 
                                        <div class="col">
                                            <p class="text-muted mt-3">Semaine en cours</p>
                                            <h3 class=" mb-0">
                                                <span>{{$currentWeekOrders}} FCFA</span>
                                            </h3>
                                        </div>
                                        <div class="col">
                                            <p class="text-muted mt-3">Semaine précédente</p> 
                                            <h3 class=" mb-0">
                                                <span>{{$lastWeekOrders}} FCFA</span>
                                            </h3>
                                        </div>
                                        <div class="col">
                                            <p class="text-muted mt-3">Total</p>
                                            <h3 class=" mb-0">
                                                <span>{{$totals}} FCFA</span>
                                            </h3>
                                        </div>
                                        <!-- <div class="col">
                                            <p class="text-muted mt-3">Customers</p>
                                            <h3 class=" mb-0">
                                                <span>3k</span>
                                            </h3>
                                        </div> -->
                                    </div>
                                </div>

                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                    
                </div>
                <!-- end row -->

                <div class="row">
                    

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="p-3">
                                    <div class="card-widgets">
                                        <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a>
                                        <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                                        <a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>
                                    </div>
                                    <h5 class="header-title mb-0">Commande qui ne sont pas encore assigné</h5>
                                </div>

                                <div id="yearly-sales-collapse" class="collapse show">

                                    <div class="table-responsive">
                                        <table class="table table-nowrap table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <!-- <th>#</th> -->
                                                    <th>Code</th>
                                                    <th>Type</th>
                                                    <th>Addresse</th>
                                                    <th>Status</th>
                                                    <th>Client</th>
                                                    <th>Agent</th>
                                                    <th>Date de prélevement</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($commandes as $i => $item)
                                                    <tr>
                                                        <!-- <td>{{$i + 1}}</td> -->
                                                        <td>{{$item->code}}</td>
                                                        <td>{{$item->type}}</td>
                                                        <td>{{$item->adress}}</td>
                                                        <td class="text-primary">{{$item->statut}}</td>
                                                        <td>@isset($item->client_id) {{$item->client->firstname}} {{$item->client->lastname}} @endisset</td>
                                                        <td class="text-primary">@isset($item->agent_id){{$item->agent->firstname}} {{$item->agent->lastname}}  @endisset @if(!@isset($item->agent_id)) En attente d'assignation  @endif</td>
                                                        <td>{{$item->date_prelevement}}</td>
                                                        <td>
                                                            <div class="table-data-feature">
                                                                <button class="item" data-toggle="tooltip" data-placement="top" title="Assigné Agent">
                                                                    <a data-bs-toggle="modal" data-bs-target="#assigner{{ $item->id }}"> <i class="bi bi-clipboard-plus"></i></a>
                                                                </button>
                                                                <!-- <button class="item" data-toggle="tooltip" data-placement="top" title="More">
                                                                    <i class="zmdi zmdi-more"></i>
                                                                </button> -->
                                                            </div>
                                                        </td>
                                                    </tr>

                                                <!-- Modal Assigner-->
                                                <div class="modal fade" id="assigner{{ $item->id }}" tabindex="-1" aria-labelledby="assignerLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered"> 
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Assigner la commande à un Agent</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form @if(isset($item)) method="PUT" action="{{ url('/commandes/update/assigne') }}" @else method="POST" action="{{ url('/commandes/update/assigne') }}" @endif enctype="multipart/form-data" class="form-horizontal">
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
                                                                                    <input type="text" hidden id="code_commande" name="code_commande" placeholder="Code Commande" class="form-control rounded-pill shadow-sm focus-ring" value="{{ isset($item) ? $item->code : '' }}">
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
                                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                                        </div>

                                                                    </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $commandes->links() }}
                                    </div>        
                                </div>
                            </div>                           
                        </div>
                    </div> 
                </div>
                <!-- end row -->

            </div>
            <!-- container -->

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    fetch('/commande-data')
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            var options = {
                                chart: {
                                    type: 'line',
                                    height: 350
                                },
                                series: data.series,
                                xaxis: {
                                    categories: data.labels
                                },
                                colors: ['#3bc0c3', '#1a2942', '#d1d7d973']
                            };

                            var chart = new ApexCharts(document.querySelector("#commande-chart"), options);
                            chart.render();
                        });
                });
            </script>
             
    @endsection