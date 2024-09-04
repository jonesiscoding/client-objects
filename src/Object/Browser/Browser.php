<?php
/**
 * Browser.php
 *
 * © 2024/06 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Browser;

use DevCoding\Client\Object\Headers\GreaseTrait;
use DevCoding\Client\Object\Headers\UA;
use DevCoding\Client\Object\Headers\UAFullVersionList;
use DevCoding\Client\Object\Version\ClientVersion;

/**
 * Object representing a web browser
 *
 * @author  AMJones <am@jonesiscoding.com>
 * @package DevCoding\Client\Factory
 */
class Browser
{
  use GreaseTrait;

  /** @var string[] Array of brand names */
  protected $brands;
  /** @var ClientVersion */
  protected $version;
  /** @var BrowserFeature[] */
  protected $features;
  /** @var UAFullVersionList Populated upon first request via method */
  private $_UAFullVersionList;
  /** @var UA Populated upon first request via method */
  private $_UA;

  /**
   * @param array|string               $brands
   * @param ClientVersion|string|float $version
   * @param BrowserFeature[]|null      $features
   */
  public function __construct($brands, $version, $features = null)
  {
    $this->brands   = is_array($brands) ? $brands : [$brands];
    $this->version  = $version instanceof ClientVersion ? $version : new ClientVersion($version);
    $this->features = $features;
  }

  /**
   * Returns a brand object for the primary brand of this Browser object, the first name in the $brands property.
   *
   * @return Brand
   */
  public function getBrand(): Brand
  {
    return new Brand($this->brands[0], $this->version);
  }

  /**
   * Returns a UAFullVersionList object generated using the data in this Browser object.
   *
   * @return UAFullVersionList
   */
  public function getFullVersionList(): UAFullVersionList
  {
    $version = (string)$this->version;
    $parts   = [];

    $parts[] = $this->getGrease(true, UA::DEFAULT_VERSION);
    foreach ($this->brands as $brand)
    {
      $parts[] = sprintf('"%s"; v="%s"', $brand, $version);
    }
    shuffle($parts);

    return new UAFullVersionList(implode(', ', $parts));
  }

  /**
   * Returns a UA object generated using the data in this Browser object.
   *
   * @return UA
   */
  public function getUserAgent(): UA
  {
    if (!isset($this->_UA))
    {
      $full  = $this->getFullVersionList();
      $parts = [];
      foreach ($full->getBrands() as $brand)
      {
        $parts[] = sprintf('"%s"; v="%s"', $brand->getName(), $brand->getVersion()->getMajor());
      }

      $this->_UA = new UA(implode(', ', $parts));
    }

    return $this->_UA;
  }

  /**
   * Returns a ClientVersion object representing the version of this Browser object.
   *
   * @return ClientVersion
   */
  public function getVersion(): ClientVersion
  {
    return $this->version;
  }

  /**
   * Evaluates if the given short feature name is supported using pre-configured data from Can I Use.
   *
   * @param string $string
   *
   * @return bool
   */
  public function isSupported(string $string): bool
  {
    foreach ($this->features as $feature)
    {
      if ($feature->getName() === $string)
      {
        if ($feature->isSupported($this->getVersion()))
        {
          return true;
        }
      }
    }

    return false;
  }
}
