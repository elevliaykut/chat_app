<?php
namespace App\Services\User;

use App\Models\User\UserDetail;
use App\Services\BaseService;

class UserDetailService extends BaseService
{

    /**
     * @var array
     */
    protected array $fieldSearchable = [
        'user_id',
        'profile_summary', // Profil yazısı
        'biography', // Biyografi
        'horoscope', // burç
        'city_id', // Şehir
        'district_id', //ilçe
        'marital status', //medeni durum
        'online_status', //çevrim içi
        'headscarf', // başörtüsü
        'tall', // boy
        'weight', // kilo
        'eye_color', // göz rengi
        'hair_color', // saç rengi
        'skin_color', // ten rengi
        'body_type', // vucut tipi
        'have_a_child', // cocugunuz varmı
        'want_a_child', //cocuk istiyormusunuz
        'use_cigarette', // sigara kullanıyormusunuz
        'use_alcohol', // alkol
        'education_status', // eğitim durumu
        'foreign_language', // yabancı dil
        'job', // meslek
        'salary', // gelir
        'work_status', // çalışma şekliniz
        'live_with', // kimle yaşıyorsunuz
        'clothing_style', // Giyim tarzınız
        'not_compromise', //taviz vermeyecekleriniz
        'community', // Cemaat
        'sect', // meshebiniz
        'do_you_pray', /// namaz kılıyormusunuz
        'do_you_know_quran', // kuran biliyormusunuz 
        'are_you_fasting', // oruç tutuyormusunuz
        'consider_important_in_islam', // islami ve ahlaki olarak önemli görükleriniz
        'listening_music_types', //dinlediğiniz müzikler
        'reading_book_types', //okuduğunuz kitap türleri
        'looking_qualities', // eş adayında aradığınız uyum
        'your_hobbies', // hobileriniz
        'your_personality', // kişiliğiniz
        'physical disability' //fiziksel engel
    ];

    /**
     * @return array|string[]
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return UserDetail::class;
    }

    public function updateOrCreate(array $data)
    {
        return UserDetail::updateOrCreate(
            ['user_id' => $data['user_id']], // Arama kriteri
            $data                            // Güncellenecek veya eklenecek veri
        );
    }
}
