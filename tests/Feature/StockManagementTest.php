<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Barang;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Notifikasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class StockManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function stock_decreases_when_order_is_created()
    {
        // Create a test item with initial stock
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 10
        ]);

        $initialStock = $barang->stok;

        // Create an order that uses 3 items
        $response = $this->post(route('pesanan.store'), [
            'nama_pembeli' => 'Test Customer',
            'no_hp' => '081234567890',
            'alamat' => 'Test Address',
            'tanggal' => now()->format('Y-m-d'),
            'status' => 'pending',
            'total_harga' => 30000,
            'barang' => [$barang->id_barang],
            'jumlah' => [3],
            'harga' => [10000]
        ]);

        $response->assertRedirect(route('pesanan.index'));
        $response->assertSessionHas('success');

        // Check that stock decreased
        $barang->refresh();
        $this->assertEquals($initialStock - 3, $barang->stok);
    }

    /** @test */
    public function stock_increases_when_order_is_deleted()
    {
        // Create a test item with initial stock
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 10
        ]);

        // Create an order
        $pesanan = Pesanan::create([
            'nama_pembeli' => 'Test Customer',
            'no_hp' => '081234567890',
            'alamat' => 'Test Address',
            'tanggal' => now(),
            'total_harga' => 30000,
            'status' => 'pending'
        ]);

        // Create order detail
        PesananDetail::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_barang' => $barang->id_barang,
            'jumlah' => 3,
            'harga' => 10000,
            'subtotal' => 30000
        ]);

        // Manually decrease stock (simulating order creation)
        $barang->decrement('stok', 3);
        $stockAfterOrder = $barang->stok;

        // Delete the order
        $response = $this->delete(route('pesanan.destroy', $pesanan->id_pesanan));

        $response->assertRedirect(route('pesanan.index'));
        $response->assertSessionHas('success');

        // Check that stock increased back
        $barang->refresh();
        $this->assertEquals($stockAfterOrder + 3, $barang->stok);
    }

    /** @test */
    public function cannot_order_more_than_available_stock()
    {
        // Create a test item with limited stock
        $barang = Barang::create([
            'nama_barang' => 'Limited Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 5
        ]);

        // Try to order more than available stock
        $response = $this->post(route('pesanan.store'), [
            'nama_pembeli' => 'Test Customer',
            'no_hp' => '081234567890',
            'alamat' => 'Test Address',
            'tanggal' => now()->format('Y-m-d'),
            'status' => 'pending',
            'total_harga' => 100000,
            'barang' => [$barang->id_barang],
            'jumlah' => [10], // More than available stock
            'harga' => [10000]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Check that stock didn't change
        $barang->refresh();
        $this->assertEquals(5, $barang->stok);
    }

    /** @test */
    public function stok_masuk_increases_item_stock()
    {
        // Create a test item
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 0
        ]);

        $initialStock = $barang->stok;

        // Add stock through StokMasuk
        $response = $this->post(route('stok.masuk.store'), [
            'id_barang' => $barang->id_barang,
            'jumlah' => 15,
            'tanggal' => now()->format('Y-m-d')
        ]);

        $response->assertRedirect(route('stok.masuk.index'));
        $response->assertSessionHas('success');

        // Check that stock increased
        $barang->refresh();
        $this->assertEquals($initialStock + 15, $barang->stok);

        // Check that StokMasuk record was created
        $this->assertDatabaseHas('stok_masuk', [
            'id_barang' => $barang->id_barang,
            'jumlah' => 15
        ]);

        // Check that notification was created
        $this->assertDatabaseHas('notifikasi', [
            'pesan' => "Stok masuk baru: {$barang->nama_barang} (+15)"
        ]);
    }

    /** @test */
    public function stok_keluar_decreases_item_stock()
    {
        // Create a test item with stock
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 20
        ]);

        $initialStock = $barang->stok;

        // Remove stock through StokKeluar
        $response = $this->post(route('stok.keluar.store'), [
            'id_barang' => $barang->id_barang,
            'jumlah' => 8,
            'tanggal' => now()->format('Y-m-d')
        ]);

        $response->assertRedirect(route('stok.keluar.index'));
        $response->assertSessionHas('success');

        // Check that stock decreased
        $barang->refresh();
        $this->assertEquals($initialStock - 8, $barang->stok);

        // Check that StokKeluar record was created
        $this->assertDatabaseHas('stok_keluar', [
            'id_barang' => $barang->id_barang,
            'jumlah' => 8
        ]);

        // Check that notification was created
        $this->assertDatabaseHas('notifikasi', [
            'pesan' => "Stok keluar baru: {$barang->nama_barang} (-8)"
        ]);
    }

    /** @test */
    public function stok_masuk_update_adjusts_stock_correctly()
    {
        // Create a test item
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 0
        ]);

        // Create initial StokMasuk entry
        $stokMasuk = StokMasuk::create([
            'id_barang' => $barang->id_barang,
            'jumlah' => 10,
            'tanggal' => now()
        ]);

        // Manually increase stock
        $barang->increment('stok', 10);

        // Update the StokMasuk entry to 15
        $response = $this->put(route('stok.masuk.update', $stokMasuk->id_masuk), [
            'id_barang' => $barang->id_barang,
            'jumlah' => 15,
            'tanggal' => now()->format('Y-m-d')
        ]);

        $response->assertRedirect(route('stok.masuk.index'));
        $response->assertSessionHas('success');

        // Check that stock was adjusted (+5 more)
        $barang->refresh();
        $this->assertEquals(15, $barang->stok);
    }

    /** @test */
    public function stok_masuk_delete_decreases_stock()
    {
        // Create a test item
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 0
        ]);

        // Create StokMasuk entry
        $stokMasuk = StokMasuk::create([
            'id_barang' => $barang->id_barang,
            'jumlah' => 12,
            'tanggal' => now()
        ]);

        // Manually increase stock
        $barang->increment('stok', 12);

        // Delete the StokMasuk entry
        $response = $this->delete(route('stok.masuk.destroy', $stokMasuk->id_masuk));

        $response->assertRedirect(route('stok.masuk.index'));
        $response->assertSessionHas('success');

        // Check that stock decreased back
        $barang->refresh();
        $this->assertEquals(0, $barang->stok);
    }

    /** @test */
    public function transaction_rollback_on_stock_error()
    {
        // Create a test item with limited stock
        $barang = Barang::create([
            'nama_barang' => 'Test Item',
            'jenis' => 'Test',
            'harga' => 10000,
            'stok' => 5
        ]);

        // Try to create an order that would cause an error
        // We'll simulate this by trying to order more than available
        $response = $this->post(route('pesanan.store'), [
            'nama_pembeli' => 'Test Customer',
            'no_hp' => '081234567890',
            'alamat' => 'Test Address',
            'tanggal' => now()->format('Y-m-d'),
            'status' => 'pending',
            'total_harga' => 60000,
            'barang' => [$barang->id_barang],
            'jumlah' => [6], // More than available
            'harga' => [10000]
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Check that no order was created
        $this->assertDatabaseMissing('pesanan', [
            'nama_pembeli' => 'Test Customer'
        ]);

        // Check that stock didn't change
        $barang->refresh();
        $this->assertEquals(5, $barang->stok);
    }
}
