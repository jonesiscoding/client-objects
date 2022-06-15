<?php
/**
 * UserAgentStringMatchInterface.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser\Base;

interface UserAgentStringMatchInterface
{
  public function getIncludePattern();

  public function getExcludePattern();

  public function getMobilePattern();
}
