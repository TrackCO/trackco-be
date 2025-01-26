<?php
namespace App\Support\Traits;
use Illuminate\Support\Facades\Log;

trait CalculatorTrait
{
    private static $countryEmissionDataset = 'lot-emission-dataset.json';
    private static $datasetCache = null;

    private function searchCountryInDataset($countryName)
    {
        // Log the country name being searched
        Log::info("Searching for country: $countryName");

        // Load the dataset
        $dataset = self::getDatasetFromJson();

        // Use `array_filter` for better performance
        $result = array_filter($dataset, fn($item) => $item['country'] === $countryName);

        if ($result) {
            Log::info("Found country data: ", reset($result));
        } else {
            Log::warning("Country not found: $countryName");
        }

        // Return the first matching result (if any)
        return reset($result) ?: null;
    }

    private static function getDatasetFromJson()
    {
        // Check if the dataset is already cached
        if (is_null(self::$datasetCache)) {
            $filePath = public_path(self::$countryEmissionDataset);

            if (!file_exists($filePath)) {
                Log::error("File not found: $filePath");
                throw new \RuntimeException("Dataset file not found.");
            }

            // Read and decode the file
            $json_data = file_get_contents($filePath);
            self::$datasetCache = json_decode($json_data, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Invalid JSON format in dataset file.");
                throw new \RuntimeException("Invalid JSON format.");
            }

            Log::info("Dataset successfully loaded from: $filePath");
        }

        return self::$datasetCache;
    }

    private function calculateMonthDifference(int $start, int $end): int
    {
        $monthDiff = $end >= $start ? ($end - $start) + 1 : (12 - $start) + $end;

        // Log the calculated month difference
        Log::debug("Calculated month difference: $monthDiff (Start: $start, End: $end)");

        return $monthDiff;
    }
}

