@if ($success = session()->pull('success'))
    <div class="alert alert-success">
        {{ $success }}
    </div>
@endif

@if ($errorMessage = session()->pull('errorMessage'))
    <div class="alert alert-danger">
        {{ $errorMessage }}
    </div>
@endif
