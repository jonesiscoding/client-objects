<?php
/**
 * AndroidMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

use DevCoding\Client\Object\Headers\UserAgentString;

class AndroidMatcher extends PlatformMatcher
{
  const PATTERN = '#^(?!.*(Windows Phone|IEMobile)).*(?<name>Android)[\s-\/]*(?<version>[0-9._]+).*$#i';
  const KEY     = 'Android';

  /**
   * @param string|UserAgentString $ua
   * @param array            $matches
   *
   * @return bool
   */
  public static function isUserAgentMatch($ua, &$matches)
  {
    return preg_match(static::PATTERN, (string) $ua, $matches);
  }
}
