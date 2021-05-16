<?php

namespace Sim\Payment\Abstracts;

use Sim\Event\Emitter;
use Sim\Event\Event;
use Sim\Event\EventProvider;

abstract class AbstractPaymentEvent
{
    /**
     * Event Constants
     */
    const BF_CREATE_REQUEST = 'create-request:bf';
    const AF_CREATE_REQUEST = 'create-request:af';
    const OK_CREATE_REQUEST = 'create-request:ok';
    const NOT_OK_CREATE_REQUEST = 'create_request:not-ok';
    const BF_SEND_ADVICE = 'send-advice:bf';
    const AF_SEND_ADVICE = 'send-advice:af';
    const OK_SEND_ADVICE = 'send-advice:ok';
    const NOT_OK_SEND_ADVICE = 'send-advice:not-ok';
    const BF_HANDLE_RESULT = 'handle-result:bf';
    const AF_HANDLE_RESULT = 'handle-result:af';
    const OK_HANDLE_RESULT = 'handle-result:ok';
    const NOT_OK_HANDLE_RESULT = 'handle-result:not-ok';

    /**
     * @var EventProvider
     */
    protected $event_provider;

    /**
     * @var Emitter
     */
    protected $emitter;

    /**
     * AbstractPaymentEvent constructor.
     */
    public function __construct()
    {
        $this->event_provider = new EventProvider();
        $this->event_provider->addEvent(new Event(self::BF_CREATE_REQUEST));
        $this->event_provider->addEvent(new Event(self::AF_CREATE_REQUEST));
        $this->event_provider->addEvent(new Event(self::OK_CREATE_REQUEST));
        $this->event_provider->addEvent(new Event(self::NOT_OK_CREATE_REQUEST));
        $this->event_provider->addEvent(new Event(self::BF_SEND_ADVICE));
        $this->event_provider->addEvent(new Event(self::AF_SEND_ADVICE));
        $this->event_provider->addEvent(new Event(self::OK_SEND_ADVICE));
        $this->event_provider->addEvent(new Event(self::NOT_OK_SEND_ADVICE));
        $this->event_provider->addEvent(new Event(self::BF_HANDLE_RESULT));
        $this->event_provider->addEvent(new Event(self::AF_HANDLE_RESULT));
        $this->event_provider->addEvent(new Event(self::OK_HANDLE_RESULT));
        $this->event_provider->addEvent(new Event(self::NOT_OK_HANDLE_RESULT));

        $this->emitter = new Emitter($eventProvider);
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function beforeCreateRequestClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::BF_CREATE_REQUEST, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function afterCreateRequestClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::AF_CREATE_REQUEST, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function createRequestOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::OK_CREATE_REQUEST, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function createRequestNotOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::NOT_OK_CREATE_REQUEST, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function beforeSendAdviceClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::BF_SEND_ADVICE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function afterSendAdviceClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::AF_SEND_ADVICE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function sendAdviceOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::OK_SEND_ADVICE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function sendAdviceNotOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::NOT_OK_SEND_ADVICE, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function beforeHandleResultClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::BF_HANDLE_RESULT, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function afterHandleResultClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::AF_HANDLE_RESULT, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function handleResultOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::OK_HANDLE_RESULT, $closure);
        return $this;
    }

    /**
     * @param \Closure $closure
     * @return static
     */
    public function handleResultNotOkClosure(\Closure $closure)
    {
        $this->emitter->addListener(self::NOT_OK_HANDLE_RESULT, $closure);
        return $this;
    }
}