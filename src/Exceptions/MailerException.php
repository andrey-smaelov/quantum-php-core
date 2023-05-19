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
 * @since 2.9.0
 */

namespace Quantum\Exceptions;

/**
 * Class MailerException
 * @package Quantum\Exceptions
 */
class MailerException extends \Exception
{

    /**
     * @param string $name
     * @return MailerException
     * @throws LangException
     */
    public static function unsupportedAdapter(string $name): MailerException
    {
        return new static(t('exception.adapter_not_supported', $name), E_WARNING);
    }

}
