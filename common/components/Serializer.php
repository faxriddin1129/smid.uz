<?php

namespace common\components;

/**
 * Class Serializer
 * @package common\components
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @var string
     */
    public $expandParam = 'include';

    /**
     * @var string
     */
    public $collectionEnvelope = 'data';

    /**
     * @var string
     */
    public $metaEnvelope = 'meta';

    /**
     * @var string
     */
    public $codeEnvelope = 'code';

    /**
     * @var string
     */
    public $messageEnvelope = 'message';

    /**
     * @var string
     */
    public $errorsEnvelop = 'errors';

    /**
     * @param \yii\data\DataProviderInterface $dataProvider
     * @return array|null
     */
    protected function serializeDataProvider($dataProvider)
    {
        if ($this->preserveKeys) {
            $models = $dataProvider->getModels();
        } else {
            $models = array_values($dataProvider->getModels());
        }
        $models = $this->serializeModels($models);

        $pagination = $dataProvider->getPagination();

        if ($this->request->getIsHead()) {
            return null;
        } elseif ($this->collectionEnvelope === null) {
            return $models;
        }

        $result = [
            $this->codeEnvelope => 1,
            $this->messageEnvelope => 'success',
            $this->collectionEnvelope => $models
        ];

        if ($pagination !== false) {
            return array_merge($result, $this->serializePagination($pagination));
        }

        return $result;
    }

    /**
     * @param \yii\data\Pagination $pagination
     * @return array
     */
    protected function serializePagination($pagination)
    {
        return [
            $this->metaEnvelope => [
                'totalCount' => $pagination->totalCount,
                'pageCount' => $pagination->getPageCount(),
                'currentPage' => $pagination->getPage() + 1,
                'perPage' => $pagination->getPageSize(),
            ]
        ];
    }

    /**
     * @param \yii\base\Arrayable $model
     * @return array|null
     */
    protected function serializeModel($model)
    {
        if ($this->request->getIsHead()) {
            return null;
        }

        $code = 1;
        $message = "success";

        if(method_exists($model, "getResponseCode") && method_exists($model, "getResponseMessage")) {
            if ($model->getResponseCode() && $model->getResponseMessage()) {
                $code = $model->getResponseCode();
                $message = $model->getResponseMessage();
                if (!$model->getResponseBody()) {
                    return [
                        $this->codeEnvelope => $code,
                        $this->messageEnvelope => $message
                    ];
                }
            }
        }

        list($fields, $expand) = $this->getRequestedFields();

        return [
            $this->codeEnvelope => $code,
            $this->messageEnvelope => $message,
            $this->collectionEnvelope => $model->toArray($fields, $expand)
        ];
    }

    /**
     * @param \yii\base\Model $model
     * @return array
     */
    protected function serializeModelErrors($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed.');
        $result = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[$name] = $message;
        }

        $code = -1;
        $message = "Validation error";

        if(method_exists($model, "getResponseCode") && method_exists($model, "getResponseMessage")) {
            if ($model->getResponseCode() && $model->getResponseMessage()) {
                $code = $model->getResponseCode();
                $message = $model->getResponseMessage();
                $this->response->setStatusCode(400, 'Bad request');
                if (!$model->getResponseBody()) {
                    return [
                        $this->codeEnvelope => $code,
                        $this->messageEnvelope => $message
                    ];
                }
            }
        }

        return [
            $this->codeEnvelope => $code,
            $this->messageEnvelope => $message,
            $this->errorsEnvelop => $result
        ];
    }
}