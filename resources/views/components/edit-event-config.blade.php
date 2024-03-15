<div class="card-body" id="events_config_append">

  @if (count($data->event_configs)>0)

  @foreach ($data->event_configs as  $conf)


  <div class="item_config">
    <form class="event_config_update"  data-config-id="{{ $conf->id }}">
      <input type="hodden" value="{{$conf->id}}">
      <div class="mb-3 row">
        <label for="phone_number" class="col-md-2 col-form-label">օր
          <span class="required-field text-danger">*</span>
        </label>
        <div class="col-md-10">
            <input class="form-control" type="date"
                value = "{{ $conf->day }}"
                name="day"
              />
        </div>

      </div>
      <div class="mb-3 row">
        <label for="time" class="col-md-2 col-form-label">ժամի սկիզբ
          <span class="required-field text-danger">*</span>
        </label>
        <div class="col-md-10">
            <input class="form-control" type="time"
              value="{{ $conf->start_time  }}"
              name="start_time"
              />
        </div>

      </div>
      <div class="mb-3 row">
        <label for="time" class="col-md-2 col-form-label">ժամի ավարտ
          <span class="required-field text-danger">*</span>
        </label>
        <div class="col-md-10">
            <input class="form-control" type="time"
                value="{{ $conf->end_time }}"
                name="end_time"
              />
        </div>

        <div class="d-flex justify-content-end mt-2">
          <button type="submit" class="btn btn-outline-danger update-event-config mx-1" data-item-id="{{$conf->id}}" data-tb-name="event_configs">Թարմացնել</button>
          <button type="button" class="btn btn-outline-danger delete-event-config" data-item-id="{{$conf->id}}" data-tb-name="event_configs">Ջնջել</button>

        </div>
      </div>
    </form>
    <hr>
  </div>
@endforeach
@endif
</div>

