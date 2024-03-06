<div class="item_config">
  <div class="mb-3 row">
    <label for="phone_number" class="col-md-2 col-form-label">օր
      <span class="required-field text-danger">*</span>
    </label>
    <div class="col-md-10">
        <input class="form-control" type="date"
        value="{{ old('day') }}"
          name="day" />
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
         value="{{ old('start_time') }}"
          name="start_time" />
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
         value="{{ old('end_time') }}"
          name="end_time" />
    </div>
    @error("time")
      <div class="mb-3 row justify-content-end">
          <div class="col-sm-10 text-danger fts-14">{{ $message }}
          </div>
      </div>
    @enderror
  </div>
</div>
