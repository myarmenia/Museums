<?php

use App\Http\Controllers\Admin\cashier\CashierController as CashierCashierController;
use App\Http\Controllers\Admin\Events\EventListController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Cart\DeleteItemController;
use App\Http\Controllers\API\Cart\ItemsController;
use App\Http\Controllers\API\Cart\StoreController;
use App\Http\Controllers\API\Chat\ChatController;
use App\Http\Controllers\API\Banner\BannerCantroller;
use App\Http\Controllers\API\EducationalPrograms\EducationalProgramController;
use App\Http\Controllers\API\Events\EventController;
use App\Http\Controllers\API\Events\EventsListController;
use App\Http\Controllers\API\Events\HeaderEventController;
use App\Http\Controllers\API\Events\SingleEventController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\Lessons\LessonController;
use App\Http\Controllers\API\MuseumBranch\MuseumBranchesController;
use App\Http\Controllers\API\MuseumController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\Purchase\PaymentResultController;
use App\Http\Controllers\API\Purchase\PurchaseStoreController;
use App\Http\Controllers\API\TestController;
use App\Http\Controllers\API\Tickets\SingleMuseumEventsTicketsController;
use App\Http\Controllers\API\Tickets\TicketsController;
use App\Http\Controllers\API\Tickets\UnitedTicketSettingsController;
use App\Http\Controllers\API\User\DeleteUserController;
use App\Http\Controllers\API\User\ListActiveQR;
use App\Http\Controllers\API\User\OrderHistoryController;
use App\Http\Controllers\API\User\SendQRToMailController;
use App\Http\Controllers\API\Lessons\UserCurrentLessonController;
use App\Http\Controllers\API\Museum\SinggleMuseumEventsController;
use App\Http\Controllers\API\Museum\SingleMuseumEventsController;
use App\Http\Controllers\API\Museum\SingleMuseumProductController;
use App\Http\Controllers\API\MuseumListController;
use App\Http\Controllers\API\Notification\AllNotificationController;
use App\Http\Controllers\API\Notification\ReadNotificationController;
use App\Http\Controllers\API\Notification\UnreadNotificationController;
use App\Http\Controllers\API\Product\ProductCantroller;
use App\Http\Controllers\API\RegionListController;
use App\Http\Controllers\API\Shop\ProductCantroller as ShopProductCantroller;
use App\Http\Controllers\API\Shop\SingleProductController;
use App\Http\Controllers\API\Student\DashboardController;
use App\Http\Controllers\API\Student\VisitHistoryController;
use App\Http\Controllers\API\User\UserController;
use App\Http\Controllers\cashier\BuyTicketController;
use App\Http\Controllers\cashier\CashierController;
use App\Http\Controllers\Turnstile\ActiveQrsController;
use App\Http\Controllers\Turnstile\CheckQRController;
use App\Http\Controllers\Turnstile\QrBlackListController;
use App\Http\Controllers\Turnstile\TurnstileLoginController;
use App\Http\Controllers\Turnstile\TurnstileRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api']], function ($router) {
  Route::group(['middleware' => ['setlang']], function ($router) {

    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('signup', [AuthController::class, 'signup']);
        Route::post('signup-google', [AuthController::class, 'authGoogle']);
        Route::post('check-verify-token', [AuthController::class, 'checkVerifyToken']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('resend-verify', [AuthController::class, 'resendVerify']);
        Route::group(['prefix' => 'mobile'], function ($router) {
          Route::post('signup-info', [AuthController::class, 'signupInfo']);
        });

        Route::group(['prefix' => 'notification'], function ($router) {
          Route::get('unread', UnreadNotificationController::class)->name('unreadNotification');
          Route::get('/{id}', ReadNotificationController::class);
        });

    });


    Route::group(['middleware' => 'apiAuthCheck'], function ($router) {

        Route::group(['prefix' => 'user'], function ($router) {
            Route::post('edit', [UserController::class, 'edit']);
            Route::post('editPassword', [UserController::class, 'editPassword']);
            Route::get('order-history', OrderHistoryController::class);
            Route::get('list-active-qr', ListActiveQR::class);
            Route::get('send-qr-to-mail/{id}', SendQRToMailController::class);

        });

        Route::group(['prefix' => 'cart'], function ($router) {
          Route::post('store', StoreController::class);
          Route::get('item/{id}/delete', DeleteItemController::class);
          Route::get('items', ItemsController::class);

        });

        Route::group(['prefix' => 'mobile'], function ($router) {
          Route::get('delete-user', DeleteUserController::class);
        });

    });

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
    Route::post('check-forgot-token', [ForgotPasswordController::class, 'checkForgotToken']);
    Route::post('send-new-password', [ForgotPasswordController::class, 'sendNewPassword']);
    Route::post('resend-forgot', [ForgotPasswordController::class, 'resendForgot']);


    Route::group(['prefix' => 'news'], function ($router) {
        Route::get('get-news', [NewsController::class, 'getNewslist']);
        Route::get('get-news/{id}', [NewsController::class, 'getNews']);
    });

    Route::group(['prefix' => 'museum'], function ($router) {
        Route::get('get-museum', [MuseumController::class, 'getMuseum']);
        Route::get('get-museum/{id}', [MuseumController::class, 'getMuseumById']);
        Route::get('/{id}/educational-programs', EducationalProgramController::class);
        Route::get('/{museum_id}/events', SingleMuseumEventsController::class);
        Route::group(['prefix' => 'mobile'], function ($router) {
          Route::get('get-museum/{id}', [MuseumController::class, 'getMobileMuseumById']);
        });
        Route::get('/{museum_id}/products',[SingleMuseumProductController::class,'index']);
    });
    Route::group(['prefix' => 'banner'], function ($router) {
      Route::get('list', [BannerCantroller::class, 'index']);
    });
    // creating product slide
    Route::group(['prefix' => 'product'], function ($router) {
      Route::get('list', [ProductCantroller::class, 'index']);
    });
    Route::group(['prefix' => 'shop'], function ($router) {
      Route::get('product-list', [ShopProductCantroller::class, 'index']);

      Route::get('product-category', [ShopProductCantroller::class, 'productCategory']);
      Route::get('product/{id}',SingleProductController::class);

    });



    Route::group(['prefix' => 'chat'], function ($router) {
      Route::group(['middleware' => 'apiAuthCheck'], function ($router) {
        Route::get('get-museum-message/{id}', [ChatController::class, 'getMuseumMessage']);
        Route::get('get-admin-message', [ChatController::class, 'getAdminMessage']);
        Route::get('get-all-museums-messages', [ChatController::class, 'getAllMuseumsMessages']);
        Route::get('delete-chat/{id}', [ChatController::class, 'deleteChat']);
      });
      // Route::get('get-museum-message/{id}', [ChatController::class, 'getMuseumMessage'])->middleware('apiAuthCheck');
      Route::post('add-message', [ChatController::class, 'addMessage']);
      Route::post('add-profile-message', [ChatController::class, 'addProfileMessage']);
      Route::post('add-admin-message', [ChatController::class, 'addAdminMessage']);
    });

    Route::group(['prefix' => 'tickets'], function ($router) {
      Route::get('', TicketsController::class);
      Route::get('museum/events', SingleMuseumEventsTicketsController::class);
      Route::get('united', UnitedTicketSettingsController::class);

    });



    Route::get('museum-list', MuseumListController::class);
    Route::get('region-list', RegionListController::class);
    Route::group(['prefix' => 'museum-branches'], function ($router) {
      Route::get('/{museum_id}',MuseumBranchesController::class);

    });

    Route::group(['prefix' => 'events'], function ($router) {
      Route::get('events-list', [EventsListController::class, 'index']);
      Route::get('single-event/{event_id}', SingleEventController::class)->name('singleEvent');
    });

    Route::get('museum-list', MuseumListController::class);
    Route::get('region-list', RegionListController::class);

    Route::group(['prefix' => 'purchase'], function ($router) {
      Route::post('store', PurchaseStoreController::class)->name('purchase_store');
    });

    Route::get('payment-result', PaymentResultController::class);
    Route::group(['prefix' => 'header'], function ($router) {
      Route::get('event-list', [HeaderEventController::class,'index']);
    });


  });
  Route::get('test-museum',[TestController::class, 'test']);



});


// ======================== turnstile Турникет ======================================
Route::group(['prefix' => 'turnstile'], function ($router) {
    Route::group(['middleware' => ['setlang']], function ($router) {

      Route::get('museums', MuseumListController::class);
      Route::post('check-qr', CheckQRController::class);
      Route::post('active-qrs', ActiveQrsController::class);
      Route::post('qr-black-list', QrBlackListController::class);

    // ================ for auth turnstile users =======================
      // Route::post('login', TurnstileLoginController::class);
      // Route::post('register', TurnstileRegisterController::class);
      // Route::group(['middleware' => ['turnstile']], function () {});

    });
  // Route::get('send-qr-to-mail/{id}', SendQRToMailController::class);

});
Route::post('buy-ticket',BuyTicketController::class);

