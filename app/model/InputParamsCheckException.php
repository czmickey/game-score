<?php

namespace App\model;

use App\model\entity\RpcResponse;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class InputParamsCheckException extends \Exception
{
    /**
     * @var RpcResponse
     */
    private $response;

    /**
     * @return RpcResponse
     */
    public function getResponse(): RpcResponse
    {
        return $this->response;
    }

    /**
     * @param RpcResponse $response
     */
    public function setResponse(RpcResponse $response)
    {
        $this->response = $response;
    }
}
