@if (session('message'))
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">{{ session('message') }}</div>
    </div>
@endif

<form action="/admin/photos/create" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="title">Title</label>
        <input id="title" type="text" class="form-control" name="title">
    </div>

    <div class="form-group">
        <label for="upload">File</label>
        <input id="upload" type="file" class="form-control" name="files[]" multiple>
    </div>

    <button type="submit" class="btn btn-default">Submit</button>
</form>
