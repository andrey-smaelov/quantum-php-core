<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.9.5
 */

use Quantum\Libraries\Database\Exceptions\DatabaseException;
use Quantum\Libraries\Auth\AuthenticatableInterface;
use Quantum\Libraries\Session\SessionException;
use Quantum\Libraries\Captcha\CaptchaException;
use Quantum\Libraries\Captcha\CaptchaInterface;
use Quantum\Libraries\Session\SessionManager;
use Quantum\Libraries\Captcha\CaptchaManager;
use Quantum\Libraries\Config\ConfigException;
use Quantum\Libraries\Mailer\MailerException;
use Quantum\Libraries\Mailer\MailerInterface;
use Quantum\Libraries\Mailer\MailerManager;
use Quantum\Libraries\Auth\AuthException;
use Quantum\Libraries\Asset\AssetManager;
use Quantum\Libraries\Cache\CacheManager;
use Quantum\Libraries\Lang\LangException;
use Quantum\Exceptions\ServiceException;
use Quantum\Libraries\Auth\AuthManager;
use Quantum\Libraries\Session\Session;
use Quantum\Exceptions\AppException;
use Quantum\Libraries\Cookie\Cookie;
use Quantum\Exceptions\DiException;
use Quantum\Libraries\Cache\Cache;
use Quantum\Libraries\Csrf\Csrf;
use Quantum\Hooks\HookManager;

/**
 * Gets the session handler
 * @return Session
 * @throws ReflectionException
 * @throws DatabaseException
 * @throws SessionException
 * @throws ConfigException
 * @throws DiException
 */
function session(): Session
{
    return SessionManager::getHandler();
}

/**
 * Gets cookie handler
 * @return Cookie
 */
function cookie(): Cookie
{
    return Cookie::getInstance($_COOKIE);
}

/**
 * Gets the Auth handler
 * @return AuthenticatableInterface
 * @throws AuthException
 * @throws ConfigException
 * @throws DiException
 * @throws LangException
 * @throws MailerException
 * @throws ReflectionException
 * @throws ServiceException
 */
function auth(): AuthenticatableInterface
{
    return AuthManager::getHandler();
}

/**
 * Gets the Mail handler
 * @return MailerInterface
 * @throws ConfigException
 * @throws DiException
 * @throws LangException
 * @throws ReflectionException
 * @throws MailerException
 */
function mailer(): MailerInterface
{
    return MailerManager::getHandler();
}

/**
 * Gets the AssetManager instance
 * @return AssetManager|null
 */
function asset(): ?AssetManager
{
    return AssetManager::getInstance();
}

/**
 * Gets the HookManager instance
 * @return HookManager
 */
function hook(): HookManager
{
    return HookManager::getInstance();
}

/**
 * Gets the Cache handler
 * @return Cache
 * @throws ReflectionException
 * @throws ConfigException
 * @throws AppException
 * @throws DiException
 */
function cache(): Cache
{
    return CacheManager::getHandler();
}

/**
 * Gets the Csrf instance
 * @return Csrf
 */
function csrf(): Csrf
{
    return Csrf::getInstance();
}

/**
 * @return CaptchaInterface
 * @throws ConfigException
 * @throws DiException
 * @throws ReflectionException
 * @throws CaptchaException
 */
function captcha(): CaptchaInterface
{
    return CaptchaManager::getHandler();
}
