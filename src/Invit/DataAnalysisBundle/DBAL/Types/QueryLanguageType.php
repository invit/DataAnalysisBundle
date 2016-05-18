<?php

namespace Invit\DataAnalysisBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class QueryLanguageType extends AbstractEnumType
{
    const DQL = 'dql';
    const SQL = 'sql';

    protected static $choices = [
        self::DQL => 'DQL',
        self::SQL => 'SQL',
    ];
}