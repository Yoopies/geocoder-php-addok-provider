<?php


namespace Geocoder\Provider\Addok\Model;

use Geocoder\Model\Address;
use Geocoder\Model\AdminLevel;
use Geocoder\Model\AdminLevelCollection;
use Geocoder\Model\Bounds;
use Geocoder\Model\Coordinates;
use Geocoder\Model\Country;

class FrenchAddress extends Address
{
    /**
     * @var string|null
     */
    private $cityCode;

    /**
     * @var string|null
     */
    private $oldCityCode;

    /**
     * @param string               $providedBy
     * @param AdminLevelCollection $adminLevels
     * @param Coordinates|null     $coordinates
     * @param Bounds|null          $bounds
     * @param string|null          $streetNumber
     * @param string|null          $streetName
     * @param string|null          $postalCode
     * @param string|null          $cityCode
     * @param string|null          $oldCityCode
     * @param string|null          $locality
     * @param string|null          $subLocality
     * @param Country|null         $country
     * @param string|null          $timezone
     */
    public function __construct(
        string $providedBy,
        AdminLevelCollection $adminLevels,
        Coordinates $coordinates = null,
        Bounds $bounds = null,
        string $streetNumber = null,
        string $streetName = null,
        string $postalCode = null,
        string $cityCode = null,
        string $oldCityCode = null,
        string $locality = null,
        string $subLocality = null,
        Country $country = null,
        string $timezone = null
    ) {
        parent::__construct($providedBy, $adminLevels, $coordinates, $bounds, $streetNumber, $streetName, $postalCode, $locality, $subLocality, $country, $timezone);
        $this->cityCode = $cityCode;
        $this->oldCityCode = $oldCityCode;
    }


    /**
     * @return string|null
     */
    public function getCityCode(): ?string
    {
        return $this->cityCode;
    }

    /**
     * @return string|null
     */
    public function getOldCityCode(): ?string
    {
        return $this->oldCityCode;
    }

    /**
     * @param string|null $cityCode
     *
     * @return self
     */
    public function setCityCode(?string $cityCode): self
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * @param string|null $oldCityCode
     *
     * @return self
     */
    public function setOldCityCode(?string $oldCityCode): self
    {
        $this->oldCityCode = $oldCityCode;

        return $this;
    }

    /**
     * Create an Address with an array. Useful for testing.
     *
     * @param array $data
     *
     * @return static
     */
    public static function createFromArray(array $data)
    {
        $defaults = [
            'providedBy' => 'n/a',
            'latitude' => null,
            'longitude' => null,
            'bounds' => [
                'south' => null,
                'west' => null,
                'north' => null,
                'east' => null,
            ],
            'streetNumber' => null,
            'streetName' => null,
            'locality' => null,
            'postalCode' => null,
            'subLocality' => null,
            'adminLevels' => [],
            'country' => null,
            'countryCode' => null,
            'timezone' => null,
        ];

        $data = array_merge($defaults, $data);

        $adminLevels = [];
        foreach ($data['adminLevels'] as $adminLevel) {
            if (empty($adminLevel['level'])) {
                continue;
            }

            $name = $adminLevel['name'] ?? $adminLevel['code'] ?? null;
            if (empty($name)) {
                continue;
            }

            $adminLevels[] = new AdminLevel($adminLevel['level'], $name, $adminLevel['code'] ?? null);
        }

        return new static(
            $data['providedBy'],
            new AdminLevelCollection($adminLevels),
            self::createCoordinates(
                $data['latitude'],
                $data['longitude']
            ),
            self::createBounds(
                $data['bounds']['south'],
                $data['bounds']['west'],
                $data['bounds']['north'],
                $data['bounds']['east']
            ),
            $data['streetNumber'],
            $data['streetName'],
            $data['postalCode'],
            $data['cityCode'],
            $data['oldCityCode'],
            $data['locality'],
            $data['subLocality'],
            self::createCountry($data['country'], $data['countryCode']),
            $data['timezone']
        );
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return Coordinates|null
     */
    private static function createCoordinates($latitude, $longitude)
    {
        if (null === $latitude || null === $longitude) {
            return null;
        }

        return new Coordinates($latitude, $longitude);
    }

    /**
     * @param string|null $name
     * @param string|null $code
     *
     * @return Country|null
     */
    private static function createCountry($name, $code)
    {
        if (null === $name && null === $code) {
            return null;
        }

        return new Country($name, $code);
    }

    /**
     * @param float $south
     * @param float $west
     * @param float $north
     *
     * @return Bounds|null
     */
    private static function createBounds($south, $west, $north, $east)
    {
        if (null === $south || null === $west || null === $north || null === $east) {
            return null;
        }

        return new Bounds($south, $west, $north, $east);
    }
}
