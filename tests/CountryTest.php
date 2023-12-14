<?php

declare(strict_types=1);

namespace MiBo\Countries\Tests;

use League\ISO3166\ISO3166;
use MiBo\Countries\Contracts\CountryInterface;
use MiBo\Countries\Contracts\CountryProviderInterface;
use MiBo\Countries\ISO\CountryProvider;
use MiBo\Currencies\ISO\ISOArrayListLoader;
use MiBo\Currencies\ISO\ISOCurrencyProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

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
 *
 * @coversDefaultClass \MiBo\Countries\ISO\Country
 */
final class CountryTest extends TestCase
{
    /**
     * @small
     *
     * @covers ::__construct
     * @covers ::getName
     * @covers ::getAlpha2
     * @covers ::getAlpha3
     * @covers ::getNumericalCode
     * @covers ::getCurrencies
     * @covers ::is
     * @covers ::__toString
     * @covers \MiBo\Countries\ISO\CountryProvider::__construct
     * @covers \MiBo\Countries\ISO\CountryProvider::getByName
     * @covers \MiBo\Countries\ISO\CountryProvider::getByAlpha2
     * @covers \MiBo\Countries\ISO\CountryProvider::getByAlpha3
     * @covers \MiBo\Countries\ISO\CountryProvider::getByNumericalCode
     * @covers \MiBo\Countries\ISO\CountryProvider::createCountry
     *
     * @param non-empty-string $alpha3
     * @param non-empty-string $alpha2
     * @param numeric-string $numerical
     *
     * @return void
     *
     * @dataProvider getData
     */
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

    /**
     * @small
     *
     * @covers \MiBo\Countries\ISO\CountryProvider::__construct
     * @covers \MiBo\Countries\ISO\CountryProvider::getByName
     * @covers \MiBo\Countries\ISO\CountryProvider::getByAlpha2
     * @covers \MiBo\Countries\ISO\CountryProvider::getByAlpha3
     * @covers \MiBo\Countries\ISO\CountryProvider::getByNumericalCode
     *
     * @param non-empty-string $alpha3
     * @param non-empty-string $alpha2
     * @param numeric-string $numerical
     * @param string $name
     *
     * @return void
     *
     * @dataProvider getInvalidData
     */
    public function testInvalidCountry(string $alpha3, string $alpha2, string $numerical, string $name): void
    {
        $provider = self::getProvider();

        self::assertNull($provider->getByAlpha3($alpha3));
        self::assertNull($provider->getByAlpha2($alpha2));
        self::assertNull($provider->getByNumericalCode($numerical));
        self::assertNull($provider->getByName($name));
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
    public static function getInvalidData(): array
    {
        return [
            [
                'SSS',
                'ZZ',
                '000',
                'ARandomString',
            ],
        ];
    }

    public static function getProvider(): CountryProviderInterface
    {
        return new CountryProvider(
            new ISO3166(),
            new ISOCurrencyProvider(new ISOArrayListLoader(), new NullLogger())
        );
    }
}
