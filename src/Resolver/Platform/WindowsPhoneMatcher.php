<?php
/**
 * WindowsPhoneMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class WindowsPhoneMatcher extends PlatformMatcher
{
  const KEY     = 'Windows Phone';
  const PATTERN = "#(?<name>Windows Phone)\s*[OS|os]*\s*(?<version>[0-9_.]+)#";

  public static function isUserAgentMatch($ua, &$matches)
  {
    return preg_match(static::PATTERN, $ua, $matches);
  }
}
