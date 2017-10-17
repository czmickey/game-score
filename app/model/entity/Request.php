<?php

namespace App\model\entity;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class Request
{
    /**
     * @var string
     */
    private $jsonrpc;

    /**
     * @var string
     */
    private $method;

    /**
     * @var mixed
     */
    private $params;

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
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return int
     */
    public function getId(): int
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
}
