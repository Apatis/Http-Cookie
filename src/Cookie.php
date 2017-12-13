<?php
/**
 * MIT License
 *
 * Copyright (c) 2017, Pentagonal Development
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Apatis\Http\Cookie;

/**
 * Class Cookie
 * @package Apatis\Http\Cookie
 */
class Cookie implements CookieInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value = "";

    /**
     * @var int
     */
    protected $expire = 0;

    /**
     * @var string
     */
    protected $path = "";

    /**
     * @var string
     */
    protected $domain = "";

    /**
     * @var bool
     */
    protected $secure = false;

    /**
     * @var bool
     */
    protected $httpOnly = false;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        $name,
        $value = "",
        $expire = 0,
        $path = "",
        $domain = "",
        $secure = false,
        $httpOnly = false
    ) {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cookie name must be as a string %s given',
                    gettype($name)
                )
            );
        }

        $this->name = $name;
        $this->setValue($value);
        $this->setPath($path);
        $this->setExpire($expire);
        $this->setDomain($domain);
        $this->setSecure($secure);
        $this->setHttpOnly($httpOnly);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        if (null === $value || false === $value) {
            $value = "";
        }

        if (! is_string($value)
            || is_object($value) && ! method_exists($value, '__toStrings')
        ) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cookie value must be as a string %s given',
                    gettype($value)
                )
            );
        }

        // use string casting to make sure value if is object to string is convert into string
        $this->value = (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpire($expire)
    {
        // check if expire is integer or numeric converted abs is integer
        $expire = is_numeric($expire) && is_int(abs($expire))
            ? abs($expire)
            : $expire;

        if (!is_int($expire)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cookie expire must be as an integer %s given',
                    gettype($expire)
                )
            );
        }

        $this->expire = $expire;
    }

    /**
     * {@inheritdoc}
     */
    public function expireAfter($time)
    {
        // check if expire is integer or numeric converted abs is integer
        $time = is_numeric($time) && is_int(abs($time))
            ? abs($time)
            : $time;

        if (!is_int($time)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cookie expire after must be as an integer %s given'
                )
            );
        }

        $this->expire = time() + $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        if (null === $path || false === $path) {
            $path = "";
        }

        if (!is_string($path)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cookie path must be as a string %s given',
                    gettype($path)
                )
            );
        }

        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function isSecure()
    {
        return (bool) $this->secure;
    }

    /**
     * {@inheritdoc}
     */
    public function setSecure($secure)
    {
        if (!is_bool($secure)) {
            throw new \InvalidArgumentException(
                'Cookie secure must be as a boolean %s given',
                gettype($secure)
            );
        }

        $this->secure = $secure;
    }

    /**
     * {@inheritdoc}
     */
    public function isHttpOnly()
    {
        return (bool) $this->httpOnly;
    }

    /**
     * {@inheritdoc}
     */
    public function setHttpOnly($httpOnly)
    {
        if (!is_bool($httpOnly)) {
            throw new \InvalidArgumentException(
                'Cookie httpOnly must be as a boolean %s given',
                gettype($httpOnly)
            );
        }

        $this->secure = $httpOnly;
    }

    /**
     * {@inheritdoc}
     */
    public function setDomain($domain)
    {
        if (null === $domain || false === $domain) {
            $domain = "";
        }

        if (! is_string($domain)) {
            throw new \InvalidArgumentException(
                'Cookie domain must be as a string %s given',
                gettype($domain)
            );
        }

        $this->domain = $domain;
    }

    /**
     * {@inheritdoc}
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function toCookieHeader()
    {
        $cookie = urlencode($this->getName()) . '=' . urlencode($this->getValue());
        if (($path = $this->getPath()) != '') {
            $path = urlencode($path);
            $cookie .= "; path={$path}";
        }
        if (($domain = $this->getDomain()) != '') {
            $domain = urlencode($domain);
            $cookie .= "; domain={$domain}";
        }
        if (($expire = $this->getExpire()) > 0) {
            $cookie .= "; expires=" . urlencode(gmdate('D, d-M-Y H:i:s \G\M\T', $expire));
        }
        if ($this->isSecure()) {
            $cookie .= "; secure";
        }
        if ($this->isHttpOnly()) {
            $cookie .= "; HttpOnly";
        }

        return $cookie;
    }
}
