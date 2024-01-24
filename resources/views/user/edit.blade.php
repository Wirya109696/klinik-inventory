<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="form-label">Username </label>
            <input class="form-control form-control-sm" type="text" id="username" value="{{ $data->username }}">
        </div>
        <div class="form-group">
            <label class="form-label">Name </label>
            <input class="form-control form-control-sm" type="text" id="name" value="{{ $data->name }}" disabled>
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-control form-control-sm" type="text" id="email" value="{{ $data->email }}" disabled>
        </div>
        <div class="form-group">
            <label class="form-label">Role </label>
            <select class="form-select form-select-sm autocomplete" id="role_id">
                <option value="">--  Selected --</option>
                @foreach ($roles as $role)
                @if (old('role_id', $data->role_id) == $role->id)
                <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
                @endif
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<input class="form-control" type="hidden" id="id" value="{{ $data->id }}">
<input class="form-control" type="hidden" id="username_old" value="{{ $data->username }}">