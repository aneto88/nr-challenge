<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('biddings', 'BiddingController@index');
Route::any('test', function () {
    try{
        $url = 'http://portal-adm.cnpq.br/documents/10157/5968902/ANEXO+IV+-+CATA%C2%B4LOGO+DE+SERVIC%C2%B8O+DE+PORTAL-28Dez17-8.pdf/14b9d7a2-8943-44f9-9ef5-d0c787871183';
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        dump($httpCode);

        curl_close($handle);
       
    } catch(Exception $e) {
        echo $e->getMessage();
    }
    echo "teste";
});