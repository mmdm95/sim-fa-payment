<?php

namespace Sim\Payment\Interfaces;

interface IResultParameterProvider
{
    /**
     * @param $key
     * @param null $prefer
     * @return mixed|null
     */
    public function getParameter($key, $prefer = null);

    /**
     * @return array
     */
    public function getParameters(): array;
}