<?php

namespace Library\CatalogBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class ReadlistEnumType extends AbstractEnumType
{
    const IN_READ = 'in_read';
    const READED = 'readed';
    const PAUSED = 'paused';
    const USUAL= 'usual';
    const MY_LIBRARY= 'my_library';

    protected static $choices = [
        self::IN_READ    => 'В чтении',
        self::READED => 'Прочитано',
        self::PAUSED  => 'Приостановлено',
        self::USUAL  => 'Список чтения',
        self::MY_LIBRARY => 'Моя библиотека',
    ];
    protected static $colors = [
        self::IN_READ    => 'info',
        self::READED => 'success',
        self::PAUSED  => 'warning',
        self::USUAL  => 'readlist',
        self::MY_LIBRARY  => 'my_library',
    ];

    protected static $allowChoices = [
        self::USUAL  => 'Usual readlist',
    ];

    public static function getInternalTypes()
    {
        return [self::IN_READ, self::MY_LIBRARY, self::PAUSED, self::READED];
    }

    public static function getAllowedChoices()
    {
        return array_diff_key(static::$choices, array_flip(static::getInternalTypes()));
    }

    /**
     * @return array
     */
    public static function getColors()
    {
        return static::$colors;
    }
}