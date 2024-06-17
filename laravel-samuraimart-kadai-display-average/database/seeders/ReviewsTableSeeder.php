<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $products = Product::all();

        $reviewsData = [
            [
                'score' => 5,
                'title' => '最高！！',
                'content' => 'リピ買い確定',
            ],
            [
                'score' => 4,
                'title' => 'とても良い商品でした。',
                'content' => '買って正解です！',
            ],
            [
                'score' => 3,
                'title' => '普通です。',
                'content' => '可もなく不可もなくかな',
            ],
            [
                'score' => 2,
                'title' => 'うーん',
                'content' => 'いまいち、使えるけど…',
            ],
            [
                'score' => 1,
                'title' => '最悪',
                'content' => 'お金返してほしい',
            ],
        ];

        $reviews = [];

        foreach ($products as $product) {
            $numberOfReviews = rand(1, 5);

            for ($i = 0; $i < $numberOfReviews; $i++) {
                $randomReview = $reviewsData[array_rand($reviewsData)];
                $reviews[] = [
                    'product_id' => $product->id,
                    'user_id' => 1,
                    'score' => $randomReview['score'],
                    'title' => $randomReview['title'],
                    'content' => $randomReview['content'],
                ];
            }
        }

        Review::insert($reviews);
    }
}
