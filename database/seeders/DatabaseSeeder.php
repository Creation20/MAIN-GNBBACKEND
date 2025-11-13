<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classification;
use App\Models\Stock;
use App\Models\IndexedArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Users
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        $classManager = User::create([
            'name' => 'Classifications Manager',
            'email' => 'manager@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'classifications_manager',
        ]);

        $entryManager = User::create([
            'name' => 'Entry Manager',
            'email' => 'entry@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'entry_manager',
        ]);

        // Create Classifications (Dewey Decimal System)
        $classifications = [
            ['class_number' => '000', 'description' => 'Computer science, information & general works'],
            ['class_number' => '100', 'description' => 'Philosophy & psychology'],
            ['class_number' => '200', 'description' => 'Religion'],
            ['class_number' => '300', 'description' => 'Social sciences'],
            ['class_number' => '400', 'description' => 'Language'],
            ['class_number' => '500', 'description' => 'Science'],
            ['class_number' => '600', 'description' => 'Technology'],
            ['class_number' => '700', 'description' => 'Arts & recreation'],
            ['class_number' => '800', 'description' => 'Literature'],
            ['class_number' => '900', 'description' => 'History & geography'],
            ['class_number' => '966.7', 'description' => 'Ghana - History'],
            ['class_number' => '823', 'description' => 'English fiction'],
            ['class_number' => '320.966', 'description' => 'Politics of Ghana'],
            ['class_number' => '398.2096', 'description' => 'African folklore'],
            ['class_number' => '641.5966', 'description' => 'Ghanaian cooking'],
        ];

        foreach ($classifications as $class) {
            Classification::create($class);
        }

        // Create Stock Entries
        $stocks = [
            [
                'title' => 'The Beautiful Ones Are Not Yet Born',
                'author' => 'Ayi Kwei Armah',
                'isbn' => '978-0435905200',
                'classification_id' => Classification::where('class_number', '823')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Ghana: A Political History',
                'author' => 'David Apter',
                'isbn' => '978-0691653846',
                'classification_id' => Classification::where('class_number', '966.7')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Things Fall Apart',
                'author' => 'Chinua Achebe',
                'isbn' => '978-0385474542',
                'classification_id' => Classification::where('class_number', '823')->first()->id,
                'is_gnb_stock' => false,
            ],
            [
                'title' => 'African Folklore: Traditional Stories',
                'author' => 'Various Authors',
                'isbn' => '978-1234567890',
                'classification_id' => Classification::where('class_number', '398.2096')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Introduction to Computer Science',
                'author' => 'John Smith',
                'isbn' => '978-0262033848',
                'classification_id' => Classification::where('class_number', '000')->first()->id,
                'is_gnb_stock' => false,
            ],
            [
                'title' => 'Philosophy of African Art',
                'author' => 'Peter Kwasi',
                'isbn' => '978-1234567891',
                'classification_id' => Classification::where('class_number', '100')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Ghanaian Cuisine: A Culinary Journey',
                'author' => 'Ama Serwah',
                'isbn' => '978-9988123456',
                'classification_id' => Classification::where('class_number', '641.5966')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Democracy and Development in Ghana',
                'author' => 'Kwame Boateng',
                'isbn' => '978-9988234567',
                'classification_id' => Classification::where('class_number', '320.966')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'West African Literature',
                'author' => 'Kofi Mensah',
                'isbn' => '978-9988345678',
                'classification_id' => Classification::where('class_number', '800')->first()->id,
                'is_gnb_stock' => false,
            ],
            [
                'title' => 'Traditional Ghanaian Religion',
                'author' => 'Esi Asante',
                'isbn' => '978-9988456789',
                'classification_id' => Classification::where('class_number', '200')->first()->id,
                'is_gnb_stock' => true,
            ],
        ];

        foreach ($stocks as $stock) {
            Stock::create($stock);
        }

        // Create Indexed Articles
        $articles = [
            [
                'title' => 'Ghana\'s Economic Growth: Challenges and Opportunities',
                'author' => 'Dr. Kwesi Agyeman',
                'publication' => 'Ghana Economic Review, Vol 12, Issue 3',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Traditional Medicine in Modern Ghana',
                'author' => 'Prof. Abena Osei',
                'publication' => 'Journal of African Medicine, 2023',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'The Impact of Technology on Ghanaian Education',
                'author' => 'Isaac Mensah',
                'publication' => 'Educational Technology Journal, Vol 5',
                'is_gnb_stock' => false,
            ],
            [
                'title' => 'Preserving Ghanaian Cultural Heritage',
                'author' => 'Dr. Yaa Asantewaa',
                'publication' => 'African Heritage Quarterly, 2024',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Climate Change and Agriculture in Ghana',
                'author' => 'Samuel Darko',
                'publication' => 'Agricultural Science Review, Vol 8, No 2',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Ghana\'s Colonial Legacy and Modern Identity',
                'author' => 'Dr. Kofi Annan',
                'publication' => 'Historical Studies Journal, 2023',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Women Empowerment in Contemporary Ghana',
                'author' => 'Akosua Mensah',
                'publication' => 'Gender Studies Review, Vol 15',
                'is_gnb_stock' => false,
            ],
            [
                'title' => 'The Evolution of Ghanaian Literature',
                'author' => 'Prof. Nana Ama Darko',
                'publication' => 'African Literature Today, 2024',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Urban Development in Accra: Past and Future',
                'author' => 'Emmanuel Quartey',
                'publication' => 'Urban Planning Studies, Vol 7',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'The Role of Music in Ghanaian Society',
                'author' => 'Kwame Nkrumah Jr.',
                'publication' => 'Musicology Journal, Issue 12',
                'is_gnb_stock' => false,
            ],
            [
                'title' => 'Ghana\'s Independence Movement: A Retrospective',
                'author' => 'Dr. Akua Serwah',
                'publication' => 'Political History Review, 2023',
                'is_gnb_stock' => true,
            ],
            [
                'title' => 'Digital Transformation in Ghanaian Banking',
                'author' => 'Yaw Boateng',
                'publication' => 'Banking & Finance Quarterly, Vol 4',
                'is_gnb_stock' => true,
            ],
        ];

        foreach ($articles as $article) {
            IndexedArticle::create($article);
        }
    }
}
