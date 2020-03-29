<?php

namespace Taskforce\Business;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'action_cancel';
    const ACTION_RESPOND = 'action_respond';
    const ACTION_COMPLETE = 'action_complete';
    const ACTION_REFUSE = 'action_refuse';

    const ROLE_CUSTOMER = 'customer';
    const ROLE_EXECUTOR = 'executor';

    private $status;
    private $customer;
    private $executor;

    /**
     * Создание задания и присвоение ему статуса «Новое»
     *
     * @param  int $customerId ID заказчика, создавшего задание
     * @param  int $executorId ID исполнителя, откликнувшегося на задание
     * @return void
     */
    public function __construct(int $customerId, int $executorId)
    {
        $this->customer = $customerId;
        $this->executor = $executorId;
        $this->status = self::STATUS_NEW;
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
     * Вывод массива всех возможных действий с заданиями
     *
     * @return array Массив всех действий
     */
    public static function getAllActions(): array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPOND => 'Откликнуться',
            self::ACTION_COMPLETE => 'Завершить',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    /**
     * Вывод возможных действий для указанного статуса задания
     *
     * @param  string $status Внутреннее имя статуса
     * @return array Массив возможных действий
     */
    public static function getPossibleActions(string $status): array
    {
        switch ($status) {
            case self::STATUS_NEW:
                return [
                    self::ACTION_CANCEL,
                    self::ACTION_RESPOND
                ];
                break;
            case self::STATUS_ACTIVE:
                return [
                    self::ACTION_COMPLETE,
                    self::ACTION_REFUSE
                ];
                break;
        }
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
            case self::ACTION_CANCEL:
                return self::STATUS_CANCELED;
                break;
            case self::ACTION_RESPOND:
                return self::STATUS_ACTIVE;
                break;
            case self::ACTION_COMPLETE:
                return self::STATUS_COMPLETED;
                break;
            case self::ACTION_REFUSE:
                return self::STATUS_FAILED;
                break;
        }
    }
}
