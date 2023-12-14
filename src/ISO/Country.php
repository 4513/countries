<?php

declare(strict_types=1);

namespace MiBo\Countries\ISO;

use MiBo\Countries\Contracts\CountryInterface;

/**
 * Class Country
 *
 * @package MiBo\Countries\ISO
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 0.1
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class Country implements CountryInterface
{
    /** @var non-empty-string */
    private string $name;

    /** @var non-empty-string */
    private string $alpha2;

    /** @var non-empty-string */
    private string $alpha3;

    /** @var numeric-string */
    private string $numericalCode;

    /** @var array<\MiBo\Currencies\CurrencyInterface> */
    private array $currencies;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $alpha2
     * @param non-empty-string $alpha3
     * @param numeric-string $numericalCode
     * @param array<\MiBo\Currencies\CurrencyInterface> $currencies
     */
    public function __construct(string $name, string $alpha2, string $alpha3, string $numericalCode, array $currencies)
    {
        $this->name          = $name;
        $this->alpha2        = $alpha2;
        $this->alpha3        = $alpha3;
        $this->numericalCode = $numericalCode;
        $this->currencies    = $currencies;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getAlpha2(): string
    {
        return $this->alpha2;
    }

    /**
     * @inheritDoc
     */
    public function getAlpha3(): string
    {
        return $this->alpha3;
    }

    /**
     * @inheritDoc
     */
    public function getNumericalCode(): string
    {
        return $this->numericalCode;
    }

    /**
     * @inheritDoc
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * @inheritDoc
     */
    public function is(CountryInterface $country): bool
    {
        return $this->getAlpha2() === $country->getAlpha2()
            && $this->getAlpha3() === $country->getAlpha3()
            && $this->getNumericalCode() === $country->getNumericalCode();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getAlpha2();
    }
}
