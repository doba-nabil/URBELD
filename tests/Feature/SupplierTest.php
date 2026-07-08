<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CompanyClassification;
use App\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_as_supplier()
    {
        $classification = CompanyClassification::create([
            'name' => 'متوسط',
            'type' => 'supplier'
        ]);

        $response = $this->post('/register', [
            'name' => 'شركة المورد',
            'email' => 'supplier@example.com',
            'phone' => '0500000000',
            'password' => 'password',
            'password_confirmation' => 'password',
            'membership_type' => 'supplier',
            'classification_id' => $classification->id,
            'terms' => 'on',
        ]);

        $response->assertRedirect('/login'); // Assuming it redirects to login or dashboard
        $this->assertDatabaseHas('users', [
            'email' => 'supplier@example.com',
            'provider_type' => 'supplier',
            'classification_id' => $classification->id,
        ]);
    }

    public function test_supplier_can_access_products_page()
    {
        $supplier = User::factory()->create([
            'user_type' => 'service_provider',
            'provider_type' => 'supplier',
        ]);

        Membership::create([
            'user_id' => $supplier->id,
            'type' => 'supplier',
            'is_active' => true,
        ]);

        $response = $this->actingAs($supplier)->get(route('supplier.products.index'));

        $response->assertStatus(200);
        $response->assertSee('المنتجات');
    }
}
