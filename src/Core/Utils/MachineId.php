<?php

declare(strict_types=1);

namespace App\Core\Utils;

use RuntimeException;

final class MachineId
{
    private static ?MachineId $instance = null;

    private string $ipAddress;

    private string $acceptLanguage;

    private string $userAgent;

    private function __construct()
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->acceptLanguage = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])
            ? strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',')
            : '';
        $this->ipAddress = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
        self::$instance = null;
    }

    /**
     * @return MachineId
     */
    public static function instance(): MachineId
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function getDeviceName(): string
    {
        return $this->userAgent . $this->acceptLanguage . $this->ipAddress;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * @return string
     */
    public function getAcceptLanguage(): string
    {
        return $this->acceptLanguage;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @return mixed
     */
    public function __wakeup()
    {
        throw new RuntimeException('Cannot unSerialize singleton');
    }
}
