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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Commandes</a></li>
                        <li class="breadcrumb-item active">Resultat</li>
                    </ol>
                </div>
                <h4 class="page-title">Resultat</h4>
            </div>

        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="table-data__tool">
            <div class="table-data__tool-right">
                <a href="{{ route('resultats.create') }}">
                    <button class="btn btn-primary mb-3">
                        <i class="zmdi zmdi-plus"></i> Ajouter
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
                            <a href="javascript:;" data-bs-toggle="reload"><i class="ri-refresh-line"></i></a>
                            <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button"
                                aria-expanded="false" aria-controls="yearly-sales-collapse"><i
                                    class="ri-subtract-line"></i></a>
                            <!--<a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>-->
                        </div>
                        <h5 class="header-title mb-0">Listes des Resultat</h5>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @endif
                    <div class="d-flex">
                        <div class="ms-auto w-25 input-group rounded p-2 bg-light mb-3">
                            <input type="text" hidden value="resultats" id="tablesearch">
                            <input type="search" class="form-control rounded" placeholder="Tape ici..." id="searchInput">
                            <button id="searchButton" class="btn btn-primary mt-2 rounded"><span class="ri-search-line"></span></button>
                        </div>
                    </div>

                    <div id="yearly-sales-collapse" class="collapse show">

                        <div class="table-responsive table-data">
                            <table class="table table-nowrap table-hover mb-2">
                                <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white text-center">Code </th>
                                        <th class="text-white text-center">Fichier</th>
                                        <th class="text-white text-center">Date</th>
                                        <th class="text-white ">Action</th>

                                    </tr>
                                </thead>
                                <tbody id="table-body">
                                    @foreach($resultats as $item)
                                        <tr class="tr-shadow">
                                            <td class=" text-center">{{$item->code_commande}}</td>
                                            <td class=" text-center">
                                                <a href="{{$item->pdf_url}}" target="_blank">
                                            </td>
                                            <td class=" text-center">{{$item->created_at}}</td>

                                            <td class="text-center">
                                                <div class="table-data-feature d-flex align-items-center">
                                                    <a href="{{route('resultats.show', $item->id)}}"
                                                        class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="View">
                                                        <i class="bi bi-eye text-white"></i>
                                                    </a>
                                                    <a href="{{route('resultats.edit', $item->id)}}"
                                                        class="btn btn-dark btn-circle mx-1" data-bs-toggle="tooltip" 
                                                        data-bs-placement="top" title="Edit">
                                                        <i class="bi bi-pencil-square text-white"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('resultat_destroy', $item->id) }}" class="form-inline d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-circle mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="bi bi-trash text-white"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Pagination Links -->
                            {{ $resultats->links() }}
                        </div>
                    </div>
                </div>
            </div> <!-- end card-->

        </div> <!-- end col-->
    </div>


</div>
<!-- End Content-->

@endsection