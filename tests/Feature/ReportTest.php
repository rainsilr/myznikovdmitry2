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

    public function test_user_sees_only_own_reports(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::create(['name' => 'Мусор и свалки']);

        $ownReport = $user->reports()->create([
            'category_id' => $category->id,
            'title' => 'Мусор у подъезда',
            'description' => 'Контейнеры переполнены.',
            'date_incident' => '2026-06-27',
            'contact' => 'email',
        ]);

        $otherUser->reports()->create([
            'category_id' => $category->id,
            'title' => 'Чужая заявка',
            'description' => 'Эту заявку видеть нельзя.',
            'date_incident' => '2026-06-27',
            'contact' => 'sms',
        ]);

        $response = $this->actingAs($user)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee($ownReport->title);
        $response->assertSee('Новое');
        $response->assertDontSee('Чужая заявка');
    }

    public function test_user_can_leave_feedback_for_resolved_report(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Повреждение тротуара']);
        $report = $user->reports()->create([
            'category_id' => $category->id,
            'title' => 'Сломан тротуар',
            'description' => 'Плитка разрушена.',
            'status' => 'resolved',
            'date_incident' => '2026-06-27',
            'contact' => 'email',
        ]);

        $response = $this->actingAs($user)->post("/reports/{$report->id}/feedback", [
            'feedback' => 'Работы выполнены хорошо.',
        ]);

        $response->assertRedirect('/reports');
        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'feedback' => 'Работы выполнены хорошо.',
        ]);
    }

    public function test_user_cannot_leave_feedback_for_unresolved_report(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Неисправное освещение']);
        $report = $user->reports()->create([
            'category_id' => $category->id,
            'title' => 'Фонарь не работает',
            'description' => 'Не горит фонарь.',
            'status' => 'new',
            'date_incident' => '2026-06-27',
            'contact' => 'email',
        ]);

        $this->actingAs($user)
            ->post("/reports/{$report->id}/feedback", ['feedback' => 'Рано оценивать.'])
            ->assertForbidden();
    }
}
