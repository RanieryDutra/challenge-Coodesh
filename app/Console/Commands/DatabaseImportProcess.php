<?php

namespace App\Console\Commands;

use App\Models\CronLog;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseImportProcess extends Command
{
    protected $signature = 'process:database-import';
    protected $description = 'Importacao do JSON.gz para salvar os dados no banco de dados';

    public function handle()
    {
        $txtUrl = 'https://challenges.coode.sh/food/data/json/index.txt';

        $txtContent = Http::get($txtUrl)->body();

        $lines = explode("\n", $txtContent);

        foreach ($lines as $line) {
            if (!empty(trim($line))) {
                $fileUrl = "https://challenges.coode.sh/food/data/json/{$line}";

                $response = Http::get($fileUrl);

                $gzFilePath = storage_path("app/temp/{$line}");
                File::put($gzFilePath, $response->body());

                $jsonFilePath = storage_path("app/temp/" . basename($line, ".gz"));
                $gz           = gzopen($gzFilePath, 'rb');
                $jsonContent  = '';

                while (!gzeof($gz)) {
                    $jsonContent .= gzread($gz, 1024);
                }

                gzclose($gz);

                File::put($jsonFilePath, $jsonContent);

                $this->processJsonFile($jsonFilePath);

                File::delete($gzFilePath);
                File::delete($jsonFilePath);
            }
        }
        CronLog::query()->create([
            'command_name' => 'process:json-files',
            'executed_at'  => Carbon::now(),
        ]);

        $this->info('Processing Completed');
    }

    protected function processJsonFile($filePath)
    {
        $file       = fopen($filePath, 'r');
        $lineNumber = 0;

        while (!feof($file) && $lineNumber < config('coodesh.import_limit_file_lines')) {
            $line     = fgets($file);
            $dataJson = json_decode($line, true);
            $data     = [
                'code'             => $dataJson['code'],
                'status'           => "published",
                'imported_t'       => now(),
                'url'              => $dataJson['url'],
                'creator'          => $dataJson['creator'],
                'created_t'        => Carbon::createFromTimestamp($dataJson['created_t']),
                'last_modified_t'  => Carbon::createFromTimestamp($dataJson['last_modified_t']),
                'product_name'     => $dataJson['product_name'],
                'quantity'         => empty($dataJson['quantity']) ? 0 : intval($dataJson['quantity']),
                'brands'           => $dataJson['brands'],
                'categories'       => $dataJson['categories'],
                'labels'           => $dataJson['labels'],
                'cities'           => $dataJson['cities'],
                'purchase_places'  => $dataJson['purchase_places'],
                'stores'           => $dataJson['stores'],
                'ingredients_text' => $dataJson['ingredients_text'],
                'traces'           => $dataJson['traces'],
                'serving_size'     => $dataJson['serving_size'],
                'serving_quantity' => empty($dataJson['serving_quantity']) ? 0 : $dataJson['serving_quantity'],
                'nutriscore_score' => empty($dataJson['nutriscore_score']) ? 0 : $dataJson['nutriscore_score'],
                'nutriscore_grade' => $dataJson['nutriscore_grade'],
                'main_category'    => $dataJson['main_category'],
                'image_url'        => $dataJson['image_url'],
            ];

            if ($data) {
                Product::query()->updateOrInsert(
                    ['code' => $data['code']],
                    $data
                );
            }

            $lineNumber++;
        }

        fclose($file);
    }
}
