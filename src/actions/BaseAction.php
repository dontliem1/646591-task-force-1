<?php
namespace Taskforce\Actions;

interface BaseAction 
{
    /**
     * Action's name getter
     *
     * @return string name of the action
     */
    public function getActionName(): string;

    /**
     * Action's system name
     *
     * @return string string of format action_...
     */
    public function getActionId(): string;

    /**
     * Checking user rights for performing the action
     *
     * @param  int $userId ID of a current user
     * @param  int $customerId ID of a task's creator
     * @param  int|null $executorId ID of a task's executor (if chosen)
     * @return bool can the user perform the action?
     */
    public function checkUserRights(int $userId, int $customerId, ?int $executorId): bool;
}