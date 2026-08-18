<?php

namespace Saccharine\BpmnEngine\Enums;

enum WorkflowPermission: string
{
    case VIEW = 'bpmn:view';
    case EDIT = 'bpmn:edit';
    case DELETE = 'bpmn:delete';
    case SUSPEND_INSTANCE = 'bpmn:suspend-instance';
    case RESUME_INSTANCE = 'bpmn:resume-instance';
    case HALT_INSTANCE = 'bpmn:halt-instance';

    public function label(): string
    {
        return match ($this) {
            self::VIEW => 'View Workflows',
            self::EDIT => 'Edit Workflows',
            self::DELETE => 'Delete Workflows',
            self::SUSPEND_INSTANCE => 'Suspend Workflow Instances',
            self::RESUME_INSTANCE => 'Resume Workflow Instances',
            self::HALT_INSTANCE => 'Halt Workflow Instances',
        };
    }
}