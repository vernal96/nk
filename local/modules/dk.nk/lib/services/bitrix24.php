<?php

namespace DK\NK\Services;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Web\HttpClient;

class Bitrix24
{

    use Services;

    protected static self $instance;

    public array $batchCMD = [];
    public array $batchResult;
    public string $botClientId;
    public string $botUrl;
    private int $batchHalt = 0;
    private HttpClient $httpClient;
    private string $url;
    private string $innerToken;

    public function __construct()
    {
        $this->url = Option::get(NK_MODULE_NAME, "BX24_HOOK");
        $this->innerToken = Option::get(NK_MODULE_NAME, "BX24_INNER_TOKEN");
        $this->botClientId = Option::get(NK_MODULE_NAME, "BX24_BOT_ID");
        $this->botUrl = Option::get(NK_MODULE_NAME, "BX24_BOT_HOOK");
        $this->httpClient = new HttpClient();
    }

    public static function getInstance(): self
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function query($method, $data = [], $resultSelect = null): array
    {
        $this->httpClient->post($this->url . $method, $data);
        return self::parseResult($this->httpClient->getResult(), $resultSelect);
    }

    public function queryBot($method, $data = [], $resultSelect = null): array
    {
        $this->httpClient->post($this->botUrl . $method, $data);
        return self::parseResult($this->httpClient->getResult(), $resultSelect);
    }

    /** @noinspection PhpUnused */
    public function setBatchHalt(int $halt): void
    {
        $this->batchHalt = $halt;
    }

    public function batchAdd(string $varName, string $method, $fields, $order = 50): void
    {
        $this->batchCMD[] = [
            "order" => $order,
            "var" => $varName,
            "data" => $method . "?" . http_build_query($fields)
        ];
    }

    public function batchCall(): void
    {
        $cmd = $this->batchCMD;
        usort($cmd, fn($a, $b) => $a["order"] - $b["order"]);
        $batchCmd = [];
        foreach ($cmd as $value) {
            $batchCmd[$value["var"]] = $value["data"];
        }
        $chunks = array_chunk($batchCmd, 50, true);
        foreach ($chunks as $chunk) {
            $this->httpClient->post($this->url . "batch", [
                "halt" => $this->batchHalt,
                "cmd" => $chunk
            ]);
            $this->batchResult[] = self::parseResult($this->httpClient->getResult());
        }
    }

    /** @noinspection PhpUnused */
    public function batchClear(): void
    {
        $this->batchCMD = [];
    }
}