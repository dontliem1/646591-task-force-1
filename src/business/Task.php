<?php
namespace Taskforce\Business;

use Taskforce\Exceptions\ActionTypeException;
use Taskforce\Exceptions\StatusNameException;
use Taskforce\Exceptions\ActionNameException;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    private $status;
    private $customer;
    private $executor;
    private $allActions = [];

    /**
     * Конструктор задания на основе id заказчика, исполнителя и статуса
     *
     * @param  array $actions Объекты-действия
     * @param  string $status Текущий статус задания
     * @param  int $customerId ID заказчика, создавшего задание
     * @param  int|null $executorId ID исполнителя, откликнувшегося на задание (при наличии)
     * @return void
     */
    public function __construct(array $actions, string $status, int $customerId, ?int $executorId = null)
    {
        foreach ($actions as $action) {
            if (!is_object($action) || !in_array('Taskforce\Actions\BaseAction', class_implements($action))) {
                throw new ActionTypeException('Неверный тип действия в массиве');
            } else {
                $this->allActions[$action->getActionId()] = $action;
            }
        }
        if (!array_key_exists($status, $this->getAllStatuses())) {
            throw new StatusNameException('Неверное название статуса');
        }
        $this->status = $status;
        $this->customer = $customerId;
        $this->executor = $executorId;
    }
    
    /**
     * Геттер статуса задания
     *
     * @return string Статус задания
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * Геттер заказчика задания
     *
     * @return int ID заказчика
     */
    public function getCustomerId(): int
    {
        return $this->customer;
    }
    
    /**
     * Геттер исполнителя задания
     *
     * @return int ID исполнителя
     */
    public function getExecutorId(): int
    {
        return $this->executor;
    }

    /**
     * Вывод массива всех возможных действий с заданиями
     *
     * @return array Массив всех действий
     */
    public static function getAllActions(): array
    {
        return $this->allActions;
    }

    /**
     * Вывод массива всех возможных статусов заданий
     *
     * @return array Массив всех статусов
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCELED => 'Отмененное',
            self::STATUS_ACTIVE => 'Активное',
            self::STATUS_COMPLETED => 'Завершенное',
            self::STATUS_FAILED => 'Проваленное'
        ];
    }
    
    /**
     * Возвращает список всех возможных действий с заданием для указанного пользователя
     *
     * @param  int $userId ID пользователя
     * @return array Массив объектов доступных действий
     */
    public function getPossibleActions(int $userId): array
    {
        $possibleActions = [];
        switch ($this->status) {
            case self::STATUS_NEW:
                if (isset($this->allActions['action_cancel']) && $this->allActions['action_cancel']->checkUserRights($userId, $this->customer, $this->executor)) {
                    $possibleActions[] = $this->allActions['action_cancel'];
                }
                if (isset($this->allActions['action_respond']) && $this->allActions['action_respond']->checkUserRights($userId, $this->customer, $this->executor)) {
                    $possibleActions[] = $this->allActions['action_respond'];
                }
                break;
            case self::STATUS_ACTIVE:
                if (isset($this->allActions['action_complete']) && $this->allActions['action_complete']->checkUserRights($userId, $this->customer, $this->executor)) {
                    $possibleActions[] = $this->allActions['action_complete'];
                }
                if (isset($this->allActions['action_refuse']) && $this->allActions['action_refuse']->checkUserRights($userId, $this->customer, $this->executor)) {
                    $possibleActions[] = $this->allActions['action_refuse'];
                }
                break;
        }
        return $possibleActions;
    }

    /**
     * Вывод статуса, в который переходит задание после совершения указанного действия
     *
     * @param  string $action Внутреннее имя действия
     * @return string Внутреннее имя статуса
     */
    public static function getNextStatus(string $action): string
    {
        switch ($action) {
            case 'action_cancel':
                return self::STATUS_CANCELED;
                break;
            case 'action_respond':
                return self::STATUS_ACTIVE;
                break;
            case 'action_complete':
                return self::STATUS_COMPLETED;
                break;
            case 'action_refuse':
                return self::STATUS_FAILED;
                break;
            default:
                throw new ActionNameException('Неверное имя действия');
        }
    }
}
