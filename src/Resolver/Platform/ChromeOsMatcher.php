<?php
/**
 * ChromeOsMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class ChromeOsMatcher extends PlatformMatcher
{
  const PATTERN = "#(?<name>CrOS).*Chrome\/(?<version>[0-9._]+)#";
  const KEY     = 'ChromeOS';

  public static function isUserAgentMatch($ua, &$matches)
  {
    if (preg_match(static::PATTERN, $ua, $matches))
    {
      $matches = ['name' => 'ChromeOS', 'version' => $matches['version']];

      return true;
    }

    return false;
  }
}
