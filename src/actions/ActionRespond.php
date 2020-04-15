<?php
namespace Taskforce\Actions;

class ActionRespond extends BaseAction
{        
    public function getActionName(): string
    {
        return 'Откликнуться';
    }

    public function getActionId(): string
    {
        return 'action_respond';
    }
    
    public function checkUserRights(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId !== $customerId;
    }
}