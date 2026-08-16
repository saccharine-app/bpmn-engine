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

        // Define the resources to publish
        $resources = [
            'WorkflowDefinitionResource',
            'WorkflowInstanceResource',
        ];

        foreach ($resources as $resource) {
            $stubPath = __DIR__ . "/../../../stubs/filament/{$resource}.stub";
            $targetPath = "{$targetDir}/{$resource}.php";

            if (File::exists($targetPath)) {
                $this->warn("{$resource} already exists. Skipping.");
                continue;
            }

            File::copy($stubPath, $targetPath);
            $this->line("Published: {$resource}");
        }

        $this->info('Filament integration complete! You should now see the BPMN resources in your admin panel.');
    }
}