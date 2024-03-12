<div class="item_config">

  <div class="mb-3 row">
    <label for="phone_number" class="col-md-2 col-form-label">օր
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="date"
        value="{{ $value['day'] ?? old('day') }}"

          {{-- name="event_config[{{ $id }}][{{$count}}][day]" --}}
          name="event_config[{{ $id }}][{{$count}}][day]"
           />
    </div>
    @error("day")
      <div class="mb-3 row justify-content-end">
          <div class="col-sm-10 text-danger fts-14">{{ $message }}
          </div>
      </div>
    @enderror
  </div>
  <div class="mb-3 row">
    <label for="time" class="col-md-2 col-form-label">ժամի սկիզբ
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="time"
         value="{{ $value['start_time'] ?? old('start_time') }}"
          name="event_config[{{ $id }}][{{$count}}][start_time]" />
    </div>
    @error("time")
      <div class="mb-3 row justify-content-end">
          <div class="col-sm-10 text-danger fts-14">{{ $message }}
          </div>
      </div>
    @enderror
  </div>
  <div class="mb-3 row">
    <label for="time" class="col-md-2 col-form-label">ժամի ավարտ
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="time"
         value="{{ $value['end_time'] ??  old('end_time') }}"
          name="event_config[{{ $id }}][{{$count}}][end_time]" />
    </div>
    @error("time")
      <div class="mb-3 row justify-content-end">
          <div class="col-sm-10 text-danger fts-14">{{ $message }}
          </div>
      </div>
    @enderror
    <div class="d-flex justify-content-end mt-2">
      <button type="button" class="btn btn-outline-danger delete-event-config" data-item-id="{{$value->id}}" data-tb-name="event_configs">Ջնջել</button>
      {{-- <button type="button" class="dropdown-item click_delete_item" data-bs-toggle="modal" data-bs-target="#smallModal"><i class="bx bx-trash me-1"></i> --}}

    </div>
  </div>

</div>
<hr>
