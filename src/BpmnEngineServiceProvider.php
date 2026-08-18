<?php

namespace Saccharine\BpmnEngine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Saccharine\BpmnEngine\Listeners\WorkflowTriggerListener;
use Saccharine\BpmnEngine\Enums\WorkflowPermission;
use Saccharine\BpmnEngine\Support\PermissionManifest;

use Saccharine\BpmnEngine\Models\WorkflowDefinition;
use Saccharine\BpmnEngine\Models\WorkflowInstance;
use Saccharine\BpmnEngine\Policies\WorkflowDefinitionPolicy;
use Saccharine\BpmnEngine\Policies\WorkflowInstancePolicy;

class BpmnEngineServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load the database migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Tell the host app where to find Blade templates
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bpmn-engine');

        // Load the package's internal API routes
        Route::prefix(config('bpmn-engine.api_prefix', 'api/bpmn'))
            ->middleware(config('bpmn-engine.api_middleware', ['api']))
            ->group(__DIR__ . '/../routes/api.php');

        // Tell the host app where to find web routes
        Route::prefix(config('bpmn-engine.prefix', 'bpmn/workflows'))
            ->middleware(config('bpmn-engine.middleware', ['web']))
            ->group(__DIR__ . '/../routes/web.php');

        // Listen to all events to check for start event triggers
        Event::listen('*', [WorkflowTriggerListener::class, 'handle']);

        if ($this->app->runningInConsole()) {
            // Register custom artisan commands
            $this->commands([
                \Saccharine\BpmnEngine\Console\Commands\MakeActivityCommand::class,
                \Saccharine\BpmnEngine\Console\Commands\MakeTriggerCommand::class,
                \Saccharine\BpmnEngine\Console\Commands\MakeTemplateCommand::class,
                \Saccharine\BpmnEngine\Console\Commands\InstallCommand::class,
                \Saccharine\BpmnEngine\Console\Commands\DemoCommand::class,
                \Saccharine\BpmnEngine\Console\Commands\InstanceControlCommand::class,
                \Saccharine\BpmnEngine\Console\Commands\MakeFilamentCommand::class
            ]);

            // Allow the host app to publish the config file
            $this->publishes([
                __DIR__ . '/../config/bpmn-engine.php' => config_path('bpmn-engine.php'),
            ], 'bpmn-engine-config');
            
            // Allow host applications to customize/override the view layout if needed
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/bpmn-engine'),
            ], 'bpmn-engine-views');
            
            // Publish compiled frontend assets
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/bpmn-engine'),
            ], 'bpmn-engine-assets');
        }

        $this->registerGates();

        PermissionManifest::register(
            array_column(WorkflowPermission::cases(), 'value')
        );

        config([
            'filament-shield.shield_resource.tabs.custom_permissions' => true,
            'filament-shield.custom_permissions' => array_unique(array_merge(
                config('filament-shield.custom_permissions', []),
                PermissionManifest::all()
            ))
        ]);
        
        Gate::policy(WorkflowDefinition::class, WorkflowDefinitionPolicy::class);
        Gate::policy(WorkflowInstance::class, WorkflowInstancePolicy::class);
    }

    public function register()
    {
        // Merge the host app's published config with our package defaults
        $this->mergeConfigFrom(
            __DIR__ . '/../config/bpmn-engine.php', 'bpmn-engine'
        );
    }

    /**
     * Register the default authorization gates for the BPMN Engine.
     */
    protected function registerGates()
    {
        // View the dashboards and editor
        Gate::define(WorkflowPermission::VIEW->value, fn ($user = null) => app()->environment('local'));
        
        // Create workflows and save diagram changes
        Gate::define(WorkflowPermission::EDIT->value, fn ($user = null) => app()->environment('local'));

        // Delete workflows and their versions
        Gate::define(WorkflowPermission::DELETE->value, fn ($user = null) => app()->environment('local'));
        
        // Control running workflow instances
        Gate::define(WorkflowPermission::SUSPEND_INSTANCE->value, fn ($user = null) => app()->environment('local'));
        Gate::define(WorkflowPermission::RESUME_INSTANCE->value, fn ($user = null) => app()->environment('local'));
        Gate::define(WorkflowPermission::HALT_INSTANCE->value, fn ($user = null) => app()->environment('local'));
    }
}