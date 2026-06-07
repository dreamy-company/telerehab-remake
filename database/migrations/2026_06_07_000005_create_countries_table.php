<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 2)->unique(); // ISO 3166-1 alpha-2
            $table->timestamps();
        });

        // Seed all countries inline so `php artisan migrate` populates them automatically
        $now = now();
        DB::table('countries')->insert([
            ['name' => 'Afghanistan', 'code' => 'AF', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Albania', 'code' => 'AL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Algeria', 'code' => 'DZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Andorra', 'code' => 'AD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Angola', 'code' => 'AO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Argentina', 'code' => 'AR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Armenia', 'code' => 'AM', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Australia', 'code' => 'AU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Austria', 'code' => 'AT', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Azerbaijan', 'code' => 'AZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bahrain', 'code' => 'BH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bangladesh', 'code' => 'BD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Belarus', 'code' => 'BY', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Belgium', 'code' => 'BE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bolivia', 'code' => 'BO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bosnia and Herzegovina', 'code' => 'BA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brazil', 'code' => 'BR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bulgaria', 'code' => 'BG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cambodia', 'code' => 'KH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cameroon', 'code' => 'CM', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Canada', 'code' => 'CA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chile', 'code' => 'CL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'China', 'code' => 'CN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Colombia', 'code' => 'CO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Croatia', 'code' => 'HR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cuba', 'code' => 'CU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cyprus', 'code' => 'CY', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Czech Republic', 'code' => 'CZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Denmark', 'code' => 'DK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ecuador', 'code' => 'EC', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Egypt', 'code' => 'EG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ethiopia', 'code' => 'ET', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Finland', 'code' => 'FI', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'France', 'code' => 'FR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Georgia', 'code' => 'GE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Germany', 'code' => 'DE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ghana', 'code' => 'GH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Greece', 'code' => 'GR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hungary', 'code' => 'HU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'India', 'code' => 'IN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Indonesia', 'code' => 'ID', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Iran', 'code' => 'IR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Iraq', 'code' => 'IQ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ireland', 'code' => 'IE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Israel', 'code' => 'IL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Italy', 'code' => 'IT', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Japan', 'code' => 'JP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jordan', 'code' => 'JO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kazakhstan', 'code' => 'KZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kenya', 'code' => 'KE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kuwait', 'code' => 'KW', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kyrgyzstan', 'code' => 'KG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Laos', 'code' => 'LA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Latvia', 'code' => 'LV', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lebanon', 'code' => 'LB', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Libya', 'code' => 'LY', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lithuania', 'code' => 'LT', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Luxembourg', 'code' => 'LU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Malaysia', 'code' => 'MY', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Maldives', 'code' => 'MV', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mexico', 'code' => 'MX', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Moldova', 'code' => 'MD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mongolia', 'code' => 'MN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Morocco', 'code' => 'MA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mozambique', 'code' => 'MZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Myanmar', 'code' => 'MM', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nepal', 'code' => 'NP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Netherlands', 'code' => 'NL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'New Zealand', 'code' => 'NZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nigeria', 'code' => 'NG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'North Korea', 'code' => 'KP', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Norway', 'code' => 'NO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Oman', 'code' => 'OM', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pakistan', 'code' => 'PK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Palestine', 'code' => 'PS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Panama', 'code' => 'PA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Peru', 'code' => 'PE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Philippines', 'code' => 'PH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Poland', 'code' => 'PL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Portugal', 'code' => 'PT', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Qatar', 'code' => 'QA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Romania', 'code' => 'RO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Russia', 'code' => 'RU', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rwanda', 'code' => 'RW', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Saudi Arabia', 'code' => 'SA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Senegal', 'code' => 'SN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Serbia', 'code' => 'RS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Singapore', 'code' => 'SG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Slovakia', 'code' => 'SK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Slovenia', 'code' => 'SI', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Somalia', 'code' => 'SO', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'South Africa', 'code' => 'ZA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'South Korea', 'code' => 'KR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'South Sudan', 'code' => 'SS', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Spain', 'code' => 'ES', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sri Lanka', 'code' => 'LK', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sudan', 'code' => 'SD', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sweden', 'code' => 'SE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Switzerland', 'code' => 'CH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Syria', 'code' => 'SY', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Taiwan', 'code' => 'TW', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tajikistan', 'code' => 'TJ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tanzania', 'code' => 'TZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Thailand', 'code' => 'TH', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Timor-Leste', 'code' => 'TL', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tunisia', 'code' => 'TN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Turkey', 'code' => 'TR', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Turkmenistan', 'code' => 'TM', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Uganda', 'code' => 'UG', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ukraine', 'code' => 'UA', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'United Arab Emirates', 'code' => 'AE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'United Kingdom', 'code' => 'GB', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'United States', 'code' => 'US', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Uruguay', 'code' => 'UY', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Uzbekistan', 'code' => 'UZ', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Venezuela', 'code' => 'VE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vietnam', 'code' => 'VN', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Yemen', 'code' => 'YE', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Zambia', 'code' => 'ZM', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Zimbabwe', 'code' => 'ZW', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
