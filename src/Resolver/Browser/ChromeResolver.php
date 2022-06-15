<?php
/**
 * ChromeResolver.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser;

use DevCoding\Client\Resolver\Browser\Base\SimpleBrowserResolver;

class ChromeResolver extends SimpleBrowserResolver
{
  public function getBrands()
  {
    return ['Chrome', 'Chromium'];
  }

  public function getIncludePattern()
  {
    return '#(?P<brand>Chrome|Chromium)\/(?P<version>[0-9\.]+)#';
  }

  public function getExcludePattern()
  {
    return '#(MRCHROME|FlyFlow|baidubrowser|bot|Edge|Edg|Silk|MxBrowser|Crosswalk|Slack_SSB|HipChat|IEMobile)#i';
  }

  public function getMobilePattern()
  {
    return '#(CrMo|EdgA|Android|Mobile)#i';
  }
}
