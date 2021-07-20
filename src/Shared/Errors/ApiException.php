<?php


namespace App\Shared\Errors;


use App\Shared\Facades\Log\Log;
use Exception;
use Throwable;

/**
 * Class ApiException
 * @package App\Errors
 */
class ApiException extends Exception
{
    /**
     * ApiException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param bool $trace
     * @throws Exception
     */
    public function __construct(string $message = "", $code = 0, Throwable $previous = null, bool $trace = false)
    {
        parent::__construct($message, $code, $previous);
        $this->writeLog($message, $trace);
    }

    /**
     * @param string $message
     * @param bool $trace
     * @throws Exception
     */
    private function writeLog(string $message, bool $trace)
    {
        $logger = Log::getInstance();
        $traceError = $trace ? ['trace' => $this->getTrace()] : [];
        $logger->warning($message, $traceError);
    }
}