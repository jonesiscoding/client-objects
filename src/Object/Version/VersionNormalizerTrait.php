<?php
/**
 * VersionNormalizerTrait.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Version;

use DevCoding\CodeObject\Object\Base\BaseVersion;

trait VersionNormalizerTrait
{
  protected function normalize($version)
  {
    if (!$version instanceof ClientVersion)
    {
      if ($version instanceof BaseVersion)
      {
        return new ClientVersion((string)$version);
      }
      elseif (is_scalar($version))
      {
        return new ClientVersion($version);
      }
    }

    return $version;
  }
}
