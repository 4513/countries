<?php

declare(strict_types=1);

namespace MiBo\Countries\Tests;

use League\ISO3166\ISO3166;
use MiBo\Countries\Contracts\CountryInterface;
use MiBo\Countries\Contracts\CountryProviderInterface;
use MiBo\Countries\Exceptions\CountryNotFoundException;
use MiBo\Countries\Exceptions\InvalidSearchedValueException;
use MiBo\Countries\ISO\Country;
use MiBo\Countries\ISO\CountryProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * Class CountryTest
 *
 * @package MiBo\Countries\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
#[CoversClass(Country::class)]
#[CoversClass(CountryProvider::class)]
#[CoversClass(CountryNotFoundException::class)]
#[CoversClass(InvalidSearchedValueException::class)]
#[Small]
final class CountryTest extends TestCase
{
    #[DataProvider('getData')]
    public function testCountry(string $alpha3, string $alpha2, string $numerical): void
    {
        $provider = self::getProvider();

        $country1 = $provider->getByAlpha3($alpha3);
        $country2 = $provider->getByAlpha2($alpha2);
        $country3 = $provider->getByNumericalCode($numerical);

        self::assertInstanceOf(CountryInterface::class, $country1);
        self::assertInstanceOf(CountryInterface::class, $country2);
        self::assertInstanceOf(CountryInterface::class, $country3);

        self::assertTrue($country1->is($country2));
        self::assertTrue($country2->is($country3));
        self::assertCount(count($country2->getCurrencies()), $country1->getCurrencies());
        self::assertSame($country1->getName(), $country2->getName());
        self::assertSame((string) $country2, $country1->getAlpha2());
        self::assertTrue($country1->is($provider->getByName($country1->getName())));
    }

    #[DataProvider('getInvalidAlpha2')]
    public function testInvalidAlpha2(string $expectedThrowable, string $value): void
    {
        $provider = self::getProvider();

        self::expectException($expectedThrowable);

        $provider->getByAlpha2($value);
    }

    #[DataProvider('getInvalidAlpha3')]
    public function testInvalidAlpha3(string $expectedThrowable, string $value): void
    {
        $provider = self::getProvider();

        self::expectException($expectedThrowable);

        $provider->getByAlpha3($value);
    }

    #[DataProvider('getInvalidNumeric')]
    public function testInvalidNumeric(string $expectedThrowable, string $value): void
    {
        $provider = self::getProvider();

        self::expectException($expectedThrowable);

        $provider->getByNumericalCode($value);
    }

    #[DataProvider('getInvalidName')]
    public function testInvalidName(string $expectedThrowable, string $value): void
    {
        $provider = self::getProvider();

        self::expectException($expectedThrowable);

        $provider->getByName($value);
    }

    /**
     * @return array<array<string>>
     */
    public static function getData(): array
    {
        return [
            [
                'SVK',
                'SK',
                '703',
            ],
            [
                'CZE',
                'CZ',
                '203',
            ],
            [
                'USA',
                'US',
                '840',
            ],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function getInvalidAlpha2(): array
    {
        return [
            'lowercase' => [
                InvalidSearchedValueException::class,
                'cz',
            ],
            'mixedcase' => [
                InvalidSearchedValueException::class,
                'cZ',
            ],
            'not an alpha2' => [
                InvalidSearchedValueException::class,
                'CZ1',
            ],
            'not two letters' => [
                InvalidSearchedValueException::class,
                'C',
            ],
            'empty' => [
                InvalidSearchedValueException::class,
                '',
            ],
            'not alpha' => [
                InvalidSearchedValueException::class,
                '12',
            ],
            'longer' => [
                InvalidSearchedValueException::class,
                'CZE',
            ],
            'not found' => [
                CountryNotFoundException::class,
                'XX',
            ],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function getInvalidAlpha3(): array
    {
        return [
            'lowercase' => [
                InvalidSearchedValueException::class,
                'svk',
            ],
            'mixedcase' => [
                InvalidSearchedValueException::class,
                'sVk',
            ],
            'not an alpha3' => [
                InvalidSearchedValueException::class,
                'SK',
            ],
            'not three letters' => [
                InvalidSearchedValueException::class,
                'SV',
            ],
            'empty' => [
                InvalidSearchedValueException::class,
                '',
            ],
            'not alpha' => [
                InvalidSearchedValueException::class,
                '123',
            ],
            'longer' => [
                InvalidSearchedValueException::class,
                'SVKA',
            ],
            'not found' => [
                CountryNotFoundException::class,
                'XXX',
            ],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function getInvalidNumeric(): array
    {
        return [
            'not a number' => [
                InvalidSearchedValueException::class,
                '123a',
            ],
            'empty' => [
                InvalidSearchedValueException::class,
                '',
            ],
            'not numeric' => [
                InvalidSearchedValueException::class,
                'SVK',
            ],
            'longer' => [
                InvalidSearchedValueException::class,
                '12345',
            ],
            'not found' => [
                CountryNotFoundException::class,
                '999',
            ],
        ];
    }

    /**
     * @return array<array<string>>
     */
    public static function getInvalidName(): array
    {
        return [
            'empty' => [
                InvalidSearchedValueException::class,
                '',
            ],
            'not found' => [
                CountryNotFoundException::class,
                'Slovak Republic',
            ],
        ];
    }

    public static function getProvider(): CountryProviderInterface
    {
        return new CountryProvider(new ISO3166());
    }
}
