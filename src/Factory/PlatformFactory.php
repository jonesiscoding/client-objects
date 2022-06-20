<?php
/**
 * PlatformFactory.php
 *
 * © 2021/11 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Factory;

use DevCoding\Client\Object\Headers\UserAgentString;
use DevCoding\Client\Object\Platform\PlatformImmutable;
use DevCoding\Client\Resolver\Platform as PlatformResolver;
use DevCoding\CodeObject\Helper\StringHelper;

class PlatformFactory
{
  const RESOLVERS = [
      PlatformResolver\WinNtMatcher::class,
      PlatformResolver\MacOsMatcher::class,
      PlatformResolver\IosMatcher::class,
      PlatformResolver\AndroidMatcher::class,
      PlatformResolver\ChromeOsMatcher::class,
      PlatformResolver\LinuxMatcher::class,
      PlatformResolver\TvOsMatcher::class,
      PlatformResolver\WinRtMatcher::class,
      PlatformResolver\WindowsPhoneMatcher::class,
      PlatformResolver\WindowsMobileMatcher::class,
  ];

  /** @var string[]|PlatformResolver\PlatformMatcher[]]*/
  protected $resolvers;

  /**
   * @param PlatformResolver\PlatformMatcher[]|string[] $resolvers
   */
  public function __construct(array $resolvers = self::RESOLVERS)
  {
    $this->resolvers = $resolvers;
  }

  public static function build($criteria, $resolvers = self::RESOLVERS)
  {
    $Factory = new PlatformFactory($resolvers);
    if ($criteria instanceof UserAgentString)
    {
      return $Factory->fromUserAgent($criteria);
    }
    elseif ($Factory->isStringable($criteria))
    {
      return $Factory->fromString($criteria);
    }

    return null;
  }

  public function fromString(string $string)
  {
    return $this->fromUserAgent(new UserAgentString($string));
  }

  public function fromUserAgent(UserAgentString $UserAgent)
  {
    foreach ($this->getResolvers() as $matcher)
    {
      $result = [];
      if ($matcher::isUserAgentMatch($UserAgent, $result))
      {
        $platform = $result['platform'] ?? $result['name'] ?? 'Unknown';
        $version  = $result['version']  ?? null;

        return new PlatformImmutable($platform, $version);
      }
    }

    return null;
  }

  /**
   * @return PlatformResolver\PlatformMatcher[]|string[]
   */
  protected function getResolvers(): array
  {
    return $this->resolvers;
  }

  /**
   * Evaluates whether the given value can be cast to a string without error.
   *
   * @param mixed $val the value to evaluate
   *
   * @return bool TRUE if the value may be safely cast to a string
   */
  protected function isStringable($val)
  {
    return StringHelper::create()->isStringable($val);
  }
}
