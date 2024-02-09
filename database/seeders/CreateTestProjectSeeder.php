<?php

namespace Database\Seeders;

use App\Models\Project\Project;
use App\Models\Project\ProjectTranslation;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;


class CreateTestProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proj = Project::create([
            'name' => 'Test project',
            'process_time' => 10, 
            'creation_date_at' => 2022,
            'link_project' => 'https://test.com',
            'type' => Project::TYPE_WEB,
        ]);

        $langProjam = ProjectTranslation::create([
            'project_id' => $proj->id,
            'description' => 'Լորեմ Իպսումը տպագրության և տպագրական արդյունաբերության համար նախատեսված մոդելային տեքստ է։ Սկսած 1500-ականներից՝ Լորեմ Իպսումը հանդիսացել է տպագրական արդյունաբերության ստանդարտ մոդելային տեքստ, ինչը մի անհայտ տպագրիչի կողմից տարբեր տառատեսակների օրինակների գիրք ստեղծելու ջանքերի արդյունք է։ Այս տեքստը ոչ միայն կարողացել է գոյատևել հինգ դարաշրջան,', 
            'lang' => 'am',
        ]);

        $langProjru = ProjectTranslation::create([
            'project_id' => $proj->id,
            'description' => 'это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков,', 
            'lang' => 'ru',
        ]);

        $langProjen = ProjectTranslation::create([
            'project_id' => $proj->id,
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proiden.', 
            'lang' => 'en',
        ]);

        Image::create([
            'project_id' => $proj->id,
            'path' => 'storage/app/public/1/Screenshot_1.png',
            'name' => "Screenshot_1.png",
        ]);

        Image::create([
            'project_id' => $proj->id,
            'path' => 'storage/app/public/1/Screenshot_2.png',
            'name' => "Screenshot_2.png",
        ]);

        Image::create([
            'project_id' => $proj->id,
            'path' => 'storage/app/public/1/Screenshot_3.png',
            'name' => "Screenshot_3.png",
        ]);

        
    }
}
