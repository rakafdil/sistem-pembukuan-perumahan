<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Rumah;
use App\Models\Penghuni;
use App\Models\JenisIuran;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Services\PaymentService;
use App\Services\ReportService;
use Mockery;

class ApiV1Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_welcome_endpoint_returns_message()
    {
        $response = $this->getJson('/api');
        $response->assertStatus(200)->assertJson(['message' => 'Welcome']);
    }

    public function test_penghuni_store_and_show()
    {
        $payload = [
            'nama_lengkap' => 'Budi Santoso',
            'status_penghuni' => 'tetap',
            'status_menikah' => false,
        ];

        $store = $this->postJson('/api/v1/penghuni', $payload);
        $store->assertStatus(201)->assertJsonPath('data.nama_lengkap', 'Budi Santoso');

        $id = $store->json('data.id');
        $show = $this->getJson("/api/v1/penghuni/{$id}");
        $show->assertStatus(200)->assertJsonPath('data.nama_lengkap', 'Budi Santoso');
    }

    public function test_rumah_assign_unassign_and_histori()
    {
        $rumah = Rumah::create(['blok_nomor' => 'A1', 'status_huni' => 'kosong']);

        $penghuni = Penghuni::create([
            'nama_lengkap' => 'Andi',
            'status_penghuni' => 'tetap',
            'status_menikah' => false,
        ]);

        $assignPayload = [
            'penghuni_id' => $penghuni->id,
            'tanggal_mulai' => now()->toDateString(),
        ];

        $assign = $this->postJson("/api/v1/rumah/{$rumah->id}/assign", $assignPayload);
        $assign->assertStatus(200)->assertJson(['message' => 'Penghuni berhasil dimasukkan ke rumah ini.']);

        $histori = $this->getJson("/api/v1/rumah/{$rumah->id}/histori");
        $histori->assertStatus(200)->assertJsonStructure(['data']);

        $unassign = $this->postJson("/api/v1/rumah/{$rumah->id}/unassign", ['tanggal_selesai' => now()->addMonth()->toDateString()]);
        $unassign->assertStatus(200)->assertJson(['message' => 'Penghuni berhasil dikeluarkan dari rumah ini.']);
    }

    public function test_tagihan_generate_manual_creates_tagihan_for_dihuni_rumah()
    {
        $rumah = Rumah::create(['blok_nomor' => 'B1', 'status_huni' => 'dihuni']);

        JenisIuran::create(['nama_iuran' => 'Iuran Kebersihan', 'nominal_default' => 50000]);
        JenisIuran::create(['nama_iuran' => 'Iuran Keamanan', 'nominal_default' => 75000]);

        $resp = $this->postJson('/api/v1/tagihan/generate-manual', ['bulan' => 1, 'tahun' => 2026]);
        $resp->assertStatus(200)->assertJsonStructure(['message', 'detail']);

        $this->assertDatabaseHas('tagihan', ['rumah_id' => $rumah->id, 'periode_bulan' => 1, 'periode_tahun' => 2026]);
    }

    public function test_pembayaran_store_uses_payment_service()
    {
        // prepare models
        $rumah = Rumah::create(['blok_nomor' => 'C1', 'status_huni' => 'dihuni']);
        $penghuni = Penghuni::create(['nama_lengkap' => 'Cici', 'status_penghuni' => 'kontrak', 'status_menikah' => false]);

        $pembayaran = Pembayaran::create([
            'penghuni_id' => $penghuni->id,
            'rumah_id' => $rumah->id,
            'tanggal_bayar' => now()->toDateString(),
            'total_bayar' => 100000,
            'metode_pembayaran' => 'tunai',
        ]);

        $mock = Mockery::mock(PaymentService::class);
        $mock->shouldReceive('processPayment')->once()->andReturn($pembayaran);
        $this->app->instance(PaymentService::class, $mock);

        $payload = [
            'penghuni_id' => $penghuni->id,
            'rumah_id' => $rumah->id,
            'total_bayar' => 100000,
            'metode_pembayaran' => 'tunai',
            'detail' => [],
        ];

        $resp = $this->postJson('/api/v1/pembayaran', $payload);
        $resp->assertStatus(201)->assertJson(['message' => 'Pembayaran berhasil diproses.']);
    }

    public function test_reports_endpoints_call_report_service()
    {
        $reportMock = Mockery::mock(ReportService::class);
        $reportMock->shouldReceive('getYearlySummary')->once()->andReturn(['income' => 1000]);
        $reportMock->shouldReceive('getMonthlyDetail')->once()->andReturn(['income' => 100]);
        $this->app->instance(ReportService::class, $reportMock);

        $summary = $this->getJson('/api/v1/reports/summary?tahun=2026');
        $summary->assertStatus(200)->assertJsonPath('data.income', 1000);

        $detail = $this->getJson('/api/v1/reports/detail?bulan=1&tahun=2026');
        $detail->assertStatus(200)->assertJsonPath('data.income', 100);
    }
}
