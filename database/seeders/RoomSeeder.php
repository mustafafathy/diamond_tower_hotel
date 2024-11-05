<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::create([
            'name_ar' => 'جناح متصل سوبيريور',
            'name_en' => 'Superior Connecting Suite',

            'description_ar' => 'يشمل المطبخ الصغير المجهز بالكامل ثلاجة وأدوات مطبخ وميكروويف وغلاية كهربائية. تتميز الغرفة الواسعة بأنها مكيفة وتوفر مدخلاً خاصًا بالإضافة إلى حمام خاص مزود بمقصورة دش ومجفف للشعر فيما تضم هذه الوحدة سريراً واحداً.',
            'description_en' => 'The fully equipped kitchenette includes a refrigerator, kitchenware, microwave, and electric kettle. The spacious room is air-conditioned and features a private entrance along with a private bathroom equipped with a shower cabin and a hairdryer. This unit includes one bed.',

            'space' => 70, // 70 square meters
            'allowed_persons' => 4, // Maximum capacity of 4 persons
            'availability' => 1, // Assuming 1 means available, 0 means unavailable

            // Room amenities
            'view' => true,
            'bathroom' => true,
            'kitchen' => true,
            'tv' => true,
            'air_condition' => true,
            'wifi' => true,
            'smoke' => false,
            'disabled' => false,

            // Bed configuration
            'king_bed' => 1,
            'single_bed' => 2,
            'sofa_bed' => 0,

            // Bathroom details
            'bathroom_details_ar' => 'تجهيزات الحمام تشمل مستلزمات مجانية، مجفف شعر، مرحاض، ورق تواليت، حوض استحمام أو دش.',
            'bathroom_details_en' => 'Bathroom amenities include free toiletries, hairdryer, toilet, toilet paper, bath or shower.',

            // Kitchen details
            'kitchen_details_ar' => 'مطبخ صغير، ثلاجة، ميكروويف، أدوات المطبخ، غلاية كهربائية، طاولة طعام.',
            'kitchen_details_en' => 'Kitchenette, refrigerator, microwave, kitchenware, electric kettle, dining table.',

            // Room preparations
            'preparations_ar' => 'خزانة، طاولة طعام، تلفاز بشاشة مسطحة، خدمة إيقاظ، هاتف، مكواة، منطقة جلوس، رف ملابس، تكييف هواء في غرفة واحدة لإقامة الضيوف.',
            'preparations_en' => 'Wardrobe, dining table, flat-screen TV, wake-up service, phone, iron, seating area, clothes rack, air conditioning in one guest room.',

            // Media and Technology
            'media_tech_ar' => 'تجهيزات الإنترنت، إنترنت عالي السرعة، تلفاز بشاشة مسطحة، قنوات الكابل.',
            'media_tech_en' => 'Internet facilities, high-speed internet, flat-screen TV, cable channels.',

            // Image paths
            'image' => 'images/suite_main.jpg', // Main image path
            'alt_images' => json_encode(['images/suite_alt1.jpg', 'images/suite_alt2.jpg']), // Alternative images as JSON array

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
