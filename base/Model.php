<?php

namespace extpoint\yii2\base;

use extpoint\yii2\exceptions\ModelDeleteException;
use extpoint\yii2\exceptions\ModelSaveException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class Model extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @param string|array $condition
     * @return null|static
     * @throws NotFoundHttpException
     */
    public static function findOrPanic($condition)
    {
        $model = static::findOne($condition);
        if (!$model) {
            throw new NotFoundHttpException('Запись не найдена');
        }
        return $model;
    }

    /**
     * @param string[]|null $attributeNames
     * @throws ModelSaveException
     */
    public function saveOrPanic($attributeNames = null)
    {
        if (!$this->save(true, $attributeNames)) {
            throw new ModelSaveException($this);
        }
    }

    /**
     * @throws ModelDeleteException
     */
    public function deleteOrPanic()
    {
        if (!$this->delete()) {
            throw new ModelDeleteException($this);
        }
    }

    /**
     * @param Model $user
     * @return bool
     */
    public function canUserUpdate($user)
    {
        return $this->canUpdated();
    }

    /**
     * @return bool
     */
    public function canUpdated()
    {
        return true;
    }

    /**
     * @param Model $user
     * @return bool
     */
    public function canUserDelete($user)
    {
        return $this->canDeleted();
    }

    public function canDeleted()
    {
        return !$this->isNewRecord;
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert) && $this->canUpdated();
    }

    public function beforeDelete()
    {
        return parent::beforeDelete() && $this->canDeleted();
    }

}
