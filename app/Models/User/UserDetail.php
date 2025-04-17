<?php

namespace App\Models\User;

use App\Models\Definitions\City;
use App\Models\Definitions\District;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_summary', // Profil yazısı
        'biography', // Biyografi
        'horoscope', // burç
        'city_id', // Şehir
        'district_id', //ilçe
        'marital_status', //medeni durum
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
        'physical_disability' //fiziksel engel
    ];

     /**
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
