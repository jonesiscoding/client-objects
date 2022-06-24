<?php
/**
 * BaseBrowser.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Browser;

use DevCoding\CodeObject\Object\Base\BaseVersion;
use DevCoding\CodeObject\Object\Version;

/**
 * @author  AMJones <am@jonesiscoding.com>
 * @package DevCoding\Object\Internet\Browser
 */
class BaseBrowser
{
  /** @var BaseVersion */
  protected $version;
  /** @var string */
  protected $brand;
  /** @var string */
  protected $engine;
  /** @var bool */
  protected $mobile;

  public function __construct(string $brand, BaseVersion $version, $engine = null, $mobile = false)
  {
    $this->brand   = $brand;
    $this->version = $version;
    $this->engine  = $engine ?? $brand;
    $this->mobile  = $mobile;
  }

  public function __toString()
  {
    return trim(implode('/', [$this->getBrand(), $this->getVersion()]), './');
  }

  /**
   * @return BaseVersion
   */
  public function getVersion()
  {
    return $this->version;
  }

  public function getName()
  {
    return $this->getBrand();
  }

  public function getBrand(): string
  {
    return $this->brand;
  }

  public function getEngine(): string
  {
    return $this->engine;
  }

  public function isMobile(): bool
  {
    return $this->mobile;
  }

  /**
   * Determines if this browser matches the given version or greater.
   *
   * @param int $cMajor
   * @param int $cMinor
   *
   * @return bool
   */
  public function isVersionUp($cMajor, $cMinor = null)
  {
    if (is_null($this->version))
    {
      return false;
    }

    return $this->getVersion()->gte(new Version(implode('.', [$cMajor, $cMinor])));
  }
}
