<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Finance;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Surakana Admin',
            'email' => 'admin@surakana.test',
            'role' => User::ROLE_ADMIN,
        ]);

        $customer = User::factory()->create([
            'name' => 'Pelanggan Demo',
            'email' => 'customer@surakana.test',
            'role' => User::ROLE_CUSTOMER,
        ]);

        Product::query()->insert([
            [
                'name' => 'Kerinci Honey',
                'description' => 'Kopi manis dengan body berlapis, cocok untuk filter dan espresso ringan.',
                'price' => 98000,
                'stock' => 24,
                'roast_level' => 'Medium Light',
                'tasting_notes' => 'Orange blossom, honey, black tea',
                'weight' => '250g',
                'image_url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Flores Bajawa Natural',
                'description' => 'Roast harian untuk brew manual dengan karakter buah matang dan cokelat.',
                'price' => 105000,
                'stock' => 18,
                'roast_level' => 'Medium',
                'tasting_notes' => 'Berry jam, cacao nibs, palm sugar',
                'weight' => '250g',
                'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Temanggung Full City',
                'description' => 'Profil lebih bold untuk penikmat body tebal, cocok untuk mokapot dan susu.',
                'price' => 89000,
                'stock' => 30,
                'roast_level' => 'Full City',
                'tasting_notes' => 'Dark chocolate, roasted almond, molasses',
                'weight' => '250g',
                'image_url' => 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=900&q=80',
                'is_active' => true,
                'is_featured' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Article::query()->insert([
            [
                'title' => 'Batch Roasting Pekan Ini',
                'slug' => 'batch-roasting-pekan-ini',
                'excerpt' => 'Catatan singkat tentang batch terbaru yang masuk ke roster minggu ini.',
                'content' => "Kami baru menyelesaikan batch honey process dari Kerinci dengan pendekatan short development untuk menjaga florality dan finish yang clean. Profil ini dirancang untuk brewer rumahan yang menyukai clarity dan sweetness.\n\nUntuk espresso, kami rekomendasikan resting minimal 7 hari agar body dan balance lebih stabil.",
                'image_url' => 'https://images.unsplash.com/photo-1459755486867-b55449bb39ff?auto=format&fit=crop&w=900&q=80',
                'author_id' => $admin->id,
                'published_at' => now()->subDays(2),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Kenapa Roast Date Itu Penting',
                'slug' => 'kenapa-roast-date-itu-penting',
                'excerpt' => 'Memahami kapan kopi siap diminum dan kapan performanya mulai turun.',
                'content' => "Roast date memberi gambaran umur kopi setelah keluar dari mesin roasting. Untuk banyak origin, jendela rasa terbaik muncul beberapa hari setelah roasting, bukan langsung pada hari yang sama.\n\nItu sebabnya setiap batch kami dikirim dengan label roast date yang jelas agar pelanggan bisa menyeduh di waktu terbaik.",
                'image_url' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=900&q=80',
                'author_id' => $admin->id,
                'published_at' => now()->subDay(),
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Finance::query()->insert([
            [
                'type' => Finance::TYPE_EXPENSE,
                'amount' => 450000,
                'description' => 'Pembelian green beans',
                'transaction_date' => now()->subDays(3)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => Finance::TYPE_EXPENSE,
                'amount' => 175000,
                'description' => 'Kemasan dan label',
                'transaction_date' => now()->subDays(1)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        collect([
            'hero_title' => 'Small batch coffee roasted with intention.',
            'hero_subtitle' => 'Surakana menghadirkan biji kopi sangrai harian untuk brewer rumah dan kedai kecil dengan fokus pada rasa bersih, konsisten, dan segar.',
            'about_text' => 'Kami adalah home roastery berskala kecil yang memanggang kopi dalam batch terbatas untuk menjaga kualitas dan fleksibilitas profiling.',
            'contact_whatsapp' => '6281234567890',
            'contact_instagram' => '@surakana.roastery',
            'faq' => "Kapan kopi dikirim? Pesanan yang masuk sebelum pukul 14.00 diproses di hari yang sama.\nApakah bisa giling? Saat ini fokus kami pada whole beans agar profil tetap stabil.",
        ])->each(function (string $value, string $key): void {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        });
    }
}
