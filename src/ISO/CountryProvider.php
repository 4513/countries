<?php

declare(strict_types=1);

namespace MiBo\Countries\ISO;

use League\ISO3166\ISO3166DataProvider;
use MiBo\Countries\Contracts\CountryInterface;
use MiBo\Countries\Contracts\CountryProviderInterface;
use MiBo\Countries\Exceptions\CountryNotFoundException;
use MiBo\Countries\Exceptions\InvalidSearchedValueException;
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

    /**
     * @var array{
     *     alpha2: array<non-empty-string, \MiBo\Countries\Contracts\CountryInterface>,
     *     alpha3: array<non-empty-string, \MiBo\Countries\Contracts\CountryInterface>,
     *     numeric: array<numeric-string, \MiBo\Countries\Contracts\CountryInterface>
     * }
     */
    private array $countrySearched = [
        'alpha2'  => [],
        'alpha3'  => [],
        'numeric' => [],
    ];

    public function __construct(ISO3166DataProvider $countryProvider)
    {
        $this->countryProvider  = $countryProvider;
    }

    /**
     * @inheritDoc
     */
    public function getByName(string $name): CountryInterface
    {
        // @phpstan-ignore-next-line - $name is not empty
        if ($name === '') {
            throw InvalidSearchedValueException::invalidValue('name', $name);
        }

        try {
            return $this->createCountry($this->countryProvider->name($name));
        } catch (OutOfBoundsException $exception) {
            throw CountryNotFoundException::notFoundBy('name', $name, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByAlpha2(string $alpha2): CountryInterface
    {
        if (key_exists($alpha2, $this->countrySearched['alpha2'])) {
            return $this->countrySearched['alpha2'][$alpha2];
        }

        if (preg_match('/^[A-Z]{2}$/', $alpha2) !== 1) {
            throw InvalidSearchedValueException::invalidValue('alpha-2', $alpha2);
        }

        try {
            return $this->createCountry($this->countryProvider->alpha2($alpha2));
        } catch (OutOfBoundsException $exception) {
            throw CountryNotFoundException::notFoundBy('alpha-2', $alpha2, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByAlpha3(string $alpha3): CountryInterface
    {
        if (key_exists($alpha3, $this->countrySearched['alpha3'])) {
            return $this->countrySearched['alpha3'][$alpha3];
        }

        if (preg_match('/^[A-Z]{3}$/', $alpha3) !== 1) {
            throw InvalidSearchedValueException::invalidValue('alpha-3', $alpha3);
        }

        try {
            return $this->createCountry($this->countryProvider->alpha3($alpha3));
        } catch (OutOfBoundsException $exception) {
            throw CountryNotFoundException::notFoundBy('alpha-3', $alpha3, $exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByNumericalCode(string $numericalCode): CountryInterface
    {
        if (key_exists($numericalCode, $this->countrySearched['numeric'])) {
            return $this->countrySearched['numeric'][$numericalCode];
        }

        if (preg_match('/^\d{3}$/', $numericalCode) !== 1) {
            throw InvalidSearchedValueException::invalidValue('numeric', $numericalCode);
        }

        try {
            return $this->createCountry($this->countryProvider->numeric($numericalCode));
        } catch (OutOfBoundsException $exception) {
            throw CountryNotFoundException::notFoundBy('numeric', $numericalCode, $exception);
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
        $country = new Country($data['name'], $data['alpha2'], $data['alpha3'], $data['numeric'], $data['currency']);

        $this->countrySearched['alpha2'][$data['alpha2']]  = $country;
        $this->countrySearched['alpha3'][$data['alpha3']]  = $country;
        $this->countrySearched['numeric'][$data['numeric']] = $country;

        return $country;
    }
}
