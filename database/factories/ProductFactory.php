<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID'); // Gunakan Faker secara manual
        
        $kebayaNames = [
            'Kebaya Modern', 'Kebaya Bali', 'Kebaya Klasik', 'Kebaya Encim', 'Kebaya Jawa',
            'Kebaya Modern Brokat', 'Kebaya Wisuda Elegant', 'Kebaya Pengantin Mewah',
            'Kebaya Sunda Cantik', 'Kebaya Modern Payet', 'Kebaya Tradisional Bordir',
            'Kebaya Bali Modern', 'Kebaya Jawa Klasik', 'Kebaya Encim Elegan',
            'Kebaya Wisuda Muda', 'Kebaya Pengantin Royal', 'Kebaya Modern Sifon',
            'Kebaya Tradisional Sutra', 'Kebaya Bali Gold', 'Kebaya Jawa Vintage'
        ];

        $materials = ['Brokat', 'Sutra', 'Sifon', 'Katun', 'Linen', 'Viscose', 'Rayon', 'Organza'];
        $colors = ['Merah', 'Biru', 'Hijau', 'Ungu', 'Pink', 'Emas', 'Perak', 'Hitam', 'Putih', 'Krem'];

        $imageUrls = [
            'https://i.etsystatic.com/33501502/r/il/ffc038/5335945196/il_300x300.5335945196_l1e4.jpg',
            'https://i.etsystatic.com/33501502/r/il/0cfe97/6022762274/il_300x300.6022762274_t2hr.jpg',
            'https://i.etsystatic.com/47130557/r/il/eb71d8/5519252049/il_1588xN.5519252049_hm8x.jpg',
            'https://i.etsystatic.com/47130557/r/il/865d53/5519716265/il_fullxfull.5519716265_tw6w.jpg',
            'https://i.etsystatic.com/47130557/r/il/fdfa20/5519252055/il_1588xN.5519252055_orex.jpg',
            'https://i.etsystatic.com/33501502/r/il/ab7031/5509114083/il_1080xN.5509114083_88zr.jpg',
            'https://i.etsystatic.com/47130557/r/il/cdaa4c/5534745766/il_fullxfull.5534745766_frz3.jpg',
            'https://i.etsystatic.com/33501502/r/il/497b99/4780796526/il_794xN.4780796526_h4hi.jpg',
            'https://tse4.mm.bing.net/th/id/OIP.5vlzlXEIpex39FoB-M_A-QHaHa?cb=12&w=800&h=800&rs=1&pid=ImgDetMain&o=7&rm=3',
        ];

        // Harga beli antara 200k - 800k
        $rentprice = $faker->numberBetween(200000, 500000);
        $price = ($rentprice * $faker->numberBetween(1, 4));
        // Harga sewa = 3-5% dari harga beli per hari (lebih realistis untuk sewa)
    
        // Ambil branch dan category yang sudah ada
        // Gunakan first() untuk mendapatkan satu record, atau buat dummy jika tidak ada
        $branch = Branch::inRandomOrder()->first();
        $category = Category::inRandomOrder()->first();

        return [
            'name' => $faker->randomElement($kebayaNames) . ' ' .
                      $faker->randomElement($colors) . ' ' .
                      $faker->randomElement($materials),
            'price' => $price,
            'rent_price_per_day' => $rentprice,
            'min_rent_days' => $faker->numberBetween(1, 3),
            'max_rent_days' => $faker->numberBetween(7, 14),
            'image' => $faker->randomElement($imageUrls),
            'category_id' => $category ? $category->id : Category::factory()->create()->id,
            'branch_id' => $branch ? $branch->id : Branch::factory()->create()->id,
            'description' => $faker->paragraph(5),
            'stock' => $faker->numberBetween(2, 10),
            'weight' => $faker->numberBetween(500, 1500), // Berat dalam gram
            'is_available' => $faker->boolean(90),
            'is_available_for_rent' => $faker->boolean(80),
        ];
    }
}