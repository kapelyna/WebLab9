<?php
header('Content-Type: application/xml');

$ip = isset($_POST['ip']) ? $_POST['ip'] : null;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://ip-api.com/xml/' . urlencode($ip));
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($curl);
$error = curl_error($curl); // Зберігаємо можливі помилки cURL
curl_close($curl);

if ($error) {
    // Помилка при виконанні cURL-запиту
    echo '<response><error>cURL Error: ' . $error . '</error></response>';
} else {
    $xml = simplexml_load_string($result);

    if ($xml !== false && $xml->status == 'success') {
        $countryCode = $xml->countryCode;
        $countryName = $xml->country;
        $region = $xml->regionName;
        $regionName = $xml->region;
        $city = $xml->city;
        $latitude = $xml->lat;
        $longitude = $xml->lon;
        $postalCode = $xml->zip;

        echo '<response>';
        echo '<ip_address>' . $ip . '</ip_address>';
        echo '<country>' . $countryName . '</country>';
        echo '<flag_url>/flags_ISO_3166-1/' . $countryCode . '.png</flag_url>';
        echo '<country_code>' . $countryCode . '</country_code>';
        echo '<region>' . $region . '</region>';
        echo '<regionName>' . $regionName . '</regionName>';
        echo '<city>' . $city . '</city>';
        echo '<postal_code>' . $postalCode . '</postal_code>';
        echo '<latitude>' . $latitude . '</latitude>';
        echo '<longitude>' . $longitude . '</longitude>';
        echo '</response>';
        exit;
    } else {
        $message = $xml->message;
        echo '<response><message>' . $message . '</message></response>';
    }
}
?>
