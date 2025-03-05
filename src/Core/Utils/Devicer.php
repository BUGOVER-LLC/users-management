<?php

declare(strict_types=1);

namespace App\Core\Utils;

use Detection\Exception\MobileDetectException;
use Detection\MobileDetect;
use JetBrains\PhpStorm\Pure;

use function array_key_exists;
use function is_array;

final class Devicer extends MobileDetect
{
    /**
     * List of desktop devices.
     *
     * @var array
     */
    protected static array $desktopDevices = [
        'Macintosh' => 'Macintosh',
    ];
    /**
     * List of additional operating systems.
     *
     * @var array
     */
    protected static array $additionalOperatingSystems = [
        'Windows' => 'Windows',
        'Windows NT' => 'Windows NT',
        'OS X' => 'Mac OS X',
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];
    /**
     * List of additional browsers.
     *
     * @var array
     */
    protected static array $additionalBrowsers = [
        'Opera Mini' => 'Opera Mini',
        'Opera' => 'Opera|OPR',
        'Edge' => 'Edge|Edg',
        'Coc Coc' => 'coc_coc_browser',
        'UCBrowser' => 'UCBrowser',
        'Vivaldi' => 'Vivaldi',
        'Chrome' => 'Chrome',
        'Firefox' => 'Firefox',
        'Safari' => 'Safari',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Netscape' => 'Netscape',
        'Mozilla' => 'Mozilla',
        'WeChat' => 'MicroMessenger',
    ];
    /**
     * List of additional properties.
     *
     * @var array
     */
    protected static array $additionalProperties = [
        // Operating systems
        'Windows' => 'Windows NT [VER]',
        'Windows NT' => 'Windows NT [VER]',
        'OS X' => 'OS X [VER]',
        'BlackBerryOS' => ['BlackBerry[\w]+/[VER]', 'BlackBerry.*Version/[VER]', 'Version/[VER]'],
        'AndroidOS' => 'Android [VER]',
        'ChromeOS' => 'CrOS x86_64 [VER]',

        // Browsers
        'Opera Mini' => 'Opera Mini/[VER]',
        'Opera' => [' OPR/[VER]', 'Opera Mini/[VER]', 'Version/[VER]', 'Opera [VER]'],
        'Netscape' => 'Netscape/[VER]',
        'Mozilla' => 'rv:[VER]',
        'IE' => ['IEMobile/[VER];', 'IEMobile [VER]', 'MSIE [VER];', 'rv:[VER]'],
        'Edge' => ['Edge/[VER]', 'Edg/[VER]'],
        'Vivaldi' => 'Vivaldi/[VER]',
        'Coc Coc' => 'coc_coc_browser/[VER]',
    ];

    /**
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     */
    public function isDesktop($userAgent = null, $httpHeaders = null): bool
    {
        // Check specifically for cloudfront headers if the useragent === 'Amazon CloudFront'
        if ('Amazon CloudFront' === $this->getUserAgent()) {
            $cfHeaders = $this->getCfHeaders();

            if (array_key_exists('HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER', $cfHeaders)) {
                return 'true' === $cfHeaders['HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER'];
            }
        }

        return !$this->isMobile($userAgent, $httpHeaders) && !$this->isTablet($userAgent, $httpHeaders);
    }

    /**
     * Get the device name.
     *
     * @param string|null $userAgent
     * @return string|bool
     */
    public function device(string $userAgent = null): bool|string
    {
        $rules = self::mergeRules(
            self::getDesktopDevices(),
            self::getPhoneDevices(),
            self::getTabletDevices(),
        );

        return $this->findDetectionRulesAgainstUA($rules, $userAgent);
    }

    /**
     * Merge multiple rules into one array.
     *
     * @param array $all
     * @return array
     */
    protected static function mergeRules(...$all): array
    {
        $merged = [];

        foreach ($all as $rules) {
            foreach ($rules as $key => $value) {
                if (empty($merged[$key])) {
                    $merged[$key] = $value;
                } elseif (is_array($merged[$key])) {
                    $merged[$key][] = $value;
                } else {
                    $merged[$key] .= '|' . $value;
                }
            }
        }

        return $merged;
    }

    /**
     * @return array
     */
    public static function getDesktopDevices(): array
    {
        return self::$desktopDevices;
    }

    /**
     * Match a detection rule and return the matched key.
     *
     * @param array $rules
     * @param string|null $userAgent
     * @return string|bool
     */
    protected function findDetectionRulesAgainstUA(array $rules, string|null $userAgent = null): bool|string
    {
        // Loop given rules
        foreach ($rules as $key => $regex) {
            if (empty($regex)) {
                continue;
            }

            // Check match
            if ($this->match($regex, $userAgent)) {
                return $key ?: reset($this->matchesArray);
            }
        }

        return false;
    }

    /**
     * Get the platform name.
     *
     * @param string|null $userAgent
     * @return string|bool
     */
    public function platform($userAgent = null): bool|string
    {
        return $this->findDetectionRulesAgainstUA(static::getPlatforms(), $userAgent);
    }

    /**
     * @return array
     */
    #[Pure] public static function getPlatforms(): array
    {
        return self::mergeRules(static::$operatingSystems, static::$additionalOperatingSystems);
    }

    /**
     * @param null $userAgent
     * @param null $httpHeaders
     * @return bool
     * @throws MobileDetectException
     */
    public function isPhone($userAgent = null, $httpHeaders = null): bool
    {
        return $this->isMobile($userAgent, $httpHeaders) && !$this->isTablet($userAgent, $httpHeaders);
    }

    /**
     * @return string
     */
    public function deviceIdentifier(): string
    {
        return MachineId::instance()->getDeviceName();
    }
}
