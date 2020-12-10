<?php

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Type\Definition\ScalarType;

/**
 * Read more about scalars here http://webonyx.github.io/graphql-php/type-system/scalar-types/
 */
class UUID extends ScalarType
{
    /**
     * Serializes an internal value to include in a response.
     *
     * @param  mixed  $value
     * @throws Error
     * @return mixed
     */
    public function serialize($value)
    {
        $validator = validator(['id' => $value], ['id' => 'uuid'], ['uuid' => 'The :attribute must be a valid UUID string.']);
        if ($validator->fails())
            throw new Error("Cannot represent following value as UUID: {$validator->errors()->first()}");
        // Assuming the internal representation of the value is always correct
        return $value;

        // TODO validate if it might be incorrect
    }

    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * @param  mixed  $value
     * @throws Error
     * @return mixed
     */
    public function parseValue($value)
    {
        $validator = validator(['id' => $value], ['id' => 'uuid'], ['uuid' => 'The :attribute must be a valid UUID string.']);
        if ($validator->fails())
            throw new Error($validator->errors()->first());

        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * E.g.
     * {
     *   user(email: "user@example.com")
     * }
     *
     * @param  \GraphQL\Language\AST\Node  $valueNode
     * @param  mixed[]|null  $variables
     * @throws Error
     * @return mixed
     */
    public function parseLiteral($valueNode, ?array $variables = null)
    {
        $validator = validator(['id' => $valueNode->value], ['id' => 'uuid'], ['uuid' => 'The :attribute must be a valid UUID string.']);
        if ($validator->fails())
            throw new Error($validator->errors()->first());
        return $valueNode->value;
    }
}
