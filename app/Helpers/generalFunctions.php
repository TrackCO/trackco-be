<?php

use App\Exceptions\ClientErrorException;
use Illuminate\Support\Facades\Log;

const COUNTRY_EMISSION_DATASET = 'lot-emission-dataset.json';

/**
 * Generate random characters...
 * @param int $chars
 * @return string
 */
function generateRandomCharacters(int $chars = 10): string
{
    $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    return substr(str_shuffle($letters), 0, $chars).time();
}

/**
 *
 * @return mixed
 * @throws ClientErrorException
 */
function getDatasetFromJson(): mixed
{
    $json_data = file_get_contents(asset(COUNTRY_EMISSION_DATASET));
    $array_data = json_decode($json_data, true);

    // Check if the data was decoded correctly
    if (json_last_error() === JSON_ERROR_NONE) return $array_data;

    throw new ClientErrorException('Unable to complete your request at the moment.');
}

/**
 * @param string $countryName
 * @return mixed|null
 * @throws ClientErrorException
 */
function getCountryAverageByName(string $countryName): mixed
{
    try{
        $countryDataset = getDatasetFromJson();
        foreach ($countryDataset as $item) {
            if ($item['country'] == $countryName) return $item;
        }
    }catch (ClientErrorException $e) {
        Log::error($e->getMessage());
    }

    return null;
}
