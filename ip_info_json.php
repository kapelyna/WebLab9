<?php
header('Content-Type: application/json');

$ip = isset($_POST['ip']) ? $_POST['ip'] : null;

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://ip-api.com/json/' . urlencode($ip));
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($curl);
$error = curl_error($curl); // Зберігаємо можливі помилки cURL
curl_close($curl);

if ($error) {
    // Помилка при виконанні cURL-запиту
    echo json_encode(['error' => 'cURL Error: ' . $error]);
} else {
    $data = json_decode($result, true);

    if ($data !== null && $data['status'] === 'success') {
        $countryCode = $data['countryCode'];
        $countryName = $data['country'];
        $region = $data['region'];
        $regionName = $data['regionName'];
        $city = $data['city'];
        $latitude = $data['lat'];
        $longitude = $data['lon'];
        $postalCode=$data['zip'];

        echo json_encode([
            'ip_address' => $ip,
            'country' => $countryName,
            'flag_url' => "/flags_ISO_3166-1/$countryCode.png",
            'country_code' => $countryCode,
            'region' => $region,
            'regionName' => $regionName,
            'city' => $city,
            'postal_code'=>$postalCode,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        exit;
    } else {
        $message=$data['message'];
        echo json_encode(['message'=>$message]);
    }
}
?>
