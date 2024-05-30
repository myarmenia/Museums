@extends('layouts/contentNavbarLayout')
@section('title', 'Վիճակագրություն')
@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')

  <div class="row">
      @php
        $ticketTypes = array_diff(reportTypes(), ["canceled"]);
      @endphp
      @foreach ($ticketTypes as $key => $item)
          <div class="col-lg-2 col-md-2 mb-4 {{$key != 0 || $key != 5 ? 'mx-3' : ''}}">
              <div class="card">
                <div class="card-body">
                  <div class="card-title d-flex align-items-start justify-content-between">

                    <div class="avatar flex-shrink-0 me-3">
                      <span class="avatar-initial rounded bg-label-success"><i class="bx bxs-coupon"></i></span>
                    </div>

                  </div>
                  <span class="fw-semibold d-block mb-1">{{__("ticket-type.$item")}}</span>
                  <h3 class="card-title mb-2">{{isset($ticket_type[$item]) ? $ticket_type[$item]['total_price'] : 0}} <small style="font-size: 14px">դրամ</small></h3>
                  <small class="text-success fw-semibold">Քանակ -  {{isset($ticket_type[$item]) ? $ticket_type[$item]['quantity'] : 0}}</small>
                </div>
              </div>
          </div>
      @endforeach

      <!-- Total Revenue -->
      <div class="col-12 col-lg-12 order-2 order-md-3 order-lg-2 mb-4">
        <div class="card">
          <div class="row row-bordered g-0">
            <div class="col-md-12">
              <h5 class="card-header m-0 me-2 pb-3">Վիճակագրություն ըստ թանգարանների</h5>
              <div id="totalRevenueChart" class="px-2"></div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Total Revenue -->

  </div>
  <div class="row">
    <!-- Attendance By Country -->
    <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">Վիճակագրություն </h5>
            <small class="text-muted">ըստ երկրների</small>
          </div>

        </div>

            @php $total_by_count = 0 @endphp
            @foreach ($attendance_by_country_arr as $item)
                @php $total_by_count += $item['totalPrice'] @endphp
            @endforeach

        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column align-items-center gap-1">
              <h2 class="mb-2">{{$total_by_count}}</h2>
              <span>Ընդհանուր գումար</span>
            </div>
            <div id="orderStatisticsChart"></div>
          </div>
          <ul class="p-0 m-0">
            @foreach ($attendance_by_country_arr as $item)
              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-primary"><i class='bx bxs-city'></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <h6 class="mb-0">{{$item['countryName']}}</h6>
                    <small class="text-muted">Երկրի</small>
                  </div>
                  <div class="user-progress">
                    <small class="fw-medium">{{$item['totalPrice']}} դրամ</small>
                  </div>
                </div>
              </li>
            @endforeach


          </ul>
        </div>
      </div>
    </div>
    <!--/ Attendance By Country -->




    <!-- Attendance By Age -->
    <div class="col-md-6 col-lg-4 col-xl-4 order-2 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">Վիճակագրություն </h5>
            <small class="text-muted">ըստ տարիքի</small>
          </div>

        </div>

        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column align-items-center gap-1">
              <h2 class="mb-2">{{$age_total_amount}}</h2>
              <span>Ընդհանուր գումար</span>
            </div>
            <div id="orderStatisticsChartAge"></div>
          </div>
          <ul class="p-0 m-0">
            @foreach ($age_total_amount_asoc_arr as $age_type => $price)

              <li class="d-flex mb-4 pb-1">
                <div class="avatar flex-shrink-0 me-3">
                  <span class="avatar-initial rounded bg-label-info"><i class='bx bx-user'></i></span>
                </div>
                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                  <div class="me-2">
                    <h6 class="mb-0">{{__("age.$age_type") }}</h6>
                    <small class="text-muted">{{__("age.$age_type".'_info') }}</small>
                  </div>
                  <div class="user-progress">
                    <small class="fw-medium">{{$price}} դրամ</small>
                  </div>
                </div>
              </li>
            @endforeach


          </ul>
        </div>
      </div>
    </div>
    <!--/ Attendance By Age -->

    <!-- Top Museums -->
    @php
        $currentDate = now();
        $start_date = $currentDate->startOfYear()->format('d.m.Y');
        $end_date = $currentDate->endOfYear()->format('d.m.Y');
    @endphp
    <div class="col-md-6 col-lg-4 order-1 mb-4">
      <div class="card h-100">
          <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">Top Թանգարաններ </h5>
            <small class="text-muted">առավելագույն տոմս վաճառած</small>
          </div>
        </div>
        <div class="card-body ">
          <div class="d-flex justify-content-between align-items-center mb-3 my-4 ">
            <div class="d-flex flex-column align-items-start gap-1">
              <h2 class="my-2">{{$start_date}} - {{$end_date}}</h2>
              <span>Ընթացիկ տարի</span>
            </div>
            <div id="orderStatisticsChartAge1"></div>
          </div>
          <ul class="p-0 m-0 mt-5 pt-4">
            @foreach ($get_top_museum as $museum)

                <li class="d-flex mb-4 pb-1">
                    <div class="avatar flex-shrink-0 me-3">
                      <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-museum'></i></span>
                    </div>
                    <div class="w-100 flex-wrap align-items-center justify-content-between gap-2">
                      <div class="me-2">
                        <small class="text-muted d-block mb-1">Թանգարան</small>
                        <h6 class="mb-0 ">{{$museum['museum_name']}} </h6>
                      </div>
                      <div class="user-progress d-flex align-items-center gap-1">
                        <h6 class="mb-0 text-warning">{{$museum['total_price']}}</h6> <span class="text-muted">դրամ</span>
                      </div>
                    </div>
                </li>
            @endforeach

          </ul>
        </div>
      </div>
    </div>
    <!--/ Top Museums -->

  </div>
@endsection
<script>
      var totalRevenueResult =  '<?php echo $total_revenue ?>';
      var attendanceByCountry = '<?php echo $attendance_by_country ?>'
      var totalByCount = {{$total_by_count}}
      var attendanceByAge = '<?php echo $attendance_by_age_arr ?>'
</script>
