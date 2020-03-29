<?php

use Taskforce\Task;

require_once '../src/Task.php';

$exampleTask = new Task(0);

echo '<pre>';
print_r($exampleTask->getAllStatuses()??'Не удалось получить список всех статусов<br>');
print_r($exampleTask->getAllActions()??'Не удалось получить список всех действий<br>');
echo '</pre>';

assert($exampleTask->getPossibleActions('new') === [Task::ACTION_CANCEL, Task::ACTION_RESPOND], 'Возможные действия для новых заданий');
assert($exampleTask->getPossibleActions('active') === [Task::ACTION_COMPLETE, Task::ACTION_REFUSE], 'Возможные действия для активных заданий');

assert($exampleTask->getNextStatus('action_cancel') === Task::STATUS_CANCELED, 'Отмена задания');
assert($exampleTask->getNextStatus('action_respond') === Task::STATUS_ACTIVE, 'Отклик на задание');
assert($exampleTask->getNextStatus('action_complete') === Task::STATUS_COMPLETED, 'Завершение задания');
assert($exampleTask->getNextStatus('action_refuse') === Task::STATUS_FAILED, 'Отказ от задания');