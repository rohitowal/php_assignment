<?php

namespace Services;

use Repositories\ProductRepository;
use Services\CurrencyService;

/**
 * Class CurrencyProcessor
 * 
 * Responsible for handling product price conversion based on country and currency.
 */
class CurrencyProcessor {

    private ProductRepository $productRepository;
    private CurrencyService $currencyService;

    /**
     * CurrencyProcessor constructor with dependency injection.
     *
     * @param ProductRepository $productRepository
     * @param CurrencyService $currencyService
     */
    public function __construct(ProductRepository $productRepository, CurrencyService $currencyService)
    {
        $this->productRepository = $productRepository;
        $this->currencyService = $currencyService;
    }

    /**
     * Fetches the product list and converts product prices based on country.
     *
     * @param string $country The country name or code used to determine the target currency.
     *
     * @return array An associative array containing:
     *               - 'products': the list of products with converted prices,
     *               - 'conversionRate': the currency conversion rate,
     *               - 'currencyInfo': the currency metadata (code and symbol).
     */
    public function getConvertedProductList(string $country): array
    {
        // Get currency info and conversion rate
        $currencyInfo = $this->currencyService->getCurrencyInfo($country);
        $conversionRate = $this->currencyService->getExchangeRate('INR', $currencyInfo['code']);

        // Fetch products
        $products = $this->productRepository->getAllProducts();

        // Convert prices
        foreach ($products as &$product) {
            $product['converted_price'] = $product['price'] * $conversionRate;
        }

        return [
            'products' => $products,
            'conversionRate' => $conversionRate,
            'currencyInfo' => $currencyInfo
        ];
    }
}
