<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controller_path = 'App\Http\Controllers';

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++++  AUTHENTICATIONS  +++++++++++++++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Route::get('/auth/login', $controller_path . '\Authentications\AuthController@loginPage')->name('login');
Route::post('/auth/login', $controller_path . '\Authentications\AuthController@login')->name('login');
Route::get('/auth/logout', $controller_path . '\Authentications\AuthController@logout')->name('logout');
Route::get('/auth/register', $controller_path . '\Authentications\AuthController@registerPage')->name('register');
Route::post('/auth/register', $controller_path . '\Authentications\AuthController@register')->name('register');
Route::get('/auth/forgot-password', $controller_path . '\Authentications\AuthController@index')->name('forgot_password');

// Main Page Route
Route::group(['middleware' => ['login.check']], function () {

    // Variable
    $controller_path = 'App\Http\Controllers';
    Route::get('/', $controller_path . '\dashboard\Analytics@index')->name('dashboard-analytics');

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ++++++++++++++++++++++++++++++++++++++++++++  MAIN MENU  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    // Main Menu : Trouble Ticket
    // berfungsi untuk menambah tiket dari Requester serta mengkontrol tiket tersebut
    Route::get('/ticket/lists', $controller_path . '\Main\TicketingController@lists')->name('ticket.lists');
    Route::get('/ticket/data', $controller_path . '\Main\TicketingController@data')->name('ticket.data');
    Route::get('/ticket/create', $controller_path . '\Main\TicketingController@create')->name('ticket.create'); 
    Route::post('/ticket/store', $controller_path . '\Main\TicketingController@store')->name('ticket.store');
    Route::get('/ticket/update/{id}', $controller_path . '\Main\TicketingController@update')->name('ticket.update'); 
    Route::post('/ticket/edit', $controller_path . '\Main\TicketingController@edit')->name('ticket.edit');
    // Menu Ticketing Status : digunakan untuk merubah status ticket 
    // /ticket/{status} = Change ticket status (Archieve, Done, Deleted, Pending, Open, On Progress, and Change PIC)
    Route::post('/ticket/{status}', $controller_path . '\Main\TicketingController@status')->name('ticket.status');

    // Main menu : Replied Comments
    // berfungsi untuk melihat komentar baru dari Requester

    // Main Menu : PIC Dashboard
    // berfungsi untuk mengukur seberapa besar tingkat kesulitan sebuah Tiket

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +++++++++++++++++++++++++++++++++++++++++++  MASTER MENU  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // Master Menu : Account Information
    Route::get('/acc/update/{id}', $controller_path . '\Master\AccountController@update')->name('acc.update');
    Route::post('/acc/edit', $controller_path . '\Master\AccountController@edit')->name('acc.edit');
    
    // Master Menu : User Management
    Route::get('/user/lists', $controller_path . '\Master\UserController@lists')->name('user.lists');
    Route::get('/user/data', $controller_path . '\Master\UserController@data')->name('user.data');
    Route::get('/user/create', $controller_path . '\Master\UserController@create')->name('user.create');
    Route::post('/user/store', $controller_path . '\Master\UserController@store')->name('user.store');
    Route::get('/user/update/{id}', $controller_path . '\Master\UserController@update')->name('user.update');
    Route::post('/user/edit', $controller_path . '\Master\UserController@edit')->name('user.edit');
    Route::get('/user/inactive', $controller_path . '\Master\UserController@inactive')->name('user.inactive');

    // Master Menu : Kategori Tiket
    Route::get('/category/lists', $controller_path . '\Master\CategorizationController@lists')->name('category.lists');
    Route::get('/category/data', $controller_path . '\Master\CategorizationController@data')->name('category.data');
    Route::get('/category/create', $controller_path . '\Master\CategorizationController@create')->name('category.create');
    Route::post('/category/store', $controller_path . '\Master\CategorizationController@store')->name('category.store');
    Route::get('/category/update/{id}', $controller_path . '\Master\CategorizationController@update')->name('category.update');
    Route::post('/category/edit', $controller_path . '\Master\CategorizationController@edit')->name('category.edit');
    Route::get('/category/inactive', $controller_path . '\Master\CategorizationController@inactive')->name('category.inactive');

    // Master Menu : Kategori User

    // Master Menu : Status Tiket

    // Master Menu : Log

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // +++++++++++++++++++++++++++++++++++++++++++  REPORT MENU  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // Report Menu : of Tiket

    // Report Menu : by PIC

    // Report Menu : of Status

    // Report Menu : with Date Time
});

// layout
Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');

// cards
Route::get('/cards/basic', $controller_path . '\cards\CardBasic@index')->name('cards-basic');

// User Interface
Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', $controller_path . '\extended_ui\PerfectScrollbar@index')->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', $controller_path . '\extended_ui\TextDivider@index')->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');
Route::get('/forms/input-groups', $controller_path . '\form_elements\InputGroups@index')->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', $controller_path . '\tables\Basic@index')->name('tables-basic');
