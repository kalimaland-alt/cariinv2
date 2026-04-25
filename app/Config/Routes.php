<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/**
 * @var RouteCollection $routes
 */

// ============================================================
// PUBLIC ROUTES
// ============================================================
$routes->get('/', 'Home::index');
$routes->get('search', 'Home::search');
$routes->get('property/(:segment)', 'Property::detail/$1');
$routes->get('category/(:segment)', 'Home::byCategory/$1');
$routes->get('agen', 'Pages::agen');
$routes->get('panduan', 'Pages::panduan');
$routes->get('blog', 'Pages::blog');

// ============================================================
// AUTH ROUTES
// ============================================================
$routes->group('auth', static function ($routes) {
    $routes->get('google', 'Auth::google');
    $routes->get('google/callback', 'Auth::googleCallback');
});
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::doRegister');
$routes->get('logout', 'Auth::logout');
$routes->get('verify-email/(:segment)', 'Auth::verifyEmail/$1');
$routes->get('resend-verification', 'Auth::resendVerify', ['filter' => 'auth']);

// Password reset
$routes->get('forgot-password', 'ForgotPassword::showForgot');
$routes->post('forgot-password', 'ForgotPassword::sendReset');
$routes->get('reset-password/(:segment)', 'ForgotPassword::showReset/$1');
$routes->post('reset-password', 'ForgotPassword::doReset');

$routes->get('dev/login-admin', 'Auth::devLoginAdmin');
$routes->get('dev/login-member', 'Auth::devLoginMember');

// ============================================================
// SITEMAP
// ============================================================
$routes->get('sitemap.xml', 'Sitemap::index');

// Wilayah Indonesia (data dari DB lokal: reg_provinces, reg_regencies, reg_districts, reg_villages)
$routes->get('api/wilayah/provinces',            'Wilayah::provinces');
$routes->get('api/wilayah/regencies/(:segment)', 'Wilayah::regencies/$1');
$routes->get('api/wilayah/districts/(:segment)', 'Wilayah::districts/$1');
$routes->get('api/wilayah/villages/(:segment)',  'Wilayah::villages/$1');


// ============================================================
// MEMBER DASHBOARD (filter: auth)
// ============================================================
$routes->group('dashboard', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('profile', 'Dashboard::profile');
    $routes->post('profile/update', 'Dashboard::updateProfile');
    $routes->post('profile/password', 'Dashboard::changePassword');
});

$routes->group('my-ads', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Ads::index');
});

$routes->group('ads', ['filter' => 'auth'], static function ($routes) {
    $routes->get('create', 'Ads::create');
    $routes->post('store', 'Ads::store');
    $routes->get('edit/(:segment)', 'Ads::edit/$1');
    $routes->post('update/(:segment)', 'Ads::update/$1');
    $routes->post('delete/(:segment)', 'Ads::delete/$1');
    $routes->get('delete-image/(:segment)', 'Ads::deleteImage/$1');
    $routes->post('upload-image', 'Ads::uploadImage');
});

$routes->group('topup', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Topup::index');
    $routes->get('history', 'Topup::history');
    $routes->post('create', 'Topup::create');
    $routes->get('pay/(:num)', 'Topup::pay/$1');
});
// Topup webhook (no auth - Midtrans calls this)
$routes->post('topup/notification', 'Topup::notification');

// Wishlist (member)
$routes->group('wishlist', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Wishlist::index');
    $routes->post('toggle/(:num)', 'Wishlist::toggle/$1');
});

// Compare (public, session-based)
$routes->get('compare', 'Compare::index');
$routes->post('compare/add/(:num)', 'Compare::add/$1');
$routes->get('compare/remove/(:num)', 'Compare::remove/$1');
$routes->get('compare/clear', 'Compare::clear');

// Chat (member)
$routes->group('chat', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Chat::index');
    $routes->get('start/(:num)', 'Chat::start/$1');
    $routes->get('thread/(:num)', 'Chat::thread/$1');
    $routes->post('send/(:num)', 'Chat::send/$1');
});

// Rating (member)
$routes->post('rating/store', 'Rating::store', ['filter' => 'auth']);

// Payment
$routes->group('payment', ['filter' => 'auth'], static function ($routes) {
    $routes->get('new-slot', 'Payment::newSlot');
    $routes->post('create-snap', 'Payment::createSnap');
    $routes->get('finish', 'Payment::finish');
});
// Webhook (no auth filter - called by Midtrans server)
$routes->post('payment/notification', 'Payment::notification');

// ============================================================
// ADMIN ROUTES (filter: admin)
// ============================================================
$routes->group('admin', ['filter' => 'admin', 'namespace' => 'App\Controllers\Admin'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('moderation', 'Moderation::index');
    $routes->post('moderation/approve/(:num)', 'Moderation::approve/$1');
    $routes->post('moderation/reject/(:num)', 'Moderation::reject/$1');

    $routes->get('users', 'Users::index');
    $routes->post('users/suspend/(:num)', 'Users::suspend/$1');
    $routes->post('users/activate/(:num)', 'Users::activate/$1');

    $routes->get('categories', 'Categories::index');
    $routes->post('categories/store', 'Categories::store');

    $routes->get('transactions', 'Transactions::index');

    // Finance
    $routes->get('finance', 'Finance::index');
    $routes->get('finance/topup-history', 'Finance::topupHistory');
    $routes->post('finance/topup/(:num)/approve', 'Finance::approveTopup/$1');
    $routes->post('finance/topup/(:num)/reject', 'Finance::rejectTopup/$1');

    // Settings
    $routes->get('settings', 'Settings::index');
    $routes->post('settings/save', 'Settings::save');
});

// ============================================================
// SITEMAP
// ============================================================
// Wilayah Indonesia (data dari DB lokal: reg_provinces, reg_regencies, reg_districts, reg_villages)
#$routes->get('api/wilayah/provinces',                'Wilayah::provinces');
#$routes->get('api/wilayah/regencies/(:segment)',     'Wilayah::regencies/$1');
#$routes->get('api/wilayah/districts/(:segment)',     'Wilayah::districts/$1');
#$routes->get('api/wilayah/villages/(:segment)',      'Wilayah::villages/$1');

$routes->get('api/wilayah/provinces',            'Wilayah::provinces');
$routes->get('api/wilayah/regencies/(:segment)', 'Wilayah::regencies/$1');
$routes->get('api/wilayah/districts/(:segment)', 'Wilayah::districts/$1');
$routes->get('api/wilayah/villages/(:segment)',  'Wilayah::villages/$1');