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
                'date' => '01/01/23',
                'vendor' => 'Legal Deposits',
                'matForm' => 'HardCopy',
                'matType' => 'newspaper',
                'contentDesc' => 'Adult',
                'nonFictionType' => 'Novels',
                'title' => 'The Beautiful Ones Are Not Yet Born',
                'author' => 'Ayi Kwei Armah',
                'isbn' => '978-0435905200',
                'copyNo' => '3',
                'accessionNo' => 'A12',
                'areaOfResponsibility' => 'Books',
                'editionStatement' => '1',
                'publishersName' => 'Kofi Mensah',
                'placeOfPublication' => 'Accra',
                'yearOfPublication' => '2001',
                'preliminaryPages' => '2',
                'numberOfPages' => '200',
                'heightOfBook' => '40cm',
                'poBox' => 'C-5 33',
                'poBoxLocation' => 'c-5 33 Accra east',
                'telephone' => '+233 2485433377',
                'email' => 'dontcallme@gmail.com',
                'website' => 'keepquite.com',
                'illustrations' => 'no',
                'subject' => 'Math',
                'publisher' => 'Me',
                'classification_id' => Classification::where('class_number', '823')->first()->id,
                'is_gnb_stock' => true,
            ],
            [
                'date' => '01/01/25',
                'vendor' => 'Legal Deposits',
                'matForm' => 'HardCopy',
                'matType' => 'newspaper',
                'contentDesc' => 'Adult',
                'nonFictionType' => 'Tales',
                'title' => 'The Ugly Ones need lashes',
                'author' => 'Ayi Kwei Armah',
                'isbn' => '978-043596789',
                'copyNo' => '3',
                'accessionNo' => 'A12',
                'areaOfResponsibility' => 'Guns',
                'editionStatement' => '1',
                'publishersName' => 'Kofi Mensah',
                'placeOfPublication' => 'Accra',
                'yearOfPublication' => '2003',
                'preliminaryPages' => '2',
                'numberOfPages' => '200',
                'heightOfBook' => '60cm',
                'poBox' => 'C-5 33',
                'poBoxLocation' => 'c-5 33 Accra east',
                'telephone' => '+233 2485433377',
                'email' => 'dontcallme@gmail.com',
                'website' => 'keepquite.com',
                'illustrations' => 'no',
                'subject' => 'Math',
                'publisher' => 'You',
                'classification_id' => Classification::where('class_number', '823')->first()->id,
                'is_gnb_stock' => true,
            ],
        ];

        foreach ($stocks as $stock) {
            Stock::create($stock);
        }

        // Create Indexed Articles
        $articles = [
            [
                'date' => '01/04/25',
                'contentDesc' => 'adult',
                'numberOfPages' => '60',
                'matType'=> 'HardCopy',
                'title' => 'Ghana\'s Economic Growth: Challenges and Opportunities',
                'writersDetails' => 'Dr. Kwesi Agyeman',
                'newspaperJournalMagazineName' => 'African Medicine, 2023',
                'issn' => '1923127479124790',
                'poBox' => '9945-5 street',
                'poBoxLocation' => '9945-5 street accra ghana',
                'email' => 'icantthinks@gmail.com',
                'telephone' => '+233456578940',
                'email' => 'apple@gmail.com',
                'articleOrNot' => 'yes',
                'website' => 'dogman.com',
            ],
            [
                'date' => '01/01/25',
                'contentDesc' => 'adult',
                'numberOfPages' => '60',
                  'matType'=> 'HardCopy',
                'articleOrNot' => 'yes',
                'title' => 'Ghana\'s Economic Growth: Challenges and Opportunities',
                'writersDetails' => 'Dr. Kwesi Agyeman',
                'newspaperJournalMagazineName' => 'African Medicine, 2023',
                'issn' => '1923127479124790',
                'poBox' => '9945-5 street',
                'poBoxLocation' => '9945-5 street accra ghana',
                'email' => 'icantthinks@gmail.com',
                'telephone' => '+233456578900',
                'email' => 'apple@gmail.com',
                'website' => 'dogman.com',
            ],
        ];

        foreach ($articles as $article) {
            IndexedArticle::create($article);
        }
    }
}
