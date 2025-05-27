<?php

namespace app\GraphQL\Scalars;

use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\Node;


class DateTime extends ScalarType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'DateTime',
            'serialize' => [$this, 'serialize'],
            'parseValue' => [$this, 'parseValue'],
            'parseLiteral' => [$this, 'parseLiteral'],
        ]);
    }

    public function serialize($value)
    {
        return $value instanceof \DateTimeInterface
            ? $value->format(\DateTime::ATOM)
            : $value;
    }

    public function parseValue($value)
    {
        return new \DateTime($value);
    }

    public function parseLiteral($valueNode, array $variables = null)
    {
        if ($valueNode instanceof \GraphQL\Language\AST\StringValueNode) {
            return new \DateTime($valueNode->value);
        }
        return null;
    }
}

