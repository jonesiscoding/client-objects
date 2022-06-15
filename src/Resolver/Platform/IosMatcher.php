<?php
/**
 * IosMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class IosMatcher extends PlatformMatcher
{
  const KEY     = 'iOS';
  const PATTERN = '#(?<name>iPod touch|iPod|iPad|iPhone).+[OS|os][\s_](?<version>[0-9_.]+)#';

  public static function isUserAgentMatch($ua, &$matches)
  {
    if (preg_match(static::PATTERN, $ua, $matches))
    {
      $matches = ['name' => 'iOS', 'version' => $matches['version']];

      return true;
    }

    return false;
  }
}
