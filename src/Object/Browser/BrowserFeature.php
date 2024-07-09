<?php
/**
 * BrowserFeature.php
 *
 * © 2024/06 AMJones <am@jonesiscoding.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DevCoding\Client\Object\Browser;

use DevCoding\Client\Object\Version\ClientVersion;
use DevCoding\CodeObject\Object\VersionImmutable;

/**
 * Object representing a web browser feature, prepared for a specific browser agent name.
 *
 * @author  AMJones <am@jonesiscoding.com>
 * @package DevCoding\Client\Factory
 */
class BrowserFeature implements \JsonSerializable
{
  /** @var string                 Short key name, as found in the "Can I Use" data */
  protected $name;
  /** @var VersionImmutable|null  The minimum browser version to use this feature with a prefix */
  protected $prefixed;
  /** @var VersionImmutable       The minimum browser version to support this feature */
  protected $first;
  /** @var VersionImmutable       The maximum browser version to support this feature */
  protected $last;
  /** @var string                 sprintf template for error messages */
  private $invalid = 'If given, the %s argument must be a valid version number or %s object';

  /**
   * @param string             $name     Short key name, as found in the "Can I Use" data
   * @param ClientVersion|null $first    The minimum browser version to support this feature
   * @param ClientVersion|null $prefixed The minimum browser version to use this feature with a prefix
   * @param ClientVersion|null $last     The maximum browser version to support this feature
   *
   * @throws \InvalidArgumentException   If an argument cannot be recognized as a version
   */
  public function __construct(string $name, $first = null, $prefixed = null, $last = null)
  {
    $this->name = $name;

    if (isset($first))
    {
      $first = $first instanceof VersionImmutable ? $first : new ClientVersion($first);
      if (empty($first))
      {
        throw new \InvalidArgumentException(sprintf($this->invalid, '$first', VersionImmutable::class));
      }

      $this->first = $first;
    }

    if (isset($prefixed))
    {
      $prefixed = $prefixed instanceof VersionImmutable ? $prefixed : new ClientVersion($prefixed);
      if (empty($prefixed))
      {
        throw new \InvalidArgumentException(sprintf($this->invalid, '$prefixed', VersionImmutable::class));
      }

      $this->prefixed = $prefixed;
    }

    if (isset($last))
    {
      $last = $last instanceof ClientVersion ? $last : new ClientVersion($last);
      if (empty($last))
      {
        throw new \InvalidArgumentException(sprintf($this->invalid, '$last', VersionImmutable::class));
      }

      $this->last = $last;
    }
  }

  /**
   * Returns the short key name of the feature, as used in the "Can I Use" data.
   *
   * @return string
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Evaluates if the feature is supported on the given client version.
   *
   * @param ClientVersion $version
   * @param bool          $allowPrefixed
   *
   * @return bool
   */
  public function isSupported(ClientVersion $version, bool $allowPrefixed = false): bool
  {
    if (isset($this->last) && $version->gt($this->last))
    {
      return false;
    }

    if ($version->lt($this->first))
    {
      if (!$allowPrefixed || $version->lt($this->prefixed))
      {
        return false;
      }
    }

    return true;
  }

  /**
   * {@inheritDoc}
   */
  public function jsonSerialize()
  {
    return array_filter([
        'name'     => $this->name,
        'prefixed' => isset($this->prefixed) ? (string)$this->prefixed : null,
        'first'    => isset($this->first) ? (string)$this->first : null,
        'last'     => isset($this->last) ? (string)$this->last : null,
    ]);
  }
}
