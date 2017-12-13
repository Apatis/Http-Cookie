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
 * Interface CookieInterface
 * @package Apatis\Http\Cookie
 */
interface CookieInterface
{
    /**
     * CookieInterface constructor.
     *
     * @param string $name          Name of cookie
     * @param string $value         Value of cookie
     * @param int    $expire           Time expire if expire time
     * @param string $path     Cookie path
     * @param bool   $secure     Determine cookie is secure or not
     * @param string $domain        Determine cookie domain
     * @param bool   $httpOnly   Determine cookie is httpOnly or not
     *
     * @see setcookie()
     * @link http://php.net/manual/en/function.setcookie.php
     */
    public function __construct(
        $name,
        $value = "",
        $expire = 0,
        $path = "",
        $domain = "",
        $secure = false,
        $httpOnly = false
    );

    /**
     * Get cookie name
     *
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * Set cookie value
     *
     * @param string $value
     */
    public function setValue($value);

    /**
     * Get expired
     *
     * @return int
     */
    public function getExpire();

    /**
     * Get expired expired values
     *
     * @param int $expire expire in seconds
     */
    public function setExpire($expire);

    /**
     * set Expire after in seconds
     *
     * @param int $time expire after time in second
     */
    public function expireAfter($time);

    /**
     * Get cookie path
     *
     * @return string
     */
    public function getPath();

    /**
     * Set cookie path
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Get secure value
     *
     * @return bool bool false if not secure, otherwise true
     */
    public function isSecure();

    /**
     * Set if cookie use secure
     *
     * @param bool $secure true if secure
     */
    public function setSecure($secure);

    /**
     * Get value if is httpOnly
     *
     * @return bool bool false if not secure, otherwise true
     */
    public function isHttpOnly();

    /**
     * Set cookie http only
     *
     * @param bool $httpOnly true if is http only
     */
    public function setHttpOnly($httpOnly);

    /**
     * Set Cookie domain
     *
     * @param string $domain Cookie domain to be set
     */
    public function setDomain($domain);

    /**
     * Get cookie domain
     *
     * @return string the cookie domain
     */
    public function getDomain();

    /**
     * Convert Cookie value into string
     *
     * @return string cookie value
     */
    public function __toString();

    /**
     * Get cookie fo header response
     *
     * eg: cookieName=cookieValue; path=/path; domain=example.com; expires=01-01-1991 00:00:00 GMT; secure;HttpOnly
     * uses eg:
     * gmdate('D, d-M-Y H:i:s e', $timeExpires); or gmdate('D, d-M-Y H:i:s \G\M\T', $timeExpires);
     *
     * @return string
     */
    public function toCookieHeader();
}
