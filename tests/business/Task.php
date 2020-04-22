<?php

declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Taskforce\Business\Task;
use Taskforce\Actions\ActionCancel;
use Taskforce\Actions\ActionRespond;
use Taskforce\Actions\ActionComplete;
use Taskforce\Actions\ActionRefuse;
use Taskforce\Exceptions\ActionTypeException;
use Taskforce\Exceptions\StatusNameException;
use Taskforce\Exceptions\ActionNameException;

require_once '../../vendor/autoload.php';

$actionCancel = new ActionCancel;
$actionRespond = new ActionRespond;
$actionComplete = new ActionComplete;
$actionRefuse = new ActionRefuse;
$actions = [$actionCancel, $actionRespond, $actionComplete, $actionRefuse];
$current_user = 1;
try {
    $newTaskCustomer = new Task($actions, 'new', 1);
    $newTaskNotCustomer = new Task($actions, 'new', 2);
    $activeTaskCustomer = new Task($actions, 'active', 1, 2);
    $activeTaskExecutor = new Task($actions, 'active', 2, 1);
    $activeTaskRandomUser = new Task($actions, 'active', 2, 3);
    assert($newTaskCustomer->getPossibleActions($current_user) === [$actionCancel], 'Customer looking at his new task');
    assert($newTaskNotCustomer->getPossibleActions($current_user) === [$actionRespond], 'User looking at another\'s new task');
    assert($activeTaskCustomer->getPossibleActions($current_user) === [$actionComplete], 'Customer looking at his active task');
    assert($activeTaskExecutor->getPossibleActions($current_user) === [$actionRefuse], 'Executor looking at his active task');
    assert($activeTaskRandomUser->getPossibleActions($current_user) === [], 'User looking at another\'s active task');
} catch (ActionTypeException | StatusNameException $e) {
    error_log("Can't construct a Task object: " . $e->getMessage());
}

try {
    assert($newTaskCustomer->getNextStatus($actionCancel->getActionId()) === Task::STATUS_CANCELED, 'Cancel task');
    assert($newTaskCustomer->getNextStatus($actionRespond->getActionId()) === Task::STATUS_ACTIVE, 'Respond to task');
    assert($newTaskCustomer->getNextStatus($actionComplete->getActionId()) === Task::STATUS_COMPLETED, 'Complete task');
    assert($newTaskCustomer->getNextStatus($actionRefuse->getActionId()) === Task::STATUS_FAILED, 'Refuse task');
} catch (ActionNameException $e) {
    error_log("Can't change status: " . $e->getMessage());
}
