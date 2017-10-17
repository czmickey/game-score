<?php

namespace App\Presenters;

use App\model\entity\RpcResponse;
use App\model\entity\RpcResponseError;
use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
    public function actionDefault()
    {
        $error = new RpcResponseError();
        $error->setCode(-32601);
        $error->setMessage('The method does not exist / is not available.');

        $response = new RpcResponse();
        $response->setError($error);

        $this->sendJson($response);
    }
}
