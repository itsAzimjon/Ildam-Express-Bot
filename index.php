<?php
    include 'Telegram.php';

    $telegram = new Telegram('6799696721:AAEytMnD8h5Xwcz0npg-kHKQVhU6xL_OSuQ');
    $chat_id = $telegram->ChatID();
    $text = $telegram->Text();
    $update = json_decode(file_get_contents('php://input'), true);

    $regions = [
        "Toshkent", "Jizzax", "Samarqand", "Sirdaryo", "Namangan", "Farg`ona", "Xorazm", "Andijon", "Buxoro", "Qashqadaryo", "Navoiy", "Surxondaryo", "Qoraqalpog`iston Respublikasi" 
    ];

    $main_keyboard = [
        [['text' => '🚪 ID kod olish'], ['text' => '💡 Yo‘riqnoma']],
        [['text' => '📞 Bog‘lanish'],['text' => '❓ Biz haqimizda']],
        [['text' => '📵 Taqiqlangan buyumlar']]
    ];

    $main_keyboard_markup = json_encode(['keyboard' => $main_keyboard, 'resize_keyboard' => true]);

    if ($text == "/start" || $text == "❓ Biz haqimizda") {
        sendInitialMessage($telegram, $chat_id);
    } elseif ($text == "🚪 ID kod olish") {
        askForUsername($telegram, $chat_id);
    } elseif ($text == "📵 Taqiqlangan buyumlar") {
        notAllow($telegram, $chat_id);
    } elseif ($text == "📞 Bog‘lanish") {
        admin($telegram, $chat_id);
    } elseif ($text == "💡 Yo‘riqnoma") {
        askForVideoChoice($telegram, $chat_id);
    } else {
        handleUserInput($telegram, $chat_id, $text, $update, $regions);
    }

    function sendInitialMessage($telegram, $chat_id) {
        global $main_keyboard_markup;
        $initial_message = "📣Assalomu alekum 

🇨🇳Xitoy saytlari ;
TAO BAO, PINDUODUO, 1688
Lardan tavar sotib olasizmi? unda

🚛 Ildamexpress tavaringizni Oʻzbekistonga yetkazib beradi❗️

Bizda tezkor avto cargo xizmati:

📆Kelish muddati 15-20 kunni tashkil etadi ✅

🏷Narxi -1kg 7,5$ (minimalka yoʻq)

🚨\"Seriya tavarlar va Brend tavarlar\"
uchun ham narx birdek

Ildamexpress - logistika sohasidagi ishonchli hamkoringiz!😊";

        $content = [
            'chat_id' => $chat_id,
            'text' => $initial_message,
            'reply_markup' => $main_keyboard_markup
        ];

        $telegram->sendMessage($content);
    }

    function askForVideoChoice($telegram, $chat_id) {
        $keyboard = [
            [['text' => 'Pinduodu']],
            [['text' => 'Taobao']]
        ];

        $keyboard_markup = json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true]);
        $content = [
            'chat_id' => $chat_id,
            'text' => "Quyidagi videolardan birini tanlang:",
            'reply_markup' => $keyboard_markup
        ];
        $telegram->sendMessage($content);
    }

    function notAllow($telegram, $chat_id) {
        global $main_keyboard_markup;
        $initial_message = "Sizga qulaylik yaratish maqsadida buyurtma qilish taqiqlangan tovarlarning ro'yxatini taqdim etmoqchimiz.

🔥 yonuvchan suyuglik (yogilg'i, kimyoviy moddalar)
tashilishi mumkin emas;

💊 dori-darmon;

🔪O’tkir uskunalar

🍲Oziq-ovgat maxsulotlari;

🔫harbiy qurol yaroqlar;

💉tibbiyot uskunalar;

💻 laptop va telefonlar

🕹️Dronlar

Tashish taqiqlangan tovarlarni jo'natish uchun sanksya choralari qo'llanilishi mumkin! Aziz do'stlar! Agar ma'lum bir mahsulotni buyurtma qilish mumkinligini bilmasangiz,
bizga murojat qilib so'rab olishingiz mumkin!";

        $content = [
            'chat_id' => $chat_id,
            'text' => $initial_message,
        ];

        $telegram->sendMessage($content);
    }

    function admin($telegram, $chat_id) {
    global $main_keyboard_markup;
    
    $admin_user_id = "@Ildamexpress1";
    
    $content = [
        'chat_id' => $chat_id, 
        'text' => "Savollaringiz bo‘lsa administratorimiz " . $admin_user_id . "'ga yozib qoldring.",
        'reply_markup' => $main_keyboard_markup
    ];

    $telegram->sendMessage($content);
    }

    function sendInstructionButtons($telegram, $chat_id) {
        $keyboard = [
            [['text' => 'Pinduodu'], ['text' => 'Taobao']],
        ];
        $keyboard_markup = json_encode(['keyboard' => $keyboard, 'resize_keyboard' => true]);
        $content = [
            'chat_id' => $chat_id,
            'text' => "Yo‘riqnoma: Quyidagi videoni tanlang.",
            'reply_markup' => $keyboard_markup
        ];
        $telegram->sendMessage($content);
    }

    function askForUsername($telegram, $chat_id) {
        $phone_keyboard = [
            [['text' => 'Telefon raqamni yuborish', 'request_contact' => true]]
        ];

        $phone_keyboard_markup = json_encode(['keyboard' => $phone_keyboard, 'resize_keyboard' => true]);
        $content = [
                'chat_id' => $chat_id,
                'text' => "Ismingizni kiriting:",
            ];
        $telegram->sendMessage($content);
        saveSessionData($chat_id, ['waiting_for' => 'username']);
    }

    function askForPhoneNumber($telegram, $chat_id) {
        $phone_keyboard = [
            [['text' => 'Telefon raqamni yuborish', 'request_contact' => true]]
        ];

        $phone_keyboard_markup = json_encode(['keyboard' => $phone_keyboard, 'resize_keyboard' => true]);
        $content = [
            'chat_id' => $chat_id,
            'text' => "Telefon raqamingizni kiriting:",
            'reply_markup' => $phone_keyboard_markup
        ];
        $telegram->sendMessage($content);
    }

    function askForRegion($telegram, $chat_id, $regions) {
        $region_keyboard = array_map(function($region) {
            return [['text' => $region]];
        }, $regions);

        $region_keyboard_markup = json_encode(['keyboard' => $region_keyboard, 'resize_keyboard' => true]);
        $content = [
            'chat_id' => $chat_id,
            'text' => "Hududingizni tanlang:",
            'reply_markup' => $region_keyboard_markup
        ];
        $telegram->sendMessage($content);
    }

    function handleUserInput($telegram, $chat_id, $text, $update, $regions) {
        $session_data = loadSessionData($chat_id);

        if (!$session_data) {
            $session_data = [];
        }

        if (isset($session_data['waiting_for'])) {
            switch ($session_data['waiting_for']) {
                case 'username':
                    $session_data['user_name'] = $text;
                    $session_data['waiting_for'] = 'phone';
                    saveSessionData($chat_id, $session_data);
                    askForPhoneNumber($telegram, $chat_id);
                    break;

                case 'phone':
                    if (isset($update['message']['contact'])) {
                        $contact = $update['message']['contact'];
                        $phone_number = $contact['phone_number'];
                        $first_name = $contact['first_name'];
                        
                        if (isset($update['message']['from']['username'])) {
                            $username = '@' . $update['message']['from']['username'];
                            $session_data['username'] = $username;
                        } else {
                            $session_data['username'] = 'Username yo‘q';
                        }
                        
                        $session_data['first_name'] = $first_name;
                        $session_data['phone_number'] = $phone_number;
                        $session_data['waiting_for'] = 'region';
                        saveSessionData($chat_id, $session_data);
                        askForRegion($telegram, $chat_id, $regions);
                    } else {
                        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Iltimos, telefon raqamingizni yuboring."]);
                    }
                    break;

                case 'region':
                    if (in_array($text, $regions)) {
                        $session_data['region'] = $text;
                        unset($session_data['waiting_for']);
                        $session_data['user_id'] = getNextUserId();
                        saveSessionData($chat_id, $session_data);
                        completeRegistration($telegram, $chat_id, $session_data);
                        postToChannel($telegram, $session_data);
                    } else {
                        $telegram->sendMessage(['chat_id' => $chat_id, 'text' => "Noto'g'ri hudud. Iltimos, hududingizni tanlang."]);
                        askForRegion($telegram, $chat_id, $regions);
                    }
                break;
            }
        } else {
            if ($text == "Yo‘riqnoma") {
                sendInstructionButtons($telegram, $chat_id);
            } elseif ($text == "Pinduodu") {
                ping($telegram, $chat_id);
            } elseif ($text == "Taobao") {
                tao($telegram, $chat_id);
            }
        }
    }

    function ping($telegram, $chat_id) {
        global $main_keyboard_markup;
        $video_path = 'ping.mp4';

        $content = [
            'chat_id' => $chat_id,
            'reply_markup' => $main_keyboard_markup,
            'video' => new CURLFile($video_path),
            'caption' => "Quyidagi videoni tomoshaqilish orqali Xitoydan qanday qilib mahsulot buyurtma qilishni o‘rganib olishingiz mumkin."
        ];
        $telegram->sendVideo($content);
    }

    function tao($telegram, $chat_id) {
        global $main_keyboard_markup;
        $video_path = 'tao.mp4';

        $content = [
            'chat_id' => $chat_id,
            'reply_markup' => $main_keyboard_markup,
            'video' => new CURLFile($video_path),
            'caption' => "Quyidagi videoni tomoshaqilish orqali Xitoydan qanday qilib mahsulot buyurtma qilishni o‘rganib olishingiz mumkin."
        ];
        $telegram->sendVideo($content);
    }

    function completeRegistration($telegram, $chat_id, $session_data) {
        global $main_keyboard_markup;
        $content = [
            'chat_id' => $chat_id,
            'reply_markup' => $main_keyboard_markup,
            'text' => "静静 Ildam-" . $session_data['user_id'] . " 15199186137 浙江省金华市义乌市 北苑街道凌云一区6幢2单元009仓库 （乌兹别克）静静 Ildam-" 
                . $session_data['user_id'] 
                . " " . $session_data['region']
                . " ( +" . $session_data['phone_number'] . " ) " . "\n"
        ];
        $telegram->sendMessage($content);
    }


    function postToChannel($telegram, $session_data) {
        $channel_id = '-1002009964245';
        $message = "ID: " . $session_data['user_id'] ."\n"
                . "FIO: " . $session_data['user_name'] . "\n"
                . "Telegram nomi: " . $session_data['first_name'] . "\n"
                . "Username: " . $session_data['username'] . "\n"
                . "Telefon raqam: +" . $session_data['phone_number'] . "\n"
                . "Viloyat: " . $session_data['region'];

        $content = ['chat_id' => $channel_id, 'text' => $message];
        $response = $telegram->sendMessage($content);

        if (!$response) {
            error_log("Failed to post to channel: " . json_encode($telegram->getError()));
        }
    }

    function getNextUserId() {
        $file = 'last_user_id.txt';
        if (!file_exists($file)) {
            file_put_contents($file, 0);
        }
        $last_id = (int)file_get_contents($file);
        $new_id = $last_id + 1;
        file_put_contents($file, $new_id);
        return $new_id;
    }

    function saveSessionData($chat_id, $data) {
        file_put_contents("session_$chat_id.json", json_encode($data));
    }

    function loadSessionData($chat_id) {
        $session_file = "session_$chat_id.json";
        return file_exists($session_file) ? json_decode(file_get_contents($session_file), true) : null;
    }

?>