<?php

declare(strict_types=1);

namespace MiBo\Countries\ISO;

use League\ISO3166\ISO3166DataProvider;
use MiBo\Countries\Contracts\CountryInterface;
use MiBo\Countries\Contracts\CountryProviderInterface;
use MiBo\Currencies\CurrencyProvider;
use OutOfBoundsException;

/**
 * Class CountryProvider
 *
 * @package MiBo\Countries\ISO
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class CountryProvider implements CountryProviderInterface
{
    private ISO3166DataProvider $countryProvider;

    private CurrencyProvider $currencyProvider;

    public function __construct(ISO3166DataProvider $countryProvider, CurrencyProvider $currencyProvider)
    {
        $this->countryProvider  = $countryProvider;
        $this->currencyProvider = $currencyProvider;
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name): ?CountryInterface
    {
        try {
            return $this->createCountry($this->countryProvider->name($name));
        } catch (OutOfBoundsException) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getByAlpha2(string $alpha2): ?CountryInterface
    {
        try {
            return $this->createCountry($this->countryProvider->alpha2($alpha2));
        } catch (OutOfBoundsException) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getByAlpha3(string $alpha3): ?CountryInterface
    {
        try {
            return $this->createCountry($this->countryProvider->alpha3($alpha3));
        } catch (OutOfBoundsException) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getByNumericalCode(string $numericalCode): ?CountryInterface
    {
        try {
            return $this->createCountry($this->countryProvider->numeric($numericalCode));
        } catch (OutOfBoundsException) {
            return null;
        }
    }

    /**
     * @param array{
     *     name: non-empty-string,
     *     alpha2: non-empty-string,
     *     alpha3: non-empty-string,
     *     numeric: numeric-string,
     *     currency: array<int, non-empty-string>
     * } $data
     *
     * @return \MiBo\Countries\Contracts\CountryInterface
     */
    private function createCountry(array $data): CountryInterface
    {
        $currencies = [];

        foreach ($data['currency'] as $currency) {
            $currencies[] = $this->currencyProvider->findByAlphabeticalCode($currency);
        }

        return new Country($data['name'], $data['alpha2'], $data['alpha3'], $data['numeric'], $currencies);
    }
}
