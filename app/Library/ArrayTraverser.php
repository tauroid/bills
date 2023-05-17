<?php

namespace App\Library;

class ArrayTraverser {
    static function traverse(
        callable|ArrayTraversalContext $context,
        array $firstArray,
        ...$args
    ): void
    {
        $context->preprocess = null;
        $context->postprocess = null;
        self::map($context, $firstArray, ...$args);
    }

    static function map(
        callable|ArrayTraversalContext $context,
        array $firstArray,
        ...$args,
    ): array
    {
        if (!is_a($context, ArrayTraversalContext::class)) {
            $context = new ArrayTraversalContext(
                function: $context
            );
        }

        $extraArrays = [];
        foreach ($args as $key => $value) {
            if (is_integer($key)) {
                $extraArrays []= $value;
            }
        }

        $newArray = [];
        foreach ($firstArray as $key => $value) {
            $valueContext = $context->accumulate($key);
            $extraValues = [];
            foreach ($extraArrays as $index => $array) {
                if (!array_key_exists($key, $array)) {
                    throw new ArrayStructureDoesntMatchException(
                        $context->getPrefix(), $index, $key
                    );
                }
                $extraValues []= $array[$key];
            }
            if ($valueContext->preprocess) {
                if (empty($extraValues)) {
                    $value = ($valueContext->preprocess)(
                        $valueContext->getPrefix(), $value,
                        $valueContext->data
                    );
                } else {
                    $values = ($valueContext->preprocess)(
                        $valueContext->getPrefix(),
                        $value, ...$extraValues,
                        contextData: $valueContext->data
                    );
                    $value = $values[0];
                    $extraValues = array_slice($values, 1);
                }
            }

            $recurse =
                is_null($valueContext->dontRecurse)
                || !(empty($extraValues)
                     ? ($valueContext->dontRecurse)(
                         $valueContext->getPrefix(),
                         $value, $valueContext->data)
                     : ($valueContext->dontRecurse)(
                         $valueContext->getPrefix(),
                         $value, ...$extraValues,
                         contextData: $valueContext->data));

            if (is_array($value) && $recurse) {
                $value = self::map(
                    $valueContext, $value, ...$extraValues
                );
            } else {
                if (empty($extraValues)) {
                    $value = ($valueContext->function)(
                        $valueContext->getPrefix(),
                        $value, $valueContext->data
                    );
                } else {
                    $value = ($valueContext->function)(
                        $valueContext->getPrefix(),
                        $value, ...$extraValues,
                        contextData: $valueContext->data
                    );
                }
            }
            if ($valueContext->postprocess) {
                $value = ($valueContext->postprocess)(
                    $valueContext->getPrefix(), $value,
                    $valueContext->data
                );
            }
            $newArray[$key] = $value;
        }
        return $newArray;
    }
}
