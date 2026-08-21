<?php

use App\Http\Controllers\AdminContentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSubmissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TrackerController;
use Illuminate\Support\Facades\Route;

/* ----------------------------- Public site ----------------------------- */
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/clear-route-cache', function () {
    Artisan::call('route:clear');
    Artisan::cal('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return 'Route cache cleared';
});

Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/projects', [PageController::class, 'projects'])->name('projects');
Route::get('/projects/{slug}', [PageController::class, 'projectShow'])->name('projects.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/quote', [PageController::class, 'quote'])->name('quote');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

/* ----------------------------- Public submissions ----------------------------- */
Route::post('/quote', [InquiryController::class, 'storeQuote'])->name('inquiry.quote');
Route::post('/contact', [InquiryController::class, 'storeContact'])->name('inquiry.contact');
Route::post('/site-visits', [InquiryController::class, 'storeSiteVisit'])->name('inquiry.site-visit');
Route::post('/meetings', [InquiryController::class, 'storeMeeting'])->name('inquiry.meeting');
Route::post('/careers', [InquiryController::class, 'storeApplication'])->name('inquiry.application');

/* ----------------------------- Live chat (public) ----------------------------- */
Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/messages/{id}', [ChatController::class, 'messages'])->name('chat.messages');
Route::post('/chat/typing', [ChatController::class, 'typing'])->name('chat.typing');

/* ----------------------------- Auth ----------------------------- */
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');

Route::get('/tracker/login', [AuthController::class, 'showClientLogin'])->name('client.login');
Route::post('/tracker/login', [AuthController::class, 'clientLogin'])->name('client.login.post');
Route::post('/tracker/logout', [AuthController::class, 'clientLogout'])->name('client.logout');

/* ----------------------------- Building tracker (client) ----------------------------- */
Route::middleware('client')->group(function () {
    Route::get('/tracker', [TrackerController::class, 'index'])->name('tracker.index');
    Route::get('/tracker/{slug}', [TrackerController::class, 'show'])->name('tracker.show');
});

/* ----------------------------- Admin ----------------------------- */
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    /* Settings */
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');

    /* Content CRUD */
    Route::get('/content/{type}', [AdminContentController::class, 'index'])->name('content.index');
    Route::get('/content/{type}/create', [AdminContentController::class, 'create'])->name('content.create');
    Route::post('/content/{type}', [AdminContentController::class, 'store'])->name('content.store');
    Route::get('/content/{type}/{id}/edit', [AdminContentController::class, 'edit'])->name('content.edit');
    Route::put('/content/{type}/{id}', [AdminContentController::class, 'update'])->name('content.update');
    Route::delete('/content/{type}/{id}', [AdminContentController::class, 'destroy'])->name('content.destroy');
    Route::post('/content/reorder', [AdminContentController::class, 'reorder'])->name('content.reorder');

    /* Project updates */
    Route::get('/projects/{projectId}/updates', [AdminContentController::class, 'updatesIndex'])->name('content.updates');
    Route::post('/projects/{projectId}/updates', [AdminContentController::class, 'updateStore'])->name('content.updates.store');
    Route::delete('/projects/{projectId}/updates/{updateId}', [AdminContentController::class, 'updateDestroy'])->name('content.updates.destroy');

    /* Submissions */
    Route::get('/submissions/{type}', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{type}/{id}', [AdminSubmissionController::class, 'show'])->name('submissions.show');
    Route::put('/submissions/{type}/{id}', [AdminSubmissionController::class, 'update'])->name('submissions.update');

    /* Live chat console */
    Route::get('/chat', [AdminSubmissionController::class, 'chatIndex'])->name('chat.index');
    Route::get('/chat/{id}', [AdminSubmissionController::class, 'chatShow'])->name('chat.show');
    Route::post('/chat/{id}/reply', [AdminSubmissionController::class, 'chatReply'])->name('chat.reply');
    Route::get('/chat/{id}/messages', [AdminSubmissionController::class, 'chatMessages'])->name('chat.messages');
    Route::post('/chat/{id}/agent-join', [ChatController::class, 'agentJoin'])->name('chat.agent-join');
    Route::post('/chat/{id}/agent-leave', [ChatController::class, 'agentLeave'])->name('chat.agent-leave');
});
