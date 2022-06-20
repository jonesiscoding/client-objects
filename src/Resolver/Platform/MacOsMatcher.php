<?php
/**
 * MacOsMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;


use DevCoding\Client\Object\Version\ClientVersion;

class MacOsMatcher extends PlatformMatcher
{
  const PATTERN  = "#(?<name>Macintosh)[^\)0-9]*(?<version>[0-9_.]+)#";
  const KEY      = 'macOS';
  const PLATFORM = 'macOS';

  public static function isUserAgentMatch($ua, &$matches)
  {
    if (preg_match(static::PATTERN, $ua, $matches))
    {
      $matches = static::normalizeMacOs($matches);

      return true;
    }

    return false;
  }

  /**
   * @param string $version
   *
   * @return string
   */
  protected static function normalizeDarwin($version)
  {
    $DarwinVersion = new ClientVersion($version);
    $patchNumber   = implode('.', [$DarwinVersion->getMinor(), $DarwinVersion->getPatch()]);

    switch ((string) $DarwinVersion->getMajor()) {
      case '20': // Big Sur
        return implode('.', [11, $DarwinVersion->getMinor(), $DarwinVersion->getPatch()]);
      case '19':
        return implode('.', [10, 15, $patchNumber]);
      case '14': // Yosemite
        return implode('.', [10, 10, $patchNumber]);
      case '13': // Mavericks
        return implode('.', [10, 9, $patchNumber]);
      case '12': // Mountain Lion
        return implode('.', [10, 8, $patchNumber]);
      case '11': // Lion
        return implode('.', [10, 7, $patchNumber]);
      case '10': // Snow Leopard
        return implode('.', [10, 6, $patchNumber]);
      case '9': // Leopard
        return implode('.', [10, 5, $patchNumber]);
      default: // Build for Tiger & before don't seem to specify versions, so we lock it.
        return implode('.', [10, 4]);
    }
  }

  /**
   * @param array $result
   *
   * @return array
   */
  protected static function normalizeMacOs($result)
  {
    $name = $result['name'];
    $slug = ($name) ? str_replace([' ', '_', '-'], '', strtolower($name)) : null;
    $verS = str_replace('_', '.', $result['version']);

    if ('darwin' === $slug || 'macpowerpc' === $slug)
    {
      $verS = static::normalizeDarwin($verS);
    }

    return ['name' => 'macOS', 'version' => $verS];
  }
}
