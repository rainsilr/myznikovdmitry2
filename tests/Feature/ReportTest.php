<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_report_create_page(): void
    {
        $this->get('/reports/create')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_report(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Ямы на дорогах']);

        $response = $this->actingAs($user)->post('/reports', [
            'title' => 'Яма возле дома',
            'category_id' => $category->id,
            'description' => 'На дороге появилась глубокая яма.',
            'date_incident' => '27.06.2026',
            'contact' => 'email',
        ]);

        $response->assertRedirect('/reports/create');
        $this->assertDatabaseHas('reports', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Яма возле дома',
            'status' => 'new',
            'date_incident' => '2026-06-27',
            'contact' => 'email',
        ]);
    }
}
