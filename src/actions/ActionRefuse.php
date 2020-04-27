<?php

namespace Taskforce\Actions;

class ActionRefuse implements BaseAction
{
    public function getActionName(): string
    {
        return 'Отказаться';
    }

    public function getActionId(): string
    {
        return 'action_refuse';
    }

    public function checkUserRights(int $userId, int $customerId, ?int $executorId): bool
    {
        return $userId === $executorId;
    }
}
