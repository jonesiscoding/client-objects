<?php
/**
 * WindowsMobileMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class WindowsMobileMatcher extends PlatformMatcher
{
  const PATTERN = "#(?<name>Windows Mobile)\s*(?<version>[0-9_.]+)#";
  const KEY     = 'Windows Mobile';

  public static function isUserAgentMatch($ua, &$matches)
  {
    return preg_match(static::PATTERN, $ua, $matches);
  }
}
