<?php

namespace App\model\entity;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class RpcResponseError implements \JsonSerializable
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array Data pro serializaci objektu do JSON
     */
    public function jsonSerialize()
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'data' => $this->getData(),
        ];
    }
}
