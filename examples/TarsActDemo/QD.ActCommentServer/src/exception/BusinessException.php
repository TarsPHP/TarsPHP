<?php

namespace Server\exception;
use Server\conf\Code;
use Exception;


class BusinessException extends Exception
{
    /**
     * ActivityException constructor.
     * @param int $code
     * @param string $message
     * @param Exception|null $previous
     */
    public function __construct($code, $message = "", Exception $previous = null)
    {
        $newMsg = '';
        if (isset(Code::$msgs[$code])) {
            $newMsg = Code::$msgs[$code];
        }

        if (!empty($message) && is_int($message)) {
            $message = isset(Code::$msgs[$message]) ? Code::$msgs[$message] : "code: $message";
        }

        $newMsg = $newMsg && $message ? "$newMsg ($message)" : ($newMsg ? $newMsg : $message);

        parent::__construct($newMsg, $code, $previous);
    }
}