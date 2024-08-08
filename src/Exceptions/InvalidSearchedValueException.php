<?php

declare(strict_types=1);

namespace MiBo\Countries\Exceptions;

use InvalidArgumentException;
use MiBo\Countries\Contracts\CountryExceptionInterface;

/**
 * Class InvalidSearchedValueException
 *
 * @package MiBo\Countries\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 2.0.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class InvalidSearchedValueException extends InvalidArgumentException implements CountryExceptionInterface
{
    public static function invalidValue(string $key, string $value): self
    {
        return new self('Invalid value "' . $value . '" for key "' . $key . '".');
    }
}
