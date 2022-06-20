<?php
/**
 * BasePlatform.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Platform;

use DevCoding\Client\Object\Version\ClientVersion;

abstract class BasePlatform implements PlatformInterface
{
  /** @var ClientVersion */
  protected $_version;
  /** @var string */
  protected $_platform;

  public function __construct(string $platform, ClientVersion $version)
  {
    $this->_platform = $platform;
    $this->_version  = $version;
  }

  /**
   * @return string
   */
  public function getPlatform(): string
  {
    return $this->_platform;
  }

  /**
   * @return ClientVersion
   */
  public function getVersion()
  {
    return $this->_version;
  }

  public function __toString()
  {
    return trim(implode('/', [$this->getPlatform(), $this->getVersion()]), './');
  }
}
