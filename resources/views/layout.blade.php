<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">

    <title>T4T Web API</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">T4T Web API Console</a>
    {{--<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">--}}
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="{{ action("AuthController@getLogout") }}">
                Hi {{ auth()->user()->name }},
                <strong title="Click here to sign out">Sign out</strong>
            </a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu == 'Dashboard'?'active':'' }}" href="{{ action('DashboardController@getIndex') }}">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>

                    @if(auth()->user()->role == "Superadmin")
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu=='Manage Participant'?'active':'' }}" href="{{ action('ManageParticipantController@getIndex') }}">
                            <span data-feather="user"></span>
                            Manage Participant
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu=='Blocked Request'?'active':'' }}" href="{{ action('BlockedRequestController@getIndex') }}">
                            <span data-feather="file-text"></span>
                            Blocked Request
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu=='Activity Log'?'active':'' }}" href="{{ action('ActivityLogController@getIndex') }}">
                            <span data-feather="file-text"></span>
                            Activity Log
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu=='User Admin'?'active':'' }}" href="{{ action('AdminUserController@getIndex') }}">
                            <span data-feather="user"></span>
                            Manage Admin User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu=='Cache'?'active':'' }}" href="{{ action('AdminUserController@getClearCache') }}">
                            <span data-feather="file-text"></span>
                            Clear Cache
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->role == "Participant")
                    <li class="nav-item">
                        <a class="nav-link {{ $currentMenu=='OAuth Clients'?'active':'' }}" href="{{ action('OAuthClientsController@getIndex') }}">
                            <span data-feather="user"></span>
                            Client Secret Key
                        </a>
                    </li>
                    @endif

                </ul>

            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">{{ $pageTitle }}</h1>
                <div style="display: none;" class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-outline-secondary">Share</button>
                        <button class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar"></span>
                        This week
                    </button>
                </div>
            </div>

            @if(session()->has('message'))
                <div class="alert alert-{{ session('message_type') }}">{!! session('message') !!}</div>
            @endif

            @yield('content')

            <br/><br/>
        </main>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset("js/jquery-3.3.1.min.js") }}"></script>
{{--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>--}}

<script src="{{ asset("js/popper.min.js") }}"></script>
<script src="{{ asset("js/bootstrap.min.js") }}"></script>

<!-- Icons -->
<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function () {
        $(".datatable").DataTable();
    })
    feather.replace()
</script>

@stack("bottom")
</body>
</html>
