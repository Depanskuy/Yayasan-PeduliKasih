<?php
// routes/web.php
use App\Core\Router;

// ==========================================
// 1. PUBLIC ROUTES
// ==========================================
Router::get('/', 'HomeController@index');
Router::get('/about', 'HomeController@about');
Router::get('/contact', 'HomeController@contact');

// Authentication
Router::get('/login', 'AuthController@showLogin', ['guest']);
Router::post('/login', 'AuthController@login', ['guest']);
Router::get('/register', 'AuthController@showRegister', ['guest']);
Router::post('/register', 'AuthController@register', ['guest']);
Router::get('/logout', 'AuthController@logout', ['auth']);

// Profile
Router::get('/profile', 'AuthController@profile', ['auth']);
Router::post('/profile', 'AuthController@updateProfile', ['auth']);

// Campaigns
Router::get('/campaigns', 'CampaignController@index');
Router::get('/campaign', 'CampaignController@index');
Router::get('/campaign/detail/{slug}', 'CampaignController@show');
Router::get('/campaigns/{slug}', 'CampaignController@show');

// Donations
Router::get('/campaign/{slug}/donate', 'DonationController@create');
Router::post('/donation/store', 'DonationController@store');
Router::get('/donation/payment/{code}', 'DonationController@payment');
Router::post('/donation/payment/{code}/upload', 'DonationController@uploadProof');
Router::get('/donation/receipt/{code}', 'DonationController@receipt');
Router::get('/donation/certificate/{code}', 'DonationController@certificate');

// Financial Transparency
Router::get('/transparency', 'TransparencyController@index');
Router::get('/transparency/report/print', 'TransparencyController@printReport');

// Volunteer Activities (Public & Volunteer)
Router::get('/volunteer/events', 'VolunteerController@events');
Router::get('/volunteer/events/{slug}', 'VolunteerController@eventDetail');
Router::post('/volunteer/events/{slug}/register', 'VolunteerController@registerEvent');

// Articles & News
Router::get('/articles', 'ArticleController@index');
Router::get('/article/{slug}', 'ArticleController@show');

// Gallery
Router::get('/gallery', 'GalleryController@index');

// ==========================================
// 2. DONOR PORTAL (Role: donatur, superadmin, staff)
// ==========================================
Router::group(['prefix' => 'donor', 'middleware' => 'auth'], function() {
    Router::get('/dashboard', 'DonorDashboardController@index');
});

// ==========================================
// 3. VOLUNTEER PORTAL (Role: volunteer)
// ==========================================
Router::group(['prefix' => 'volunteer', 'middleware' => 'auth'], function() {
    Router::get('/dashboard', 'VolunteerController@dashboard');
    Router::post('/attendance/{id}', 'VolunteerController@attendance');
    Router::post('/report/submit', 'VolunteerController@submitReport');
});

// ==========================================
// 4. ADMIN & STAFF DASHBOARD (Role: superadmin, staff)
// ==========================================
Router::group(['prefix' => 'admin', 'middleware' => 'role:superadmin,staff'], function() {
    Router::get('/', 'AdminDashboardController@index');
    Router::get('/dashboard', 'AdminDashboardController@index');

    // Campaigns
    Router::get('/campaigns', 'CampaignController@adminIndex');
    Router::get('/campaigns/create', 'CampaignController@create');
    Router::post('/campaigns/store', 'CampaignController@store');
    Router::get('/campaigns/edit/{id}', 'CampaignController@edit');
    Router::post('/campaigns/update/{id}', 'CampaignController@update');
    Router::post('/campaigns/{id}/update-progress', 'CampaignController@addUpdate');

    // Donations & Verification
    Router::get('/donations', 'DonationController@adminIndex');
    Router::post('/donations/verify/{id}', 'DonationController@verify');
    Router::post('/donations/reject/{id}', 'DonationController@reject');
    Router::get('/donations/offline', 'DonationController@offlineCreate');
    Router::post('/donations/offline/store', 'DonationController@offlineStore');

    // Beneficiaries (Mustahik)
    Router::get('/beneficiaries', 'BeneficiaryController@index');
    Router::get('/beneficiaries/create', 'BeneficiaryController@create');
    Router::post('/beneficiaries/store', 'BeneficiaryController@store');
    Router::get('/beneficiaries/{id}', 'BeneficiaryController@detail');
    Router::get('/beneficiaries/edit/{id}', 'BeneficiaryController@edit');
    Router::post('/beneficiaries/update/{id}', 'BeneficiaryController@update');

    // Disbursements (Penyaluran Bantuan)
    Router::get('/distributions', 'DisbursementController@index');
    Router::get('/distributions/create', 'DisbursementController@create');
    Router::post('/distributions/store', 'DisbursementController@store');
    Router::get('/distributions/{id}', 'DisbursementController@detail');

    // Volunteer Management
    Router::get('/volunteers/events', 'VolunteerController@adminEvents');
    Router::get('/volunteers/events/create', 'VolunteerController@createEvent');
    Router::post('/volunteers/events/store', 'VolunteerController@storeEvent');
    Router::get('/volunteers/events/{id}/applicants', 'VolunteerController@applicants');
    Router::post('/volunteers/applicant/{id}/status', 'VolunteerController@updateApplicantStatus');
    Router::get('/volunteers/reports', 'VolunteerController@adminReports');
    Router::post('/volunteers/reports/{id}/feedback', 'VolunteerController@feedbackReport');

    // Articles & News
    Router::get('/articles', 'ArticleController@adminIndex');
    Router::get('/articles/create', 'ArticleController@create');
    Router::post('/articles/store', 'ArticleController@store');
    Router::get('/articles/edit/{id}', 'ArticleController@edit');
    Router::post('/articles/update/{id}', 'ArticleController@update');

    // Gallery
    Router::get('/gallery', 'GalleryController@adminIndex');
    Router::post('/gallery/store', 'GalleryController@store');
});

// ==========================================
// 5. SUPERADMIN ONLY (Audit Log, Users, Settings)
// ==========================================
Router::group(['prefix' => 'admin', 'middleware' => 'role:superadmin'], function() {
    Router::get('/audit-logs', 'AdminDashboardController@auditLogs');
    Router::get('/users', 'AdminDashboardController@users');
    Router::get('/users/create', 'AdminDashboardController@createUser');
    Router::post('/users/store', 'AdminDashboardController@storeUser');
    Router::get('/settings', 'AdminDashboardController@settings');
    Router::post('/settings/update', 'AdminDashboardController@updateSettings');
});
