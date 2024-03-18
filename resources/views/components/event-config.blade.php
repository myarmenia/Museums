<div class="item_config">

  <div class="mb-3 row">
    <label for="phone_number" class="col-md-2 col-form-label">օր
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="date"
            value="{{ old('day') }}"

            name="event_config[{{ $id }}][{{$count}}][day]"
           />
    </div>

      <div class="mb-3 row justify-content-end" data-id="event_config.{{ $id }}.{{$count}}.day">
          <div class="col-sm-10 text-danger fts-14" >

          </div>
      </div>
    {{-- @enderror --}}
  </div>
  <div class="mb-3 row">
    <label for="time" class="col-md-2 col-form-label">ժամի սկիզբ
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="time"
         value="{{ old('start_time') }}"
          name="event_config[{{ $id }}][{{$count}}][start_time]" />
    </div>

      <div class="mb-3 row justify-content-end" data-id="event_config.{{ $id }}.{{$count}}.start_time">
          <div class="col-sm-10 text-danger fts-14">

          </div>
      </div>

  </div>
  <div class="mb-3 row">
    <label for="time" class="col-md-2 col-form-label">ժամի ավարտ
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="time"
         value="{{ old('end_time') }}"
          name="event_config[{{ $id }}][{{$count}}][end_time]" />
    </div>

      <div class="mb-3 row justify-content-end" data-id="event_config.{{ $id }}.{{$count}}.end_time">
          <div class="col-sm-10 text-danger fts-14">

          </div>
      </div>


  </div>
  <div class="d-flex justify-content-end mt-2">

    {{-- <button type="button" class="btn btn-outline-danger delete-event-config" data-count="{{$count}}" data-tb-name="event_configs">Ջնջել</button> --}}

  </div>
</div>
<hr>
