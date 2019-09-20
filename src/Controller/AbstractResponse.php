<?php

namespace rsavinkov\HangmanGame\Controller;

abstract class AbstractResponse
{
    public const RESPONSE_CODE_OK = 200;
    public const RESPONSE_CODE_BAD_REQUEST = 400;
    public const RESPONSE_CODE_NOT_FOUND = 404;
    public const RESPONSE_CODE_METHOD_NOT_ALLOWED = 405;
    public const RESPONSE_CODE_INTERNAL_SERVER_ERROR = 500;

    protected $code;
    protected $error;

    /**
     * @return static
     */
    public static function ok()
    {
        return new static(self::RESPONSE_CODE_OK);
    }

    /**
     * @param string $message
     * @param string|null $field
     * @return static
     */
    public static function badRequest(string $message, ?string $field = null)
    {
        return new static(self::RESPONSE_CODE_BAD_REQUEST, ['field' => $field, 'message' => $message]);
    }

    /**
     * @param string $message
     * @return static
     */
    public static function notFound(string $message)
    {
        return new static(self::RESPONSE_CODE_NOT_FOUND, ['message' => $message]);
    }

    /**
     * @return static
     */
    public static function methodNotAllowed()
    {
        return new static(self::RESPONSE_CODE_METHOD_NOT_ALLOWED, ['message' => 'Method not allowed']);
    }

    /**
     * @param string $message
     * @return static
     */
    public static function internalServerError(string $message)
    {
        return new static(self::RESPONSE_CODE_INTERNAL_SERVER_ERROR, ['message' => $message]);
    }

    public function __construct(int $code, array $errors = null)
    {
        $this->code = $code;
        $this->error = $errors;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function toArray(): array
    {
        $arrResult = [];
        if (!empty($this->error)) {
            $arrResult['error'] = $this->error;
        }

        return $arrResult;
    }
}
