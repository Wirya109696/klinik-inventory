<div class="row">
    <div class="col-lg">
        <div class="form-group">
            <label class="control-label">Menu Name </label>
            <input class="form-control form-control-sm" type="text" id="menu_name" value="{{ $data->menu_name }}">
        </div>
        <div class="form-group">
            <label class="control-label">Role </label><br>
            <select class="select w-100" id="role" multiple>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Parent Name </label><br>
            <select class="form-select form-select-sm" id="parent_id">
                <option value="">-- selected --</option>
                @foreach ($parents as $parent)
                @if (old('parent_id', $data->parent_id) == $parent->id)
                <option value="{{ $parent->id }}" selected>{{ $parent->menu_name }}</option>
                @endif
                <option value="{{ $parent->id }}">{{ $parent->menu_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="control-label">Icon </label>
            <input class="form-control form-control-sm iconpicker" type="text" id="icon"  value="{{ $data->icon }}">
        </div>
    </div>
    <div class="col-lg">
        <div class="form-group">
            <label class="control-label">Order Line </label>
            <input class="form-control form-control-sm" type="text" id="order_line"  value="{{ $data->order_line }}">
        </div>
        <div class="form-group">
            <label class="control-label">Is Route</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="is_routeY" @if ($data->has_route == 'Y') checked @endif>
                <label class="form-check-label">
                    Y 
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="is_routeN" @if ($data->has_route == 'N') checked @endif>
                <label class="form-check-label">
                    N
                </label>
            </div>
        </div>
        <div class="form-group  @if ($data->has_route == 'N') d-none @endif " id="hd_route_name">
            <label class="control-label">Route Name </label>
            <input class="form-control form-control-sm" type="text" id="route_name" value="{{ $data->route_name }}">
        </div>
        <div class="form-group">
            <label class="control-label">Has Child</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="has_childY"  @if ($data->has_child == 'Y') checked @endif>
                <label class="form-check-label">
                    Y
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="has_childN"  @if ($data->has_child == 'N') checked @endif>
                <label class="form-check-label">
                    N
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">Allow</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="index" @if ($data->index == 'Y') checked @endif>
                <label class="form-check-label">
                    Index
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="create" @if ($data->store == 'Y') checked @endif>
                <label class="form-check-label">
                    Store
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="edit" @if ($data->edits == 'Y') checked @endif>
                <label class="form-check-label">
                    Edit
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="erase" @if ($data->erase == 'Y') checked @endif>
                <label class="form-check-label">
                    Erase
                </label>
            </div>
        </div>
    </div>
</div>
