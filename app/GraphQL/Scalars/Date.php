<?php

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use GraphQL\Type\Definition\ScalarType;

class Date extends ScalarType
{
    public $name = 'Date';

    public function serialize($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function parseValue($value)
    {
        return Carbon::parse($value);
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof \GraphQL\Language\AST\StringValueNode) {
            return Carbon::parse($valueNode->value);
        }
        return null;
    }
}
