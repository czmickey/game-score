<?php

namespace App\model\service;

use App\model\entity\Request;
use App\model\entity\RpcResponse;
use App\model\entity\RpcResponseError;
use App\model\InputParamsCheckException;
use App\model\InvalidParamsException;
use App\model\InvalidRequestException;
use App\model\MethodNotAvailableException;
use Nette\Http\IRequest;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class InputParamsChecker
{
    /**
     * @var IRequest
     */
    private $httpRequest;

    /**
     * InputParamsChecker constructor.
     * @param IRequest $httpRequest
     */
    public function __construct(IRequest $httpRequest)
    {
        $this->httpRequest = $httpRequest;
    }

    /**
     * @param array $decodedRequest
     * @return Request
     * @throws InputParamsCheckException
     */
    public function processInputParams($decodedRequest): Request
    {
        try {
            $this->checkMethod($this->httpRequest);
            $this->checkValidJSONrequest($decodedRequest);
            $request = $this->getAllMandatoryInputParams($decodedRequest);
        } catch (\Exception $e) {
            $error = new RpcResponseError();
            $error->setCode($e->getCode());
            $error->setMessage($e->getMessage());

            $response = new RpcResponse();
            $response->setError($error);

            $checkException = new InputParamsCheckException('', 0, $e);
            $checkException->setResponse($response);

            throw $checkException;
        }

        return $request;
    }

    /**
     * @param $httpRequest
     * @throws MethodNotAvailableException
     */
    private function checkMethod($httpRequest): void
    {
        if ($httpRequest->getMethod() !== 'POST') {
            throw new MethodNotAvailableException(
                'The method does not exist / is not available.',
                -32601
            );
        }
    }

    /**
     * @param $decoded
     * @throws InvalidRequestException
     */
    private function checkValidJSONrequest($decoded): void
    {
        if ($decoded === null) {
            throw new InvalidRequestException(
                'The JSON sent is not a valid Request object.',
                -32600
            );
        }
    }

    /**
     * @param array $decoded Pole z vstupních parametrů z requestu
     * @return Request zpracované vstupní parametry do entity Request
     * @throws InvalidParamsException Invalid method parameter(s).
     */
    private function getAllMandatoryInputParams($decoded): Request
    {
        $reflectionClass = new \ReflectionClass(Request::class);
        $requestProperties = $reflectionClass->getProperties();
        $request = new Request();

        foreach ($requestProperties as $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $decoded)) {
                $request->{'set' . ucfirst($propertyName)}($decoded[$propertyName]);
            } else {
                throw new InvalidParamsException(
                    'Invalid method parameter(s).',
                    -32602
                );
            }
        }

        return $request;
    }

    /**
     * @param array $mandatoryParams Pole s povinnými parametry
     * @param array $requestParams Pole s parametry z request parametru `params`
     * @throws InvalidParamsException Invalid method parameter(s).
     */
    public function checkRequestMandatoryParams($mandatoryParams, $requestParams)
    {
        if (!is_array($requestParams) || count($requestParams) !== count($mandatoryParams)) {
            throw new InvalidParamsException(
                'Invalid method parameter(s).',
                -32602
            );
        }

        foreach ($mandatoryParams as $mandatoryParam) {
            if (!array_key_exists($mandatoryParam, $requestParams)) {
                throw new InvalidParamsException(
                    'Invalid method parameter(s).',
                    -32602
                );
            }
        }
    }
}
