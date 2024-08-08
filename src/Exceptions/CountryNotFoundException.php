<?php

declare(strict_types=1);

namespace MiBo\Countries\Exceptions;

use MiBo\Countries\Contracts\CountryExceptionInterface;
use OutOfBoundsException;
use Throwable;

/**
 * Class CountryNotFoundException
 *
 * @package MiBo\Countries\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 2.0.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class CountryNotFoundException extends OutOfBoundsException implements CountryExceptionInterface
{
    public static function notFoundBy(string $key, string $value, ?Throwable $previous = null): self
    {
        return new self('Country with ' . $key . ' "' . $value . '" not found.', 0, $previous);
    }
}
