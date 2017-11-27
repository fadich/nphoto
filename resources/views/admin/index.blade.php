@include('components.alert')

<form id="upload-form" action="/admin/photos/create" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group file-upload">
        <div class="file-upload-wrap">
            <input id="upload" type="file" name="photos[]" multiple>
            <span class="glyphicon glyphicon-plus"></span>
        </div>
    </div>
</form>

<div class="photo-list row"></div>
