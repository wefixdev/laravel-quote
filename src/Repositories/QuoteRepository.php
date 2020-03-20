<?php

namespace Quote\Laravel\Repositories;

use Carbon\Carbon;

class QuoteRepository
{
    public static function createCache($quotes, $currencies)
    {
        try {
            $modelClass = config('quote.model');

            $cache = new $modelClass;
            $cache->date = !empty($quotes['date']) ? $quotes['date'] : Carbon::now();
            $cache->source = $quotes['source'];
            $cache->quotes = json_encode($quotes['quotes']);
            $cache->save();

            return self::transform($cache, $currencies);
        } catch (\Exception $e) {
            throw new \Exception('Problem to save.');
        }

        return;
    }

    public static function getCache($source, $currencies, $date)
    {
        $modelClass = config('quote.model');

        $quote = $modelClass::where('source', $source)
            ->where('date', $date)
            ->first();

        if (!$quote) {
            return null;
        }

        return self::transform($quote, $currencies);
    }

    private static function transform($quote, $targetCurrencies)
    {
        $quotes = [];
        $quoteCurrencies = json_decode($quote->quotes,true);

        foreach ($targetCurrencies as $currency) {
            $quotes[$currency] =  $quoteCurrencies[$quote->source . $currency];
        }

        return [
            'date' => $quote->date,
            'source' => $quote->source,
            'quote' => $quotes
        ];
    }
}
