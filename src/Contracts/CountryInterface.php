<?php

declare(strict_types=1);

namespace MiBo\Countries\Contracts;

use Stringable;

/**
 * Interface CountryInterface
 *
 * Implements common function for countries.
 *
 * @package MiBo\Countries\Contracts
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface CountryInterface extends Stringable
{
    /**
     * Official name of the country.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * ISO 3166-1 alpha-2 code of the country.
     *
     * @return non-empty-string
     */
    public function getAlpha2(): string;

    /**
     * ISO 3166-1 alpha-3 code of the country.
     *
     * @return non-empty-string
     */
    public function getAlpha3(): string;

    /**
     * ISO 3166-1 numeric code of the country.
     *
     * @return numeric-string
     */
    public function getNumericalCode(): string;

    /**
     * Currency list used within the country.
     *
     * @return array<non-empty-string>
     */
    public function getCurrencies(): array;

    /**
     * Compares the current country with the provided one.
     *
     * @param \MiBo\Countries\Contracts\CountryInterface $country Country to compare with.
     *
     * @return bool True if the countries are the same, false otherwise.
     */
    public function is(self $country): bool;
}
