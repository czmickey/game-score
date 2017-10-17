<?php

namespace App\Presenters;

use App\model\entity\Request;
use App\model\entity\RpcResponse;
use App\model\entity\RpcResponseError;
use App\model\entity\PlayerScore;
use App\model\InputParamsCheckException;
use App\model\InvalidParamsException;
use App\model\repository\ScoreRepository;
use App\model\ScoreSaveErrorException;
use App\model\service\InputParamsChecker;
use Nette\Application\UI\Presenter;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class ApiScorePresenter extends Presenter
{
    /**
     * @var InputParamsChecker @inject
     */
    public $inputParamsChecker;

    /**
     * @var ScoreRepository @inject
     */
    public $scoreRepository;

    /**
     * Výchozí zpracování requestu
     */
    public function actionDefault(): void
    {
        $httpRequest = $this->getHttpRequest();

        $decoded = json_decode($httpRequest->getRawBody(), true);

        try {
            $request = $this->inputParamsChecker->processInputParams($decoded);
        } catch (InputParamsCheckException $e) {
            $this->sendJson($e->getResponse());
        }

        if ($request->getMethod() === 'save') {
            $this->actionSaveScore($request);
        } elseif ($request->getMethod() === 'getTopTen') {
            $this->actionGetTopTen($request);
        } else {
            $this->actionMethodNotSupported();
        }
    }

    /**
     * @param int $code
     * @param string $message
     * @return RpcResponse
     */
    private function prepareErrorResponse($code, $message)
    {
        $error = new RpcResponseError();
        $error->setCode($code);
        $error->setMessage($message);

        $response = new RpcResponse();
        $response->setError($error);

        return $response;
    }

    /**
     * Akce pro uložení score
     * @param Request $request Zpracovaný request
     */
    private function actionSaveScore($request): void
    {
        try {
            $this->inputParamsChecker->checkRequestMandatoryParams(
                ['gameId', 'userId', 'score'],
                $request->getParams()
            );
        } catch (InvalidParamsException $e) {
            $response = $this->prepareErrorResponse($e->getCode(), $e->getMessage());
            $this->sendJson($response);
        }

        $sanitizedParams = array_map('intval', $request->getParams());

        $userScore = new PlayerScore();
        $userScore->setGameId($sanitizedParams['gameId']);
        $userScore->setPlayerId($sanitizedParams['userId']);
        $userScore->setValue($sanitizedParams['score']);

        try {
            $this->scoreRepository->saveUserScore($userScore);

            $response = new RpcResponse();
            $response->setId($request->getId());
            $response->setResult([
                'message' => 'Score was saved successful',
                'params' => $sanitizedParams
            ]);
        } catch (ScoreSaveErrorException $e) {
            $response = $this->prepareErrorResponse(
                9002,
                'Player`s score saving error'
            );
        }

        $this->sendJson($response);
    }

    /**
     * @param $request
     */
    public function actionGetTopTen($request): void
    {
        try {
            $this->inputParamsChecker->checkRequestMandatoryParams(['gameId'], $request->getParams());
        } catch (InvalidParamsException $e) {
            $response = $this->prepareErrorResponse($e->getCode(), $e->getMessage());
            $this->sendJson($response);
        }

        try {
            $sanitizedParams = array_map('intval', $request->getParams());
            $playersWithRank = $this->scoreRepository->getTopTen($sanitizedParams['gameId']);

            if (empty($playersWithRank)) {
                $response = $this->prepareErrorResponse(
                    9004,
                    'Score for game `' . $sanitizedParams['gameId'] . '` not exists'
                );
                $this->sendJson($response);
            }

            $response = new RpcResponse();
            $response->setId($request->getId());
            $response->setResult([
                'players' => $playersWithRank,
                'params' => $sanitizedParams
            ]);
        } catch (ScoreSaveErrorException $e) {
            $response = $this->prepareErrorResponse(
                9003,
                'Getting top ten players error'
            );
        }

        $this->sendJson($response);
    }

    /**
     * Akce pro neznámou požadovanou metodu API
     */
    private function actionMethodNotSupported(): void
    {
        $response = $this->prepareErrorResponse(
            9001,
            'API score method is not supported'
        );

        $this->sendJson($response);
    }
}
