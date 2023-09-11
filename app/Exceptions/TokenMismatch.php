<?php

namespace App\Exceptions;

class TokenMismatch implements \Throwable
{

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return "Token mismatch";
    }

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    public function getFile(): string
    {
        return "TokenMismatch";
    }

    /**
     * @inheritDoc
     */
    public function getLine(): int
    {
        return "TokenMismatch";
    }

    /**
     * @inheritDoc
     */
    public function getTrace(): array
    {
        return ["TokenMismatch"];
    }

    /**
     * @inheritDoc
     */
    public function getTraceAsString(): string
    {
        return "TokenMismatch";
    }

    /**
     * @inheritDoc
     */
    public function getPrevious()
    {
        return "TokenMismatch";
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return "TokenMismatch";
    }
}
