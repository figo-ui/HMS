<?php

namespace App\Providers\Filament;
use App\Filament\Resources\Appointments\AppointmentsResource;
use App\Filament\Resources\Radiologies\RadiologyResource;
use App\Filament\Resources\Triages\TriageResource;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Resources\BillingPayments\BillingPaymentResource;
use App\Filament\Resources\Departments\DepartmentResource;
use App\Filament\Resources\Doctors\DoctorsResource;
use App\Filament\Resources\Inventories\InventoryResource;
use App\Filament\Resources\Nurses\NurseResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Settings;
use Filament\Actions\Action;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            //->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->passwordReset()
            ->profile(EditProfile::class)
            ->databaseNotifications()
            ->databaseNotificationsPolling('10s')
            ->brandName('OBU')
            ->brandLogo(asset('images/odb-logo.jfif'))
            ->brandLogoHeight('2.5rem')
            ->sidebarWidth('12rem')
            ->collapsedSidebarWidth('4.25rem')
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups()
            ->colors([
                'primary' => Color::Amber,
            ])

/*
    ->userMenuItems([
            Action::make('settings')
                ->url(fn (): string => Settings::getUrl())
                ->icon('heroicon-o-cog-6-tooth'),
            // ...
         ])

*/
            ->renderHook(
    PanelsRenderHook::STYLES_AFTER,
    fn (): HtmlString => new HtmlString(<<<'HTML'
        <style>
            /* 1. Fix Sidebar Scrollbar (Make it visible but thin) */
          
            .fi-sidebar-nav::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 10px;
            }
            .fi-sidebar-nav {
                -ms-overflow-style: auto; 
                scrollbar-width: 100%;
                overflow-y: auto; /* Ensures it can actually scroll */
            }

            /* 2. Fix Body/Layout background and scrolling */
            .fi-body {
                background-color: #aab5c7;
                overflow-y: auto !important; 
                 overflow-x: auto !important;
                 overflow-x: auto !important;
    display: block;
    width: 100%;/* Forces vertical scroll to work */
            }
        
            .fi-simple-layout {
                position: relative;
                min-height: 100vh;
                background-image: 
                    linear-gradient(135deg, rgba(6, 23, 45, 0.78), rgba(6, 23, 45, 0.60)),
                    url('/images/login-bg.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }

            /* Table Zebra Striping Fix */
            .fi-ta-table tbody tr:nth-child(odd) td {
                background-color: #ffffff !important;
            }
            .fi-ta-table tbody tr:nth-child(even) td {
                background-color: #acbeda !important;
            }

            /* Sidebar Styling */
            .fi-sidebar {
                background: #0d346e;
                border-inline-end: 1px solid rgba(24, 73, 136, 0.08);
            }
            
            /* Rest of your custom CSS... */
        </style>
    HTML),
)
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <style>
                        .fi-sidebar-nav::webkit-scrollbar{
                            display:none;
                        }
                        .fi-sidebar-nav{
                            -ms-overflow-style: none;
                            scrollbar-width: none;

                        }
                         
                        
                        .fi-ta-table tbody tr:nth-child(odd) td {
                            background-color: #ffffff;
                        }
                       

                        .fi-ta-table tbody tr:nth-child(even) td {
                            background-color: #acbeda;
                        }
          
                        .fi-sidebar {
                            background: #0d346e;
                            border-inline-end: 1px solid rgba(24, 73, 136, 0.08);
                            box-shadow: none;
                        }

                        .fi-sidebar-header {
                            min-height: 4.5rem;
                            padding-inline: 0.85rem;
                        }

                        .fi-sidebar-nav {
                            padding-inline: 0.55rem;
                            padding-block: 0.65rem;
                            gap: 0.35rem;
                        }

                        .fi-sidebar-nav-groups {
                            gap: 0.25rem;
                        }

                        .fi-sidebar-group-label {
                            color: #f3f4f6;
                            font-size: 0.8rem;
                            letter-spacing: 0;
                            text-transform: none;
                            font-weight: 700;
                        }

                        .fi-sidebar-item-btn,
                        .fi-sidebar-group-dropdown-trigger-btn,
                        .fi-sidebar-group-btn,
                        .fi-sidebar-database-notifications-btn {
                            min-height: 2rem;
                            border-radius: 0.25rem;
                            padding-block: 0.15rem;
                        }

                        .fi-sidebar-item-btn > .fi-icon,
                        .fi-sidebar-group-btn .fi-icon,
                        .fi-sidebar-group-dropdown-trigger-btn .fi-icon,
                        .fi-sidebar-database-notifications-btn > .fi-icon {
                            color: #a3a3a3;
                        }

                        .fi-sidebar-item-label,
                        .fi-sidebar-database-notifications-btn-label {
                            color: #e5e7eb;
                            font-size: 0.95rem;
                        }

                        .fi-sidebar-item.fi-active > .fi-sidebar-item-btn,
                        .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn {
                            background: linear-gradient(135deg, rgba(14, 116, 144, 0.45), rgba(30, 64, 175, 0.42));
                            box-shadow: inset 0 0 0 1px rgba(125, 211, 252, 0.2);
                        }

                        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover,
                        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:focus-visible,
                        .fi-sidebar-group-dropdown-trigger-btn:hover,
                        .fi-sidebar-group-dropdown-trigger-btn:focus-visible,
                        .fi-sidebar-group-btn:hover,
                        .fi-sidebar-group-btn:focus-visible {
                            background: rgba(148, 163, 184, 0.14);
                        }

                        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover > .fi-sidebar-item-label,
                        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:focus-visible > .fi-sidebar-item-label,
                        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:hover > .fi-icon,
                        .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn:focus-visible > .fi-icon,
                        .fi-sidebar-group-dropdown-trigger-btn:hover .fi-sidebar-group-label,
                        .fi-sidebar-group-dropdown-trigger-btn:focus-visible .fi-sidebar-group-label,
                        .fi-sidebar-group-dropdown-trigger-btn:hover .fi-icon,
                        .fi-sidebar-group-dropdown-trigger-btn:focus-visible .fi-icon,
                        .fi-sidebar-group-btn:hover .fi-sidebar-group-label,
                        .fi-sidebar-group-btn:focus-visible .fi-sidebar-group-label,
                        .fi-sidebar-group-btn:hover .fi-icon,
                        .fi-sidebar-group-btn:focus-visible .fi-icon {
                            color: #ffffff;
                        }

                        .fi-sidebar-sub-group-items {
                            margin-inline-start: 0.9rem;
                            padding-inline-start: 0.9rem;
                            border-inline-start: 1px solid rgba(15, 15, 15, 0.14);
                            display: flex;
                            flex-direction: column;
                            gap: 0.1rem;
                        }

                        .fi-sidebar-item[grouped],
                        .fi-sidebar-item.fi-sidebar-item-has-children {
                            position: relative;
                        }

                        .fi-sidebar-item .fi-sidebar-sub-group-items .fi-sidebar-item-btn {
                            position: relative;
                            padding-inline-start: 0.25rem;
                        }

                        .fi-sidebar-item .fi-sidebar-sub-group-items .fi-sidebar-item-btn::before {
                            content: '';
                            position: absolute;
                            inset-inline-start: -0.72rem;
                            top: 50%;
                            width: 0.6rem;
                            border-top: 1px solid rgba(255, 255, 255, 0.14);
                            transform: translateY(-50%);
                        }

                        .fi-sidebar-item-grouped-border {
                            width: 0.7rem;
                            min-width: 0.7rem;
                            height: 1rem;
                        }

                        .fi-sidebar-item-grouped-border-part-not-first,
                        .fi-sidebar-item-grouped-border-part-not-last {
                            background: rgba(255, 255, 255, 0.14);
                        }

                        .fi-sidebar-item-grouped-border-part {
                            width: 0.35rem;
                            height: 0.35rem;
                            background: #9ca3af;
                            border-radius: 9999px;
                        }

                        .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-sidebar-item-label,
                        .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn > .fi-sidebar-item-label {
                            color: #ffffff;
                            font-weight: 600;
                        }

                        .fi-sidebar-item.fi-active > .fi-sidebar-item-btn > .fi-icon,
                        .fi-sidebar-item.fi-sidebar-item-has-active-child-items > .fi-sidebar-item-btn > .fi-icon {
                            color: #e0f2fe;
                        }

                        .fi-sidebar-item-toggle {
                            margin-inline-start: auto;
                            width: 0.95rem;
                            height: 0.95rem;
                            color: #a3a3a3;
                            transition: transform 0.18s ease, color 0.18s ease;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .fi-sidebar-item-toggle-open {
                            transform: rotate(90deg);
                            color: #ffffff;
                        }

                        .fi-sidebar-item-icon {
                            width: 1rem;
                            height: 1rem;
                        }

                        .fi-sidebar-item .fi-sidebar-sub-group-items .fi-sidebar-item-icon {
                            color: #9ca3af;
                        }

                        .fi-sidebar-header .fi-logo {
                            margin-inline-start: 0;
                        }

                        .fi-sidebar-header-logo-ctn {
                            display: flex;
                            align-items: center;
                        }

                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) {
                            width: var(--collapsed-sidebar-width);
                        }

                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-header,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-nav,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-footer {
                            padding-inline: 0.45rem;
                        }

                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-btn,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-dropdown-trigger-btn,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-btn,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-database-notifications-btn {
                            justify-content: center;
                            padding-inline: 0;
                        }

                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-label,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-label,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-badge-ctn,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-database-notifications-btn-label,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-database-notifications-btn-badge-ctn,
                        .fi-body.fi-body-has-sidebar-collapsible-on-desktop .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-sub-group-items {
                            display: none;
                        }
                    </style>
                    HTML),
            )
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn (): HtmlString => new HtmlString(<<<'HTML'
                    <style>
                        .fi-body {
                            background-color: #2a416d;
                        }

                        .fi-simple-layout {

                            position: relative;
                            min-height: 100vh;
                            background-image:
                                linear-gradient(135deg, rgba(6, 23, 45, 0.78), rgba(6, 23, 45, 0.60)),
                                url('/images/login-bg.jpg');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                            background-attachment: fixed;
                        }

                        .fi-simple-layout::before {
                            content: '';
                            position: absolute;
                            inset: 0;
                            background:
                                radial-gradient(circle at 18% 20%, rgba(34, 211, 238, 0.22), transparent 45%),
                                radial-gradient(circle at 78% 82%, rgba(59, 130, 246, 0.18), transparent 40%);
                            pointer-events: none;
                        }

                        .fi-simple-main-ctn {
                            position: relative;
                            z-index: 1;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 100vh;
                            padding: 1.25rem;
                        }

                        .fi-simple-main {
                            width: min(30rem, 95vw);
                        }

                        .fi-simple-main > div {
                            border: 1px solid rgba(148, 163, 184, 0.30);
                            border-radius: 1.1rem;
                            background: rgba(47, 58, 85, 0.62);
                            backdrop-filter: blur(8px);
                            box-shadow: 0 30px 80px rgba(2, 6, 23, 0.55);
                        }

                        .fi-logo {
                            filter: drop-shadow(0 4px 10px rgba(51, 61, 102, 0.35)5));
                        }

                        /* Forgot Password Link Styling */
                        .fi-form-actions a.fi-link {
                            background: linear-gradient(135deg, #0f766e, #0f172a);
                            color: #ffffff !important;
                            padding: 10px 16px;
                            border-radius: 8px;
                            font-weight: 600;
                            text-decoration: none;
                            display: inline-block;
                            margin-top: 12px;
                            transition: all 0.2s ease;
                            box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
                        }

                        .fi-form-actions a.fi-link:hover {
                            background: linear-gradient(135deg, #0f172a, #0f766e);
                            transform: translateY(-1px);
                            box-shadow: 0 4px 8px rgba(15, 118, 110, 0.3);
                            color: #ffffff !important;
                        }
                    </style>
                    HTML),
                    
                scopes: Login::class,
            )
              
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->navigationItems([
                
                NavigationItem::make('Pharmacy Management')
                    ->icon('heroicon-o-folder')
                    ->sort(30)
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['admin', 'super_admin', 'pharmacy_staff', 'pharmacist']))
                    ->url(fn (): string => InventoryResource::getUrl())
                    ->isActiveWhen(fn (): bool => request()->routeIs([
                        'filament.admin.resources.inventories.*',
                        'filament.admin.resources.inventory-transactions.*',
                        'filament.admin.resources.pharmacies.*',
                        'filament.admin.resources.pharmacy-sales.*',
                        'filament.admin.pages.pharmacy-report-dashboard',
                        'pharmacy-reports.export.csv',
                        'pharmacy-reports.print',
                        'filament.admin.resources.settings.*',
                    ])),
                NavigationItem::make('Staff Management')
                    ->icon('heroicon-o-folder')
                    ->sort(31)
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['admin', 'super_admin']))
                    ->url(fn (): string => NurseResource::getUrl())
                    ->isActiveWhen(fn (): bool => request()->routeIs([
                        'filament.admin.resources.nurses.*',
                        'filament.admin.resources.doctors.*',
                        'filament.admin.resources.departments.*',
                    ])),
            ])


          
            ->pages([
                Dashboard::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ]);
            
    }
    
}
