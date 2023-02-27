<?php

namespace Sim\Payment\Abstracts;

use Sim\Payment\Interfaces\IParameterProvider;
use Sim\Payment\Interfaces\IResultParameterProvider;

abstract class AbstractBaseParameterProvider implements
    IParameterProvider,
    IResultParameterProvider
{
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * {@inheritdoc}
     */
    public function setExtraParameter(string $key, $value)
    {
        if ('' !== trim($key)) {
            $this->parameters[$key] = $value;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($key, $prefer = null)
    {
        return $this->parameters[$key] ?? $prefer;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param $data
     */
    protected function addExtraParameters($data)
    {
        try {
            if(is_array($data) && is_array($this->parameters)) {
                $data = array_diff($data, $this->parameters);
                foreach ($data as $k => $v) {
                    $this->parameters[$k] = $v;
                }
            }
        } catch (\Exception $e) {
            // do nothing for now
        }
    }
}
