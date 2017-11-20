@include('components.alert')

<form id="upload-form" action="/admin/photos/create" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="upload">File</label>
        <input id="upload" type="file" class="form-control" name="photos[]" multiple>
    </div>
</form>

<div class="photo-list row"></div>
