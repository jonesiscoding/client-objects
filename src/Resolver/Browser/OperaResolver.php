<?php
/**
 * OperaResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser;

use DevCoding\Client\Object\Headers\HeaderBag;
use DevCoding\Client\Object\Headers\UserAgentString;
use DevCoding\Client\Resolver\Browser\Base\BrowserRegex;
use DevCoding\Client\Resolver\Browser\Base\ComplexBrowserResolver;

class OperaResolver extends ComplexBrowserResolver
{
  /** @var HeaderBag */
  protected $_HeaderBag;

  /**
   * @return string
   */
  public function getPrimaryBrand(): string
  {
    return 'Opera';
  }

  /**
   * @return null
   */
  public function getEngineBrand()
  {
    return null;
  }

  public function getPatterns()
  {
    $exclude   = $this->isOperaMini() ? '#(.*)#' : '#(Opera Mobi|Mobile Safari)#';
    $oChromium = new BrowserRegex(
      '#(?:Chrome).*(OPR)\/(\d+)\.(\d+)\.(\d+)#',
      '#bot#i',
      function ($uam) {
        $fVersion = implode('.', [$uam[3], $uam[4]]);

        return [0 => $uam[0], 1 => 'Chromium', 'brand' => 'Chromium', 2 => $fVersion, 'version' => $fVersion];
      }
    );

    // The 'Opera/x.xx' stopped at 9.80, then the version went into the 'Version/x.x.x' format.
    $o98Desktop = new BrowserRegex(
      '#(Opera)\/9.80.*Version\/((\d+)\.(\d+)(?:\.(\d+))?)#',
      $exclude,
      function ($uam) {
        $version = implode('.', [$uam[3], $uam[4]]);
        $brand   = 'Presto';

        return [0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    // Versions 4.02 - 9.8
    $o4Desktop = new BrowserRegex(
      '#Opera[/\s](([0-9]+)\.?([0-9]*))#',
      $exclude,
      function ($uam) {
        $version = implode('.', [$uam[2], $uam[3]]);
        $brand   = 'Presto';

        return [ 0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    $oDesktop = new BrowserRegex(
      '#Opera#',
      $exclude,
      function ($uam) {
        // Very old, so we're guessing here
        $version = 3.5;
        $brand   = 'Presto';

        return [ 0 => $uam[0], 1 => $brand, 'brand' => $brand, 2 => $version, 'version' => $version];
      }
    );

    return [$oChromium, $o98Desktop, $o4Desktop, $oDesktop];
  }

  protected function isMobileUserAgentString(UserAgentString $userAgentString): bool
  {
    if ($userAgentString->isMatch('Opera Mobi'))
    {
      return true;
    }
    elseif ($this->isOperaMini())
    {
      return true;
    }
    elseif ($userAgentString->isMatch('#(mini|safari)#i'))
    {
      return true;
    }

    return false;
  }

  /**
   * @param string $name
   *
   * @return bool
   */
  public function isBrandName($name)
  {
    return 'Presto' === $name || parent::isBrandName($name);
  }

  /**
   * @return HeaderBag
   */
  protected function getHeaderBag(): HeaderBag
  {
    if (!isset($this->_HeaderBag))
    {
      $this->_HeaderBag = new HeaderBag();
    }

    return $this->_HeaderBag;
  }

  protected function isOperaMini(): bool
  {
    if ($this->getHeaderBag()->resolve(['X-OperaMini-Phone-UA', 'Device-Stock-UA']))
    {
      return true;
    }

    return false;
  }
}
