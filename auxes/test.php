<?php 
    include_once('auth.php');
    include_once('utils.php');
    include_once('validation.php');

    // Testing of auth
    check(auth('fL1XVQ5CeeyZ6sBcQlgthfoXeZDxqY')
        and !auth('')
        and !auth('tets'), 
        'auth'
    );

    // Testing of keyGen
    check( is_string(keyGen()) and strlen(keyGen()) === 30 and strlen(keyGen(5)) === 5, 'keyGen');

    // Testing of checkDataSet
    check(
        checkDataSet(['test' => 'TST'], ['test'])
        and !checkDataSet(['test' => null], ['test']) 
        and !checkDataSet(['string' => 'TST'], ['test']) 
        and !checkDataSet([], ['test'])
        and checkDataSet([], []) 
        and checkDataSet(['test'], []), 
        'checkDataSet'
    );

    // Testing of cleanData
    check(
        is_string(cleanData('string')) 
        and is_integer(cleanData('1', 'i')) 
        and cleanData('1', 'i') == 1 
        and cleanData('sting', 'i') == 0, 
        'cleanData'
    );

