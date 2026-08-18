<?php

namespace Saccharine\BpmnEngine\Policies;

use Saccharine\BpmnEngine\Models\WorkflowDefinition;
use Saccharine\BpmnEngine\Enums\WorkflowPermission;

class WorkflowDefinitionPolicy
{
    public function viewAny($user): bool {
        return $user->can(WorkflowPermission::VIEW->value);
    }
    
    public function view($user, WorkflowDefinition $model): bool {
        return $user->can(WorkflowPermission::VIEW->value);
    }
    
    public function create($user): bool {
        return $user->can(WorkflowPermission::EDIT->value);
    }
    
    public function update($user, WorkflowDefinition $model): bool {
        return $user->can(WorkflowPermission::EDIT->value);
    }
    
    public function delete($user, WorkflowDefinition $model): bool {
        return $user->can(WorkflowPermission::DELETE->value);
    }
    
}
