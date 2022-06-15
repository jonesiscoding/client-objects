<?php
/**
 * PlatformMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

abstract class PlatformMatcher
{
  abstract public static function isUserAgentMatch($ua, &$matches);
}
