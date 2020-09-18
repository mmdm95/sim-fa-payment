<?php

namespace Sim\Payment\Providers;

class HeaderProvider
{
    /**
     * @var array $headers
     */
    protected $headers = [];

    /**
     * @param string $name
     * @param string $value
     * @return static
     */
    public function addHeader(string $name, string $value)
    {
        if (!is_array($this->headers[$name])) {
            $this->headers[$name] = [];
        }

        $this->headers[$name][] = $value;
        return $this;
    }

    /**
     * @param string $content_length
     * @return static
     */
    public function contentLength(string $content_length)
    {
        if (!is_array($this->headers['Content-Length'])) {
            $this->headers['Content-Length'] = [];
        }

        $this->headers['Content-Length'][] = $content_length;
        return $this;
    }

    /**
     * @param string $content_type
     * @return static
     */
    public function contentType(string $content_type)
    {
        if (!is_array($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = [];
        }

        $this->headers['Content-Type'][] = $content_type;
        return $this;
    }

    /**
     * @param string $content_encoding
     * @return static
     */
    public function contentEncoding(string $content_encoding)
    {
        if (!is_array($this->headers['Content-Encoding'])) {
            $this->headers['Content-Encoding'] = [];
        }

        $this->headers['Content-Encoding'][] = $content_encoding;
        return $this;
    }

    /**
     * @param string $age
     * @return static
     */
    public function age(string $age)
    {
        if (!is_array($this->headers['Age'])) {
            $this->headers['Age'] = [];
        }

        $this->headers['Age'][] = $age;
        return $this;
    }

    /**
     * @param string $cache_control
     * @return static
     */
    public function cacheControl(string $cache_control)
    {
        if (!is_array($this->headers['Cache-Control'])) {
            $this->headers['Cache-Control'] = [];
        }

        $this->headers['Cache-Control'][] = $cache_control;
        return $this;
    }

    /**
     * @param string $expires
     * @return static
     */
    public function expires(string $expires)
    {
        if (!is_array($this->headers['Expires'])) {
            $this->headers['Expires'] = [];
        }

        $this->headers['Expires'][] = $expires;
        return $this;
    }

    /**
     * @param string $pragma
     * @return static
     */
    public function pragma(string $pragma)
    {
        if (!is_array($this->headers['Pragma'])) {
            $this->headers['Pragma'] = [];
        }

        $this->headers['Pragma'][] = $pragma;
        return $this;
    }

    /**
     * @param string $eTag
     * @return static
     */
    public function eTag(string $eTag)
    {
        if (!is_array($this->headers['ETag'])) {
            $this->headers['ETag'] = [];
        }

        $this->headers['ETag'][] = $eTag;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $headersArray = [];

        foreach ($this->headers as $name => $headers) {
            $headerString = $name . ': ';
            foreach ($headers as $k => $header) {
                if (0 != $k) {
                    $headerString .= ', ';
                }
                $headerString .= $header;
            }
            $headersArray[] = $headerString;
        }

        return $headersArray;
    }

    /**
     * @return string
     */
    public function getHeadersString(): string
    {
        return implode("\n", $this->getHeaders());
    }
}