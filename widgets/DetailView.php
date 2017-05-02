<?php

namespace extpoint\yii2\widgets;

use extpoint\yii2\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DetailView extends \yii\widgets\DetailView
{
    /**
     * @var Model
     */
    public $model;

    protected function renderAttribute($attribute, $index)
    {
        if (is_string($this->template)) {
            $captionOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'captionOptions', []));
            $contentOptions = Html::renderTagAttributes(ArrayHelper::getValue($attribute, 'contentOptions', []));
            return strtr($this->template, [
                '{label}' => $attribute['label'],
                '{value}' => $this->renderValue($attribute, $index),
                '{captionOptions}' => $captionOptions,
                '{contentOptions}' =>  $contentOptions,
            ]);
        } else {
            return call_user_func($this->template, $attribute, $index, $this);
        }
    }

    protected function normalizeAttributes()
    {
        if ($this->model instanceof Model && $this->attributes === null) {
            $modelClass = $this->model;
            $this->attributes = array_keys(array_filter($modelClass::meta(), function($item) {
                return !empty($item['showInView']);
            }));
        }
        parent::normalizeAttributes();
    }

    protected function renderValue($attribute, $index) {
        if (isset($attribute['attribute']) && $this->model instanceof Model) {
            $options = ArrayHelper::getValue($attribute, 'options', []);
            return \Yii::$app->types->renderForView($this->model, $attribute['attribute'], $options);
        }

        return $this->formatter->format($attribute['value'], $attribute['format']);
    }
}