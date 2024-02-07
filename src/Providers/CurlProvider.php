<?php

namespace Sim\Payment\Providers;

use Sim\Payment\PaymentFactory;

class CurlProvider
{
    /**
     * @var resource|false $curl_handler
     */
    protected $curl_handler = false;

    /**
     * @var array $options
     */
    protected $options = [];

    /**
     * @var string|null $response
     */
    protected $response = null;

    /**
     * CurlProvider destructor.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param $key
     * @param $value
     * @return static
     */
    public function addOptions($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param string $url
     * @return static
     */
    public function setUrl(string $url)
    {
        $this->options[CURLOPT_URL] = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->options[CURLOPT_URL] ?? '';
    }

    /**
     * @param string $method
     * @return static
     */
    public function setRequestMethod(string $method)
    {
        $this->options[CURLOPT_CUSTOMREQUEST] = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->options[CURLOPT_CUSTOMREQUEST] ?? PaymentFactory::METHOD_POST;
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function setReturnTransfer(bool $answer)
    {
        $this->options[CURLOPT_RETURNTRANSFER] = $answer;
        return $this;
    }

    /**
     * @return bool
     */
    public function getReturnTransfer(): bool
    {
        return $this->options[CURLOPT_RETURNTRANSFER] ?? true;
    }

    /**
     * @param bool $answer
     * @return static
     */
    public function setSSLVerifyHost(bool $answer)
    {
        $this->options[CURLOPT_SSL_VERIFYHOST] = $answer;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSSLVerifyHost(): bool
    {
        return $this->options[CURLOPT_SSL_VERIFYHOST] ?? false;
    }

    /**
     * @param $verifyPeer
     * @return static
     */
    public function setSSLVerifyPeer($verifyPeer)
    {
        $this->options[CURLOPT_SSL_VERIFYPEER] = $verifyPeer;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getSSLVerifyPeer()
    {
        return $this->options[CURLOPT_SSL_VERIFYPEER] ?? null;
    }

    /**
     * @param string|array $data
     * @return static
     */
    public function setFields($data)
    {
        $this->options[CURLOPT_POSTFIELDS] = $data;
        return $this;
    }

    /**
     * @return mixed|array
     */
    public function getFields()
    {
        return $this->options[CURLOPT_POSTFIELDS] ?? [];
    }

    /**
     * @param HeaderProvider $header_provider
     * @return static
     */
    public function setHeader(HeaderProvider $header_provider)
    {
        $this->options[CURLOPT_HTTPHEADER] = $header_provider->getHeaders();
        return $this;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->options[CURLOPT_HTTPHEADER] ?? [];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return false|resource
     */
    public function getHandler()
    {
        return $this->curl_handler;
    }

    /**
     * @return string|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Do not init a curl without a close at the end
     *
     * @return static
     */
    public function init()
    {
        if (false === $this->curl_handler) {
            $this->curl_handler = curl_init();
        }
        return $this;
    }

    public function setOptionArray()
    {
        if (false !== $this->curl_handler) {
            curl_setopt_array($this->curl_handler, $this->getOptions());
        }
        return $this;
    }

    /**
     * @return static
     */
    public function execute()
    {
        if (false !== $this->curl_handler) {
            $this->response = curl_exec($this->curl_handler);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorNO(): int
    {
        if (false !== $this->curl_handler) {
            return curl_errno($this->curl_handler);
        }
        return 0;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        if (false !== $this->curl_handler) {
            return curl_error($this->curl_handler);
        }
        return null;
    }

    /**
     * Close curl handler
     */
    public function close()
    {
        if (false !== $this->curl_handler) {
            curl_close($this->curl_handler);
            $this->curl_handler = false;
        }
    }
}