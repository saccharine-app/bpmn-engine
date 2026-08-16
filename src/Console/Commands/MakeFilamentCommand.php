<?php
namespace Saccharine\BpmnEngine\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFilamentCommand extends Command
{
    protected $signature = 'bpmn:filament';
    protected $description = 'Publish Filament resources for the BPMN Engine';

    public function handle()
    {
        $this->info('Scaffolding Filament Resources for BPMN Engine...');

        // Ensure the host app has a Filament Resources directory
        $targetDir = app_path('Filament/Resources/Bpmn');
        if (!File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $resourcesDir = app_path('Filament/Resources/Bpmn');

        // Publish the Resources
        $resources = ['WorkflowDefinitionResource', 'WorkflowInstanceResource'];
        foreach ($resources as $resource) {
            File::ensureDirectoryExists($resourcesDir);
            File::copy(
                __DIR__ . "/../../../stubs/filament/{$resource}.stub", 
                "{$resourcesDir}/{$resource}.php"
            );
        }

        // Publish the List Pages
        $pages = [
            'WorkflowDefinitionResource' => 'ListWorkflowDefinitions',
            'WorkflowInstanceResource' => 'ListWorkflowInstances',
        ];

        foreach ($pages as $resourceFolder => $pageClass) {
            $pageDir = "{$resourcesDir}/{$resourceFolder}/Pages";
            File::ensureDirectoryExists($pageDir);
            
            File::copy(
                __DIR__ . "/../../../stubs/filament/{$pageClass}.stub", 
                "{$pageDir}/{$pageClass}.php"
            );
        }

        $this->info('Filament integration complete! You should now see the BPMN resources in your admin panel.');
    }
}