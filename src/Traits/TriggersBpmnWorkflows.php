<?php

namespace Saccharine\BpmnEngine\Traits;

use Saccharine\BpmnEngine\Events\ModelEventTrigger;

trait TriggersBpmnWorkflows
{
    public static function bootTriggersBpmnWorkflows(): void
    {
        static::created(function ($model) {
            ModelEventTrigger::dispatch($model, 'created');
        });

        static::updated(function ($model) {
            ModelEventTrigger::dispatch($model, 'updated', $model->getChanges());
        });

        static::deleted(function ($model) {
            ModelEventTrigger::dispatch($model, 'deleted');
        });
    }
}