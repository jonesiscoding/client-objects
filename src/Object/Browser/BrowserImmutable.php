<?php
/**
 * BrowserImmutable.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Browser;

use DevCoding\Client\Object\Version\ClientVersion;
use DevCoding\Client\Object\Version\VersionNormalizerTrait;

/**
 * @method ClientVersion getVersion()
 *
 * @author  AMJones <am@jonesiscoding.com>
 * @package DevCoding\Object\Internet\Browser
 */
class BrowserImmutable extends BaseBrowser
{
  use VersionNormalizerTrait;

  public function __construct(string $brand, $version, $engine = null, $mobile = false)
  {
    parent::__construct($brand, $this->normalize($version), $engine, $mobile);
  }
}
