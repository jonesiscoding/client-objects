<?php
/**
 * PlatformImmutable.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Platform;

use DevCoding\Client\Object\Version\ClientVersion;

use DevCoding\Client\Object\Version\VersionNormalizerTrait;

/**
 * Class PlatformImmutable.
 *
 * @method ClientVersion getVersion()
 *
 * @package DevCoding\Object\System\Platform
 */
class PlatformImmutable extends BasePlatform
{
  use VersionNormalizerTrait;

  public function __construct(string $platform, $version)
  {
    parent::__construct($platform, $this->normalize($version));
  }
}
