<?php

namespace App\Transformer;

class StockResponseTransformer
{
    public function transform(array $data): array
    {
        return [
            "symbol" => $data['symbol'],
            "open" => $data['02. open'],
            "high" => $data['03. high'],
            "low" => $data['04. low'],
            "close" => $data['08. previous close'],
        ];
    }
}