<?php
/**
 * SimpleBrowserResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser\Base;


use DevCoding\Client\Object\Browser\BrowserImmutable;
use DevCoding\Client\Object\Headers\UserAgentString;
use DevCoding\Client\Object\Version\ClientVersion;

/**
 * @author  AMJones <am@jonesiscoding.com>
 *
 * @package DevCoding\Factory\Browser
 */
abstract class SimpleBrowserResolver extends BaseBrowserResolver implements UserAgentStringMatchInterface
{
  /** @var string */
  protected $version;
  /** @var bool */
  protected $mobile;

  /**
   * @param UserAgentString $UserAgentString
   *
   * @return BrowserImmutable|null
   */
  public static function fromUserAgentString(UserAgentString $UserAgentString)
  {
    $BM = new static();
    if ($UserAgentString->isMatch($BM->getIncludePattern(), $BM->getExcludePattern(), $m))
    {
      if ($BM->isBrandName($m['brand']))
      {
        if ($version = $BM->getParsedVersionFromMatch($m))
        {
          $BM
              ->setVersion($version)
              ->setMobile((($mp = $BM->getMobilePattern())) && $UserAgentString->isMatch($mp))
          ;

          return new BrowserImmutable($BM->getBrands(), $BM->getVersion(), $BM->isMobile());
        }
      }
    }

    return null;
  }

  /**
   * @param array  $match
   * @param string $key
   *
   * @return string|null
   */
  protected function getParsedVersionFromMatch($match, $key = 'version')
  {
    if (!empty($match[$key]))
    {
      try
      {
        return (string) (new ClientVersion($match[$key]));
      }
      catch (\Exception $e)
      {
        return null;
      }
    }

    throw new \LogicException(sprintf('Invalid key "%s" given to extract version from match.', $key));
  }

  protected function setVersion($version)
  {
    $this->version = $version;

    return $this;
  }

  protected function setMobile(bool $mobile)
  {
    $this->mobile = $mobile;
  }

  /**
   * @return string
   */
  public function getVersion()
  {
    return $this->version;
  }

  /**
   * @return bool
   */
  private function isMobile()
  {
    return $this->mobile ?? false;
  }
}
