<?php

use App\Http\Controllers\Admin\Banner\BannerCreateController;
use App\Http\Controllers\Admin\Banner\BannerEditController;
use App\Http\Controllers\Admin\Banner\BannerListController;
use App\Http\Controllers\Admin\Banner\BannerStoreController;
use App\Http\Controllers\Admin\Banner\BannerUpdateController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ChangeStatusController;
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
use App\Http\Controllers\Admin\Events\EventConfigComponentController;
use App\Http\Controllers\Admin\Events\EventConfigController;
use App\Http\Controllers\Admin\Events\EventCreateController;
use App\Http\Controllers\Admin\Events\EventEditController;
use App\Http\Controllers\Admin\Events\EventListController;
use App\Http\Controllers\Admin\Events\EventStoreController;
use App\Http\Controllers\Admin\Events\EventUpdateController;
use App\Http\Controllers\Admin\Logs\LogController;
use App\Http\Controllers\Admin\MuseumBranches\MuseumBranchController;
use App\Http\Controllers\Admin\Reports\ReportsForMuseumAdminController;
use App\Http\Controllers\Admin\Reports\ReportsForSuperAdminController;
use App\Http\Controllers\Admin\Tickets\GuideServiceController;
use App\Http\Controllers\Admin\Tickets\ShowTicketsController;
use App\Http\Controllers\Admin\Tickets\ShowUnitedTicketController;
use App\Http\Controllers\Admin\Tickets\StandartTicketController;
use App\Http\Controllers\Admin\Tickets\SubscriptionTicketController;
use App\Http\Controllers\Admin\Tickets\UnitedTicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\News\NewsController;
use App\Http\Controllers\Admin\Product\CreateController;
use App\Http\Controllers\Admin\Product\ProductCreateController;
use App\Http\Controllers\Admin\Product\ProductEditController;
use App\Http\Controllers\Admin\Product\ProductListController;
use App\Http\Controllers\Admin\Product\ProductStoreController;
use App\Http\Controllers\Admin\Product\ProductUpdateController;
use App\Http\Controllers\Admin\cashier\CashierController;
use App\Http\Controllers\cashier\BuyTicketController;
use App\Http\Controllers\cashier\CorporativeTicket;
use App\Http\Controllers\cashier\EducationalTicket;
use App\Http\Controllers\cashier\EventTicket;
use App\Http\Controllers\cashier\SubscriptionTicket;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Corporative\CorporativeSaleController;
use App\Http\Controllers\museum\MuseumController;
use App\Http\Controllers\NodeApiController;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\IncrementController;
use App\Http\Controllers\tables\Basic as TablesBasic;
use Illuminate\Http\Request;

// authentication

Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');

// Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');
Auth::routes(['register' => false]);


// Route::post('/web/login-check', [AuthController::class, 'login'])->name('web-login-check');
Route::get('/test-qr', [NodeApiController::class, 'test']);
Route::group(['middleware' => ['auth']], function () {
  // Main Page Route
  Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
  // Route::resource('roles', RoleController::class);
  Route::resource('users', UserController::class);
  Route::get('users-visitors', [UserController::class, 'users_visitors'])->name('users_visitors');


  // pages
  Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
  Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
  Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');

  // cards
  Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

  // User Interface
  Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
  Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
  Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
  Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
  Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
  Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
  Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
  Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
  Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
  Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
  Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
  Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
  Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
  Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
  Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
  Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
  Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
  Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
  Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

  // extended ui
  Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
  Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

  // icons
  Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

  // form elements
  Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
  Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

  // form layouts
  Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
  Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

  // tables
  Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

  Route::post('change-status', [ChangeStatusController::class, 'change_status'])->name('change_status');
  Route::get('delete-item/{tb_name}/{id}', [DeleteItemController::class, 'index'])->name('delete_item');
  Route::get('logs', [LogController::class, 'index'])->name('logs');
  Route::get('reports/{request_report_type}', [ReportsForSuperAdminController::class, 'index'])->name('reports');
  Route::get('museum/reports/{request_report_type}', [ReportsForMuseumAdminController::class, 'index'])->name('museum_reports');


  Route::group(['prefix' => 'museum'], function () {
    Route::get('/', [MuseumController::class, 'index'])->name('museum')->middleware('role:super_admin');
    Route::group(['middleware' => ['role:museum_admin|content_manager']], function () {
      Route::get('/create', [MuseumController::class, 'create'])->name('create-museum');
      Route::post('/add-museum', [MuseumController::class, 'addMuseum'])->name('museum.add');
      Route::get('/edit/{id}', [MuseumController::class, 'edit'])->name('museum.edit');
      Route::post('/update/{id}', [MuseumController::class, 'update'])->name('museum.update');
    });

  });

  // News
  Route::group(['prefix' => 'news'], function () {
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/news-create', [NewsController::class, 'createNewsPage'])->name('news-create-page');
    Route::post('/news-create', [NewsController::class, 'createNews'])->name('news-create');

    Route::get('/news-edit/{id}', [NewsController::class, 'editNews'])->name('news-edit');
    Route::put('/news-update/{id}', [NewsController::class, 'updateNews'])->name('news-update');

  });
  // Museum branches
  Route::group(['prefix' => 'musuem_branches'], function () {
    Route::get('/list', [MuseumBranchController::class, 'index'])->name('branches-list');
    Route::get('/create', [MuseumBranchController::class, 'create'])->name('branches-create');
    Route::post('/store', [MuseumBranchController::class, 'store'])->name('branches-store');
    Route::get('/edit/{id}', [MuseumBranchController::class, 'edit'])->name('branches-edit');
    Route::put('/update/{id}', [MuseumBranchController::class, 'update'])->name('branches-update');

  });
  Route::group(['prefix' => 'product'], function () {
    Route::get('/list', [ProductListController::class, 'index'])->name('product_list');
    Route::get('/create', [ProductCreateController::class, 'create'])->name('product_create');
    Route::post('/store', [ProductStoreController::class, 'store'])->name('product_store');
    Route::get('/edit/{id}', [ProductEditController::class, 'edit'])->name('product_edit');
    Route::put('/update/{id}', [ProductUpdateController::class, 'update'])->name('product_update');

  });

  Route::group(['prefix' => 'chats', 'middleware' => ['role:museum_admin|content_manager|super_admin|general_manager|manager']], function () {
    Route::get('/', [ChatController::class, 'index'])->name('chats');
    Route::get('/room/{id}', [ChatController::class, 'getRoomMessage'])->name('room-message');
    Route::post('/send-message', [ChatController::class, 'addMessage'])->name('send-message');
  });

  Route::group(['prefix' => 'cashier', 'middleware' => ['role:museum_admin|cashier', 'check_auth_have_museum']], function () {
    Route::get('/tickets', [CashierController::class, 'index'])->name('cashier.tickets');
    Route::post('/check-coupon', [CashierController::class, 'checkCoupon'])->name('cashier.check.coupon');
    Route::post('/corporative-ticket', [CashierController::class, 'corporativeTicket'])->name('cashier.buy.corporative');
    Route::get('/get-event-details/{id}', [CashierController::class, 'getEventDetails'])->name('cashier.eveent.details');
    Route::post('/create-ticket', BuyTicketController::class)->name('cashier.add.ticket');
    Route::post('/create-educational', EducationalTicket::class)->name('cashier.add.educational');
    Route::post('/create-event', EventTicket::class)->name('cashier.add.event');
    Route::post('/create-subscription', SubscriptionTicket::class)->name('cashier.add.subscription');
    Route::post('/create-corporative', CorporativeTicket::class)->name('cashier.add.corporative');
    Route::get('/products', [CashierController::class, 'getMuseumProduct'])->name('cashier.product');
    Route::post('/sale-product', [CashierController::class, 'saleProduct'])->name('cashier.add.product');


    // Route::get('/create', [CashierController::class, 'create'])->name('cashier.add');

  });

  Route::group(['prefix' => 'educational-programs'], function () {
    Route::group(['middleware' => ['role:museum_admin|manager|content_manager']], function () {
      Route::get('list', EducationalProgramListController::class)->name('educational_programs_list');
      Route::get('create', EducationalProgramCreateController::class)->name('educational_programs_create');
      Route::post('store', EducationalProgramStoreController::class)->name('educational_programs_store');
      Route::group(['middleware' => ['model_access']], function () {
        Route::put('update/{id}', EducationalProgramUpdateController::class)->name('educational_programs_update');
        Route::get('edit/{id}', EducationalProgramEditController::class)->name('educational_programs_edit');
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
    Route::get('/list', [BannerListController::class, 'index'])->name('banner_list');
    Route::get('/create', [BannerCreateController::class, 'create'])->name('banner_create');
    Route::post('/store', [BannerStoreController::class, 'store'])->name('banner_store');
    Route::get('edit/{id}', [BannerEditController::class, 'edit'])->name('banner_edit');
    Route::put('/update/{id}', [BannerUpdateController::class, 'update'])->name('banner_update');
  });
  Route::group(['prefix' => 'events'], function ($router) {
    Route::get('list', EventListController::class)->name('event_list');
    Route::get('create', EventCreateController::class)->name('event_create');
    Route::post('store', EventStoreController::class)->name('event_store');
    Route::get('edit/{id}', EventEditController::class)->name('event_edit');
    Route::put('update/{id}', EventUpdateController::class)->name('event_update');

      Route::get('config/component/{id}/{value}', [IncrementController::class,'increment']);
      Route::post('event-config',[EventConfigController::class,'store'])->name('event_config_store');
      Route::post('/event-config-update',[EventConfigController::class,'update'])->name('event_config_update');
      // Route::post('/call-edit-component',EventConfigComponentController::class)->name('edit_component');






  });

  Route::group(['prefix' => 'corporative', 'middleware' => ['role:museum_admin|manager']], function () {
    Route::get('/', [CorporativeSaleController::class, 'index'])->name('corporative');
    Route::get('/create', [CorporativeSaleController::class, 'create'])->name('corporative.create');
    Route::post('/create', [CorporativeSaleController::class, 'addCorporative'])->name('corporative.add');
  });


  Route::group(['prefix' => 'tickets'], function () {
    Route::group(['middleware' => ['role:museum_admin|manager']], function () {
        Route::get('show', ShowTicketsController::class)->name('tickets_show');
            Route::group(['middleware' => ['model_access']], function () {
            Route::post('ticket-standart', StandartTicketController::class)->name('ticket_standart_store');
            Route::post('ticket-standart/{id}', StandartTicketController::class)->name('ticket_standart_update');
            Route::post('ticket-subscription', SubscriptionTicketController::class)->name('ticket_subscription_store');
            Route::post('ticket-subscription/{id}', SubscriptionTicketController::class)->name('ticket_subscription_update');
            Route::post('guide-service', GuideServiceController::class)->name('guide_service_store');
            Route::post('guide-service/{id}', GuideServiceController::class)->name('guide_service_update');
        });
    });
    Route::group(['middleware' => ['role:super_admin|general_manager']], function () {
        Route::get('united', ShowUnitedTicketController::class)->name('tickets_united');
        Route::post('ticket-united', UnitedTicketController::class)->name('ticket_united_store');
        Route::post('ticket-united/{id}', UnitedTicketController::class)->name('ticket_united_update');
    });
  });


});






Route::get('get-file', [FileUploadService::class, 'get_file'])->name('get-file');
