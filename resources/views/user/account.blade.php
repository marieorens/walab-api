@extends('layout')
    @section('page_content')

        <div class="wrapper">

            <div class="">
                <div class="">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="profile-bg-picture"
                                    style="background-image: url('{{asset('asset/images/15218.jpg')}}')">
                                    <span class="picture-bg-overlay"></span>
                                    <!-- overlay -->
                                </div>
                                <!-- meta -->
                                <div class="profile-user-box">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="profile-user-img"><img src="{{asset($user->url_profil)}}" alt=""
                                                    class="avatar-lg rounded-circle"></div>
                                            <div class="">
                                                <h4 class="mt-4 fs-17 ellipsis">{{$user->firstname}} {{$user->lastname}}</h4>
                                                <p class="font-13"> @if($user->role_id == 1) Admin @elseif($user->role_id == 2) Agent @else Client @endif</p>
                                                <!-- <p class="text-muted mb-0"><small>California, United States</small></p> -->
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-6">
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <button type="button" class="btn btn-soft-danger">
                                                    <i class="ri-settings-2-line align-text-bottom me-1 fs-16 lh-1"></i>
                                                    Mofifier Profile
                                                </button>
                                                <a class="btn btn-soft-info" href="#"> <i class="ri-check-double-fill fs-18 me-1 lh-1"></i> Following</a>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                                <!--/ meta -->
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card p-0">
                                    <div class="card-body p-0">
                                        <div class="profile-content">
                                            <ul class="nav nav-underline nav-justified gap-0">
                                                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                                        data-bs-target="#aboutme" type="button" role="tab"
                                                        aria-controls="home" aria-selected="true" href="#aboutme">A propos</a>
                                                </li>
                                                <!-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                        data-bs-target="#user-activities" type="button" role="tab"
                                                        aria-controls="home" aria-selected="true"
                                                        href="#user-activities">Activities</a></li> -->
                                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                        data-bs-target="#edit-profile" type="button" role="tab"
                                                        aria-controls="home" aria-selected="true"
                                                        href="#edit-profile">Modifier mot de passe</a></li>
                                                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                        data-bs-target="#projects" type="button" role="tab"
                                                        aria-controls="home" aria-selected="true"
                                                        href="#projects">Commande</a></li>
                                            </ul>

                                            <div class="tab-content m-0 p-4">
                                                <div class="tab-pane active" id="aboutme" role="tabpanel"
                                                    aria-labelledby="home-tab" tabindex="0">
                                                    <div class="profile-desk">
                                                        <h5 class="text-uppercase fs-17 text-dark">{{$user->fisrtname}} {{$user->lastname}}</h5>
                                                        <div class="designation mb-4">@if($user->role_id == 1) Admin @elseif($user->role_id == 2) Agent @else Client @endif</div>
                                                        <p class="text-muted fs-16">
                                                            {{$user->adress}}
                                                        </p>

                                                        <h5 class="mt-4 fs-17 text-dark">Contact Information</h5>
                                                        <table class="table table-condensed mb-0 border-top">
                                                            <tbody>
                                                                <!-- <tr>
                                                                    <th scope="row">Url</th>
                                                                    <td>
                                                                        <a href="#" class="ng-binding">
                                                                            www.example.com
                                                                        </a>
                                                                    </td>
                                                                </tr> -->
                                                                <tr>
                                                                    <th scope="row">Email</th>
                                                                    <td>
                                                                        <a href="#" class="ng-binding">
                                                                            {{$user->email}}
                                                                        </a>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <th scope="row">Phone</th>
                                                                    <td class="ng-binding">{{$user->phone}}</td>
                                                                </tr>
                                                                <!-- <tr>
                                                                    <th scope="row">Skype</th>
                                                                    <td>
                                                                        <a href="#" class="ng-binding">
                                                                            jonathandeo123
                                                                        </a>
                                                                    </td>
                                                                </tr> -->

                                                            </tbody>
                                                        </table>
                                                    </div> <!-- end profile-desk -->
                                                </div> <!-- about-me -->

                                                <!-- Activities -->
                                                <!-- <div id="user-activities" class="tab-pane">
                                                    <div class="timeline-2">
                                                        <div class="time-item">
                                                            <div class="item-info ms-3 mb-3">
                                                                <div class="text-muted">5 minutes ago</div>
                                                                <p><strong><a href="#" class="text-info">John
                                                                            Doe</a></strong>Uploaded a photo</p>
                                                                <img src="assets/images/small/small-3.jpg" alt=""
                                                                    height="40" width="60" class="rounded-1">
                                                                <img src="assets/images/small/small-4.jpg" alt=""
                                                                    height="40" width="60" class="rounded-1">
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ms-3 mb-3">
                                                                <div class="text-muted">30 minutes ago</div>
                                                                <p><a href="#" class="text-info">Lorem</a> commented your
                                                                    post.
                                                                </p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                                        elit.
                                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ms-3 mb-3">
                                                                <div class="text-muted">59 minutes ago</div>
                                                                <p><a href="#" class="text-info">Jessi</a> attended a meeting
                                                                    with<a href="#" class="text-success">John Doe</a>.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                                        elit.
                                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ms-3 mb-3">
                                                                <div class="text-muted">5 minutes ago</div>
                                                                <p><strong><a href="#" class="text-info">John
                                                                            Doe</a></strong> Uploaded 2 new photos</p>
                                                                <img src="assets/images/small/small-2.jpg" alt=""
                                                                    height="40" width="60" class="rounded-1">
                                                                <img src="assets/images/small/small-1.jpg" alt=""
                                                                    height="40" width="60" class="rounded-1">
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ms-3 mb-3">
                                                                <div class="text-muted">30 minutes ago</div>
                                                                <p><a href="#" class="text-info">Lorem</a> commented your
                                                                    post.
                                                                </p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                                        elit.
                                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="time-item">
                                                            <div class="item-info ms-3 mb-3">
                                                                <div class="text-muted">59 minutes ago</div>
                                                                <p><a href="#" class="text-info">Jessi</a> attended a meeting
                                                                    with<a href="#" class="text-success">John Doe</a>.</p>
                                                                <p><em>"Lorem ipsum dolor sit amet, consectetur adipiscing
                                                                        elit.
                                                                        Aliquam laoreet tellus ut tincidunt euismod. "</em>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->

                                                <!-- settings -->
                                                <div id="edit-profile" class="tab-pane">
                                                    <div class="user-profile-content">
                                                        <form method="POST" action="{{route('user_password', $user)}}" enctype="multipart/form-data" class="form-horizontal">
                                                            @csrf
                                                            <div class="row row-cols-sm-2 row-cols-1">

                                                                <div class="mb-3">
                                                                    <label class="form-label"
                                                                        for="Password">Mot de passe</label>
                                                                    <input type="password" placeholder="6 - 15 Characters"
                                                                        id="Password" class="form-control" name="password">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="form-label"
                                                                        for="password_confirmation">Confirmer le mot de passe</label>
                                                                    <input type="text"  id="password_confirmation"
                                                                        class="form-control" name="password_confirmation">
                                                                </div>

                                                            </div>
                                                            <button class="btn btn-primary" type="submit"><i
                                                                    class="ri-save-line me-1 fs-16 lh-1"></i> Enregistrer</button>
                                                        </form>
                                                    </div>
                                                </div>

                                                <!-- profile -->
                                                <div id="projects" class="tab-pane">
                                                    <div class="row m-t-10">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Examen/Type Bilan</th>
                                                                            <th>Client</th>
                                                                            <th>Date de début</th>
                                                                            <th>Status</th>
                                                                            <th>Agent</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($commandes as $item)
                                                                            <tr>
                                                                                <td>{{$loop->iteration}}</td>
                                                                                <td>{{$item->type}}</td>
                                                                                <td>@isset($item->client_id) {{$item->client->firstname}} {{$item->client->lastname}} @endisset</td>
                                                                                <td>{{$item->created_at}}</td>
                                                                                <td>@isset($item->agent_id){{$item->agent->firstname}} {{$item->agent->lastname}}  @endisset</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                    </div>
                    <!-- end row -->

                </div>
                <!-- container -->

            </div>
            <!-- content -->
            </div>

        </div>

    @endsection
