<?php

namespace Taskforce\Actions;

class ActionComplete implements BaseAction
{
    public function getActionName(): string
    {
        return 'Завершить';
    }

    public function getActionId(): string
    {
        return 'action_complete';
    }

    public function checkUserRights(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $customerId;
    }
}
