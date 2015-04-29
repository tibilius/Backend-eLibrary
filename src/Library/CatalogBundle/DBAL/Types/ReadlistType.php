<?php

namespace Library\CatalogBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class ReadlistType extends AbstractEnumType
{
    const IN_READ = 'in_read';
    const READED = 'readed';
    const PAUSED = 'paused';


    protected static $choices = [
        self::IN_READ    => 'In read',
        self::READED => 'Already red',
        self::PAUSED  => 'Pause in read',

    ];
}