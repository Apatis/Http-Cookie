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
 * Interface CookiesInterface
 * @package Apatis\Http\Cookie
 *
 * According RFC6265 {@link https://tools.ietf.org/html/rfc6265#section-5.2}
 * `Cookie Name` doesn't state explicitly, just determine that cookie properties
 * must be case-insensitive key name for:
 * Path, Domain, Expires, Secure & HttpOnly
 */
interface CookiesInterface
{
    /**
     * CookiesInterface constructor.
     *
     * @param array|CookieInterface[]|false[]|null[] $cookies Compatible with $_COOKIES or CookieInterface array value
     *      NULL / (boolean) false indicate that the cookie value is empty string
     *
     * @throws \InvalidArgumentException if cookie value contains invalid
     */
    public function __construct(array $cookies = array());

    /**
     * Get cookie
     *
     * @param string $name
     *
     * @return CookieInterface returning CookieInterface if cookie found
     *
     * @throws CookieNotFoundException   if cookie not found
     * @throws \InvalidArgumentException if cookie name is invalid
     * @throws \UnexpectedValueException if cookie value is not instance of CookieInterface
     */
    public function get($name);

    /**
     * Set Cookie
     *
     * @param string $name     The cookie name (case sensitive)
     * @param string $value    Cookie value, the represented values of cookie to stored into client.
     * @param int    $expire   integer time expire, this include future / past full time (eg : @uses time())
     * @param string $path     Cookie path that determine as (URI path) to represented for cookie path placed
     * @param string $domain   the cookie domain
     * @param bool   $secure   indicate the cookie only transmit for secure HTTPS connection
     * @param bool   $httpOnly When true the cookie will be made accessible only through the HTTP protocol
     *
     * @see CookiesInterface::__construct
     *
     * @throws \InvalidArgumentException if invalid cookie name or is not as a string
     */
    public function set(
        $name,
        $value = "",
        $expire = 0,
        $path = "",
        $domain = "",
        $secure = false,
        $httpOnly = false
    );

    /**
     * Set expired / set Expire time for cookie to indicate cookie must be deleted on client.
     *
     * @param string $name The cookie name
     *
     * @throws CookieNotFoundException if cookie has not found
     * @throws \InvalidArgumentException if invalid cookie name or is not as a string
     */
    public function delete($name);

    /**
     * Validate if cookie exists
     *
     * @param string $name
     *
     * @return bool true if cookie exists otherwise false
     *
     * @throws \InvalidArgumentException if invalid cookie name or is not as a string
     */
    public function exist($name);

    /**
     * Convert into headers values
     *
     *  eg:
     * array(
     *     'name' => name=value; path=/path; domain=example.com; expires=01-01-1991 00:00:00 GMT; secure; HttpOnly
     * )
     *
     * @see CookieInterface::toCookieHeader()
     *
     * @return string[] contains string array values represented to server on header
     */
    public function toHeaders();

    /**
     * Get cookie as string array cookie params, like a $_COOKIE Compatible values
     *
     * eg:
     * array(
     *     'cookieName' => 'cookieValue'
     * )
     *
     * @return string[]
     */
    public function toCookieParams();

    /**
     * Get cookies collection contains array value of CookieInterface
     *
     * @return CookieInterface[]
     */
    public function getCookies();
}
