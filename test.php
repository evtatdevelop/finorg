<?php
    $DB_INST = 'PROD';

    include_once('auxes/utils.php');

    function check($clause, $testName) {
        $results = [
            'success' => "<span class='success'>Ok</span>",
            'fail' => "<span class='fail'>Fail</span>",
        ];
        if ($clause) $result = $results['success'];
        else $result = $results['fail'];
        echo "<li>$testName $result</li>";
        return true;
    }

    echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Testing of services working</title>
            <style>
                h1 {
                    color: #424242;
                }
                fieldset {
                    margin-bottom: 5px;
                }
                legend {
                    padding: 0 3px;
                    font-weight: bold;
                    color: #008080;
                }
                .testList {
                    list-style: none;
                    margin: 0;
                    padding: 0;
                }
                .testList li {
                    display: flex;
                    justify-content: space-between;
                }
                .testList li:hover {
                    background-color: #E3F1FF;
                }
                .testList li span {
                    font-weight: bold;
                }
                .success {
                    color: green;
                }
                .fail {
                    color: red;
                }
                .subLiGroup {
                    font-weight: bold;
                    color: #424242;
                    text-decoration: underline;
                    margin: 3px 0;
                }
            </style>
        </head>
        <body>
    ';    
        $dbInst = isset($DB_INST) ? $DB_INST : 'TEST';
        echo "<h1>Service testing ( $dbInst  )</h1>";

        // ORACLE TESTS
        // 
        echo "<fieldset><legend>Oracle</legend>";
        echo "<ul class='testList'>";
        
            include_once('oracle/test.php');

        echo "</ul>";
        echo "</fieldset>";


        // UTILS TESTS
        // 
        echo "<fieldset><legend>Utils</legend>";
        echo "<ul class='testList'>";

            include_once('auxes/test.php');

        echo "</ul>";
        echo "</fieldset>";


        // MODULES TESTS
        // 
        echo "<fieldset><legend>Modules</legend>";
        echo "<ul class='testList'>";
        
            include_once('modules/test.php');
        
        echo "</ul>";
        echo "</fieldset>";


        // MANAGERS TESTS
        // 
        // echo "<fieldset><legend>Managers</legend>";
        // echo "<ul class='testList'>";

        //     include_once('managers/test.php');

        // echo "</ul>";
        // echo "</fieldset>";

        

    echo "          
    </body>
    </html>";
