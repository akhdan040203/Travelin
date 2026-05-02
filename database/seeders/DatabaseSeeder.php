<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Review;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // USERS
        // ==========================================
        $admin = User::create([
            'name' => 'Admin TravelGo',
            'email' => 'admin@travelgo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        $user1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081298765432',
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Sari Dewi',
            'email' => 'sari@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081356789012',
            'email_verified_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081478901234',
            'email_verified_at' => now(),
        ]);

        // ==========================================
        // CATEGORIES
        // ==========================================
        $categories = [
            ['name' => 'Pantai', 'slug' => 'pantai', 'icon' => '🏖️', 'description' => 'Wisata pantai dan laut', 'sort_order' => 1],
            ['name' => 'Gunung', 'slug' => 'gunung', 'icon' => '⛰️', 'description' => 'Wisata pendakian gunung', 'sort_order' => 2],
            ['name' => 'Budaya', 'slug' => 'budaya', 'icon' => '🏛️', 'description' => 'Wisata budaya dan sejarah', 'sort_order' => 3],
            ['name' => 'Alam', 'slug' => 'alam', 'icon' => '🌿', 'description' => 'Wisata alam dan hutan', 'sort_order' => 4],
            ['name' => 'Kota', 'slug' => 'kota', 'icon' => '🏙️', 'description' => 'Wisata kota dan urban', 'sort_order' => 5],
            ['name' => 'Adventure', 'slug' => 'adventure', 'icon' => '🧗', 'description' => 'Wisata petualangan dan olahraga', 'sort_order' => 6],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat + ['is_active' => true]);
        }

        // ==========================================
        // DESTINATIONS
        // ==========================================
        $destinations = [
            [
                'category' => 'pantai',
                'name' => 'Raja Ampat Paradise',
                'slug' => 'raja-ampat-paradise',
                'location' => 'Raja Ampat, Papua Barat',
                'province' => 'Papua Barat',
                'short_description' => 'Jelajahi keindahan bawah laut Raja Ampat yang menakjubkan dengan terumbu karang terbaik di dunia.',
                'description' => "Raja Ampat adalah surga bawah laut yang terletak di ujung barat Papua. Kepulauan ini terdiri dari empat pulau besar yaitu Waigeo, Batanta, Salawati, dan Misool beserta ratusan pulau kecil lainnya.\n\nDengan keanekaragaman hayati laut tertinggi di dunia, Raja Ampat menjadi destinasi impian bagi para penyelam dari seluruh penjuru dunia. Anda akan menemukan lebih dari 1.500 spesies ikan, 700 spesies moluska, dan 75% spesies karang yang ada di dunia.\n\nSelain diving dan snorkeling, Anda juga bisa menjelajahi pulau-pulau karst yang eksotis, berjemur di pantai pasir putih yang masih perawan, dan menyaksikan burung cendrawasih di habitat aslinya.",
                'price' => 8500000,
                'duration_days' => 5,
                'is_featured' => true,
                'itinerary' => [
                    ['day' => 'D1', 'title' => 'Tiba di Sorong', 'description' => 'Penjemputan di bandara, transfer ke pelabuhan, naik speedboat ke Waisai, check-in homestay.'],
                    ['day' => 'D2', 'title' => 'Pianemo & Bintang Laut', 'description' => 'Explore Pianemo viewpoint, snorkeling di Bintang Laut, sunset di Piaynemo.'],
                    ['day' => 'D3', 'title' => 'Diving Arborek & Manta Point', 'description' => 'Diving di Arborek, berenang dengan manta ray di Manta Point, kunjungi desa Arborek.'],
                    ['day' => 'D4', 'title' => 'Pulau Wayag', 'description' => 'Full day trip ke Pulau Wayag, hiking ke viewpoint, snorkeling, foto drone.'],
                    ['day' => 'D5', 'title' => 'Check-out & Kembali', 'description' => 'Transfer ke Waisai, speedboat ke Sorong, antar ke bandara.'],
                ],
                'included' => ['Transportasi speedboat', 'Akomodasi 4 malam homestay', 'Guide lokal berpengalaman', 'Peralatan snorkeling', 'Makan 3x sehari', 'Tiket masuk Raja Ampat Marine Park', 'Dokumentasi underwater'],
                'excluded' => ['Tiket pesawat ke Sorong', 'Asuransi perjalanan', 'Peralatan diving (bisa sewa)', 'Pengeluaran pribadi', 'Tips untuk guide'],
            ],
            [
                'category' => 'gunung',
                'name' => 'Bromo Sunrise Experience',
                'slug' => 'bromo-sunrise-experience',
                'location' => 'Probolinggo, Jawa Timur',
                'province' => 'Jawa Timur',
                'short_description' => 'Saksikan keajaiban sunrise di Gunung Bromo yang legendaris dan jelajahi lautan pasir yang luas.',
                'description' => "Gunung Bromo adalah salah satu gunung berapi paling ikonik di Indonesia. Terletak di ketinggian 2.329 meter di atas permukaan laut, Bromo menawarkan pemandangan sunrise yang spektakuler.\n\nAnda akan menyaksikan momen magis saat matahari terbit dari Pananjakan viewpoint, dengan latar belakang Gunung Semeru yang menjulang gagah. Setelah itu, Anda akan menjelajahi lautan pasir yang membentang luas dan mendaki ke bibir kawah Bromo.\n\nTrip ini cocok untuk semua level traveler, baik pemula maupun berpengalaman.",
                'price' => 1800000,
                'duration_days' => 2,
                'is_featured' => true,
                'itinerary' => [
                    ['day' => 'D1', 'title' => 'Surabaya - Cemoro Lawang', 'description' => 'Pickup dari Surabaya, drive ke Cemoro Lawang, check-in hotel, brief & persiapan.'],
                    ['day' => 'D2', 'title' => 'Sunrise & Kawah Bromo', 'description' => 'Bangun jam 3 pagi, jeep ke Pananjakan, nonton sunrise, jelajahi kawah Bromo, kembali ke Surabaya.'],
                ],
                'included' => ['Transport jeep 4x4', 'Hotel 1 malam', 'Guide lokal', 'Tiket masuk TNBTS', 'Makan 2x'],
                'excluded' => ['Tiket pesawat ke Surabaya', 'Kuda di lautan pasir (opsional)', 'Pengeluaran pribadi'],
            ],
            [
                'category' => 'pantai',
                'name' => 'Bali Island Hopping',
                'slug' => 'bali-island-hopping',
                'location' => 'Bali',
                'province' => 'Bali',
                'short_description' => 'Explore Nusa Penida, Nusa Lembongan, dan spot-spot tersembunyi Bali dalam satu paket lengkap.',
                'description' => "Bali tidak pernah berhenti memukau. Dalam paket Island Hopping ini, Anda akan mengunjungi tiga pulau sekaligus: Bali, Nusa Penida, dan Nusa Lembongan.\n\nDari tebing-tebing dramatis Kelingking Beach, snorkeling bersama manta ray di Crystal Bay, hingga sunset di Tanah Lot — semua dalam satu perjalanan yang tak terlupakan.",
                'price' => 4200000,
                'duration_days' => 4,
                'is_featured' => true,
                'itinerary' => [
                    ['day' => 'D1', 'title' => 'Tiba di Bali', 'description' => 'Pickup bandara, explore Ubud, sawah terasering Tegallalang, Tirta Empul.'],
                    ['day' => 'D2', 'title' => 'Nusa Penida', 'description' => 'Speedboat ke Nusa Penida, Kelingking Beach, Angel Billabong, Broken Beach.'],
                    ['day' => 'D3', 'title' => 'Nusa Lembongan', 'description' => 'Snorkeling Crystal Bay, Mangrove Point, Devil Tears, sunset.'],
                    ['day' => 'D4', 'title' => 'South Bali & Departure', 'description' => 'Uluwatu Temple, Padang Padang Beach, Kecak Dance, antar bandara.'],
                ],
                'included' => ['Transportasi selama trip', 'Hotel 3 malam', 'Speedboat antar pulau', 'Guide', 'Tiket masuk objek wisata', 'Makan 3x sehari'],
                'excluded' => ['Tiket pesawat', 'Aktivitas water sport', 'Pengeluaran pribadi'],
            ],
            [
                'category' => 'alam',
                'name' => 'Taman Nasional Komodo',
                'slug' => 'taman-nasional-komodo',
                'location' => 'Labuan Bajo, NTT',
                'province' => 'Nusa Tenggara Timur',
                'short_description' => 'Bertemu langsung dengan komodo dan nikmati Pink Beach yang eksotis di Labuan Bajo.',
                'description' => "Taman Nasional Komodo menyimpan keajaiban alam yang tiada tara. Di sinilah satu-satunya tempat di dunia Anda bisa melihat komodo raksasa di habitat aslinya.\n\nSelain komodo, Anda akan dimanjakan dengan pantai-pantai surga termasuk Pink Beach yang langka, Padar Island dengan viewpoint terbaik, dan spot snorkeling kelas dunia di Manta Point.",
                'price' => 5500000,
                'duration_days' => 3,
                'is_featured' => true,
                'itinerary' => [
                    ['day' => 'D1', 'title' => 'Labuan Bajo - Rinca Island', 'description' => 'Naik kapal phinisi, trekking di Rinca Island, bertemu komodo, snorkeling.'],
                    ['day' => 'D2', 'title' => 'Padar & Pink Beach', 'description' => 'Hiking Padar Island viewpoint, Pink Beach, Manta Point, Kanawa Island.'],
                    ['day' => 'D3', 'title' => 'Kelor Island & Kembali', 'description' => 'Snorkeling di Kelor Island, kembali ke Labuan Bajo, city tour.'],
                ],
                'included' => ['Kapal phinisi 2 malam', 'Guide ranger TN Komodo', 'Peralatan snorkeling', 'Makan 3x sehari', 'Tiket masuk TN Komodo'],
                'excluded' => ['Tiket pesawat ke Labuan Bajo', 'Diving equipment', 'Pengeluaran pribadi'],
            ],
            [
                'category' => 'budaya',
                'name' => 'Yogyakarta Heritage Tour',
                'slug' => 'yogyakarta-heritage-tour',
                'location' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'short_description' => 'Telusuri warisan budaya Jawa di Borobudur, Prambanan, dan Keraton Yogyakarta.',
                'description' => "Yogyakarta adalah jantung kebudayaan Jawa yang masih kental hingga saat ini. Paket ini mengajak Anda menelusuri warisan UNESCO dan menyelami kekayaan budaya yang luar biasa.\n\nDari kemegahan Candi Borobudur saat sunrise, keindahan Candi Prambanan, Keraton Sultan, hingga menikmati gudeg legendaris dan batik khas Jogja.",
                'price' => 2200000,
                'duration_days' => 3,
                'is_featured' => true,
                'itinerary' => [
                    ['day' => 'D1', 'title' => 'City Tour Yogyakarta', 'description' => 'Keraton, Taman Sari, Malioboro, kuliner lokal, batik workshop.'],
                    ['day' => 'D2', 'title' => 'Borobudur Sunrise & Prambanan', 'description' => 'Sunrise di Borobudur, wisata desa, Candi Prambanan, Ramayana Ballet.'],
                    ['day' => 'D3', 'title' => 'Jomblang Cave & Departure', 'description' => 'Cave tubing Goa Jomblang, heavenly light, oleh-oleh, antar ke bandara.'],
                ],
                'included' => ['Transportasi AC', 'Hotel 2 malam', 'Guide berlisensi', 'Tiket masuk semua objek wisata', 'Makan 2x sehari'],
                'excluded' => ['Tiket pesawat', 'Tips guide', 'Oleh-oleh pribadi'],
            ],
            [
                'category' => 'adventure',
                'name' => 'Dieng Plateau Adventure',
                'slug' => 'dieng-plateau-adventure',
                'location' => 'Wonosobo, Jawa Tengah',
                'province' => 'Jawa Tengah',
                'short_description' => 'Petualangan di dataran tinggi Dieng dengan golden sunrise dan kawah vulkanik yang menakjubkan.',
                'description' => "Dataran tinggi Dieng menawarkan pengalaman unik di ketinggian 2.000 meter. Dengan suhu yang bisa mencapai 0°C, Dieng dijuluki Negeri di Atas Awan.\n\nAnda akan menyaksikan Golden Sunrise dari Bukit Sikunir, menjelajahi kawah Sikidang yang aktif, Telaga Warna yang berubah warna, dan candi-candi Hindu tertua di Jawa.",
                'price' => 1500000,
                'duration_days' => 2,
                'is_featured' => true,
                'itinerary' => [
                    ['day' => 'D1', 'title' => 'Wonosobo - Dieng', 'description' => 'Drive ke Dieng, Telaga Warna, Kawah Sikidang, Candi Arjuna, sunset point.'],
                    ['day' => 'D2', 'title' => 'Golden Sunrise & Kembali', 'description' => 'Hiking Sikunir untuk golden sunrise, sarapan, Batu Ratapan Angin, kembali.'],
                ],
                'included' => ['Transport dari Wonosobo', 'Homestay 1 malam', 'Guide lokal', 'Tiket masuk', 'Makan 2x'],
                'excluded' => ['Transport ke Wonosobo', 'Jaket tebal (bisa sewa)', 'Pengeluaran pribadi'],
            ],
        ];

        $destModels = [];
        foreach ($destinations as $dest) {
            $catSlug = $dest['category'];
            unset($dest['category']);
            $dest['category_id'] = $catModels[$catSlug]->id;
            $dest['is_active'] = true;
            $dest['views_count'] = rand(100, 5000);
            $destModels[] = Destination::create($dest);
        }

        // ==========================================
        // SCHEDULES
        // ==========================================
        foreach ($destModels as $dest) {
            // Create 2-3 schedules per destination
            $scheduleCount = rand(2, 3);
            for ($i = 0; $i < $scheduleCount; $i++) {
                $departureDate = now()->addDays(rand(7, 60));
                Schedule::create([
                    'destination_id' => $dest->id,
                    'departure_date' => $departureDate,
                    'return_date' => $departureDate->copy()->addDays($dest->duration_days - 1),
                    'quota' => $quota = rand(15, 30),
                    'booked' => $booked = rand(0, $quota - 3),
                    'price' => null, // Use destination price
                    'meeting_point' => collect(['Bandara', 'Terminal Bus', 'Hotel Lobby', 'Stasiun Kereta'])->random(),
                    'status' => 'open',
                ]);
            }
        }

        $destBySlug = collect($destModels)->keyBy('slug');
        $fixedSchedules = [
            ['slug' => 'raja-ampat-paradise', 'days_from_now' => 10, 'quota' => 24, 'booked' => 8, 'meeting_point' => 'Bandara Domine Eduard Osok Sorong'],
            ['slug' => 'raja-ampat-paradise', 'days_from_now' => 31, 'quota' => 20, 'booked' => 4, 'meeting_point' => 'Pelabuhan Waisai'],
            ['slug' => 'bromo-sunrise-experience', 'days_from_now' => 14, 'quota' => 18, 'booked' => 9, 'meeting_point' => 'Stasiun Malang'],
            ['slug' => 'bromo-sunrise-experience', 'days_from_now' => 28, 'quota' => 22, 'booked' => 13, 'meeting_point' => 'Hotel Area Surabaya'],
            ['slug' => 'bali-island-hopping', 'days_from_now' => 17, 'quota' => 26, 'booked' => 11, 'meeting_point' => 'Bandara I Gusti Ngurah Rai'],
            ['slug' => 'taman-nasional-komodo', 'days_from_now' => 21, 'quota' => 16, 'booked' => 6, 'meeting_point' => 'Bandara Komodo Labuan Bajo'],
            ['slug' => 'yogyakarta-heritage-tour', 'days_from_now' => 24, 'quota' => 28, 'booked' => 10, 'meeting_point' => 'Stasiun Tugu Yogyakarta'],
            ['slug' => 'dieng-plateau-adventure', 'days_from_now' => 35, 'quota' => 20, 'booked' => 7, 'meeting_point' => 'Alun-Alun Wonosobo'],
        ];

        foreach ($fixedSchedules as $item) {
            $dest = $destBySlug->get($item['slug']);
            if (!$dest) continue;

            $departureDate = now()->addDays($item['days_from_now']);
            Schedule::create([
                'destination_id' => $dest->id,
                'departure_date' => $departureDate,
                'return_date' => $departureDate->copy()->addDays($dest->duration_days - 1),
                'quota' => $item['quota'],
                'booked' => $item['booked'],
                'price' => null,
                'meeting_point' => $item['meeting_point'],
                'status' => 'open',
            ]);
        }

        // ==========================================
        // SAMPLE BOOKINGS & REVIEWS
        // ==========================================
        $reviewers = [$user1, $user2, $user3];
        $comments = [
            'Trip yang luar biasa! Pemandu wisata sangat ramah dan profesional. Pasti akan kembali lagi!',
            'Pengalaman yang tak terlupakan. View-nya benar-benar memukau. Worth every penny!',
            'Akomodasi nyaman, makanan enak, destinasinya mantap. Recommended banget!',
            'Sangat puas dengan pelayanan TravelGo. Semuanya terorganisir dengan baik.',
            'Sunrise-nya absolutely stunning! Guide-nya juga very knowledgeable tentang sejarah tempat.',
            'Harga terjangkau dengan kualitas premium. Sudah 3x booking lewat TravelGo, selalu memuaskan!',
        ];

        foreach ($destModels as $index => $dest) {
            $reviewer = $reviewers[$index % count($reviewers)];
            $schedule = $dest->schedules()->first();
            if (!$schedule) continue;

            // Create a booking for this reviewer on this destination
            $booking = Booking::create([
                'booking_code' => 'TG-' . strtoupper(Str::random(8)),
                'user_id' => $reviewer->id,
                'schedule_id' => $schedule->id,
                'participants' => rand(1, 3),
                'price_per_person' => $schedule->destination->price,
                'total_price' => $schedule->destination->price * rand(1, 3),
                'contact_name' => $reviewer->name,
                'contact_phone' => $reviewer->phone,
                'contact_email' => $reviewer->email,
                'status' => 'completed',
                'payment_method' => collect(['bank_transfer', 'e_wallet', 'credit_card'])->random(),
                'paid_at' => now()->subDays(rand(10, 30)),
                'confirmed_at' => now()->subDays(rand(8, 28)),
            ]);

            Review::create([
                'user_id' => $reviewer->id,
                'destination_id' => $dest->id,
                'booking_id' => $booking->id,
                'rating' => rand(4, 5),
                'comment' => $comments[$index % count($comments)],
                'is_approved' => true,
            ]);
        }

        // ==========================================
        // FAQs
        // ==========================================
        $faqs = [
            ['question' => 'Bagaimana cara melakukan booking?', 'answer' => 'Pilih destinasi yang kamu inginkan, pilih jadwal keberangkatan, isi form booking, dan lakukan pembayaran. Kami akan mengirimkan konfirmasi melalui email.', 'sort_order' => 1],
            ['question' => 'Apa saja metode pembayaran yang tersedia?', 'answer' => 'Kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BNI, BRI), E-Wallet (GoPay, OVO, DANA), dan Kartu Kredit (Visa, Mastercard).', 'sort_order' => 2],
            ['question' => 'Apakah bisa membatalkan booking?', 'answer' => 'Ya, kamu bisa membatalkan booking maksimal 7 hari sebelum keberangkatan untuk mendapatkan refund penuh. Pembatalan 3-7 hari sebelum keberangkatan dikenakan biaya 50%. Pembatalan kurang dari 3 hari tidak bisa di-refund.', 'sort_order' => 3],
            ['question' => 'Apakah harga sudah termasuk tiket pesawat?', 'answer' => 'Tidak, harga paket biasanya belum termasuk tiket pesawat kecuali disebutkan dalam deskripsi paket. Kami bisa membantu mencarikan tiket pesawat dengan harga terbaik jika diperlukan.', 'sort_order' => 4],
            ['question' => 'Berapa minimal peserta untuk trip?', 'answer' => 'Minimal peserta tergantung paket wisata. Umumnya minimal 1 orang untuk trip reguler. Untuk private trip, silakan hubungi kami untuk request khusus.', 'sort_order' => 5],
            ['question' => 'Apakah aman untuk traveling sendiri?', 'answer' => 'Sangat aman! Semua trip kami dipandu oleh guide profesional berlisensi. Kami juga menyediakan asuransi perjalanan dan nomor darurat 24 jam.', 'sort_order' => 6],
            ['question' => 'Apa yang harus dibawa saat trip?', 'answer' => 'Kami akan mengirimkan packing list lengkap melalui email setelah booking dikonfirmasi. Biasanya yang wajib dibawa adalah pakaian nyaman, sepatu outdoor, sunscreen, obat pribadi, dan kamera.', 'sort_order' => 7],
            ['question' => 'Apakah ada diskon untuk rombongan?', 'answer' => 'Ya! Kami memberikan diskon khusus untuk group booking minimal 5 orang. Hubungi kami untuk mendapatkan penawaran terbaik.', 'sort_order' => 8],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq + ['is_active' => true]);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('   Admin: admin@travelgo.com / password');
        $this->command->info('   User:  budi@gmail.com / password');
    }
}
