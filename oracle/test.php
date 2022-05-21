<?php 
    include_once('driver.php');

    // Testing of connections to DB.
    $conn = connection('TEST');
    check($conn, 'Connection to TEST');
    ocilogoff($conn);

    $conn = connection('PROD');
    check($conn, 'Connection to PROD');
    ocilogoff($conn);

    $conn = connection('PROD');

    // Testing select from DB
    $data = select($conn, "SELECT APP12.LOGIN DN__LOGIN FROM APP12_ADDRESSBOOK APP12 WHERE APP12.ID = 1833");
    check($data[0]['login'] === 'TatarenkoEG', 'Select');
    
    // Testing of getting data duto DB-function
    $data = execute($conn, 
        'FUNCTION p_asz_util.get_app12_id_by_login(login_in IN VARCHAR2) RETURN NUMBER',
        ['login_in' => 'suekcorp\tatarenkoeg']
    );
    check($data === '1833', 'Function execute');
    
    // Testing of geting data duto DB-procedure
    $data = execute($conn, 
            'PROCEDURE p_asz62.get_pcsign_info_by_snils(	
                snils_in IN VARCHAR2,
                agree_code_out OUT VARCHAR2,
                agree_reason_log_out OUT VARCHAR2,
                agree_reason_log_tech_out OUT VARCHAR2,
                agree_reason_show_out OUT VARCHAR2,
                agree_webform_enable_flag_out OUT NUMBER
            )', [
                'snils_in' => '12345678964', 
            ]);
    check($data['snils_in'] === '12345678964', 'Procedure execute');

    ocilogoff($conn);