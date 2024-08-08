<?php

declare(strict_types=1);

namespace MiBo\Countries\Contracts;

/**
 * Interface CountryProviderInterface
 *
 * @package MiBo\Countries\Contracts
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface CountryProviderInterface
{
    /**
     * Retrieves country by its name.
     *
     * @param non-empty-string $name
     *
     * @return \MiBo\Countries\Contracts\CountryInterface
     *
     * @throws \MiBo\Countries\Exceptions\CountryNotFoundException If no country with the given name was found.
     * @throws \MiBo\Countries\Exceptions\InvalidSearchedValueException If provided value is invalid.
     */
    public function getByName(string $name): CountryInterface;

    /**
     * Retrieves country by its alpha-2 code. (US, SK,...)
     *
     * @param non-empty-string $alpha2
     *
     * @return \MiBo\Countries\Contracts\CountryInterface
     *
     * @throws \MiBo\Countries\Exceptions\CountryNotFoundException If no country with the given alpha2 was found.
     * @throws \MiBo\Countries\Exceptions\InvalidSearchedValueException If provided value is invalid.
     */
    public function getByAlpha2(string $alpha2): CountryInterface;

    /**
     * Retrieves country by its alpha-3 code. (USA, SVK,...)
     *
     * @param non-empty-string $alpha3
     *
     * @return \MiBo\Countries\Contracts\CountryInterface
     *
     * @throws \MiBo\Countries\Exceptions\CountryNotFoundException If no country with the given alpha3 was found.
     * @throws \MiBo\Countries\Exceptions\InvalidSearchedValueException If provided value is invalid.
     */
    public function getByAlpha3(string $alpha3): CountryInterface;

    /**
     * Retrieves country by its numerical code.
     *
     * @param numeric-string $numericalCode
     *
     * @return \MiBo\Countries\Contracts\CountryInterface
     *
     * @throws \MiBo\Countries\Exceptions\CountryNotFoundException If no country with the given numeric was found.
     * @throws \MiBo\Countries\Exceptions\InvalidSearchedValueException If provided value is invalid.
     */
    public function getByNumericalCode(string $numericalCode): CountryInterface;
}
