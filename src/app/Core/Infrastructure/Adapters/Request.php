<?php

namespace App\Core\Infrastructure\Adapters;

use App\Core\Libraries\Common\RequestInterface;
use Illuminate\Http\Request as ClientRequest;
use Illuminate\Support\Arr;

class Request implements RequestInterface
{
    private $data;

    public function __construct(
        private ClientRequest $clientRequest
    ) {
        $this->data = $this->clientRequest->all();
    }

    public function getAttr(string $parameter, $default = null)
    {
        return Arr::get($this->data, $parameter, $default);
    }

    public function getBodyRequest(): array
    {
        return Arr::get($this->data, 'data.attributes');
    }

    public function getFilter(string $parameter, $default = null)
    {
        return Arr::get($this->data, 'filter.' . $parameter, $default);
    }

    public function getSize($default = null): ?int
    {
        return Arr::get($this->data, 'page.size', $default);
    }

    public function getStart($default = null): ?int
    {
        return Arr::get($this->data, 'page.start', $default);
    }
}
