<?php
declare(strict_types=1);

namespace App\Normalizers;

use App\Constants\E500Con;
use App\Errors\BadArrayValuesException;
use TypeError;

class Normalize
{
    /**
     * Convert mixed argument input into an array of (assumed to be) ids
     *
     * If a bare value is provided, it will be converted to an array
     *
     * Arrays are assumed to have the values at the first level.
     * Strings and integers will be pass on without change.
     * Object types will be checked, but they assumed to have an id property
     *
     * Set the $nullable flag to allow/disallow null values for ids
     *
     * @param mixed $arg
     * @param string $obj_type
     * @param bool $nullable
     * @return array<string|int>
     */
    public static function mixedToIdArray(mixed $arg, string $obj_type, bool $nullable = false): array
    {
        $arg = is_array($arg) ? $arg : [$arg];
        $accum = ['count' => 0, 'valid' => [], 'errors' => []];
        //callable silo-ing functions
        $add = function ($accum, $value, $index, $key = 'valid'): array {
            $accum[$key][$index] = $value;
            $accum['count']++;

            return $accum;
        };
        $addError = function ($accum, $value, $index) use ($add): array {
            return $add($accum, $value, $index, 'errors');
        };

        $result = collection($arg)
            ->reduce(function ($accum, $value, $index) use ($obj_type, $add, $addError, $nullable) {
                match (true) {
                    $value instanceof $obj_type => $accum = $add($accum, $value->id, $index),
                    is_string($value), is_int($value) => $accum = $add($accum, $value, $index),
                    is_null($value) => $nullable
                        ? $accum = $add($accum, $value, $index)
                        : $addError($accum, $value, $index),
                    default => $accum = $addError($accum, $value, $index),
                };

            return $accum;
            }, $accum);

        if (!empty($result['errors'])) {
//            debug($result);
            throw new BadArrayValuesException([
                E500Con::MESSAGE => 'Some values in the array were the wrong type',
                E500Con::EXPECTED_VALUES => [$obj_type, 'int (id)', 'string (id)'],
                E500Con::ERROR_SET => $result['errors'],
                E500Con::SOURCE_COUNT => $result['count'],
            ]);
        }

        return $result['valid'];
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function toId(mixed $value): string
    {
        return match (true) {
            is_string($value), is_int($value) => (string)$value,
            is_object($value) && isset($value->id) => (string)$value->id,
            default => throw new TypeError('Value must be sting|int|obj and obj->id must exist.'),
        };
    }
}
