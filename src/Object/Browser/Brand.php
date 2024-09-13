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
  const ARC      = 'Arc';
  const BRAVE    = 'Brave';
  const CHROME   = 'Google Chrome';
  const EDGE     = 'Microsoft Edge';
  const OPERA    = 'Opera';
  const CHROMIUM = 'Chromium';
  const FIREFOX  = 'Firefox';
  const GECKO    = 'Gecko';
  const SAFARI   = 'Safari';
  const WEBKIT   = 'WebKit';
  const IE       = 'Internet Explorer';
  const TRIDENT  = 'Trident';

  const BRANDS = [
      self::CHROME, self::EDGE, self::OPERA, self::BRAVE, self::ARC, self::FIREFOX, self::SAFARI, self::IE,
      self::CHROMIUM, self::WEBKIT, self::GECKO, self::TRIDENT
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
