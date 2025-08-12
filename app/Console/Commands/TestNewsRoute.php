<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NewsController;
use App\Services\NewsApiService;

class TestNewsRoute extends Command
{
    protected $signature = 'test:news-route';
    protected $description = 'Test the news route to see if it\'s working correctly';

    public function handle()
    {
        $this->info('Testing news route...');
        
        try {
            $newsApiService = app(NewsApiService::class);
            $controller = new NewsController($newsApiService);
            $response = $controller->index();
            
            $this->info('Response type: ' . get_class($response));
            
            // Para respostas Inertia, vamos verificar os dados
            if (method_exists($response, 'getData')) {
                $data = $response->getData();
                $this->info('Response data keys: ' . implode(', ', array_keys($data)));
                
                if (isset($data->news)) {
                    $this->info('News data found!');
                    $this->info('Success: ' . ($data->news->success ? 'true' : 'false'));
                    $this->info('Articles count: ' . count($data->news->articles ?? []));
                    
                    if (!empty($data->news->articles)) {
                        $this->info('First article title: ' . $data->news->articles[0]->title);
                    }
                } else {
                    $this->error('No news data found in response');
                }
            } else {
                // Para respostas Inertia, vamos tentar acessar os dados de outra forma
                $this->info('Trying to access Inertia response data...');
                
                // Verificar se é uma resposta Inertia
                if (method_exists($response, 'toResponse')) {
                    $this->info('This is an Inertia response');
                    
                    // Vamos verificar se há dados sendo passados
                    $reflection = new \ReflectionClass($response);
                    if ($reflection->hasProperty('props')) {
                        $propsProperty = $reflection->getProperty('props');
                        $propsProperty->setAccessible(true);
                        $props = $propsProperty->getValue($response);
                        
                        $this->info('Inertia props: ' . json_encode(array_keys($props)));
                        
                        if (isset($props['news'])) {
                            $this->info('News data in Inertia props!');
                            $this->info('Success: ' . ($props['news']['success'] ? 'true' : 'false'));
                            $this->info('Articles count: ' . count($props['news']['articles'] ?? []));
                        }
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}
