<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <style>
        ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        li {
            margin-bottom: 12px;
        }
        ul h3 {
            margin-bottom: 4px;
        }
        ul a {
            color: #424242;
            text-decoration: none;
        }
        ul a:hover {
            text-decoration: underline;
        }
    </style>
    <title>Services</title>
</head>
<body>
    <ul>
        <li>
            <h3>Users search</h3>
            <a href = "http://localhost/finorg/?data=names&search=%D0%B8%D0%B2%D0%B0%D0%BD%D0%BE%D0%B2%20%D1%81%D0%B5%D1%80%D0%B3%D0%B5%D0%B9&system=SAP&key=fL1XVQ5CeeyZ6sBcQlgthfoXeZDxqY">
                http://localhost/finorg/?data=names&search=иванов сергей&system=SAP&key=[YOUR_KEY]
            </a>
            <h3>Additional users search</h3>
            <a href = "http://localhost/finorg/?data=adduser&search=фур&system=SAP&asz01_id=22&ids=1833&key=fL1XVQ5CeeyZ6sBcQlgthfoXeZDxqY">
                http://localhost/finorg/?data=adduser&search=фур&system=SAP&asz01_id=22&ids=1833&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get user data</h3>
            <a href = "http://localhost/finorg/?data=user&id=1833&key=CrgFJ2MlXCB1JZXw94kqzg3fZZL1wK">
                http://localhost/finorg/?data=user&id=1833&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=user&id=1833&asz00_id=1121&key=CrgFJ2MlXCB1JZXw94kqzg3fZZL1wK">
                http://localhost/finorg/?data=user&id=1833&asz00_id=1121&key=[YOUR_KEY]
            </a>
        </li>
        <li>
        <li>
            <h3>Get system data</h3>
            <a href = "http://localhost/finorg/?data=systemdata&asz24_id=21&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=systemdata&asz24_id=21&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=systemdata&url=http://request-tst.sibgenco.local/corpsystems/&path=/sap_devform/&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=systemdata&url=http://request-tst.sibgenco.local/corpsystems/&path=/sap_devform/&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=systemdata&lang=EN&url=http://request-tst.sibgenco.local/corpsystems/&path=/sap_devform/&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=systemdata&lang=EN&url=http://request-tst.sibgenco.local/corpsystems/&path=/sap_devform/&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get SAP system list</h3>
            <a href = "http://localhost/finorg/?data=sapsystems&asz22_id=1&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=sapsystems&asz22_id=1&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get location list</h3>
            <a href = "http://localhost/finorg/?data=locations&hrs05_id=219&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=locations&hrs05_id=219&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get company list</h3>
            <a href = "http://localhost/finorg/?data=companies&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=companies&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get branch list</h3>
            <a href = "http://localhost/finorg/?data=branches&hrs01_id=97&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=branches&hrs01_id=97&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get division list</h3>
            <a href = "http://localhost/finorg/?data=divisions&hrs05_id=219&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=divisions&hrs05_id=219&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get loggeed user data</h3>
            <a href = "http://localhost/finorg/?data=remote&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=remote&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get main page data</h3>
            <a href = "http://localhost/finorg/?data=mainpage&lang=EN&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=mainpage&leng=EN&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=mainpage&lang=RU&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=mainpage&leng=RU&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get /Set user language</h3>
            <a href = "http://localhost/finorg/?data=userlang&app12_id=1833&lang=EN&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=userlang&app12_id=1833&lang=EN&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=userlang&app12_id=1833&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=userlang&app12_id=1833&key=[YOUR_KEY]
            </a>
        </li>
        <li>
            <h3>Get a phrase</h3>
            <a href = "http://localhost/finorg/?data=phrase&form_name=mainpage&lang=EN&phrase=head_systemname&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=phrase&form_name=mainpage&lang=EN&phrase=head_systemname&key=[YOUR_KEY]
            </a>

        </li>

        <li>
            <h3>Get process groups</h3>
            <a href = "http://localhost/finorg/?data=groups&asz00_id=1&asz01_id=22&app12_id=1833&app12_id_author=1833&order_type=ADD_PRIVS&instance_type=PROD&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=groups&asz00_id=1&asz01_id=22&app12_id=1833&app12_id_author=1833&order_type=ADD_PRIVS&instance_type=PROD&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Get all roles / group roles</h3>
            <a href = "http://localhost/finorg/?data=roles&asz00_id=1&asz01_id=22&app12_id=1833&app12_id_author=1833&order_type=ADD_PRIVS&instance_type=PROD&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=roles&asz00_id=1&asz01_id=22&app12_id=1833&app12_id_author=1833&order_type=ADD_PRIVS&instance_type=PROD&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=roles&asz02_id=44&asz00_id=1&asz01_id=22&app12_id=1833&app12_id_author=1833&order_type=ADD_PRIVS&instance_type=PROD&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=roles&asz02_id=44&asz00_id=1&asz01_id=22&app12_id=1833&app12_id_author=1833&order_type=ADD_PRIVS&instance_type=PROD&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Get role group</h3>
            <a href = "http://localhost/finorg/?data=group&asz00_id=1&asz03_id=663&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=group&asz00_id=1&asz03_id=663&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Clearing settings of role selection</h3>
            <a href = "http://localhost/finorg/?data=unlock&session_key=session_key&cnt=1&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=unlock&session_key=session_key&cnt=1&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=unlock&session_key=session_key&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=unlock&session_key=session_key&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Getting a list of role levels</h3>
            <a href = "http://localhost/finorg/?data=levels&asz03_id=50407&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
                http://localhost/finorg/?data=levels&asz03_id=50407&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Getting level values</h3>
            <a href = "http://localhost/finorg/?data=levelvalues&asz05_id=9115&session_key=TEST&cnt=1&asz00_id=1121&asz03_id=663&app12_id=1833&order_type=ADD_PRIVS&asz22_id=50407&process_group=TEST&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
            http://localhost/finorg/?data=levelvalues&asz05_id=9115&session_key=TEST&cnt=1&asz00_id=1121&asz03_id=663&app12_id=1833&order_type=ADD_PRIVS&asz22_id=50407&process_group=TEST&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Adding and removing list of asz06_id during selection level values</h3>
            <a href = "http://localhost/finorg/?data=runlevelvalues&mode_asz06_id_list=add&asz06_id_previous_list=1,3&asz06_id_list=1,2,3&session_key=TEST&cnt=1&asz03_id=663&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
            http://localhost/finorg/?data=runlevelvalues&mode_asz06_id_list=add&asz06_id_previous_list=1,3&asz06_id_list=1,2,3&session_key=TEST&cnt=1&asz03_id=663&key=[YOUR_KEY]
            </a>
            <br>
            <a href = "http://localhost/finorg/?data=runlevelvalues&mode_asz06_id_list=del&asz06_id_previous_list=1,2,3&session_key=TEST&cnt=1&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
            http://localhost/finorg/?data=runlevelvalues&mode_asz06_id_list=del&asz06_id_previous_list=1,2,3&session_key=TEST&cnt=1&key=[YOUR_KEY]
            </a>
        </li>

        <li>
            <h3>Role approval process information</h3>
            <a href = "http://localhost/finorg/?data=getroleagree&asz01_id=22&asz03_id=663&session_key=TEST&cnt=1&app12_id_boss=1833&asz22_id=1&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS">
            http://localhost/finorg/?data=getroleagree&asz01_id=22&asz03_id=663&session_key=TEST&cnt=1&app12_id_boss=1833&asz22_id=1&key=N7Ej1YO2kqFH2FnqNiKA6tm980bwMS&key=[YOUR_KEY]
            </a>
        </li>





        <li>
            <h3>Tests</h3>
            <a href = "http://localhost/finorg/test.php">
                TESTS
            </a>
        </li>
    </ul>
</body>
</html>
 