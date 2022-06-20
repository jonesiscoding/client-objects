<?php
/**
 * WindowsRtMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class WinRtMatcher extends PlatformMatcher
{
  const PATTERN  = "#(?<name>Windows\s*NT\s*)(?<version>6\.[23]);\s*ARM#";
  const PLATFORM = 'Windows RT';
  const KEY      = 'WinRT';

  /**
   * @param string $ua
   * @param array  $matches
   *
   * @return bool
   */
  public static function isUserAgentMatch($ua, &$matches)
  {
    if (preg_match(static::PATTERN, $ua, $matches))
    {
      if (isset($matches['version']))
      {
        $matches['version'] = '6.2' == substr($matches['version'], 0, 3) ? '8' : '8.1';
      }

      $matches['name'] = static::NAME;

      return true;
    }

    return false;
  }
}
