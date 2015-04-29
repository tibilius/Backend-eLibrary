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
        self::IN_READ    => 'In read',
        self::READED => 'Already red',
        self::PAUSED  => 'Pause in read',
        self::USUAL  => 'Usual readlist',
    ];
}