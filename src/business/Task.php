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
     * Task constructor
     *
     * @param  array $actions array of Taskforce\Actions\BaseAction implementations' instances
     * @param  string $status task's current status
     * @param  int $customerId ID of taks's creator
     * @param  int|null $executorId ID of executor (if chosen)
     * @return void
     */
    public function __construct(array $actions, string $status, int $customerId, ?int $executorId = null)
    {
        foreach ($actions as $action) {
            if (!is_object($action) || !in_array('Taskforce\Actions\BaseAction', class_implements($action))) {
                throw new ActionTypeException('The first argument must be an array of Taskforce\Actions\BaseAction implementations\'s instances');
            } else {
                $this->allActions[$action->getActionId()] = $action;
            }
        }
        if (!array_key_exists($status, $this->getAllStatuses())) {
            throw new StatusNameException('Incorrect status name');
        }
        $this->status = $status;
        $this->customer = $customerId;
        $this->executor = $executorId;
    }
    
    /**
     * Task's status getter
     *
     * @return string task's status
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * Task's creator getter
     *
     * @return int ID of task's creator
     */
    public function getCustomerId(): int
    {
        return $this->customer;
    }
    
    /**
     * Task's executor getter
     *
     * @return int|null ID of executor (if chosen)
     */
    public function getExecutorId(): ?int
    {
        return $this->executor;
    }

    /**
     * Actions' getter
     *
     * @return array array of all actions
     */
    public static function getAllActions(): array
    {
        return $this->allActions;
    }

    /**
     * Statuses' getter
     *
     * @return array array of all statuses
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
     * Get possible actions for a user
     *
     * @param  int $userId ID of user
     * @return array array of possible actions
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
     * Get next status after action's performing
     *
     * @param  string $action system name of action
     * @return string system name of status
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
                throw new ActionNameException('Incorrect name of action');
        }
    }
}
