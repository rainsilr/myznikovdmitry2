<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_admin_panel(): void
    {
        $this->get('/admin/reports')->assertRedirect('/login');
    }

    public function test_regular_user_cannot_open_admin_panel(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get('/admin/reports')
            ->assertForbidden();
    }

    public function test_admin_can_see_all_reports_and_filter_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['fio' => 'Петров Петр Петрович']);
        $category = Category::create(['name' => 'Незаконная парковка']);

        $this->makeReport($user, $category, 'Новая заявка', 'new');
        $this->makeReport($user, $category, 'Отклоненная заявка', 'rejected');

        $response = $this->actingAs($admin)->get('/admin/reports?status=rejected');

        $response->assertStatus(200);
        $response->assertSee('Отклоненная заявка');
        $response->assertSee('Петров Петр Петрович');
        $response->assertDontSee('Новая заявка');
    }

    public function test_admin_can_update_report_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Открытые люки и опасные участки']);
        $report = $this->makeReport($user, $category, 'Опасный люк', 'new');

        $response = $this->actingAs($admin)->patch("/admin/reports/{$report->id}/status", [
            'status' => 'resolved',
        ]);

        $response->assertRedirect('/admin/reports');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'resolved',
        ]);
    }

    private function makeReport(User $user, Category $category, string $title, string $status): Report
    {
        return $user->reports()->create([
            'category_id' => $category->id,
            'title' => $title,
            'description' => 'Описание заявки.',
            'status' => $status,
            'date_incident' => '2026-06-27',
            'contact' => 'email',
        ]);
    }
}
