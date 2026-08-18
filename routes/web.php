<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Middleware\EnsureTeamMembership;
use App\Jobs\TestLoadJob;
use Illuminate\Http\StreamedEvent;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::get('test-load', function () {
    return response()->eventStream(function () {
        $total = 1000;

        for ($i = 1; $i <= $total; $i++) {
            TestLoadJob::dispatch($i)->delay(now()->addSeconds(rand(1, 5)));

            yield new StreamedEvent(
                event: 'update',
                data: "Dispatched {$i}/{$total} jobs",
            );
        }
    });
})->name('test-load');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::get('dashboard', DashboardController::class)->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::post('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::delete('invitations/{invitation}', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

require __DIR__.'/settings.php';
