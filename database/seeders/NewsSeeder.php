<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = [
            [
                'title' => 'Nova Tecnologia Revoluciona o Mercado de Energia Solar',
                'content' => 'Uma nova tecnologia de painéis solares desenvolvida por pesquisadores brasileiros promete aumentar a eficiência energética em até 40%. A inovação utiliza materiais orgânicos que são mais baratos e sustentáveis que os painéis tradicionais. A descoberta foi publicada na revista Nature Energy e já está sendo testada em parceria com empresas do setor energético.',
                'author' => 'Maria Silva',
                'published_at' => now()->subDays(2),
                'source' => 'TechNews Brasil',
                'url' => 'https://example.com/tecnologia-solar'
            ],
            [
                'title' => 'Crise Climática: Cientistas Alertam sobre Impactos no Brasil',
                'content' => 'Relatório divulgado por cientistas brasileiros revela que as mudanças climáticas podem ter impactos devastadores na agricultura do país nos próximos 30 anos. O estudo analisou dados de temperatura e precipitação de 50 anos e projetou cenários futuros. As regiões Nordeste e Centro-Oeste são as mais vulneráveis, segundo os pesquisadores.',
                'author' => 'João Santos',
                'published_at' => now()->subDays(5),
                'source' => 'Ciência Hoje',
                'url' => 'https://example.com/crise-climatica'
            ],
            [
                'title' => 'Startup Brasileira Desenvolve App para Combater Fake News',
                'content' => 'Uma startup de São Paulo criou um aplicativo que utiliza inteligência artificial para identificar e combater notícias falsas em tempo real. O app analisa o conteúdo de textos e imagens, comparando com fontes confiáveis. A ferramenta já foi testada por mais de 10 mil usuários e apresentou 95% de precisão na detecção de fake news.',
                'author' => 'Ana Costa',
                'published_at' => now()->subDays(1),
                'source' => 'Inovação Digital',
                'url' => 'https://example.com/app-fake-news'
            ],
            [
                'title' => 'Descoberta Arqueológica Revela História Antiga da Amazônia',
                'content' => 'Arqueólogos descobriram vestígios de uma civilização antiga na região amazônica que pode ter existido há mais de 3 mil anos. As escavações revelaram estruturas complexas, cerâmicas elaboradas e evidências de agricultura avançada. A descoberta muda a compreensão sobre o povoamento da região e sugere que a Amazônia abrigou sociedades mais complexas do que se pensava.',
                'author' => 'Carlos Mendes',
                'published_at' => now()->subDays(3),
                'source' => 'Arqueologia Brasil',
                'url' => 'https://example.com/arqueologia-amazonia'
            ],
            [
                'title' => 'Vacina contra COVID-19: Novos Estudos Mostram Eficácia',
                'content' => 'Pesquisadores brasileiros publicaram novos estudos sobre a eficácia das vacinas contra COVID-19 em diferentes faixas etárias. Os resultados mostram que a proteção se mantém alta mesmo após 6 meses da aplicação. O estudo acompanhou mais de 50 mil pessoas e foi publicado em revista científica internacional.',
                'author' => 'Dr. Roberto Lima',
                'published_at' => now()->subDays(4),
                'source' => 'Saúde Pública',
                'url' => 'https://example.com/vacina-covid'
            ],
            [
                'title' => 'Economia Digital: Brasil Avança em Inclusão Financeira',
                'content' => 'Relatório do Banco Central mostra que o Brasil registrou crescimento recorde na inclusão financeira digital em 2024. Mais de 80% da população adulta já possui conta bancária digital, e o uso de pagamentos por PIX cresceu 150% em relação ao ano anterior. O avanço foi impulsionado pela pandemia e pela popularização dos smartphones.',
                'author' => 'Fernanda Oliveira',
                'published_at' => now()->subDays(6),
                'source' => 'Economia Digital',
                'url' => 'https://example.com/economia-digital'
            ],
            [
                'title' => 'Esporte: Seleção Brasileira Anuncia Novos Jogadores',
                'content' => 'O técnico da seleção brasileira anunciou a convocação de novos jogadores para os próximos jogos das eliminatórias. Entre os nomes estão jovens promessas que se destacaram no campeonato brasileiro. A lista inclui três atacantes, dois meias e um zagueiro, todos com menos de 23 anos.',
                'author' => 'Pedro Almeida',
                'published_at' => now()->subDays(7),
                'source' => 'Esporte Total',
                'url' => 'https://example.com/selecao-brasileira'
            ],
            [
                'title' => 'Educação: Novo Método de Ensino Reduz Evasão Escolar',
                'content' => 'Uma escola pública de Fortaleza implementou um novo método de ensino baseado em projetos e tecnologia, resultando em redução de 60% na evasão escolar. O projeto, que utiliza gamificação e ensino híbrido, foi desenvolvido em parceria com universidades locais e já está sendo replicado em outras escolas do estado.',
                'author' => 'Lucia Ferreira',
                'published_at' => now()->subDays(8),
                'source' => 'Educação Hoje',
                'url' => 'https://example.com/educacao-fortaleza'
            ]
        ];

        foreach ($news as $article) {
            News::create($article);
        }
    }
}
