@if (session('success'))
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">{!! session('success') !!}</div>
    </div>
@endif
@if (session('info'))
    <div class="col-md-12">
        <div class="alert alert-info" role="alert">{!! session('info') !!}</div>
    </div>
@endif
@if (session('warning'))
    <div class="col-md-12">
        <div class="alert alert-warning" role="alert">{!! session('warning') !!}</div>
    </div>
@endif
@if (session('error'))
    <div class="col-md-12">
        <div class="alert alert-danger" role="alert">{!!  session('error') !!}</div>
    </div>
@endif
