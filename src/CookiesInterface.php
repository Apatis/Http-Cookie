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
 */
interface CookiesInterface
{
    /**
     * CookiesInterface constructor.
     *
     * @param array|CookieInterface[]|false[]|null[] $cookies Compatible with $_COOKIES or CookieInterface array value
     * @throws \InvalidArgumentException if cookie value contains invalid
     */
    public function __construct(array $cookies = array());

    /**
     * Get cookie
     *
     * @param string $name
     *
     * @return CookieInterface
     *
     * @throws CookieNotFoundException   if cookie not found
     * @throws \InvalidArgumentException if cookie name is invalid
     * @throws \UnexpectedValueException if cookie value is not instance of CookieInterface
     */
    public function get($name);

    /**
     * Set Cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     *
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
     * Set expired for cookie
     *
     * @param string $name
     *
     * @throws CookieNotFoundException if cookie has not found
     * @throws \InvalidArgumentException if cookie name is not a string
     */
    public function delete($name);

    /**
     * Validate if cookie exists
     *
     * @param string $name
     *
     * @return bool
     */
    public function exist($name);

    /**
     * Convert into headers values
     *  eg:
     * array(
     *     'name' => name=value; path=/path; domain=example.com; expires=01-01-1991 00:00:00 GMT; secure; HttpOnly
     * )
     *
     * @see CookieInterface::toCookieHeader()
     *
     * @return string[]
     */
    public function toHeaders();

    /**
     * Get cookie as string array cookie params
     * eg:
     * array(
     *     'cookieName' => 'cookieValue'
     * )
     *
     * @return string[]
     */
    public function toCookieParams();

    /**
     * Get cookies collection
     *
     * @return CookieInterface[]
     */
    public function getCookies();
}
