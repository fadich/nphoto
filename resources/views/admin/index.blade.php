@include('components.alert')

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
