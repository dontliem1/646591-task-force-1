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
    assert($newTaskCustomer->getPossibleActions($current_user) === [$actionCancel], 'Заказчик при просмотре своего нового задания');
    assert($newTaskNotCustomer->getPossibleActions($current_user) === [$actionRespond], 'Пользователь при просмотре чужого нового задания');
    assert($activeTaskCustomer->getPossibleActions($current_user) === [$actionComplete], 'Заказчик при просмотре активного задания');
    assert($activeTaskExecutor->getPossibleActions($current_user) === [$actionRefuse], 'Исполнитель при просмотре активного задания');
    assert($activeTaskRandomUser->getPossibleActions($current_user) === [], 'Пользователь при просмотре чужого активного задания');
} catch (ActionTypeException $e) {
    error_log("Не удалось создать объект задания: " . $e->getMessage());
} catch (StatusNameException $e) {
    error_log("Не удалось создать объект задания: " . $e->getMessage());
}

try {
    assert($newTaskCustomer->getNextStatus($actionCancel->getActionId()) === Task::STATUS_CANCELED, 'Отмена задания');
    assert($newTaskCustomer->getNextStatus($actionRespond->getActionId()) === Task::STATUS_ACTIVE, 'Отклик на задание');
    assert($newTaskCustomer->getNextStatus($actionComplete->getActionId()) === Task::STATUS_COMPLETED, 'Завершение задания');
    assert($newTaskCustomer->getNextStatus($actionRefuse->getActionId()) === Task::STATUS_FAILED, 'Отказ от задания');
} catch (ActionNameException $e) {
    error_log("Не удалось проверить смену статус: " . $e->getMessage());
}