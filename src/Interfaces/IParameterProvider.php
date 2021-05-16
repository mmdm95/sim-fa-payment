<?php

namespace Sim\Payment\Interfaces;

interface IParameterProvider
{
    /**
     * @param string $key
     * @param $value
     * @return static
     */
    public function setExtraParameter(string $key, $value);
}