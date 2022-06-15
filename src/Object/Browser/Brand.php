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
}
