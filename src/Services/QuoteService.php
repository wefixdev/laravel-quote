<?php

namespace Quote\Laravel\Services;

use Carbon\Carbon;
use Quote\Laravel\Repositories\QuoteRepository;
use Quote\Quote;

class QuoteService
{
    protected $key;
    protected $cache;
    protected $quoteClass;

    public function __construct($key, $cache)
    {
        $this->key = $key;
        $this->cache = $cache;
        $this->quoteClass = new Quote($this->key);
    }

    public function live($params)
    {
        return $this->quote('live', $params);
    }

    public function historical(\DateTime $date, $params)
    {
        $params['date'] = $date->format('Y-m-d');

        return $this->quote('historical', $params);
    }

    private function quote($type, $params = [])
    {
        $quotes = null;

        if ($this->cache) {
            $source = 'USD';
            $currencies = ['BRL'];
            $date = Carbon::now();


            if (array_key_exists('source', $params)) {
                $source = $params['source'];
            }

            if (array_key_exists('date', $params)) {
                $date = $params['date'];
            }

            if (array_key_exists('currencies', $params)) {
                $currencies = $params['currencies'];
            }

            $quotes = QuoteRepository::getCache($source, $currencies, $date);
        }

        if (empty($quotes)) {
            $quotes = $this->quoteClass->{$type}($params);
            $quotes = QuoteRepository::createCache($quotes, $currencies);
        }

        return $quotes;
    }
}
