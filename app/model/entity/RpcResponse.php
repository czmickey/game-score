<?php

namespace App\model\entity;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class RpcResponse implements \JsonSerializable
{
    /**
     * @var string
     */
    private $jsonrpc = '2.0';

    /*
     * @var mixed
     */
    private $result;

    /**
     * @var RpcResponseError
     */
    private $error;

    /**
     * @var int
     */
    private $id;

    /**
     * @return string
     */
    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    /**
     * @param string $jsonrpc
     */
    public function setJsonrpc(string $jsonrpc)
    {
        $this->jsonrpc = $jsonrpc;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return RpcResponseError
     */
    public function getError(): ?RpcResponseError
    {
        return $this->error;
    }

    /**
     * @param RpcResponseError $error
     */
    public function setError(RpcResponseError $error)
    {
        $this->error = $error;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return array Data pro serializaci objektu do JSON
     */
    public function jsonSerialize()
    {
        $data = [
            'jsonrpc' => $this->getJsonrpc(),
            'id' => $this->getId(),
        ];

        if ($this->getError() !== null) {
            $data['error'] = $this->getError();
            $data['id'] = null;
        } else {
            $data['result'] = $this->getResult();
        }

        return $data;
    }
}
