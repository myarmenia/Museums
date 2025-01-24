<?php

use App\Http\Controllers\Admin\Banner\BannerCreateController;
use App\Http\Controllers\Admin\Banner\BannerEditController;
use App\Http\Controllers\Admin\Banner\BannerListController;
use App\Http\Controllers\Admin\Banner\BannerStoreController;
use App\Http\Controllers\Admin\Banner\BannerUpdateController;
use App\Http\Controllers\Admin\ChangeStatusController;
use App\Http\Controllers\Admin\ChangeStyleController;
use App\Http\Controllers\Admin\DeleteItemController;
use App\Http\Controllers\Admin\EducationalPrograms\EducationalProgramCalendarController;
use App\Http\Controllers\Admin\EducationalPrograms\EducationalProgramCreateController;
use App\Http\Controllers\Admin\EducationalPrograms\EducationalProgramEditController;
use App\Http\Controllers\Admin\EducationalPrograms\EducationalProgramListController;
use App\Http\Controllers\Admin\EducationalPrograms\EducationalProgramStoreController;
use App\Http\Controllers\Admin\EducationalPrograms\EducationalProgramUpdateController;
use App\Http\Controllers\Admin\EducationalPrograms\GetCalendarDataController;
use App\Http\Controllers\Admin\EducationalPrograms\Reserve\GetDayReservationsController;
use App\Http\Controllers\Admin\EducationalPrograms\Reserve\ReserveStoreController;
use App\Http\Controllers\Admin\EducationalPrograms\Reserve\ReserveUpdateController;
use App\Http\Controllers\Admin\Events\EventConfigController;
use App\Http\Controllers\Admin\Events\EventCreateController;
use App\Http\Controllers\Admin\Events\EventEditController;
use App\Http\Controllers\Admin\Events\EventListController;
use App\Http\Controllers\Admin\Events\EventStoreController;
use App\Http\Controllers\Admin\Events\EventUpdateController;
use App\Http\Controllers\Admin\Logs\CashierLogController;
use App\Http\Controllers\Admin\Logs\LogController;
use App\Http\Controllers\Admin\MuseumBranches\MuseumBranchController;
use App\Http\Controllers\Admin\OtherServices\OSCreateController;
use App\Http\Controllers\Admin\OtherServices\OSEditController;
use App\Http\Controllers\Admin\OtherServices\OSListController;
use App\Http\Controllers\Admin\OtherServices\OSStoreController;
use App\Http\Controllers\Admin\OtherServices\OSUpdateController;
use App\Http\Controllers\Admin\Partners\PartnerCreateController;
use App\Http\Controllers\Admin\Partners\PartnerEditController;
use App\Http\Controllers\Admin\Partners\PartnerListController;
use App\Http\Controllers\Admin\Partners\PartnerStoreController;
use App\Http\Controllers\Admin\Partners\PartnerUpdateController;
use App\Http\Controllers\Admin\Reports\ExportExcelController;
use App\Http\Controllers\Admin\Reports\ReportsForMuseumAdminController;
use App\Http\Controllers\Admin\Reports\ReportsForSuperAdminController;
use App\Http\Controllers\Admin\Tickets\GuideServiceController;
use App\Http\Controllers\Admin\Tickets\SchoolTicketController;
use App\Http\Controllers\Admin\Tickets\ShowTicketsController;
use App\Http\Controllers\Admin\Tickets\ShowUnitedTicketController;
use App\Http\Controllers\Admin\Tickets\StandartTicketController;
use App\Http\Controllers\Admin\Tickets\SubscriptionTicketController;
use App\Http\Controllers\Admin\Tickets\UnitedTicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Admin\Product\ProductCreateController;
use App\Http\Controllers\Admin\Product\ProductEditController;
use App\Http\Controllers\Admin\Product\ProductListController;
use App\Http\Controllers\Admin\Product\ProductStoreController;
use App\Http\Controllers\Admin\Product\ProductUpdateController;
use App\Http\Controllers\Admin\cashier\CashierController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\cashier\BuyProduct;
use App\Http\Controllers\cashier\BuyTicketController;
use App\Http\Controllers\cashier\CorporativeTicket;
use App\Http\Controllers\cashier\EducationalTicket;
use App\Http\Controllers\cashier\EventTicket;
use App\Http\Controllers\cashier\SubscriptionTicket;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Corporative\CorporativeSaleController;
use App\Http\Controllers\Dashboard\AnalyticsController;
use App\Http\Controllers\Dashboard\SingleMuseumAnalyticsController;
use App\Http\Controllers\museum\MuseumController;
use App\Http\Controllers\return_ticket\ReturnTicketController;
use App\Http\Controllers\Turnstile\ManagmentController;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cashier\OtherServices;
use App\Http\Controllers\cashier\OtherServicesController;
use App\Http\Controllers\cashier\PartnerController;
use App\Http\Controllers\IncrementController;
use Illuminate\Http\Request;

// authentication
Auth::routes(['register' => false]);


Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');


// Route::post('/web/login-check', [AuthController::class, 'login'])->name('web-login-check');
Route::group(['middleware' => ['auth']], function () {

  //Welcome page
  Route::get('/welcome', function () {
    return view('content.welcome.welcome');
  })->name('welcome');

  // Main Page Route
  // Route::group(['middleware' => ['role:super_admin|general_manager|chief_accountant']], function () {
  //   Route::get('/', AnalyticsController::class)->name('dashboard_analytics');
  // });


    Route::get('/', AnalyticsController::class)->name('dashboard_analytics');


  Route::group(['middleware' => ['role:museum_admin|manager|content_manager|accountant', 'check_auth_have_museum']], function () {
    Route::get('/museum-dashboard', SingleMuseumAnalyticsController::class)->name('museum_dashboard_analytics');
  });

  Route::post('/get-event', function (Illuminate\Http\Request $request) {

      $museum_id = $request->input('museum_id');
      return response()->json(getMuseumAllEventsWithTranslation($museum_id, 'am'));
  });

  // Route::resource('roles', RoleController::class);
  Route::resource('users', UserController::class);
  Route::get('users-visitors', [UserController::class, 'users_visitors'])->name('users_visitors');

  Route::post('/change-status', [ChangeStatusController::class, 'change_status'])->name('change_status');
  Route::get('delete-item/{tb_name}/{id}', [DeleteItemController::class, 'index'])->name('delete_item');
  Route::get('logs', [LogController::class, 'index'])->name('logs');

  Route::get('reports/{request_report_type}', [ReportsForSuperAdminController::class, 'index'])->name('reports');
  Route::get('museum/reports/{request_report_type}', [ReportsForMuseumAdminController::class, 'index'])->name('museum_reports');
  Route::get('export-report-excel', [ExportExcelController::class, "export"])->name('export_report_excel');

  Route::get('event-reports', [ReportsForSuperAdminController::class, 'events'])->name('event_reports');
  Route::get('museum/event-reports', [ReportsForMuseumAdminController::class, 'events'])->name('museum_event_reports');
  Route::get('museum/partners-reports', [ReportsForMuseumAdminController::class, 'partners'])->name('museum_partners_reports');


  Route::group(['prefix' => 'museum'], function (): void {
    Route::get('/', [MuseumController::class, 'index'])->name('museum')->middleware('role:super_admin|general_manager');
    Route::group(['middleware' => ['role:museum_admin|content_manager|manager']], function () {
      Route::get('/create', [MuseumController::class, 'create'])->name('create-museum');
      Route::post('/add-museum', [MuseumController::class, 'addMuseum'])->name('museum.add');
      Route::get('/edit/{id}', [MuseumController::class, 'edit'])->name('museum.edit');
      Route::post('/update/{id}', [MuseumController::class, 'update'])->name('museum.update');
    });

    Route::post('/managment-to-turnstil', ManagmentController::class);

  });

  Route::group(['prefix' => 'return-ticket'], function () {
    Route::group(['middleware' => ['role:museum_admin|manager|cashier']], function () {
      Route::get('/', [ReturnTicketController::class, 'index'])->name('return-ticket');
      Route::get('/check/{token}', [ReturnTicketController::class, 'checkTicket']);
      Route::post('/remove', [ReturnTicketController::class, 'removeTicket']);
    });
  });


  //News
  Route::group(['prefix' => 'news'], function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news-create', [NewsController::class, 'createNewsPage'])->name('news-create-page');
    Route::post('/news-create', [NewsController::class, 'createNews'])->name('news-create');

    Route::get('/news-edit/{id}', [NewsController::class, 'editNews'])->name('news-edit');
    Route::put('/news-update/{id}', [NewsController::class, 'updateNews'])->name('news-update');

  });
  //Museum branches
  Route::group(['prefix' => 'musuem_branches'], function () {
    Route::get('/list', [MuseumBranchController::class, 'index'])->name('branches-list');
    Route::get('/create/{museum_id}', [MuseumBranchController::class, 'create'])->name('branches-create');
    Route::post('/store', [MuseumBranchController::class, 'store'])->name('branches-store');
    Route::get('/edit/{id}', [MuseumBranchController::class, 'edit'])->name('branches-edit');
    Route::put('/update/{id}', [MuseumBranchController::class, 'update'])->name('branches-update');

  });
  Route::group(['prefix' => 'product'], function () {
    Route::get('/list', [ProductListController::class, 'index'])->name('product_list');
    Route::get('/create/{museum_id}', [ProductCreateController::class, 'create'])->name('product_create');
    Route::post('/store', [ProductStoreController::class, 'store'])->name('product_store');
    Route::get('/edit/{id}', [ProductEditController::class, 'edit'])->name('product_edit');
    Route::put('/update/{id}', [ProductUpdateController::class, 'update'])->name('product_update');

  });





  Route::group(['prefix' => 'chats', 'middleware' => ['role:museum_admin|super_admin|general_manager|manager', 'check_auth_have_museum']], function () {
    Route::get('/', [ChatController::class, 'index'])->name('chats');
    Route::get('/room/{id}', [ChatController::class, 'getRoomMessage'])->name('room-message');
    Route::post('/send-message', [ChatController::class, 'addMessage'])->name('send-message');
  });

  Route::group(['prefix' => 'cashier', 'middleware' => ['role:museum_admin|cashier|manager', 'check_auth_have_museum']], function () {
    Route::get('/tickets', [CashierController::class, 'index'])->name('cashier.tickets');
    Route::get('/tickets-with-hdm', [CashierController::class, 'index_with_hdm'])->name('cashier.tickets-with-hdm');
    Route::post('/check-coupon', [CashierController::class, 'checkCoupon'])->name('cashier.check.coupon');
    Route::post('/corporative-ticket', [CashierController::class, 'corporativeTicket'])->name('cashier.buy.corporative');
    Route::get('/get-event-details/{id}', [CashierController::class, 'getEventDetails'])->name('cashier.eveent.details');
    Route::get('/products', [CashierController::class, 'getMuseumProduct'])->name('cashier.product');
    Route::get('/show-ticket', [CashierController::class, 'showLastTicket'])->name('cashier.show-ticket');
    Route::post('/create-ticket', BuyTicketController::class)->name('cashier.add.ticket');
    Route::post('/create-educational', EducationalTicket::class)->name('cashier.add.educational');
    Route::post('/create-event', EventTicket::class)->name('cashier.add.event');
    Route::post('/create-subscription', SubscriptionTicket::class)->name('cashier.add.subscription');
    Route::post('/create-corporative', CorporativeTicket::class)->name('cashier.add.corporative');
    Route::post('/sale-product', BuyProduct::class)->name('cashier.add.product');
    Route::post('/sale-product', BuyProduct::class)->name('cashier.add.product');
    Route::get('get-other-service/{id}', [CashierController::class,'getOtherServiceDetails'])->name('cashier.otherService.details');
    Route::post('/create-other-service', OtherServicesController::class)->name('cashier.add.otherServices');
    Route::get('get-partner/{id}', [CashierController::class,'getPartnerDetails'])->name('cashier.partner.details');
    Route::post('create-partner', PartnerController::class)->name('cashier.add.partner');
    // Route::get('/create', [CashierController::class, 'create'])->name('cashier.add');

  });

  Route::group(['prefix' => 'educational-programs'], function () {
    Route::group(['middleware' => ['role:museum_admin|manager|content_manager|cashier']], function () {
      Route::get('list', EducationalProgramListController::class)->name('educational_programs_list');
    });

    Route::group(['middleware' => ['role:content_manager|museum_admin|manager']], function () {
      Route::get('create', EducationalProgramCreateController::class)->name('educational_programs_create');
      Route::post('store', EducationalProgramStoreController::class)->name('educational_programs_store');
      Route::group(['middleware' => ['model_access']], function () {
        Route::put('update/{id}', EducationalProgramUpdateController::class)->name('educational_programs-update');
        Route::get('edit/{id}', EducationalProgramEditController::class)->name('educational_programs-edit');
      });
    });

    Route::group(['middleware' => ['role:museum_admin|manager|cashier']], function () {
      Route::get('calendar', EducationalProgramCalendarController::class)->name('educational_programs_calendar');
      Route::post('reserve-store', ReserveStoreController::class)->name('educational_programs_reserve_store');
      Route::post('reserve-update/{id}', ReserveUpdateController::class)->name('educational_programs_reserve_update');
      Route::get('calendar-data', GetCalendarDataController::class);
      Route::get('get-day-reservations/{date}', GetDayReservationsController::class);

    });
  });
  Route::group(['prefix' => 'banner'], function () {
    Route::get('/list',[BannerListController::class, 'index'])->name('banner_list');
    Route::get('/create',[BannerCreateController::class, 'create'])->name('banner_create');
    Route::post('/store',[BannerStoreController::class, 'store'])->name('banner_store');
    Route::get('edit/{id}',[BannerEditController::class, 'edit'])->name('banner_edit');
    Route::put('/update/{id}',[BannerUpdateController::class, 'update'])->name('banner_update');
  });


  Route::group(['prefix' => 'events'], function ($router) {
    Route::get('list',EventListController::class)->name('event_list');
    Route::get('create',EventCreateController::class)->name('event_create');
    Route::post('store',EventStoreController::class)->name('event_store');
    Route::get('edit/{id}',EventEditController::class)->name('event_edit');
    Route::put('update/{id}',EventUpdateController::class)->name('event_update');

    Route::get('config/component/{id}/{value}', [IncrementController::class, 'increment']);
    Route::post('event-config', [EventConfigController::class, 'store'])->name('event_config_store');
    Route::post('/event-config-update', [EventConfigController::class, 'update'])->name('event_config_update');
    // Route::post('/call-edit-component',EventConfigComponentController::class)->name('edit_component');
    Route::get('event-config-delete/{id}', [EventConfigController::class, 'delete'])->name('event-config-delete');





  });

  Route::group(['prefix' => 'corporative', 'middleware' => ['role:museum_admin|manager|accountant']], function () {
    Route::get('/', [CorporativeSaleController::class, 'index'])->name('corporative');
    Route::get('/create', [CorporativeSaleController::class, 'create'])->name('corporative.create');
    Route::post('/create', [CorporativeSaleController::class, 'addCorporative'])->name('corporative.add');
    Route::get('/edit/{id}', [CorporativeSaleController::class, 'edit'])->name('corporative_edit');
    Route::post('/edit/{id}', [CorporativeSaleController::class, 'update'])->name('corporative.edit');
    Route::delete('/delete-file/{id}', [CorporativeSaleController::class, 'deleteFile']);
  });


  Route::group(['prefix' => 'tickets'], function () {
    Route::group(['middleware' => ['role:museum_admin|manager']], function () {
      Route::get('show', ShowTicketsController::class)->name('tickets_show');
      Route::group(['middleware' => ['model_access']], function () {
        Route::post('ticket-standart', StandartTicketController::class)->name('ticket-store');
        Route::post('ticket-standart/{id}', StandartTicketController::class)->name('ticket-update');
        Route::post('ticket-subscription', SubscriptionTicketController::class)->name('ticket_subscription_settings-store');
        Route::post('ticket-subscription/{id}', SubscriptionTicketController::class)->name('ticket_subscription_settings-update');
        Route::post('guide-service', GuideServiceController::class)->name('guide_services-store');
        Route::post('guide-service/{id}', GuideServiceController::class)->name('guide_services-update');
      });
    });
    Route::group(['middleware' => ['role:super_admin|general_manager']], function () {
      Route::get('united', ShowUnitedTicketController::class)->name('tickets_united');
      Route::post('ticket-united', UnitedTicketController::class)->name('ticket_united_store');
      Route::post('ticket-united/{id}', UnitedTicketController::class)->name('ticket_united_update');

      Route::post('ticket-school', SchoolTicketController::class)->name('ticket_school_store');
      Route::post('ticket-school/{id}', SchoolTicketController::class)->name('ticket_school_update');
    });
  });

  Route::group(['prefix' => 'partners', 'middleware' => ['role:accountant|museum_admin|manager', 'check_auth_have_museum']], function () {
    Route::get('list', PartnerListController::class)->name('partners_list');
    Route::get('create', PartnerCreateController::class)->name('partners_create');
    Route::post('store', PartnerStoreController::class)->name('partners_store');
    Route::group(['middleware' => ['model_access']], function () {
      Route::put('update/{id}', PartnerUpdateController::class)->name('partners-update');
      Route::get('edit/{id}', PartnerEditController::class)->name('partners-edit');
    });
  });

  Route::group(['prefix' => 'other-services', 'middleware' => ['role:content_manager|museum_admin|manager', 'check_auth_have_museum']], function () {
    Route::get('list', OSListController::class)->name('other_services_list');
    Route::get('create', OSCreateController::class)->name('other_services_create');
    Route::post('store', OSStoreController::class)->name('other_services_store');
    Route::group(['middleware' => ['model_access']], function () {
      Route::put('update/{id}', OSUpdateController::class)->name('other_services-update');
      Route::get('edit/{id}', OSEditController::class)->name('other_services-edit');
    });
  });

  Route::group(['middleware' => ['role:accountant|museum_admin|manager', 'check_auth_have_museum']], function () {
    Route::get('cashier-logs', [CashierLogController::class, 'index'])->name('cashier_logs');
    Route::post('cashier-logs-show-more', [CashierLogController::class, 'cashier_logs_show_more'])->name('cashier_logs_show_more');
  });

  Route::get('change-style/{type}', [ChangeStyleController::class, "change_style"])->name('change_style');
  Route::get('test-email/{purchase_id}/{email}', [ChangeStyleController::class, "test_email"])->name('test_email');  //important
  Route::get('test-pdfTickets/{purchase_id}', [ChangeStyleController::class, "testPdfTickets"])->name('est_pdfTickets');  //important


});

Route::get('get-file', [FileUploadService::class, 'get_file'])->name('get-file');



Route::post('/hash-make', function (Request $request) { /// for hashed password

      $validated = $request->validate([
        'password' => 'required|string',
      ]);

      $hashed = Hash::make($validated['password']);

      return response()->json([
        'hashed' => $hashed,
      ]);
});
