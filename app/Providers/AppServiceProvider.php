<?php

namespace App\Providers;

use App\Support\PatientAccess;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DeleteAction::configureUsing(fn (DeleteAction $action) => $action->successNotificationTitle('Delete is successful.'));
        DeleteBulkAction::configureUsing(fn (DeleteBulkAction $action) => $action->successNotificationTitle('Delete is successful.'));

        if (Schema::hasTable('roles') && Schema::hasTable('permissions')) {
            try {
                PatientAccess::ensureRolePermissions();
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }
}
