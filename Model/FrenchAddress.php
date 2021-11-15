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
     * @var string|null
     */
    private $oldCity;

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
     * @param string|null          $oldCity
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
        string $oldCity = null,
        string $locality = null,
        string $subLocality = null,
        Country $country = null,
        string $timezone = null
    ) {
        parent::__construct($providedBy, $adminLevels, $coordinates, $bounds, $streetNumber, $streetName, $postalCode, $locality, $subLocality, $country, $timezone);
        $this->cityCode = $cityCode;
        $this->oldCityCode = $oldCityCode;
        $this->oldCity = $oldCity;
    }


    /**
     * @return string|null
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }

    /**
     * @return string|null
     */
    public function getOldCityCode()
    {
        return $this->oldCityCode;
    }

    /**
     * @return string|null
     */
    public function getOldCity()
    {
        return $this->oldCity;
    }

    /**
     * @param string|null $cityCode
     *
     * @return self
     */
    public function setCityCode($cityCode): self
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * @param string|null $oldCityCode
     *
     * @return self
     */
    public function setOldCityCode($oldCityCode): self
    {
        $this->oldCityCode = $oldCityCode;

        return $this;
    }

    /**
     * @param string|null $oldCity
     *
     * @return self
     */
    public function setOldCity($oldCity): self
    {
        $this->oldCity = $oldCity;

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
            $data['oldCity'],
            $data['locality'],
            $data['subLocality'],
            self::createCountry($data['postalCode']),
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
     * @param string|null $postalCode
     *
     * @return Country|null
     */
    private static function createCountry($postalCode)
    {
        if (null === $postalCode) {
            return null;
        }

        switch (substr($postalCode, 0, 3)) {
            case '971':
                return new Country('Guadeloupe', 'GP');
            case '972':
                return new Country('Martinique', 'MQ');
            case '973':
                return new Country('Guyane', 'GF');
            case '974':
                return new Country('La Réunion', 'RE');
            case '975':
                return new Country('Saint-Pierre-et-Miquelon', 'PM');
            case '976':
                return new Country('Mayotte', 'YT');
            case '977':
                return new Country('Saint-Barthélemy', 'BL');
            case '978':
                return new Country('Saint-Martin', 'MF');
            case '986':
                return new Country('Wallis-et-Futuna', 'WF');
            case '987':
                return new Country('Polynésie française', 'PF');
            case '988':
                return new Country('Nouvelle-Calédonie', 'NC');
        }

        return new Country('France', 'FR');
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
