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
                                <li class="breadcrumb-item active">Roles</li>
                            </ol>
                        </div>
                        <h4 class="page-title text-primary">Roles</h4>
                    </div>

                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="table-data__tool">
                    <div class="table-data__tool-right">
                        <a href="{{ route('roles.create') }}">
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
                                    <a data-bs-toggle="collapse" href="#yearly-sales-collapse" role="button" aria-expanded="false" aria-controls="yearly-sales-collapse"><i class="ri-subtract-line"></i></a>
                                    <!--<a href="#" data-bs-toggle="remove"><i class="ri-close-line"></i></a>-->
                                </div>
                                <h5 class="header-title mb-0">Listes des Roles</h5>
                            </div>

                            <div id="yearly-sales-collapse" class="collapse show">

                                <div class="table-responsive table-data">
                                    <table class="table table-nowrap table-hover mb-0">
                                        <thead class="bg-primary">
                                            <tr>  
                                                <th class="text-white text-center">#</th>
                                                <th class="text-white text-center">Nom</th>
                                                <th class="text-white text-center">value</th>
                                                <th class="text-white text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roles as $i => $item)
                                                <tr>
                                                    <td class="text-center">{{$i + 1}}</td>
                                                    <td class="text-center">{{$item->label}}</td>
                                                    <td class="text-center">{{$item->value}}</td>
                                                    <td class="text-center">
                                                        <div class="table-data-feature">
                                                            <a href="{{ route('roles.show', $item->id) }}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                                <i class="bi bi-eye text-white"></i>
                                                            </a>
                                                            <a href="{{ route('roles.edit', $item->id) }}" class="btn btn-dark btn-circle " data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                                <i class="bi bi-pencil-square text-white"></i>
                                                            </a>
                                                            <a href="{{ route('roles.destroy', $item->id) }}" class="btn btn-danger btn-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                                <i class="bi bi-trash text-white"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Pagination Links -->
                                    {{ $roles->links() }}
                                </div>        
                            </div>
                        </div>                           
                    </div> <!-- end card-->

                </div> <!-- end col-->
            </div>

            
        </div>
        <!-- End Content-->

    @endsection