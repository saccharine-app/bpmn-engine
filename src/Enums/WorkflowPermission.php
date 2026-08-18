<?php

namespace Saccharine\BpmnEngine\Enums;

enum WorkflowPermission: string
{
    case VIEW = 'view';
    case EDIT = 'edit';
    case DELETE = 'delete';
    case SUSPEND_INSTANCE = 'suspend-instance';
    case RESUME_INSTANCE = 'resume-instance';
    case HALT_INSTANCE = 'halt-instance';

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