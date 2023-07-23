<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Add Bootstrap CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Graycyan') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('showLogin') }}">{{ __('Login') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @else
            <li class="nav-item">
              <a class="nav-link" href="{{ route('tickets.index') }}">{{ __('Tickets') }}</a>
            </li>
            <li class="nav-item">
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
              </div>
            </li>
            @php $notifications = auth()->user()->unreadNotifications; @endphp
            <li class="nav-item ml-2">
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Notifications 
                    @if ($notifications->count() > 0 )
                      <span class="badge badge-danger"> {{ $notifications->count() }}</span>
                    @endif
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  @if ($notifications->count() > 0 )
                    @foreach($notifications as $notification)
                      <p class="dropdown-item">
                        <b>{{ $notification->data['ticket_name'] }} </b>&nbsp; status change to (closed).
                        <a href="javascript:void(0)"><button type="button" rel="tooltip" title="Mark as read" class="btn btn-danger btn-link btn-sm mark-read" data-id="{{ $notification->id }}">Read</button></a>
                      </p>
                    @endforeach
                  @else
                    <p class="dropdown-item">No new notification</p>
                  @endif
                </div>
              </div>
            </li>
            @endguest
            
          </ul>
          
        </div>
      </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Add Bootstrap JS file -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script>

        $(function() {

            $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.mark-read').click(function (e) {
              e.preventDefault();
              console.log($(this).data('id'));
              $.ajax({
                url: "{{ route('tickets.markNotification') }}",
                type: "POST",
                dataType: 'json',
                data: {id : $(this).data('id')},
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
              });
            });
        });
    </script>

    @stack('scripts')

</body>
</html>
