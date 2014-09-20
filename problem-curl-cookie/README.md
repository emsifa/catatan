PHP Problem Curl Cookie
=======================

Entah ini bug extensi cURL atau emang sengaja dibuat begitu karena masalah security atau apalah itu. 
Pokoknya entah kenapa cURL extension bawaan PHP nggak mau ngirim cookie current PHPSESSID ke server yg sama.

Jadi gini, gw mau buat tools dimana tools itu punya fitur
untuk testing RESTful service semacem Postman gitulah. 
Nah untuk request ke REST route itu gw manfaatin cURL.
Karena bug ini, fitur ini jadi nggak bisa auto login (dengan native session PHP) di current session yang lagi jalan.
Jadi setiap mau coba route yang butuh autentikasi via PHP $_SESSION, mesti login ulang karena PHPSESSIDnya beda.

#### Coba pake CURLOPT_COOKIEJAR + CURLOPT_COOKIEFILE
Udah, tapi tetep aja kalo PHPSESSID yang dikirim itu si PHPSESSID 
di controller yang dipake buat nge-cURL hasilnya tetep sama... -_-