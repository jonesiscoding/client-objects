<?php
/**
 * WindowsNtMatcher.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Platform;

class WinNtMatcher extends PlatformMatcher
{
  const PATTERN  = '#^(?!.*(6\.[23]; ARM|CE)).*(?<name>WinNT\s|Windows\sNT\s|Windows\sServer\s|Windows\s|Win\s?)(?<version>(2000|2003|2008|95|Vista|98|ME|Me|XP|9x|[0-9]+\.[0-9_.]+)).*$#';
  const KEY      = 'WinNT';
  const PLATFORM = 'Windows';

  public static function isUserAgentMatch($ua, &$matches)
  {
    if (preg_match(static::PATTERN, $ua, $matches))
    {
      if (isset($matches['version']))
      {
        if (is_numeric($matches['version']) && 95 >= $matches['version'])
        {
          $parts = explode('.', $matches['version']);
          $major = trim(array_shift($parts));
          $minor = trim(array_shift($parts));
          $winnt = implode('.', [$major, $minor]);
          if (6 >= $major)
          {
            if (6 == $major && 1 <= $minor)
            {
              // Normalizes NT version into Windows Version
              $matches['version'] = static::normalizeNt($winnt);
            }
            else
            {
              // Normalizes Name for NT versions from 3.x - 5.x
              $matches['name'] = static::normalizeName(['name' => $matches['name'], 'version' => $winnt]);
            }
          }
        }
        else
        {
          // Normalize name & version for oddballs like 95, 98, 2000, XP, Vista, Server 2003/2008/2012
          $matches = static::normalizeNameVersion($matches);
        }
      }
      else
      {
        // Just normalize Win to Windows, otherwise leave alone.
        $matches['name']    = 'Win' == $matches['name'] ? 'Windows' : $matches['name'];
        $matches['version'] = null;
      }

      $matches = array_merge($matches, ['platform' => 'Windows']);

      return true;
    }

    return false;
  }

  public static function normalizeNt($version)
  {
    switch ((string) $version)
    {
      case '6.1':
        return '7';
      case '6.2':
        return '8';
      case '6.3':
        return '8.1';
      case '6.4':
        return '10';
      case '6.5':
        return '11';
      default:
        return null;
    }
  }

  public static function normalizeHint($platform)
  {
    $pVersion = (string) $platform;

    if ($pVersion > 0)
    {
      return $pVersion >= 13 ? '11' : '10';
    }
    else
    {
      return null;
    }
  }

  protected static function normalizeNameVersion($result)
  {
    $vers = $result['version'] ?? null;
    switch ($vers)
    {
      case '95':
        return ['name' => 'Windows 95', 'version' => 4.00];
      case '98':
        return ['name' => 'Windows 98', 'version' => 4.1];
      case '2000':
        return ['name' => 'Windows 2000', 'version' => 5.0];
      case 'ME':
      case 'Me':
        return ['name' => 'Windows Me', 'version' => 4.90];
      case 'XP':
        return ['name' => 'Windows XP', 'version' => 5.1];
      case 'Vista':
        return ['name' => 'Windows Vista', 'version' => 6.1];
      case '2003':
        return ['name' => 'Windows Server 2003', 'version' => 5.2];
      case '2008':
        return ['name' => 'Windows Server 2003', 'version' => 6.0];
      case '2012':
        return ['name' => 'Windows Server 2012', 'version' => 6.2];
      default:
        return ['name' => static::PLATFORM, 'version' => $vers];
    }
  }

  protected static function normalizeName($result)
  {
    $name = $result['name']    ?? null;
    $vers = $result['version'] ?? null;

    if ($vers <= 3.1)
    {
      $name = 'Win' === $name ? 'Windows' : $name;
    }
    elseif (4.1 == $vers)
    {
      $name = 'Windows 98';
    }
    elseif (4.9 == $vers)
    {
      $name = 'Windows Me';
    }
    elseif ($vers < 5.0)
    {
      $name = 'Windows NT';
    }
    elseif (5 == $vers)
    {
      $name = 'Windows 2000';
    }
    elseif (5.1 == $vers || 5.2 == $vers)
    {
      // Technically, this could be Windows Server 2003, but there's no way to determine that.
      $name = 'Windows XP';
    }
    elseif (6.0 == $vers)
    {
      // Technically, this could be Windows Server 2008, but there's no way to determine that.
      $name = 'Windows Vista';
    }

    return $name;
  }
}
