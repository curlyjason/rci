<?php
declare(strict_types=1);

namespace App\Constants;

class E500Con
{
    /**
     * Accepts a string; the primary error message
     */
    public const MESSAGE = 'message';

    /**
     * Can be a single entity or the entity's error array
     * EntityInterface|array
     */
    public const ENTITY_ERRORS = 'entity_errors';

    /**
     * Can be a string or an array (for Text::toList())
     * string|array
     */
    public const EXPECTED_VALUES = 'expected_value';

    /**
     * An array of rejected values on their original keys
     */
    public const ERROR_SET = 'error_set';

    /**
     * The count of array element that were checked to generate ERROR_SET
     * int|array
     */
    public const SOURCE_COUNT = 'source_count';
}
