<?php

namespace App\Console\Commands;

use App\Services\ProductImportService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa produtos da Api do Open Food Facts e salva no banco de dados';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle() : void
    {
        $this->info('Iniciando importação de produtos...');

        try {
            $importService = new ProductImportService();
            $importService->importProducts();
            Cache::put('last_cron_execution', now(), 86400);
            $this->info('Importação concluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao importar produtos: ' . $e->getMessage());
            $this->error('Erro ao importar Produtos.');
        }
    }
}
