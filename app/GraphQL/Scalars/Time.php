<?php

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\StringValueNode;

class Time extends ScalarType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Time',
            'description' => ' A Time string in HH:MM:SS format (24 -hour)'
        ]);
    }
    public function serialize($value): string
    {
        return $value;
    }

    public function parseValue($value) : string
    {
        if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/', $value)) {
            throw new \InvalidArgumentException("Format waktu harus HH:MM:SS (24-hour)");
        }
        return $value;
    }

    public function parseLiteral($valueNode, ?array $valiables = null): string
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: waktu harus berupa string', [$valueNode]);
        }
        return $this->parseValue($valueNode->value);
    }

}
