<?php
namespace Taskforce\Actions;

class ActionCancel implements BaseAction
{        
    public function getActionName(): string
    {
        return 'Отменить';
    }

    public function getActionId(): string
    {
        return 'action_cancel';
    }
    
    public function checkUserRights(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}