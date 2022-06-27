<?php
/**
 * FirefoxResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser;

use DevCoding\Client\Resolver\Browser\Base\SimpleBrowserResolver;

class FirefoxResolver extends SimpleBrowserResolver
{
  /**
   * @return string
   */
  public function getPrimaryBrand(): string
  {
    return 'Mozilla Firefox';
  }

  /**
   * @return string
   */
  public function getEngineBrand()
  {
    return 'Firefox';
  }

  /**
   * @return string
   */
  public function getIncludePattern()
  {
    return sprintf('#(?<brand>%s)\/(?<version>[^\s^;^)]+)#', implode('|', $this->getUserAgentStringNames()));
  }

  public function getExcludePattern()
  {
    return '#(bot|MSIE|HbbTV|Chimera|Seamonkey|Camino)#i';
  }

  public function getMobilePattern()
  {
    return '#(Fennec|Tablet|Phone|Mobile|Maemo)#i';
  }

  public function isBrandName($name)
  {
    return in_array($name, $this->getUserAgentStringNames()) || parent::isBrandName($name);
  }

  protected function getUserAgentStringNames()
  {
    return ['Firefox', 'Fennec', 'Namoroka', 'Shiretoka', 'Minefield', 'MozillaDeveloperPreview'];
  }
}

