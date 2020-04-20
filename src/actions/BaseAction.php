<?php
namespace Taskforce\Actions;

interface BaseAction 
{
    /**
     * Вывод названия действия
     *
     * @return string Название действия
     */
    public function getActionName(): string;

    /**
     * Вывод внутреннего имени действия
     *
     * @return string Строка вида action_*
     */
    public function getActionId(): string;

    /**
     * Проверка условий на возможность совершения действия
     *
     * @param  mixed $user ID пользователя
     * @param  mixed $task Объект задания
     * @return bool Может ли указанный пользователь совершить текущее действие с указанным заданием
     */

    
    /**
     * Проверка прав доступа
     *
     * @param  int $userId ID пользователя
     * @param  int $customerId ID заказчика
     * @param  int|null $executorId ID исполнителя
     * @return bool Может ли указанный пользователь совершить это действие
     */
    public function checkUserRights(int $userId, int $customerId, ?int $executorId): bool;
}