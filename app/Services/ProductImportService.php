<?php

namespace App\Services;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductImportService
{
    /**
     * Processa um arquivo JSON grande linha por linha e insere ou atualiza os produtos no banco de dados.
     * @param string $jsonFilePath
     * @param string $gzFilePath
     * @return string
     */
    public function processLargeJsonFile($jsonFilePath, $gzFilePath): string
    {
        try {
            // Abrir o arquivo JSON para leitura
            $handle = fopen($jsonFilePath, 'r');
            if (!$handle) {
                throw new \Exception("Erro ao abrir o arquivo: $jsonFilePath");
            }

            // Processar cada linha do arquivo JSON
            $count = 0;
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if (!empty($line)) {
                    $product = json_decode($line, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception("Erro ao decodificar JSON: " . json_last_error_msg());
                    }

                    if ($count < 100) {
                        // Inserir ou atualizar o produto no banco de dados
                        Product::updateOrCreate(
                            ['code' => trim($product['code'], '"')],
                            [
                                'url' => $product['url'] ?? null,
                                'creator' => $product['creator'] ?? null,
                                'created_t' => isset($product['created_t']) ? Carbon::createFromTimestamp($product['created_t']) : null,
                                'last_modified_t' => isset($product['last_modified_t']) ? Carbon::createFromTimestamp($product['last_modified_t']) : null,
                                'product_name' => $product['product_name'] ?? null,
                                'quantity' => $product['quantity'] ?? null,
                                'brands' => $product['brands'] ?? null,
                                'categories' => $product['categories'] ?? null,
                                'labels' => $product['labels'] ?? null,
                                'cities' => $product['cities'] ?? null,
                                'purchase_places' => $product['purchase_places'] ?? null,
                                'stores' => $product['stores'] ?? null,
                                'ingredients_text' => $product['ingredients_text'] ?? null,
                                'traces' => $product['traces'] ?? null,
                                'serving_size' => $product['serving_size'] ?? null,
                                'serving_quantity' => is_numeric($product['serving_quantity']) ? (float) $product['serving_quantity'] : null,
                                'nutriscore_score' => is_numeric($product['nutriscore_score']) ? (int) $product['nutriscore_score'] : null,
                                'nutriscore_grade' => $product['nutriscore_grade'] ?? null,
                                'main_category' => $product['main_category'] ?? null,
                                'image_url' => $product['image_url'] ?? null,
                                'imported_t' => now(),
                                'status' => 'published',
                            ]
                        );
                        $count++;
                    } else {
                        break;
                    }
                }
            }

            fclose($handle);

            // Excluir arquivos após o processamento
            unlink($jsonFilePath);
            unlink($gzFilePath);

            return "Importação concluída e arquivos removidos.";
        } catch (\Exception $e) {
            Log::error("Erro ao processar JSON: " . $gzFilePath . ' - ' . $e->getMessage());
            //return "Erro ao processar JSON.";
            throw $e;
        }
    }

    /**
     * Importa produtos da API do Open Food Facts e salva no banco de dados.
     * @return void
     */
    public function importProducts(): void
    {
        $baseUrl = 'https://challenges.coode.sh/food/data/json/';
        $indexUrl = $baseUrl . 'index.txt';

        try {
            // Obter a lista de arquivos do index.txt
            $response = Http::get($indexUrl);
            if ($response->failed()) {
                throw new \Exception("Erro ao acessar o index.txt");
            }

            // Transformar a resposta em um array de nomes de arquivos
            $fileNames = explode("\n", trim($response->body()));

            foreach ($fileNames as $fileName) {
                if (!empty($fileName)) {
                    $fileUrl = $baseUrl . trim($fileName);

                    // Baixar e descompactar o arquivo JSON
                    $jsonFilePath = $this->fetchAndExtractJson($fileUrl);
                    $gzFilePath = storage_path('app/public/' . trim($fileName));

                    if ($jsonFilePath) {
                        $this->processLargeJsonFile($jsonFilePath, $gzFilePath);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Erro ao importar produtos: " . $e->getMessage());
        }
    }


    /**
     * Baixa e descompacta um arquivo JSON.
     * @param string $fileUrl
     * @return string|null
     */
    public function fetchAndExtractJson($fileUrl): ?string
    {
        try {
            // Criar caminho temporário para armazenar os arquivos
            $gzFilePath = storage_path('app/public/' . basename($fileUrl));
            $jsonFilePath = str_replace('.gz', '', $gzFilePath);

            // Baixar o arquivo .gz
            $response = Http::get($fileUrl);
            if ($response->failed()) {
                throw new \Exception("Erro ao baixar: $fileUrl");
            }

            // Salvar o arquivo compactado
            File::put($gzFilePath, $response->body());

            // Descompactar o arquivo GZ
            $bufferSize = 4096;
            $gzFile = gzopen($gzFilePath, 'rb');
            $jsonFile = fopen($jsonFilePath, 'wb');

            while (!gzeof($gzFile)) {
                fwrite($jsonFile, gzread($gzFile, $bufferSize));
            }

            gzclose($gzFile);
            fclose($jsonFile);

            return $jsonFilePath;
        } catch (\Exception $e) {
            Log::error("Erro ao descompactar JSON: " . $e->getMessage());
            return null;
        }
    }
}
