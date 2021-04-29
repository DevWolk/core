<?php

namespace Apiato\Core\Abstracts\Transformers;

use Apiato\Core\Exceptions\CoreInternalErrorException;
use Apiato\Core\Exceptions\UnsupportedFractalIncludeException;
use ErrorException;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Scope;
use League\Fractal\TransformerAbstract as FractalTransformer;

abstract class Transformer extends FractalTransformer
{
    /**
     * @param $adminResponse
     * @param $clientResponse
     */
    public function ifAdmin($adminResponse, $clientResponse): array
    {
        $user = $this->user();

        if (!is_null($user) && $user->hasAdminRole()) {
            return array_merge($clientResponse, $adminResponse);
        }

        return $clientResponse;
    }

    public function user(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * @param mixed $data
     * @param callable|FractalTransformer $transformer
     * @param null                        $resourceKey
     */
    public function item($data, $transformer, $resourceKey = null): Item
    {
        // set a default resource key if none is set
        if (!$resourceKey && $data) {
            $resourceKey = $data->getResourceKey();
        }

        return parent::item($data, $transformer, $resourceKey);
    }

    /**
     * @param mixed $data
     * @param callable|FractalTransformer $transformer
     * @param null                        $resourceKey
     */
    public function collection($data, $transformer, $resourceKey = null): Collection
    {
        // set a default resource key if none is set
        if (!$resourceKey && $data->isNotEmpty()) {
            $resourceKey = (string)$data->modelKeys()[0];
        }

        return parent::collection($data, $transformer, $resourceKey);
    }

    /**
     * @FIXME : thinking about this method
     *
     * @param string $includeName
     *
     * @throws CoreInternalErrorException
     * @throws UnsupportedFractalIncludeException
     * @noinspection PhpInternalEntityUsedInspection
     */
    protected function callIncludeMethod(Scope $scope, $includeName, $data): ResourceInterface
    {
        try {
            return parent::callIncludeMethod($scope, $includeName, $data);
        } catch (Exception $exception) {
            if (config('apiato.requests.force-valid-includes', true)) {
                throw new UnsupportedFractalIncludeException($exception->getMessage());
            }
            throw new CoreInternalErrorException($exception->getMessage());
        }
    }
}
