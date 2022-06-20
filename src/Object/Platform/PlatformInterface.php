<?php

namespace DevCoding\Client\Object\Platform;

use DevCoding\Client\Resolver\Platform\AndroidMatcher;
use DevCoding\Client\Resolver\Platform\ChromeOsMatcher;
use DevCoding\Client\Resolver\Platform\IosMatcher;
use DevCoding\Client\Resolver\Platform\LinuxMatcher;
use DevCoding\Client\Resolver\Platform\MacOsMatcher;
use DevCoding\Client\Resolver\Platform\TvOsMatcher;
use DevCoding\Client\Resolver\Platform\WindowsMobileMatcher;
use DevCoding\Client\Resolver\Platform\WindowsPhoneMatcher;
use DevCoding\Client\Resolver\Platform\WinNtMatcher;
use DevCoding\Client\Resolver\Platform\WinRtMatcher;
use DevCoding\CodeObject\Object\Base\BaseVersion;
use DevCoding\CodeObject\Object\VersionImmutable;

interface PlatformInterface
{
  const MACOS     = MacOsMatcher::PLATFORM;
  const WINNT     = WinNtMatcher::PLATFORM;
  const WINRT     = WinRtMatcher::PLATFORM;
  const LINUX     = LinuxMatcher::PLATFORM;
  const ANDROID   = AndroidMatcher::PLATFORM;
  const IOS       = IosMatcher::PLATFORM;
  const CHROMEOS  = ChromeOsMatcher::PLATFORM;
  const TVOS      = TvOsMatcher::PLATFORM;
  const WINMOBILE = WindowsMobileMatcher::PLATFORM;
  const WINPHONE  = WindowsPhoneMatcher::PLATFORM;

  public function getVersion();

  public function getPlatform();

  public function getName();
}
