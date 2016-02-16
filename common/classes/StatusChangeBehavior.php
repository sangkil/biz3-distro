<?php

namespace common\classes;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;

/**
 * Description of StatusChangeBehavior
 *
 * @property ActiveRecord $owner
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class StatusChangeBehavior extends Behavior
{
    public $attribute = 'status';
    public $useBeforeInsert = true;

    /**
     * Status state, [old_status, new_status, handler]
     * ```
     * [
     *     [Purchase::STATUS_DRAFT, Purchase::STATUS_APPLY, 'apply'],
     *     [Purchase::STATUS_APPLY, Purchase::STATUS_DRAFT, 'revert'],
     * ]
     * ```
     * @var array
     */
    public $states = [];
    private $_status = true;

    public function events()
    {
        return[
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_AFTER_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function getStatusChanged()
    {
        return $this->_status;
    }

    /**
     *
     * @param ModelEvent $event
     */
    public function beforeInsert($event)
    {
        $this->_status = true;
        if (!$this->useBeforeInsert) {
            return;
        }
        $model = $this->owner;
        $attribute = $this->attribute;
        if (($new = $model->$attribute) != null) {
            foreach ($this->states as $state) {
                if ($state[0] == null && $state[1] == $new) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        $event->isValid = false;
                        $this->_status = false;
                    }
                    return;
                }
            }
        }
    }

    /**
     *
     * @param AfterSaveEvent $event
     */
    public function afterInsert($event)
    {
        if ($this->useBeforeInsert) {
            return;
        }
        $model = $this->owner;
        $attribute = $this->attribute;
        if (($new = $model->$attribute) != null) {
            foreach ($this->states as $state) {
                if ($state[0] == null && $state[1] == $new) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        $this->_status = false;
                    }
                    return;
                }
            }
        }
    }

    /**
     *
     * @param ModelEvent $event
     */
    public function beforeUpdate($event)
    {
        $model = $this->owner;
        $attribute = $this->attribute;
        $dirty = $model->getDirtyAttributes([$attribute]);
        if (isset($dirty[$attribute])) {
            $new = $dirty[$attribute];
            $old = $model->getOldAttribute($attribute);
            foreach ($this->states as $state) {
                if ($state[0] == $old && $state[1] == $new) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        $event->isValid = false;
                        $this->_status = false;
                    }
                    return;
                }
            }
        }
    }

    /**
     *
     * @param ModelEvent $event
     */
    public function beforeDelete($event)
    {
        $model = $this->owner;
        $attribute = $this->attribute;
        if (($old = $model->$attribute) != null) {
            foreach ($this->states as $state) {
                if ($state[0] == $old && $state[1] == null) {
                    $handler = is_string($state[2]) ? [$model, $state[2]] : $state[2];
                    $result = call_user_func_array($handler, array_slice($state, 3));
                    if ($result === false) {
                        $event->isValid = false;
                        $this->_status = false;
                    }
                    return;
                }
            }
        }
    }
}
