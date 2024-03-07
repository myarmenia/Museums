  <div class="col-lg-4 col-md-6">
        <div class="mt-3">
              <button class="btn btn-primary" id="show_reservetion" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBoth" aria-controls="offcanvasBoth">Enable both scrolling & backdrop</button>
              <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasBoth" aria-labelledby="offcanvasBothLabel">
                    <div class="offcanvas-header">
                          <h5 id="offcanvasBothLabel" class="offcanvas-title">{{count($reservetions) > 0 ?  date('d-m-Y', strtotime($reservetions[0]->date))  : ''}} օրվա գրանցումները</h5>
                          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>

                    </div>
                    <div class="reservetion-result text-center"><span class="text-danger">{{count($reservetions) == 0 ? 'Գրանցումներ չկան' : ''}}</span></div>
                    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
                        <div class="reservetions">
                            @if (count($reservetions) > 0)
                                <div class="accordion mt-3" id="accordionExample">
                                    @foreach ($reservetions as $key => $item)
                                          <div class="card accordion-item">
                                            <h2 class="accordion-header" id="headingOne-{{$key}}">
                                              <button type="button" class="accordion-button collapsed btn-primary" data-bs-toggle="collapse" data-bs-target="#accordionOne-{{$key}}" aria-expanded="false" aria-controls="accordionOne-{{$key}}">
                                              {{date('d-m-Y', strtotime($item->date)). " " . date('H:i', strtotime($item->time))}} - {{$item->educational_program ? $item->educational_program->translation('am')->name : 'Էքսկուրսիա'}}
                                              </button>
                                            </h2>

                                            <div id="accordionOne-{{$key}}" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                                              <div class="accordion-body">
                                                <form class="reserve" method="put" data-id="{{$item->id}}">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="educational_program">Ծրագրի տեսակը <span class="required-field text-danger">*</span></label>

                                                        <select id="educational_program" name="educational_program_id" class="form-select item educational_program_id">
                                                            <option value="" disabled selected>Ծրագրի տեսակը</option>

                                                            @foreach (museumEducationalPrograms() as $program)
                                                                <option value="{{ $program->id }}" {{$program->id == $item->educational_program_id ? 'selected' : ''}}>{{ __('logs.store') }}</option>
                                                            @endforeach
                                                            <option value="null_id" {{!in_array($item->educational_program_id, museumEducationalPrograms()->pluck('id')->toArray()) ? 'selected' : ''}}>Էքսկուրսիա</option>

                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="date-{{$key}}">Այցելության օրը <span class="required-field text-danger">*</span></label>
                                                        <input type="date" class="form-control item" id="date-{{$key}}" placeholder=""
                                                            name="date" value="{{$item->date}}" min="{{date('Y-m-d')}}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="time-{{$key}}">Այցելության ժամը <span class="required-field text-danger">*</span></label>
                                                        <input type="time" class="form-control item" id="time-{{$key}}" placeholder="Այցելության ժամը"
                                                            name="time" value="{{date('H:i', strtotime($item->time))}}" min="00:00" max="23:59">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="visitor_quantity-{{$key}}">Այցելուների քանակը <span class="required-field text-danger">*</span></label>
                                                        <input type="text" class="form-control item" id="visitor_quantity-{{$key}}"
                                                            placeholder="Այցելության քանակը" name="visitor_quantity" value="{{$item->visitor_quantity}}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="description-{{$key}}">Մանրամասներ <span class="required-field text-danger">*</span></label>
                                                        <textarea id="description-{{$key}}" class="form-control item" placeholder="Մանրամասներ" name="description">{{$item->description}}</textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="submit" class="btn btn-primary ">Պահպանել</button>
                                                        <button type="button" class="btn btn-outline-danger delete-reservation" data-item-id="{{$item->id}}" data-tb-name="educational_program_reservations">Ջնջել</button>
                                                    </div>
                                                    <div class="result_message mt-2"></div>
                                                </form>
                                              </div>
                                            </div>
                                          </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>

                    </div>
              </div>
        </div>
  </div>
