<?php

namespace extpoint\yii2\base;

use yii\base\Object;

abstract class Enum extends Object
{
    /**
     * @return array
     */
    public static function getLabels()
    {
        return [];
    }

    /**
     * @return string[]
     */
    public static function getKeys()
    {
        return array_keys(static::getLabels());
    }

    /**
     * @param string $id
     * @throws \Exception if label doesn't exist
     * @return mixed
     */
    public static function getLabel($id)
    {
        $idLabelMap = static::getLabels();

        if (!isset($idLabelMap[$id])) {
            throw new \Exception('Unknown enum id: ' . $id);
        }
        return $idLabelMap[$id];
    }

    /**
     * @param string $id
     * @return string
     */
    public static function getCssClass($id) {
        return '';
    }

    /**
     * @param string|null $default
     * @return string
     */
    public static function toMysqlEnum($default = null)
    {
        $keys = static::getKeys();
        if ($default === true) {
            $default = reset($keys);
        }

        return "enum('" . implode("','", $keys) . "')"
            . ($default ? 'NOT NULL DEFAULT ' . \Yii::$app->db->quoteValue($default) : 'NULL');
    }
}