<?php
/**
 * UA.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Headers;

use DevCoding\Client\Object\Version\ClientVersion;
use DevCoding\Client\Object\Browser\Brand;

class UA
{
  const KNOWN           = ['Chrome', 'Chromium', 'Internet Explorer', 'Microsoft Edge', 'Edge', 'Edg', 'Firefox', 'Safari', 'Opera'];
  const DEFAULT_VERSION = 73;
  /** @var Brand[] */
  protected $brands;
  /** @var string */
  protected $string;

  /**
   * @param string $string
   */
  public function __construct(string $string)
  {
    $this->string = $string;
  }

  public function __toString()
  {
    return $this->string;
  }

  /**
   * @return string
   */
  public function getString()
  {
    return $this->string;
  }

  public function isBrandName($string)
  {
    foreach ($this->getBrands() as $brand)
    {
      if ($brand->isName($string))
      {
        return true;
      }
    }

    return false;
  }

  /**
   * @return Brand[]
   */
  public function getBrands()
  {
    if (!isset($this->brands))
    {
      if ($m = $this->getBrandMatches($this->string))
      {
        $this->brands = [];
        $versions     = [];
        foreach ($m as $set)
        {
          $versions[trim($set['brand'])] = $set['version'];
        }
        $brands = Brand::sort(array_keys($versions));

        foreach ($brands as $brand)
        {
          $this->brands[] = new Brand($brand, new ClientVersion($versions[$brand]));
        }
      }
    }

    return $this->brands;
  }

  /**
   * @return int|null
   */
  public function getVersion()
  {
    return ($v = $this->getCommonVersion()) ? $v->getMajor() : null;
  }

  /**
   * @param $string
   *
   * @return array|null
   */
  protected function getBrandMatches($string)
  {
    $m = [];
    if (preg_match_all('/(?<brand>[^"]+)\";\s?v="(?<version>[^",\s]+)"?,?\s?/', $string, $m, PREG_SET_ORDER))
    {
      return $m;
    }

    return null;
  }

  /**
   * @return ClientVersion|null
   */
  protected function getCommonVersion()
  {
    foreach ($this->getBrands() as $brand)
    {
      $versions[] = (string) $brand->getVersion();
    }

    if (!empty($versions))
    {
      // Prefer the most common value
      if ($v = $this->getCommonValue($versions))
      {
        return new ClientVersion($v);
      }

      // If no common value, default to the first value from a known Client UA Brand
      foreach ($this->getBrands() as $brand)
      {
        if (in_array($brand->getName(), static::KNOWN))
        {
          return $brand->getVersion();
        }
      }
    }

    return null;
  }

  /**
   * @param array $arr
   *
   * @return int|string|null
   */
  private function getCommonValue(array $arr)
  {
    // Get count of each version number represented
    $counts = array_count_values($arr);
    // Sort the counted values
    arsort($counts);
    // Only valid if it's more than 1
    if (reset($counts) > 1)
    {
      $keys = array_slice(array_keys($counts), 0, 1, true);

      return array_shift($keys);
    }

    return null;
  }
}
