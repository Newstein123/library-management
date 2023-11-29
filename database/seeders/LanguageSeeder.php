<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            'English', 'Spanish', 'French', 'German', 'Mandarin Chinese',
            'Hindi', 'Arabic', 'Portuguese', 'Bengali', 'Russian',
            'Urdu', 'Indonesian', 'Italian', 'Japanese', 'Turkish',
            'Telugu', 'Vietnamese', 'Korean', 'Tamil', 'Marathi',
            'French', 'Urdu', 'Malayalam', 'Chinese (Cantonese)', 'Thai',
            'Gujarati', 'Javanese', 'Filipino', 'Persian', 'Polish',
            'Pashto', 'Kannada', 'Odia', 'Malay', 'Hausa',
            'Punjabi', 'Burmese', 'Yoruba', 'Sundanese', 'Romanian',
            'Dutch', 'Maithili', 'Bhojpuri', 'Ukrainian', 'Tagalog',
            'Igbo', 'Farsi', 'Malagasy', 'Sinhalese', 'Amharic',
            'Oromo', 'Zulu', 'Czech', 'Tigrinya', 'Swahili',
            'Xhosa', 'Nepali', 'Balochi', 'Sindhi', 'Afrikaans',
            'Danish', 'Greek', 'Bulgarian', 'Hebrew', 'Serbo-Croatian',
            'Finnish', 'Hungarian', 'Slovak', 'Catalan', 'Belarusian',
            'Swedish', 'Latvian', 'Lithuanian', 'Norwegian', 'Georgian',
            'Irish', 'Albanian', 'Macedonian', 'Kurdish', 'Bosnian',
            'Montenegrin', 'Luxembourgish', 'Moldovan', 'Mongolian', 'Uighur',
            'Samoan', 'Tongan', 'Hawaiian', 'Marshallese', 'Fijian',
            'Chamorro', 'Tahitian', 'Guamanian', 'Palauan', 'Kiribati',
            'Tuvaluan', 'Kosraean', 'Pohnpeian', 'Yapese', 'Nauruan',
        ];
        foreach ($languages as $value) {
            Language::create([
                'name' => $value,
                'value' => Str::lower($value),
            ]);
        }        
    }
}
