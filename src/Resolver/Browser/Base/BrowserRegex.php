<?php
/**
 * BrowserRegex.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Resolver\Browser\Base;

use DevCoding\CodeObject\Helper\RegexHelper;

class BrowserRegex extends RegexHelper
{
  protected $exclude;

  public function __construct($include, $exclude = '#bot#i', $normalizer = null)
  {
    $this->exclude = $exclude;

    parent::__construct($include, $normalizer);
  }

  public function matchAll(string $subject, $offset = null)
  {
    if ($matches = parent::matchAll($subject))
    {
      if (!preg_match_all($this->exclude, $subject))
      {
        return $matches;
      }
    }

    return false;
  }

  public function match(string $subject, $offset = null)
  {
    if ($matches = parent::match($subject, $offset))
    {
      if (!preg_match($this->exclude, $subject))
      {
        return $matches;
      }
    }

    return false;
  }
}
