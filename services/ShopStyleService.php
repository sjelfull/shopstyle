<?php
/**
 * ShopStyle plugin for Craft CMS
 *
 * ShopStyle Service
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   ShopStyle
 * @since     1.0.0
 */

namespace Craft;

use Guzzle\Http\Client;

class ShopStyleService extends BaseApplicationComponent
{
    protected $client;

    public function init ()
    {
        parent::init();

        $this->client = new \Guzzle\Http\Client();

        $this->client->setDefaultOption('query', [
            'pid'   => craft()->config->get('pid', 'shopstyle'),
            'fl'    => 'd0',
            'limit' => 50,
            'sort'  => 'Recency'
        ]);
    }

    public function getBatchResults ()
    {
        $maxResults   = (int)craft()->config->get('maxResults', 'shopstyle');
        $increment    = 50;
        $perBatch     = 10;
        $firstResults = $this->getResult();

        $products = [ ];

        if ( isset($firstResults['metadata']['total']) ) {
            $total = (int)$firstResults['metadata']['total'];

            if ( $total < $maxResults ) {
                $maxResults = $total;
            }

            // Add first products
            $products = array_merge($products, $firstResults['products']);

            $page             = 0;
            $totalPageResults = 0;
            $pageCount        = (int)$maxResults / $increment;
            $offsets          = [ ];

            //var_dump($pageCount);

            while ($page < $pageCount && $totalPageResults < $maxResults) {
                $page             = $page + 1;
                $offsets[]        = $page * $increment;
                $totalPageResults = $totalPageResults + ($increment);
            }

            if ( !empty($offsets) ) {
                $offsetBatches = array_chunk($offsets, $perBatch);

                foreach ($offsetBatches as $batch) {
                    $batchProducts = $this->getBatch($batch);

                    if ( $batchProducts ) {
                        $products = array_merge($products, $batchProducts);
                    }
                }
            }
        }

        return $products;
    }

    public function getBatch ($offsets = [ ])
    {
        $endpoint = 'http://api.shopstyle.co.uk/api/v2/products';
        $products = [ ];

        try {
            $requests = [ ];

            foreach ($offsets as $offset) {
                $query      = [
                    'offset' => $offset,
                ];
                $requests[] = $this->client->get($endpoint, [ ], [
                    'query' => $query,
                ]);
            }

            $responses = $this->client->send($requests);

            foreach ($responses as $response) {
                $data = $response->json();

                if ( isset($data['products']) ) {
                    $products = array_merge($products, $data['products']);
                }
            }
        }
        catch (\MultiTransferException $e) {

            foreach ($e->getSuccessfulRequests() as $request) {
                $response = $request->getResponse();
                $data     = $response->json();

                if ( isset($data['products']) ) {
                    $products = array_merge($products, $data['products']);
                }
            }

            ShopStylePlugin::log(Craft::t('Could not parse API response as JSON: {error}', [ 'error' => $e->getMessage() ]), LogLevel::Error);
        }

        if ( empty($products) ) {
            return null;
        }

        return $products;
    }

    public function getResult ($offset = 0)
    {
        $endpoint = 'http://api.shopstyle.co.uk/api/v2/products';

        try {
            $query    = [
                'offset' => $offset,
            ];
            $request  = $this->client->get($endpoint, [ ], [
                'query' => $query,
            ]);
            $response = $request->send();

            return $response->json();
        }
        catch (\Exception $e) {
            ShopStylePlugin::log(Craft::t('Could not parse API response as JSON: {error}', [ 'error' => $e->getMessage() ]), LogLevel::Error);
        }
    }

}
