<?php
/**
 * Brand.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Browser;

use DevCoding\Client\Object\Version\ClientVersion;

class Brand
{
  const BRANDS = [
      'Google Chrome','Microsoft Edge','Safari','Firefox','Opera','Internet Explorer',
      'Chromium','WebKit','Gecko','Trident'
  ];

  /** @var string */
  protected $name;
  /** @var ClientVersion */
  protected $version;

  public function __construct(string $name, ClientVersion $version)
  {
    $this->name    = $name;
    $this->version = $version;
  }

  public function __toString()
  {
    return $this->getName();
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getVersion(): ClientVersion
  {
    return $this->version;
  }

  public function isName($name): bool
  {
    return strtolower($name) == strtolower($this->getName());
  }

  /**
   * Sorts the given brand name array in order to surface the likely primary brand and rendering engine.
   *
   * @param array $brands
   *
   * @return array
   */
  public static function sort(array $brands): array
  {
    if (count($brands) > 1)
    {
      $brands = array_values(
        array_intersect(self::BRANDS, $brands) + array_diff($brands, self::BRANDS)
      );
    }

    return $brands;
  }
}
