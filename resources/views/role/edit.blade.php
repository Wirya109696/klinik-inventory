<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="control-label">Role Name </label>
            <input class="form-control form-control-sm" type="text" id="name" value="{{ $data->name }}">
        </div>
        <div class="form-group">
            <label class="control-label">Guard Name </label>
            <input class="form-control form-control-sm" type="text" id="guard_name" value="{{ $data->guard_name }}">
        </div>
        <div class="form-group">
            <label class="control-label">Description </label>
            <textarea id="description" class="form-control form-control-sm">{{ $data->description }}</textarea>
        </div>
    </div>
</div>

<input type="hidden" id="id" value="{{ $data->id }}">
<input type="hidden" id="name_old" value="{{ $data->name }}">