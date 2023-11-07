<?php
declare(strict_types=1);

namespace App\Errors;

use Cake\Core\Exception\CakeException;
use Throwable;

/**
 * BadArrayValuesException
 */
class BadArrayValuesException extends CakeException
{
    /**
     * Used when bad values were found while verifying array content
     *
     * <pre>
     * Keys:
     *   'message'
     *   'expected_value' => string|array; to inform user of the required value type(s)
     *   'error_set' => array; an array of the bad values (ideally on their original indexes)
     *   'source_count' => int|array; the full input set or the count of the input set
     * </pre>
     *
     * @param array|string $message
     * @param int|null $code
     * @param \Throwable|null $previous
     */
    public function __construct(array|string $message = '', ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
