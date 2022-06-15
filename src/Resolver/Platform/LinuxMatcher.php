<?php
/**
 * LinuxMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class LinuxMatcher extends PlatformMatcher
{
  const KEY     = 'Linux';
  const PATTERN = '#^(?!.*(Win|Android|Darwin|T)).*(?<name>Linux).*$#i';

  public static function isUserAgentMatch($ua, &$matches)
  {
    return preg_match(static::PATTERN, $ua, $matches);
  }
}
