<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create(['id' => 1, 	'city' => 'Karachi', 	'city_ascii' => 'Karachi', 	'lat' => 24.86, 	'lng' => 67.01, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'admin']);
        City::create(['id' => 2, 	'city' => 'Lahore', 	'city_ascii' => 'Lahore', 	'lat' => 31.5497, 	'lng' => 74.3436, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'admin']);
        City::create(['id' => 3, 	'city' => 'Sialkot City', 	'city_ascii' => 'Sialkot City', 	'lat' => 32.5, 	'lng' => 74.5333, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 4, 	'city' => 'Faisalabad', 	'city_ascii' => 'Faisalabad', 	'lat' => 31.418, 	'lng' => 73.079, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 5, 	'city' => 'Rawalpindi', 	'city_ascii' => 'Rawalpindi', 	'lat' => 33.6007, 	'lng' => 73.0679, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 6, 	'city' => 'Peshawar', 	'city_ascii' => 'Peshawar', 	'lat' => 34, 	'lng' => 71.5, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'admin']);
        City::create(['id' => 7, 	'city' => 'Saidu Sharif', 	'city_ascii' => 'Saidu Sharif', 	'lat' => 34.75, 	'lng' => 72.3572, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 8, 	'city' => 'Multan', 	'city_ascii' => 'Multan', 	'lat' => 30.1978, 	'lng' => 71.4711, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 9, 	'city' => 'Gujranwala', 	'city_ascii' => 'Gujranwala', 	'lat' => 32.15, 	'lng' => 74.1833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 10, 	'city' => 'Islamabad', 	'city_ascii' => 'Islamabad', 	'lat' => 33.6989, 	'lng' => 73.0369, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Islāmābād', 	'capital' => 'primary']);
        City::create(['id' => 11, 	'city' => 'Quetta', 	'city_ascii' => 'Quetta', 	'lat' => 30.192, 	'lng' => 67.007, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Balochistān', 	'capital' => 'admin']);
        City::create(['id' => 12, 	'city' => 'Bahawalpur', 	'city_ascii' => 'Bahawalpur', 	'lat' => 29.3956, 	'lng' => 71.6722, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 13, 	'city' => 'Sargodha', 	'city_ascii' => 'Sargodha', 	'lat' => 32.0836, 	'lng' => 72.6711, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 14, 	'city' => 'New Mirpur', 	'city_ascii' => 'New Mirpur', 	'lat' => 33.1333, 	'lng' => 73.75, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Azad Kashmir', 	'capital' => 'minor']);
        City::create(['id' => 15, 	'city' => 'Chiniot', 	'city_ascii' => 'Chiniot', 	'lat' => 31.7167, 	'lng' => 72.9833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 16, 	'city' => 'Sukkur', 	'city_ascii' => 'Sukkur', 	'lat' => 27.6995, 	'lng' => 68.8673, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 17, 	'city' => 'Larkana', 	'city_ascii' => 'Larkana', 	'lat' => 27.56, 	'lng' => 68.2264, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 18, 	'city' => 'Shekhupura', 	'city_ascii' => 'Shekhupura', 	'lat' => 31.7083, 	'lng' => 74, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 19, 	'city' => 'Jhang City', 	'city_ascii' => 'Jhang City', 	'lat' => 31.2681, 	'lng' => 72.3181, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 20, 	'city' => 'Rahimyar Khan', 	'city_ascii' => 'Rahimyar Khan', 	'lat' => 28.4202, 	'lng' => 70.2952, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 21, 	'city' => 'Gujrat', 	'city_ascii' => 'Gujrat', 	'lat' => 32.5736, 	'lng' => 74.0789, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 22, 	'city' => 'Kasur', 	'city_ascii' => 'Kasur', 	'lat' => 31.1167, 	'lng' => 74.45, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 23, 	'city' => 'Mardan', 	'city_ascii' => 'Mardan', 	'lat' => 34.1958, 	'lng' => 72.0447, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 24, 	'city' => 'Mingaora', 	'city_ascii' => 'Mingaora', 	'lat' => 34.7717, 	'lng' => 72.36, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => '']);
        City::create(['id' => 25, 	'city' => 'Dera Ghazi Khan', 	'city_ascii' => 'Dera Ghazi Khan', 	'lat' => 30.05, 	'lng' => 70.6333, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 26, 	'city' => 'Nawabshah', 	'city_ascii' => 'Nawabshah', 	'lat' => 26.2442, 	'lng' => 68.41, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 27, 	'city' => 'Sahiwal', 	'city_ascii' => 'Sahiwal', 	'lat' => 30.6706, 	'lng' => 73.1064, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 28, 	'city' => 'Mirpur Khas', 	'city_ascii' => 'Mirpur Khas', 	'lat' => 25.5269, 	'lng' => 69.0111, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 29, 	'city' => 'Okara', 	'city_ascii' => 'Okara', 	'lat' => 30.81, 	'lng' => 73.4597, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 30, 	'city' => 'Mandi Burewala', 	'city_ascii' => 'Mandi Burewala', 	'lat' => 30.15, 	'lng' => 72.6833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 31, 	'city' => 'Jacobabad', 	'city_ascii' => 'Jacobabad', 	'lat' => 28.2769, 	'lng' => 68.4514, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 32, 	'city' => 'Saddiqabad', 	'city_ascii' => 'Saddiqabad', 	'lat' => 28.3006, 	'lng' => 70.1302, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 33, 	'city' => 'Kohat', 	'city_ascii' => 'Kohat', 	'lat' => 33.5869, 	'lng' => 71.4414, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 34, 	'city' => 'Muridke', 	'city_ascii' => 'Muridke', 	'lat' => 31.802, 	'lng' => 74.255, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 35, 	'city' => 'Muzaffargarh', 	'city_ascii' => 'Muzaffargarh', 	'lat' => 30.0703, 	'lng' => 71.1933, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 36, 	'city' => 'Khanpur', 	'city_ascii' => 'Khanpur', 	'lat' => 28.6471, 	'lng' => 70.662, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 37, 	'city' => 'Gojra', 	'city_ascii' => 'Gojra', 	'lat' => 31.15, 	'lng' => 72.6833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 38, 	'city' => 'Mandi Bahauddin', 	'city_ascii' => 'Mandi Bahauddin', 	'lat' => 32.5861, 	'lng' => 73.4917, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 39, 	'city' => 'Abbottabad', 	'city_ascii' => 'Abbottabad', 	'lat' => 34.15, 	'lng' => 73.2167, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 40, 	'city' => 'Dadu', 	'city_ascii' => 'Dadu', 	'lat' => 26.7319, 	'lng' => 67.775, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 41, 	'city' => 'Khuzdar', 	'city_ascii' => 'Khuzdar', 	'lat' => 27.8, 	'lng' => 66.6167, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Balochistān', 	'capital' => '']);
        City::create(['id' => 42, 	'city' => 'Pakpattan', 	'city_ascii' => 'Pakpattan', 	'lat' => 30.35, 	'lng' => 73.4, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 43, 	'city' => 'Tando Allahyar', 	'city_ascii' => 'Tando Allahyar', 	'lat' => 25.4667, 	'lng' => 68.7167, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 44, 	'city' => 'Jaranwala', 	'city_ascii' => 'Jaranwala', 	'lat' => 31.3342, 	'lng' => 73.4194, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 45, 	'city' => 'Vihari', 	'city_ascii' => 'Vihari', 	'lat' => 30.0419, 	'lng' => 72.3528, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 46, 	'city' => 'Kamalia', 	'city_ascii' => 'Kamalia', 	'lat' => 30.7258, 	'lng' => 72.6447, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 47, 	'city' => 'Kot Addu', 	'city_ascii' => 'Kot Addu', 	'lat' => 30.47, 	'lng' => 70.9644, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 48, 	'city' => 'Nowshera', 	'city_ascii' => 'Nowshera', 	'lat' => 34.0153, 	'lng' => 71.9747, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 49, 	'city' => 'Swabi', 	'city_ascii' => 'Swabi', 	'lat' => 34.1167, 	'lng' => 72.4667, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 50, 	'city' => 'Dera Ismail Khan', 	'city_ascii' => 'Dera Ismail Khan', 	'lat' => 31.8167, 	'lng' => 70.9167, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 51, 	'city' => 'Chaman', 	'city_ascii' => 'Chaman', 	'lat' => 30.921, 	'lng' => 66.4597, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Balochistān', 	'capital' => '']);
        City::create(['id' => 52, 	'city' => 'Charsadda', 	'city_ascii' => 'Charsadda', 	'lat' => 34.1453, 	'lng' => 71.7308, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 53, 	'city' => 'Kandhkot', 	'city_ascii' => 'Kandhkot', 	'lat' => 28.4, 	'lng' => 69.3, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => '']);
        City::create(['id' => 54, 	'city' => 'Hasilpur', 	'city_ascii' => 'Hasilpur', 	'lat' => 29.6967, 	'lng' => 72.5542, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 55, 	'city' => 'Muzaffarabad', 	'city_ascii' => 'Muzaffarabad', 	'lat' => 34.37, 	'lng' => 73.4711, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Azad Kashmir', 	'capital' => '']);
        City::create(['id' => 56, 	'city' => 'Mianwali', 	'city_ascii' => 'Mianwali', 	'lat' => 32.5853, 	'lng' => 71.5436, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 57, 	'city' => 'Jalalpur Jattan', 	'city_ascii' => 'Jalalpur Jattan', 	'lat' => 32.7667, 	'lng' => 74.2167, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 58, 	'city' => 'Bhakkar', 	'city_ascii' => 'Bhakkar', 	'lat' => 31.6333, 	'lng' => 71.0667, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 59, 	'city' => 'Zhob', 	'city_ascii' => 'Zhob', 	'lat' => 31.3417, 	'lng' => 69.4486, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Balochistān', 	'capital' => 'minor']);
        City::create(['id' => 60, 	'city' => 'Kharian', 	'city_ascii' => 'Kharian', 	'lat' => 32.811, 	'lng' => 73.865, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 61, 	'city' => 'Mian Channun', 	'city_ascii' => 'Mian Channun', 	'lat' => 30.4397, 	'lng' => 72.3544, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 62, 	'city' => 'Jamshoro', 	'city_ascii' => 'Jamshoro', 	'lat' => 25.4283, 	'lng' => 68.2822, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 63, 	'city' => 'Pattoki', 	'city_ascii' => 'Pattoki', 	'lat' => 31.0214, 	'lng' => 73.8528, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 64, 	'city' => 'Harunabad', 	'city_ascii' => 'Harunabad', 	'lat' => 29.61, 	'lng' => 73.1361, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 65, 	'city' => 'Toba Tek Singh', 	'city_ascii' => 'Toba Tek Singh', 	'lat' => 30.9667, 	'lng' => 72.4833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => 'minor']);
        City::create(['id' => 66, 	'city' => 'Shakargarh', 	'city_ascii' => 'Shakargarh', 	'lat' => 32.2628, 	'lng' => 75.1583, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 67, 	'city' => 'Hujra Shah Muqim', 	'city_ascii' => 'Hujra Shah Muqim', 	'lat' => 30.7333, 	'lng' => 73.8167, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 68, 	'city' => 'Kabirwala', 	'city_ascii' => 'Kabirwala', 	'lat' => 30.4068, 	'lng' => 71.8667, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 69, 	'city' => 'Mansehra', 	'city_ascii' => 'Mansehra', 	'lat' => 34.3333, 	'lng' => 73.2, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 70, 	'city' => 'Lala Musa', 	'city_ascii' => 'Lala Musa', 	'lat' => 32.7012, 	'lng' => 73.9605, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 71, 	'city' => 'Nankana Sahib', 	'city_ascii' => 'Nankana Sahib', 	'lat' => 31.4492, 	'lng' => 73.7124, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 72, 	'city' => 'Bannu', 	'city_ascii' => 'Bannu', 	'lat' => 32.9889, 	'lng' => 70.6056, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 73, 	'city' => 'Timargara', 	'city_ascii' => 'Timargara', 	'lat' => 34.8281, 	'lng' => 71.8408, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 74, 	'city' => 'Parachinar', 	'city_ascii' => 'Parachinar', 	'lat' => 33.8992, 	'lng' => 70.1008, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => '']);
        City::create(['id' => 75, 	'city' => 'Abdul Hakim', 	'city_ascii' => 'Abdul Hakim', 	'lat' => 30.5522, 	'lng' => 72.1278, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 76, 	'city' => 'Gwadar', 	'city_ascii' => 'Gwadar', 	'lat' => 25.1264, 	'lng' => 62.3225, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Balochistān', 	'capital' => 'minor']);
        City::create(['id' => 77, 	'city' => 'Hassan Abdal', 	'city_ascii' => 'Hassan Abdal', 	'lat' => 33.8195, 	'lng' => 72.689, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 78, 	'city' => 'Tank', 	'city_ascii' => 'Tank', 	'lat' => 32.2167, 	'lng' => 70.3833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 79, 	'city' => 'Hangu', 	'city_ascii' => 'Hangu', 	'lat' => 33.5281, 	'lng' => 71.0572, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 80, 	'city' => 'Risalpur Cantonment', 	'city_ascii' => 'Risalpur Cantonment', 	'lat' => 34.0049, 	'lng' => 71.9989, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => '']);
        City::create(['id' => 81, 	'city' => 'Karak', 	'city_ascii' => 'Karak', 	'lat' => 33.1167, 	'lng' => 71.0833, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 82, 	'city' => 'Kundian', 	'city_ascii' => 'Kundian', 	'lat' => 32.4522, 	'lng' => 71.4718, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 83, 	'city' => 'Umarkot', 	'city_ascii' => 'Umarkot', 	'lat' => 25.3614, 	'lng' => 69.7361, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => 'minor']);
        City::create(['id' => 84, 	'city' => 'Chitral', 	'city_ascii' => 'Chitral', 	'lat' => 35.8511, 	'lng' => 71.7889, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => 'minor']);
        City::create(['id' => 85, 	'city' => 'Dainyor', 	'city_ascii' => 'Dainyor', 	'lat' => 35.9206, 	'lng' => 74.3783, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Gilgit-Baltistan', 	'capital' => '']);
        City::create(['id' => 86, 	'city' => 'Kulachi', 	'city_ascii' => 'Kulachi', 	'lat' => 31.9286, 	'lng' => 70.4592, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Khyber Pakhtunkhwa', 	'capital' => '']);
        City::create(['id' => 87, 	'city' => 'Kotli', 	'city_ascii' => 'Kotli', 	'lat' => 33.5156, 	'lng' => 73.9019, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Azad Kashmir', 	'capital' => 'minor']);
        City::create(['id' => 88, 	'city' => 'Murree', 	'city_ascii' => 'Murree', 	'lat' => 33.9042, 	'lng' => 73.3903, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Punjab', 	'capital' => '']);
        City::create(['id' => 89, 	'city' => 'Mithi', 	'city_ascii' => 'Mithi', 	'lat' => 24.7333, 	'lng' => 69.8, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Sindh', 	'capital' => '']);
        City::create(['id' => 90, 	'city' => 'Gilgit', 	'city_ascii' => 'Gilgit', 	'lat' => 35.9208, 	'lng' => 74.3144, 	'country' => 'Pakistan', 	'iso2' => 'PK', 	'iso3' => 'PAK', 	'admin_name' => 'Gilgit-Baltistan', 	'capital' => 'minor']);

    }
}
