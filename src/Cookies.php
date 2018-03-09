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
 * Class Cookies
 * @package Apatis\Http\Cookie
 */
class Cookies implements CookiesInterface, \Countable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var CookieInterface[]
     */
    protected $cookies = array();

    /**
     * {@inheritdoc}
     */
    public function __construct(array $cookies = array())
    {
        foreach ($cookies as $key => $cookie) {
            if ($cookie instanceof CookieInterface) {
                $this->cookies[$cookie->getName()] = $cookie;
                continue;
            }
            if (is_null($cookie) || $cookie === false) {
                $cookie = "";
            }
            $key = (string) $key;
            if (!is_string($cookie)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Cookie value for %s must be as a string %s given',
                        $key,
                        gettype($cookie)
                    )
                );
            }

            $this->cookies[$key] = new Cookie($key, $cookie);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        $name = is_numeric($name) ? (string) $name : $name;
        if (!is_string($name)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cookie nam must be as a string %s given',
                    gettype($name)
                )
            );
        }

        if (!$this->exist($name)) {
            throw new CookieNotFoundException(
                sprintf(
                    'Cookie for %s has not found',
                    $name
                )
            );
        }

        $cookie = $this->cookies[$name];
        if (!$cookie instanceof CookieInterface) {
            throw new \UnexpectedValueException(
                sprintf(
                    'Cookie for %s has invalid return value',
                    $name
                )
            );
        }

        return $cookie;
    }

    /**
     * {@inheritdoc}
     */
    public function set(
        $name,
        $value = "",
        $expire = 0,
        $path = "",
        $domain = "",
        $secure = false,
        $httpOnly = false
    ) {
        $this->cookies[$name] = new Cookie($name, $value, $expire, $path, $secure, $domain, $httpOnly);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($name)
    {
        $cookie = $this->get($name);
        //  (60 * 60 * 24 * 3) is 3 days on seconds
        $cookie->setExpire(time() - (60 * 60 * 24 * 3));
    }

    /**
     * {@inheritdoc}
     */
    public function exist($name)
    {
        return array_key_exists($name, $this->cookies);
    }

    /**
     * {@inheritdoc}
     */
    public function toHeaders()
    {
        $return = array();
        foreach ($this->cookies as $key => $cookie) {
            $return[$key] = $cookie->toCookieHeader();
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function toCookieParams()
    {
        $return = array();
        foreach ($this->cookies as $key => $cookie) {
            $return[$key] = $cookie->getValue();
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Returning count of cookies
     * @implement method of \Countable
     *
     * @return int
     */
    public function count()
    {
        return count($this->cookies);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->cookies[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->cookies[$offset]);
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getCookies());
    }

    /**
     * Parse HTTP request `Cookie:` header and extract
     * into a PHP associative array.
     *
     * @param  string|array $header The raw HTTP request `Cookie:` header
     *
     * @return array Associative array of cookie names and values
     * @throws InvalidArgumentException if the cookie data cannot be parsed
     */
    public static function parseHeader($header)
    {
        if (is_array($header)) {
            $header = isset($header[0]) ? $header[0] : '';
        }

        if (is_string($header) === false) {
            throw new \InvalidArgumentException(
                'Cannot parse Cookie data. Header value must be a string.'
            );
        }

        $header = rtrim($header, "\r\n");
        $pieces = preg_split('@[;]\s*@', $header);
        $cookies = [];
        foreach ($pieces as $cookie) {
            $cookie = explode('=', $cookie, 2);
            if (count($cookie) === 2) {
                $key = urldecode($cookie[0]);
                $value = urldecode($cookie[1]);
                if (!isset($cookies[$key])) {
                    $cookies[$key] = $value;
                }
            }
        }

        return $cookies;
    }
}
