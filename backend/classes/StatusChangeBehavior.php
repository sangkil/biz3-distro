<?php

namespace backend\classes;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\ModelEvent;

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

    public function events()
    {
        return[
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate'
        ];
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
                    }
                    return;
                }
            }
        }
    }
}
