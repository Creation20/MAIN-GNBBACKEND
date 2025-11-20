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
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Classifications Manager',
            'email' => 'manager@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'classifications_manager',
        ]);

        User::create([
            'name' => 'Entry Manager',
            'email' => 'entry@ghla.com',
            'password' => Hash::make('password123'),
            'role' => 'entry_manager',
        ]);

        // Create Classifications
        $class1 = Classification::create([
            'class_number' => '823',
            'isbn' => '837487423748283',
            'subject' => 'English Literature'
        ]);

        $class2 = Classification::create([
            'class_number' => '100',
            'isbn' => '3482684882848',
            'subject' => 'Philosophy'
        ]);

        // Create Stock Entries with new fields
        Stock::create([
            'date' => '2023-01-01',
            'vendor' => 'Legal Deposits',
            'matForm' => 'HardCopy',
            'matType' => 'Book',
            'contentDesc' => 'Adult Fiction',
            'nonFictionType' => '',
            'title' => 'The Beautiful Ones Are Not Yet Born',
            'author' => 'Ayi Kwei Armah',
            'isbn' => '978-0435905200',
            'gnb' => 'GNB2023001',
            'copyNo' => '3',
            'accessionNo' => 'A12',
            'areaOfResponsibility' => 'Books',
            'editionStatement' => '1',
            'publishersName' => 'Heinemann',
            'placeOfPublication' => 'Accra',
            'yearOfPublication' => '2001',
            'preliminaryPages' => '2',
            'numberOfPages' => '200',
            'heightOfBook' => '40cm',
            'poBox' => 'C-5 33',
            'poBoxLocation' => 'C-5 33 Accra East',
            'telephone' => '+233 2485433377',
            'email' => 'publisher@example.com',
            'website' => 'https://example.com',
            'illustrations' => 'no',
            'subject' => 'English Literature',
            'classification_id' => $class1->id,
            'class_number' => '823',
            'sysOfClass' => 'DDC',
            'is_gnb_stock' => true,
        ]);

        Stock::create([
            'date' => '2023-01-15',
            'vendor' => 'Purchase',
            'matForm' => 'HardAndSoftCopy',
            'matType' => 'Book',
            'contentDesc' => 'Adult Non-Fiction',
            'nonFictionType' => 'Textbook',
            'title' => 'Introduction to Philosophy',
            'author' => 'John Smith',
            'isbn' => '978-043596789',
            'gnb' => 'GNB2023002',
            'copyNo' => '5',
            'accessionNo' => 'A13',
            'areaOfResponsibility' => 'Books',
            'editionStatement' => '2',
            'publishersName' => 'Academic Press',
            'placeOfPublication' => 'Kumasi',
            'yearOfPublication' => '2020',
            'preliminaryPages' => '3',
            'numberOfPages' => '350',
            'heightOfBook' => '45cm',
            'poBox' => 'B-10 45',
            'poBoxLocation' => 'B-10 45 Kumasi',
            'telephone' => '+233 2485433388',
            'email' => 'academic@example.com',
            'website' => 'https://academicpress.com',
            'illustrations' => 'yes',
            'subject' => 'Philosophy',
            'classification_id' => $class2->id,
            'class_number' => '100',
            'sysOfClass' => 'DDC',
            'is_gnb_stock' => false,
        ]);

        // Create Indexed Articles
        IndexedArticle::create([
            'date' => '2023-04-01',
            'contentDesc' => 'Adult',
            'numberOfPages' => '60',
            'matType' => 'HardCopy',
            'subject' => 'Economics',
            'title' => 'Ghana\'s Economic Growth: Challenges and Opportunities',
            'writersDetails' => 'Dr. Kwesi Agyeman',
            'newspaperJournalMagazineName' => 'Ghana Economic Journal',
            'issn' => '1923-1274-7912-4790',
            'poBox' => '9945-5 Street',
            'poBoxLocation' => '9945-5 Street Accra Ghana',
            'telephone' => '+233456578940',
            'email' => 'journal@example.com',
            'articleOrNot' => 'yes',
            'website' => 'https://economicjournal.com',
        ]);

        IndexedArticle::create([
            'date' => '2023-01-15',
            'contentDesc' => 'Juvenile',
            'numberOfPages' => '30',
            'matType' => 'HardCopy',
            'articleOrNot' => 'yes',
            'subject' => 'Math',
            'title' => 'Fun Science Experiments for Kids',
            'writersDetails' => 'Ms. Ama Osei',
            'newspaperJournalMagazineName' => 'Kids Magazine Ghana',
            'issn' => '1923-1274-7912-4791',
            'poBox' => '1234 Main St',
            'poBoxLocation' => '1234 Main St Accra Ghana',
            'telephone' => '+233456578900',
            'email' => 'kids@example.com',
            'website' => 'https://kidsmagazine.com',
        ]);
    }
}