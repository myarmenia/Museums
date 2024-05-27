@if ($success = session()->pull('success'))
    <div class="session-message alert alert-success">
        {{ $success }}
    </div>
@endif

@if ($errorMessage = session()->pull('errorMessage'))
    <div class="session-message alert alert-danger">
        {{ $errorMessage }}
    </div>
@endif
