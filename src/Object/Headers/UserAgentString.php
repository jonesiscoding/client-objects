<?php
/**
 * UserAgentString.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Headers;

class UserAgentString
{
  const BOTS = [
      'crawler', 'bot', 'spider', 'archiver', 'scraper', 'stripper', 'wget', 'curl', 'AppEngine-Google',
      'AdsBot-Google', 'AdsBot-Google-Mobile-Apps', 'Mediapartners-Google', 'Slurp', 'facebookexternalhit',
      'Zeus 32297 Webster Pro', '008', 'PagePeeker', 'Nutch', 'grub-client', 'NewsGator', 'Yandex',
  ];

  const HEADERS = [
      'USER_AGENT',
      'X_OPERAMINI_PHONE_UA',
      'X_DEVICE_USER_AGENT',
      'X_ORIGINAL_USER_AGENT',
      'X_SKYFIRE_PHONE',
      'X_BOLT_PHONE_UA',
      'DEVICE_STOCK_UA',
      'X_UCBROWSER_DEVICE_UA',
  ];

  /** @var string */
  protected $_ua = '';

  public function __construct($ua)
  {
    $this->_ua = (string) $ua;
  }

  public function __toString()
  {
    return $this->_ua;
  }

  public function isBot()
  {
    return $this->isMatch(sprintf('#(%s)#i', implode('|', static::BOTS)));
  }

  public function isMatch($inc, $exc = null, &$matches = [])
  {
    if (!empty($this->_ua))
    {
      if (preg_match($inc, (string) $this, $matches))
      {
        if (empty($exc) || !preg_match($exc, (string) $this))
        {
          return true;
        }
      }
    }

    return false;
  }

  /**
   * @param string        $inc        Regex Pattern to match this browser, including delimiters and options
   * @param string|null   $exc        Optional Regex Pattern of exclusions, including delimiters and options
   * @param \Closure|null $normalizer Normalizer for regex matches.  Must return an array.
   *
   * @return array|bool
   */
  public function getMatches(string $inc, $exc = null, $normalizer = null)
  {
    $matches = [];
    if ($this->isMatch($inc, $exc, $matches))
    {
      if ($normalizer instanceof \Closure)
      {
        return $normalizer($matches);
      }
      else
      {
        return $matches;
      }
    }

    return false;
  }
}
