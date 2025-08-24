<?php
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\PageInviteController;
use App\Http\Services\MessagingService;
use Illuminate\Support\Facades\Route;

// Public endpoints (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register-user', [AuthenticationController::class, 'registerUser']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/refresh', [AuthenticationController::class, 'refresh']);
    Route::post('/assign-user-admin', [AuthenticationController::class, 'assignUserAdmin']);
});

Route::get('/health', [AuthenticationController::class, 'health']);

// JWT cookie middleware endpoints
Route::middleware(['jwt.cookie'])->group(function () {
    Route::get('/verify-token', [AuthenticationController::class, 'verifyToken']);
    Route::get('/verify-role', [AuthenticationController::class, 'verifyRole']);
});

// Test endpoints
Route::post('/test-push-notification', [AuthenticationController::class, 'testPushNotifications']);

// Page viewing endpoints (public)
Route::prefix('pages')->group(function () {
    Route::get('/{slug}', [PageViewController::class, 'show']);
    Route::get('/{slug}/preview', [PageViewController::class, 'preview'])->middleware('jwt.cookie');
    Route::get('/{slug}/analytics', [PageViewController::class, 'analytics'])->middleware('jwt.cookie');
});

// Lead submission (public)
Route::post('/leads/submit', [LeadController::class, 'submit']);

// Invite tracking (public)
Route::post('/invites/track-click', [PageInviteController::class, 'trackClick']);

// Page sharing (public)
Route::post('/pages/share', [MessagingService::class, 'sharePageLink']);

// Page invite link shares (public)
Route::prefix('page-invite-shares')->group(function () {
    Route::post('/', [PageInviteController::class, 'createLinkShare']);
    Route::get('/{share}', [PageInviteController::class, 'getLinkShare']);
    Route::put('/{share}/status', [PageInviteController::class, 'updateLinkShareStatus']);
});

// Authenticated user endpoints
Route::middleware('jwt.cookie')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    
    // User leads
    Route::prefix('leads')->group(function () {
        Route::get('/my-leads', [LeadController::class, 'myLeads']);
        Route::put('/{lead}/status', [LeadController::class, 'updateStatus']);
        Route::post('/{lead}/send-message', [LeadController::class, 'sendFollowUpMessage']);
        Route::post('/bulk-messages', [LeadController::class, 'sendBulkMessages']);
        Route::get('/messaging-stats/{page}', [LeadController::class, 'getMessagingStats']);
    });
    
    // User invites
    Route::prefix('invites')->group(function () {
        Route::get('/my-invites', [PageInviteController::class, 'myInvites']);
        Route::post('/create', [PageInviteController::class, 'createInvite']);
        Route::put('/{invite}', [PageInviteController::class, 'updateInvite']);
        Route::delete('/{invite}', [PageInviteController::class, 'deleteInvite']);
        Route::get('/{invite}/referral-tree', [PageInviteController::class, 'getReferralTree']);
    });
    

});

// Admin-only endpoints
Route::middleware(['jwt.cookie', 'role:super admin'])
->prefix('admin')->group(function () {
    // Admin verification
    Route::get('/verify', [AuthenticationController::class, 'verifyAdmin']);
    
    // Page management
    Route::prefix('pages')->group(function () {
        Route::get('/', [AdminController::class, 'getAllPages']);
        Route::post('/', [AdminController::class, 'createPage']);
        Route::get('/{page}', [AdminController::class, 'getPage']);
        Route::put('/{page}', [AdminController::class, 'updatePage']);
        Route::delete('/{page}', [AdminController::class, 'deletePage']);
        Route::post('/{page}/publish', [AdminController::class, 'publishPage']);
        Route::post('/{page}/unpublish', [AdminController::class, 'unpublishPage']);
        Route::post('/{page}/duplicate', [AdminController::class, 'duplicatePage']);
        Route::get('/{page}/stats', [AdminController::class, 'getPageStats']);
        
        // Image upload
        Route::post('/upload-image', [AdminController::class, 'uploadPageImage']);
    });
    
    // Lead management
    Route::prefix('leads')->group(function () {
        Route::get('/', [AdminController::class, 'getAllLeads']);
        Route::put('/{lead}/status', [AdminController::class, 'updateLeadStatus']);
        Route::get('/analytics/{page}', [AdminController::class, 'getLeadAnalytics']);
        Route::post('/{lead}/send-message', [LeadController::class, 'sendFollowUpMessage']);
        Route::post('/bulk-messages', [LeadController::class, 'sendBulkMessages']);
        Route::get('/messaging-stats/{page}', [LeadController::class, 'getMessagingStats']);
    });
    
    // Invite management
    Route::get('/invites', [PageInviteController::class, 'allInvites']);
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
});
