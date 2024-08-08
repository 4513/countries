# Countries  
[![codecov](https://codecov.io/gh/4513/countries/graph/badge.svg?token=Gk4bJ8AKhJ)](https://codecov.io/gh/4513/countries)

*mibo/countries*

The library provides a simple interface for a country entity and country provider, which retrieves
a country by its ISO 3166-1 alpha-2 code, alpha-3 code, or numeric code. The provider is able to
retrieve a country by its name, too.  
If the provider does not find the country, it returns a null.

The Country entity contains its name, ISO 3166-1 alpha-2 code, alpha-3 code, numeric code, and
the country's currencies (objects).

The list of the available countries can be changed by the provider, because the library (ISO) provider
uses a provider from 'league/iso3166' library, which provides the same data but as array instead.

```php
$provider = new \MiBo\Countries\ISO\CountryProvider(
    new \League\ISO3166\ISO3166($myCountryList ?? []),
    new \MiBo\Currencies\ISO\ISOCurrencyProvider(
        new \MiBo\Currencies\ISO\ISOArrayListLoader(),
        new \Psr\Log\NullLogger()
    )
)

$country = $provider->getByAlpha2('SK');

echo $country->getName(); // Slovakia
echo $country->getAlpha2(); // SK
echo $country->getAlpha3(); // SVK
echo $country->getNumericalCode(); // 703
echo $country->getCurrencies()[0]; // EUR
```
