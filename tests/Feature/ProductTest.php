<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase; // Garante que o banco de dados seja resetado a cada teste

    /**
     * Testa se a API retorna uma lista de produtos
     */
    public function test_it_can_list_products(): void
    {
        // Criar 5 produtos fictícios no banco
        Product::factory()->count(5)->create();

        // Chamar a API para buscar os produtos
        $response = $this->getJson('/api/products');

        // Verificar se a requisição foi bem-sucedida e contém 5 produtos
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /**
     * Testa se a API retorna um produto específico
     */
    public function test_it_can_fetch_a_single_product(): void
    {
        // Criar um produto fictício
        $product = Product::factory()->create();

        // Buscar o produto via API
        $response = $this->getJson("/api/products/{$product->code}");

        // Verificar se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'code' => (string) $product->code,
                'product_name' => $product->product_name,
            ]);
    }

    /**
     * Testa se a API retorna 404 se o produto não for encontrado
     */
    public function test_it_returns_404_if_product_not_found(): void
    {
        // Tentar buscar um produto que não existe
        $response = $this->getJson('/api/products/999999');

        // Verificar se retorna 404
        $response->assertStatus(404);
    }

    /**
     * Testa se a API consegue atualizar um produto
     */
    public function test_it_can_update_a_product(): void
    {
        // Criar um produto fictício
        $product = Product::factory()->create();

        // Dados para atualizar o produto
        $updateData = [
            'product_name' => 'Produto Atualizado',
            'quantity' => '500g',
        ];

        // Chamar a API para atualizar o produto
        $response = $this->putJson("/api/products/{$product->code}", $updateData);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'product_name' => 'Produto Atualizado',
                'quantity' => '500g',
            ]);

        // Verificar se o banco foi atualizado corretamente
        $this->assertDatabaseHas('products', [
            'code' => $product->code,
            'product_name' => 'Produto Atualizado',
        ]);
    }

    /** 
     * Testa se a API retorna 404 se tentar atualizar um produto que não existe
     */
    public function test_it_returns_404_if_trying_to_update_non_existent_product(): void
    {
        // Dados para atualização
        $updateData = [
            'product_name' => 'Produto Não Existente',
            'quantity' => '1kg',
        ];

        // Chamar a API para atualizar um produto que não existe
        $response = $this->putJson('/api/products/999999', $updateData);

        // Verificar se retorna 404
        $response->assertStatus(404);
    }
}
