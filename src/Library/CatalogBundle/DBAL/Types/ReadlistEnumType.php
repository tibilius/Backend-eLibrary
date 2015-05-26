<?php

namespace Library\CatalogBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class ReadlistEnumType extends AbstractEnumType
{
    const IN_READ = 'in_read';
    const READED = 'readed';
    const PAUSED = 'paused';
    const USUAL= 'usual';

    protected static $choices = [
        self::IN_READ    => 'В чтении',
        self::READED => 'Прочитано',
        self::PAUSED  => 'Приостановлено',
        self::USUAL  => 'Список чтения',
    ];
    protected static $colors = [
        self::IN_READ    => 'info',
        self::READED => 'success',
        self::PAUSED  => 'warning',
        self::USUAL  => 'readlist',
    ];

    protected static $allowChoices = [
        self::USUAL  => 'Usual readlist',
    ];

    public static function getAllowedChoices()
    {
        return static::$allowChoices;
    }

    /**
     * @return array
     */
    public static function getColors()
    {
        return static::$colors;
    }
}