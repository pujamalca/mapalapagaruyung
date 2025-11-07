<?php

namespace App\Providers;

use App\Filament\Admin\Widgets\AnalyticsTrendsChart;
use App\Filament\Admin\Widgets\DoctorLatencyChart;
use App\Models\Activity;
use App\Models\Comment;
use App\Models\Page;
use App\Models\Post;
USE App\Models\Tag;
use App\Models\User;
use App\Observers\CommentObserver;
use App\Observers\PostObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use App\Repositories\PageRepository;
use App\Support\HtmlCleaner;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HtmlCleaner::class, fn () => new HtmlCleaner());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->aliasFilamentTableActions();

        // Register observers
        Post::observe(PostObserver::class);
        User::observe(UserObserver::class);
        Comment::observe(CommentObserver::class);
        Tag::observe(TagObserver::class);

        RateLimiter::for('public-content', function (Request $request) {
            return [
                Limit::perMinute((int) config('starterkit.rate_limit.public', 120))
                    ->by($request->ip()),
            ];
        });

        RateLimiter::for('content-write', function (Request $request) {
            $identifier = $request->user()?->getAuthIdentifier()
                ? sprintf('user:%s', $request->user()->getAuthIdentifier())
                : sprintf('ip:%s', $request->ip());

            return [
                Limit::perMinute((int) config('starterkit.rate_limit.content_write', 30))
                    ->by($identifier),
            ];
        });

        RateLimiter::for('comments', function (Request $request) {
            return [
                Limit::perMinute((int) config('starterkit.rate_limit.comments', 20))
                    ->by($request->ip()),
            ];
        });

        if (! app()->runningInConsole()) {
            $request = request();

            if ($request) {
                $storageUrl = rtrim($request->getSchemeAndHttpHost(), '/') . '/storage';
                config()->set('filesystems.disks.public.url', $storageUrl);
            }
        }

        Activity::saving(function (Activity $activity): void {
            if (app()->runningInConsole()) {
                return;
            }

            $request = request();

            if (! $request) {
                return;
            }

            $activity->ip_address ??= $request->ip();
            $activity->user_agent ??= $request->userAgent();
            $activity->url ??= URL::full();
            $activity->method ??= $request->method();
        });

        Page::saved(function (Page $page): void {
            /** @var PageRepository $repository */
            $repository = app(PageRepository::class);
            $originalSlug = $page->getOriginal('slug');

            $repository->forget($page, $originalSlug);
        });

        Page::deleted(function (Page $page): void {
            /** @var PageRepository $repository */
            $repository = app(PageRepository::class);
            $repository->forget($page, $page->getOriginal('slug'));
        });

        Page::restored(function (Page $page): void {
            /** @var PageRepository $repository */
            $repository = app(PageRepository::class);
            $repository->forget($page, $page->getOriginal('slug'));
        });

        Livewire::component('filament.admin.widgets.doctor-latency-chart', DoctorLatencyChart::class);
        Livewire::component('filament.admin.widgets.analytics-trends-chart', AnalyticsTrendsChart::class);
    }

    protected function aliasFilamentTableActions(): void
    {
        $aliases = [
            'Filament\Tables\Actions\Action' => \Filament\Actions\Action::class,
            'Filament\Tables\Actions\ActionGroup' => \Filament\Actions\ActionGroup::class,
            'Filament\Tables\Actions\AssociateAction' => \Filament\Actions\AssociateAction::class,
            'Filament\Tables\Actions\AttachAction' => \Filament\Actions\AttachAction::class,
            'Filament\Tables\Actions\ButtonAction' => \Filament\Actions\ButtonAction::class,
            'Filament\Tables\Actions\BulkAction' => \Filament\Actions\BulkAction::class,
            'Filament\Tables\Actions\BulkActionGroup' => \Filament\Actions\BulkActionGroup::class,
            'Filament\Tables\Actions\CreateAction' => \Filament\Actions\CreateAction::class,
            'Filament\Tables\Actions\DeleteAction' => \Filament\Actions\DeleteAction::class,
            'Filament\Tables\Actions\DeleteBulkAction' => \Filament\Actions\DeleteBulkAction::class,
            'Filament\Tables\Actions\DissociateAction' => \Filament\Actions\DissociateAction::class,
            'Filament\Tables\Actions\DetachAction' => \Filament\Actions\DetachAction::class,
            'Filament\Tables\Actions\DetachBulkAction' => \Filament\Actions\DetachBulkAction::class,
            'Filament\Tables\Actions\EditAction' => \Filament\Actions\EditAction::class,
            'Filament\Tables\Actions\ExportAction' => \Filament\Actions\ExportAction::class,
            'Filament\Tables\Actions\ExportBulkAction' => \Filament\Actions\ExportBulkAction::class,
            'Filament\Tables\Actions\ForceDeleteAction' => \Filament\Actions\ForceDeleteAction::class,
            'Filament\Tables\Actions\ForceDeleteBulkAction' => \Filament\Actions\ForceDeleteBulkAction::class,
            'Filament\Tables\Actions\IconButtonAction' => \Filament\Actions\IconButtonAction::class,
            'Filament\Tables\Actions\ImportAction' => \Filament\Actions\ImportAction::class,
            'Filament\Tables\Actions\ReplicateAction' => \Filament\Actions\ReplicateAction::class,
            'Filament\Tables\Actions\RestoreAction' => \Filament\Actions\RestoreAction::class,
            'Filament\Tables\Actions\RestoreBulkAction' => \Filament\Actions\RestoreBulkAction::class,
            'Filament\Tables\Actions\SelectAction' => \Filament\Actions\SelectAction::class,
            'Filament\Tables\Actions\ViewAction' => \Filament\Actions\ViewAction::class,
        ];

        foreach ($aliases as $legacy => $modern) {
            if (! class_exists($legacy) && class_exists($modern)) {
                class_alias($modern, $legacy);
            }
        }
    }
}
