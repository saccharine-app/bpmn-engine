<?php

namespace Saccharine\BpmnEngine\Models;

use Illuminate\Database\Eloquent\Model;
use Saccharine\BpmnEngine\Traits\TriggersBpmnWorkflows;

class DemoOrder extends Model
{
    use TriggersBpmnWorkflows;

    protected $table = 'bpmn_demo_orders';

    protected $guarded = [];
}