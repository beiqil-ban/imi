<?php

declare(strict_types=1);

namespace Imi\Server\Http\Message\Proxy;

use Imi\Bean\Annotation\Bean;
use Imi\RequestContextProxy\Annotation\RequestContextProxy;
use Imi\RequestContextProxy\BaseRequestContextProxy;

/**
 * @Bean(name="HttpResponseProxy", recursion=false, instanceType="singleton")
 * @RequestContextProxy(class="Imi\Server\Http\Message\Contract\IHttpResponse", name="response")
 *
 * @method \Imi\Server\Http\Message\Contract\IHttpResponse redirect(string $url, int $status = 302)
 * @method static                                          \Imi\Server\Http\Message\Contract\IHttpResponse redirect(string $url, int $status = 302)
 * @method \Imi\Server\Http\Message\Contract\IHttpResponse send()
 * @method static                                          \Imi\Server\Http\Message\Contract\IHttpResponse send()
 * @method \Imi\Server\Http\Message\Contract\IHttpResponse sendFile(string $filename, ?string $contentType = NULL, ?string $outputFileName = NULL, int $offset = 0, int $length = 0)
 * @method static                                          \Imi\Server\Http\Message\Contract\IHttpResponse sendFile(string $filename, ?string $contentType = NULL, ?string $outputFileName = NULL, int $offset = 0, int $length = 0)
 * @method bool                                            isHeaderWritable()
 * @method static                                          bool isHeaderWritable()
 * @method bool                                            isBodyWritable()
 * @method static                                          bool isBodyWritable()
 * @method \Imi\Util\Http\Contract\IResponse               setStatus(int $code, string $reasonPhrase = '')
 * @method static                                          \Imi\Util\Http\Contract\IResponse setStatus(int $code, string $reasonPhrase = '')
 * @method \Imi\Util\Http\Contract\IResponse               withCookie(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false)
 * @method static                                          \Imi\Util\Http\Contract\IResponse withCookie(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false)
 * @method \Imi\Util\Http\Contract\IResponse               setCookie(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false)
 * @method static                                          \Imi\Util\Http\Contract\IResponse setCookie(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false)
 * @method array                                           getCookieParams()
 * @method static                                          array getCookieParams()
 * @method mixed                                           getCookie(string $name, $default = NULL)
 * @method static                                          mixed getCookie(string $name, $default = NULL)
 * @method array                                           getTrailers()
 * @method static                                          array getTrailers()
 * @method bool                                            hasTrailer(string $name)
 * @method static                                          bool hasTrailer(string $name)
 * @method string|null                                     getTrailer(string $name)
 * @method static                                          string|null getTrailer(string $name)
 * @method \Imi\Util\Http\Contract\IResponse               withTrailer(string $name, string $value)
 * @method static                                          \Imi\Util\Http\Contract\IResponse withTrailer(string $name, string $value)
 * @method \Imi\Util\Http\Contract\IResponse               setTrailer(string $name, string $value)
 * @method static                                          \Imi\Util\Http\Contract\IResponse setTrailer(string $name, string $value)
 * @method int                                             getStatusCode()
 * @method static                                          int getStatusCode()
 * @method \Psr\Http\Message\ResponseInterface             withStatus($code, $reasonPhrase = '')
 * @method static                                          \Psr\Http\Message\ResponseInterface withStatus($code, $reasonPhrase = '')
 * @method string                                          getReasonPhrase()
 * @method static                                          string getReasonPhrase()
 * @method string                                          getProtocolVersion()
 * @method static                                          string getProtocolVersion()
 * @method \Psr\Http\Message\MessageInterface              withProtocolVersion($version)
 * @method static                                          \Psr\Http\Message\MessageInterface withProtocolVersion($version)
 * @method string[][]                                      getHeaders()
 * @method static                                          string[][] getHeaders()
 * @method bool                                            hasHeader($name)
 * @method static                                          bool hasHeader($name)
 * @method string[]                                        getHeader($name)
 * @method static                                          string[] getHeader($name)
 * @method string                                          getHeaderLine($name)
 * @method static                                          string getHeaderLine($name)
 * @method \Psr\Http\Message\MessageInterface              withHeader($name, $value)
 * @method static                                          \Psr\Http\Message\MessageInterface withHeader($name, $value)
 * @method \Psr\Http\Message\MessageInterface              withAddedHeader($name, $value)
 * @method static                                          \Psr\Http\Message\MessageInterface withAddedHeader($name, $value)
 * @method \Psr\Http\Message\MessageInterface              withoutHeader($name)
 * @method static                                          \Psr\Http\Message\MessageInterface withoutHeader($name)
 * @method \Psr\Http\Message\StreamInterface               getBody()
 * @method static                                          \Psr\Http\Message\StreamInterface getBody()
 * @method \Psr\Http\Message\MessageInterface              withBody(\Psr\Http\Message\StreamInterface $body)
 * @method static                                          \Psr\Http\Message\MessageInterface withBody(\Psr\Http\Message\StreamInterface $body)
 * @method \Imi\Util\Http\Contract\IMessage                setProtocolVersion(string $version)
 * @method static                                          \Imi\Util\Http\Contract\IMessage setProtocolVersion(string $version)
 * @method \Imi\Util\Http\Contract\IMessage                setHeader(string $name, $value)
 * @method static                                          \Imi\Util\Http\Contract\IMessage setHeader(string $name, $value)
 * @method \Imi\Util\Http\Contract\IMessage                addHeader(string $name, $value)
 * @method static                                          \Imi\Util\Http\Contract\IMessage addHeader(string $name, $value)
 * @method \Imi\Util\Http\Contract\IMessage                removeHeader(string $name)
 * @method static                                          \Imi\Util\Http\Contract\IMessage removeHeader(string $name)
 * @method \Imi\Util\Http\Contract\IMessage                setBody(\Psr\Http\Message\StreamInterface $body)
 * @method static                                          \Imi\Util\Http\Contract\IMessage setBody(\Psr\Http\Message\StreamInterface $body)
 */
class ResponseProxyObject extends BaseRequestContextProxy implements \Imi\Server\Http\Message\Contract\IHttpResponse
{
    /**
     * {@inheritDoc}
     */
    public function redirect(string $url, int $status = 302): \Imi\Server\Http\Message\Contract\IHttpResponse
    {
        return self::__getProxyInstance()->redirect($url, $status);
    }

    /**
     * {@inheritDoc}
     */
    public function send(): \Imi\Server\Http\Message\Contract\IHttpResponse
    {
        return self::__getProxyInstance()->send(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function sendFile(string $filename, ?string $contentType = null, ?string $outputFileName = null, int $offset = 0, int $length = 0): \Imi\Server\Http\Message\Contract\IHttpResponse
    {
        return self::__getProxyInstance()->sendFile($filename, $contentType, $outputFileName, $offset, $length);
    }

    /**
     * {@inheritDoc}
     */
    public function isHeaderWritable(): bool
    {
        return self::__getProxyInstance()->isHeaderWritable(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function isBodyWritable(): bool
    {
        return self::__getProxyInstance()->isBodyWritable(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(int $code, string $reasonPhrase = ''): \Imi\Util\Http\Contract\IResponse
    {
        return self::__getProxyInstance()->setStatus($code, $reasonPhrase);
    }

    /**
     * {@inheritDoc}
     */
    public function withCookie(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false): \Imi\Util\Http\Contract\IResponse
    {
        return self::__getProxyInstance()->withCookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * {@inheritDoc}
     */
    public function setCookie(string $key, string $value, int $expire = 0, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = false): \Imi\Util\Http\Contract\IResponse
    {
        return self::__getProxyInstance()->setCookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams(): array
    {
        return self::__getProxyInstance()->getCookieParams(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getCookie(string $name, $default = null)
    {
        return self::__getProxyInstance()->getCookie($name, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function getTrailers(): array
    {
        return self::__getProxyInstance()->getTrailers(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function hasTrailer(string $name): bool
    {
        return self::__getProxyInstance()->hasTrailer($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getTrailer(string $name): ?string
    {
        return self::__getProxyInstance()->getTrailer($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withTrailer(string $name, string $value): \Imi\Util\Http\Contract\IResponse
    {
        return self::__getProxyInstance()->withTrailer($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function setTrailer(string $name, string $value): \Imi\Util\Http\Contract\IResponse
    {
        return self::__getProxyInstance()->setTrailer($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return self::__getProxyInstance()->getStatusCode(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return self::__getProxyInstance()->withStatus($code, $reasonPhrase);
    }

    /**
     * {@inheritDoc}
     */
    public function getReasonPhrase()
    {
        return self::__getProxyInstance()->getReasonPhrase(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return self::__getProxyInstance()->getProtocolVersion(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        return self::__getProxyInstance()->withProtocolVersion($version);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        return self::__getProxyInstance()->getHeaders(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return self::__getProxyInstance()->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return self::__getProxyInstance()->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return self::__getProxyInstance()->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        return self::__getProxyInstance()->withHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        return self::__getProxyInstance()->withAddedHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        return self::__getProxyInstance()->withoutHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return self::__getProxyInstance()->getBody(...\func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
        return self::__getProxyInstance()->withBody($body);
    }

    /**
     * {@inheritDoc}
     */
    public function setProtocolVersion(string $version): \Imi\Util\Http\Contract\IMessage
    {
        return self::__getProxyInstance()->setProtocolVersion($version);
    }

    /**
     * {@inheritDoc}
     */
    public function setHeader(string $name, $value): \Imi\Util\Http\Contract\IMessage
    {
        return self::__getProxyInstance()->setHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function addHeader(string $name, $value): \Imi\Util\Http\Contract\IMessage
    {
        return self::__getProxyInstance()->addHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function removeHeader(string $name): \Imi\Util\Http\Contract\IMessage
    {
        return self::__getProxyInstance()->removeHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function setBody(\Psr\Http\Message\StreamInterface $body): \Imi\Util\Http\Contract\IMessage
    {
        return self::__getProxyInstance()->setBody($body);
    }
}
