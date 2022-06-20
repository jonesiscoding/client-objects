<?php
/**
 * TvOsMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class TvOsMatcher extends PlatformMatcher
{
  const PATTERN  = '#(?<name>Apple\s?TV|tvOS)/?(?<version>[0-9_.]+)?#';
  const KEY      = 'tvOS';
  const PLATFORM = 'tvOS';

  public static function isUserAgentMatch($ua, &$matches)
  {
    if (preg_match(static::PATTERN, $ua, $matches))
    {
      $matches = ['name' => 'tvOS', 'version' => $matches['version']];

      return true;
    }

    return false;
  }
}
