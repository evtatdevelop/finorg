<?php
    include_once( 'modules/userData.php' );

    function names( $props ) {
        // if ( !checkDataSet( $props, ['search'] )) return null;
        // $userMames = getUserNames( $props );
        // return $userMames;
        return [ [ "id" => 1833, "last_name" => "Татаренко", "first_name" => "Евгений", "middle_name" => "Геннадьевич", "login" => "TatarenkoEG", "email" => "TatarenkoEG@sibgenco.ru", ], [ "id" => 261582, "last_name" => "Татаренко", "first_name" => "Игорь", "middle_name" => "Юрьевич", "login" => "Tatarenko_IY@MOSCOW", "email" => "Igor.Tatarenko@eurochem.ru", ], [ "id" => 45449, "last_name" => "Татаренко", "first_name" => "Ирина", "middle_name" => "Валерьевна", "login" => "TatarenkoIV", "email" => "TatarenkoIV@sibgenco.ru", ], [ "id" => 6388, "last_name" => "Татаринов", "first_name" => "Виктор", "middle_name" => "Иванович", "login" => "TatarinovVI", "email" => "TatarinovVI@sibgenco.ru", ], [ "id" => 248699, "last_name" => "Татаринов", "first_name" => "Владимир", "middle_name" => "Александрович", "login" => "TatarinovVlA", "email" => "TatarinovVlA@sibgenco.ru", ], [ "id" => 105544, "last_name" => "Татаринов", "first_name" => "Владимир", "middle_name" => "Витальевич", "login" => "TatarinovVV", "email" => "TatarinovVV@sibgenco.ru", ], [ "id" => 248068, "last_name" => "Татаринов", "first_name" => "Владимир", "middle_name" => "Сергеевич", "login" => "TatarinovVS", "email" => "TatarinovVS@sibgenco.ru", ], [ "id" => 240971, "last_name" => "Татаринов", "first_name" => "Владислав", "middle_name" => "Андреевич", "login" => "TatarinovVA", "email" => "TatarinovVA@suek.ru", ], [ "id" => 264073, "last_name" => "Татаринов", "first_name" => "Вячеслав", "middle_name" => "Александрович", "login" => "Tatarinov_VA@NMK", "email" => "Vyacheslav.Tatarinov@eurochem.ru", ], [ "id" => 248713, "last_name" => "Татаринова", "first_name" => "Галина", "middle_name" => "Александровна", "login" => "TatarinovaGA", "email" => "TatarinovaGA@sibgenco.ru", ], [ "id" => 264646, "last_name" => "Татаринова", "first_name" => "Елена", "middle_name" => "Юрьевна", "login" => "Tatarinova_EY@NMK", "email" => "Elena.Tatarinova@eurochem.ru", ], [ "id" => 263550, "last_name" => "Татаринова", "first_name" => "Ирина", "middle_name" => "Вячеславовна", "login" => "Tatarinova_IV@NMK", "email" => "Irina.Tatarinova@eurochem.ru", ], [ "id" => 6389, "last_name" => "Татаринова", "first_name" => "Любовь", "middle_name" => "Андреевна", "login" => "tatarinovala", "email" => "tatarinovala@sibgenco.ru", ], [ "id" => 8643, "last_name" => "Татаринова", "first_name" => "Наталья", "middle_name" => "Владимировна", "login" => "TatarinovaNV", "email" => "TatarinovaNV@sibgenco.ru", ], [ "id" => 258276, "last_name" => "Татаринцев", "first_name" => "Андрей", "middle_name" => "Иванович", "login" => "TatarintcevAI", "email" => "TatarintcevAI@sibgenco.ru", ], [ "id" => 239804, "last_name" => "Татаринцев", "first_name" => "Евгений", "middle_name" => "Григорьевич", "login" => "TatarintsevEG", "email" => "TatarintsevEG@sibgenco.ru", ], [ "id" => 259975, "last_name" => "Татаринцева", "first_name" => "Наталья", "middle_name" => "Ивановна", "login" => "Tatarinceva_NI@KSP", "email" => "Natalya.Tatarinceva@eurochem.ru", ], [ "id" => 267744, "last_name" => "Татаринцева", "first_name" => "Наталья", "middle_name" => "Ивановна", "login" => "Tatarintseva_NI@TGP", "email" => "Natalya.Tatarintseva@tulagiprochem.ru", ], [ "id" => 3671, "last_name" => "Татаркин", "first_name" => "Александр", "middle_name" => "Васильевич", "login" => "TatarkinAVa", "email" => "TatarkinAVa@sibgenco.ru", ], [ "id" => 202191, "last_name" => "Татаркина", "first_name" => "Мария", "middle_name" => "Николаевна", "login" => "TatarkinaMN", "email" => "TatarkinaMN@suek.ru", ], [ "id" => 101803, "last_name" => "Татарникова", "first_name" => "Екатерина", "middle_name" => "Анатольевна", "login" => "TatarnikovaEA", "email" => "TatarnikovaEA@sibgenco.ru", ], [ "id" => 66505, "last_name" => "Татарникова", "first_name" => "Ия", "middle_name" => "Ильинична", "login" => "TatarnikovaII", "email" => "TatarnikovaII@sibgenco.ru", ], [ "id" => 201056, "last_name" => "Татарникова", "first_name" => "Лариса", "middle_name" => "Федоровна", "login" => "TatarnikovaLF", "email" => "TatarnikovaLF@suek.ru", ], [ "id" => 247551, "last_name" => "Татарникова", "first_name" => "Юлия", "middle_name" => "Николаевна", "login" => "TatarnikovaIuN", "email" => "TatarnikovaIuN@suek.ru", ], ];
    }

    function adduser( $props ) {
        // if ( !checkDataSet( $props, ['search'] )) return null;
        // $userMames = getAddUserNames( $props );
        // return $userMames;
        return [ [ "id" => "1952", "last_name" => "Фурс", "first_name" => "Сергей", "middle_name" => "Николаевич", "login" => "FursSN", "email" => "FursSN@sibgenco.ru", ] ];
    }

    function user( $props ) {
        // if ( !checkDataSet( $props, ['id'] )) return array();
        // $userData = getUserDataApp12( $props );
        // if ( empty( $userData )) return array();
        // $props['login'] = $userData['login'];
        // $props['domain'] = $userData['domain'];
        // $userData['sap_login'] = get_remote_login( $props );
        // $props['app12_id'] = $userData['id'];
        // $userData['company'] = getCompanyByUserId( $props );           
        // $userData['branch'] = getBranchByUserId( $props );           
        // $userData['sap_branch'] = getSapBranchByUserId( $props );           
        // return $userData;
        return ["id"=>1833, "last_name"=>"Татаренко", "first_name"=>"Евгений", "middle_name"=>"Геннадьевич", "login"=>"TatarenkoEG", "phone1"=>"", "phone2"=>"", "position_name"=>"Ведущий специалист", "email"=>"TatarenkoEG@sibgenco.ru", "location"=>"", "div_name"=>"", "app22_id"=>17940, "subdivision_id"=>18908, "domain"=>"SUEKCORP", "ad_user"=>"SUEKCORP\TatarenkoEG", "main_subdivision_name"=>"ООО «Сибирская генерирующая компания»", "address"=>"пр-кт Ленина, 90/3", "mts_person_id"=>7376, "app03_id"=>6910, "ad_id"=>35400, "office_number"=>619, "city"=>"Кемерово", "region"=>"Кузбасский филиал ООО «Сибирская Генерирующая Компания»", "given_name"=>"Татаренко Евгений Геннадьевич", "state"=>"Enabled", "sap_login"=>"", "company"=>["hrs01_id"=>97, "name"=>"ООО «Сибирская генерирующая компания»", ],  "branch"=>["hrs05_id"=>219, "name"=>"Кузбасский филиал ООО «Сибирская Генерирующая Компания»", ], "sap_branch"=>["asz01_id"=>22, "name"=>"Кузбасский филиал ООО \"СГК\"", ],];
    }

    function remote( $props ) {
        // $props['login'] = $_SERVER['REMOTE_USER'];
        // $props['id'] = get_app12_id_by_login( $props );
        // return  getUserDataApp12( $props );
        return ["id"=>"1833", "last_name"=>"Татаренко", "first_name"=>"Евгений", "middle_name"=>"Геннадьевич", "login"=>"TatarenkoEG", "phone1"=>"33833", "phone2"=>"", "position_name"=>"Ведущий специалист", "email"=>"TatarenkoEG@sibgenco.ru", "location"=>"Кемерово, пр. Ленина, 90/3, 619", "div_name"=>"Отдел развития информационных систем", "app22_id"=>"17940", "subdivision_id"=>"18908", "domain"=>"SUEKCORP", "ad_user"=>"SUEKCORP\TatarenkoEG", "main_subdivision_name"=>"ООО «Сибирская генерирующая компания»", "address"=>"пр-кт Ленина, 90/3", "mts_person_id"=>"7376", "app03_id"=>"6910", "ad_id"=>"35400", "office_number"=>"619", "city"=>"Кемерово", "region"=>"Кузбасский филиал ООО «Сибирская Генерирующая Компания»", "given_name"=>"Татаренко Евгений Геннадьевич", "state"=>"Enabled",];
    }

    function userlang( $props ) {
        // if ( !checkDataSet( $props, ['app12_id'] )) return '';
        // $lang = isset( $props['lang'] ) ? cleanData( $props['lang'] ) : null;
        // if ( $lang ) setUserLang( $props );
        // return getUserLang( $props );

        if ( $props['lang'] == 'EN' ) return 'EN';
        else if ( $props['lang'] == 'RU' ) return 'RU';
        else return 'RU';
    }