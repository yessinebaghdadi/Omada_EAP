<?php
$controllerIP = '192.168.1.109';
$controllerPort = '8043';
$controllerID = '5fa91b0519e58aaee25d2a19f3700cfd';
$operatorUsername = 'yassine';
$operatorPassword = 'yassine';

function loginOperator($username, $password, $controllerIP, $controllerPort, $controllerID) {
    $url = "https://$controllerIP:$controllerPort/$controllerID/api/v2/hotspot/login";
    $loginData = [
        'name' => $username,
        'password' => $password
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($loginData));

    $response = curl_exec($curl);

    if ($response === false) {
        echo 'Erreur : ' . curl_error($curl);
    } else {
        $responseData = json_decode($response, true);
        echo 'Réponse : ';
        var_dump($responseData);
    }

    curl_close($curl);
}

function loginGuest($clientMac, $apMac, $ssidName, $radioId, $site, $time, $controllerIP, $controllerPort, $controllerID) {
    $url = "https://$controllerIP:$controllerPort/$controllerID/api/v2/hotspot/extPortal/auth";
    $loginData = [
        'clientMac' => $clientMac,
        'apMac' => $apMac,
        'ssidName' => $ssidName,
        'radioId' => $radioId,
        'site' => $site,
        'time' => $time,
        'authType' => '1'
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($loginData));

    $response = curl_exec($curl);

    if ($response === false) {
        echo 'Erreur : ' . curl_error($curl);
    } else {
        $responseData = json_decode($response, true);
        echo 'Réponse : ';
        var_dump($responseData);
    }

    curl_close($curl);
}

// Extraction des paramètres de l'URL
$params = [];
parse_str($_SERVER['QUERY_STRING'], $params);

?>

<!doctype html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">

    <title>Custom External CP</title>
  </head>
  <body>
    <h5>Paramètres URL Extraits</h5>
    <p><strong>Client MAC:</strong> <?php echo htmlspecialchars($params['clientMac']); ?></p>
    <p><strong>Client IP:</strong> <?php echo htmlspecialchars($params['clientIp']); ?></p>
    <p><strong>Timestamp:</strong> <?php echo htmlspecialchars($params['t']); ?></p>
    <p><strong>Site:</strong> <?php echo htmlspecialchars($params['site']); ?></p>
    <p><strong>URL de redirection:</strong> <?php echo htmlspecialchars(urldecode($params['redirectUrl'])); ?></p>
    <p><strong>AP MAC:</strong> <?php echo htmlspecialchars($params['apMac']); ?></p>
    <p><strong>Nom du SSID:</strong> <?php echo htmlspecialchars($params['ssidName']); ?></p>
    <p><strong>Radio ID:</strong> <?php echo htmlspecialchars($params['radioId']); ?></p>

    <form method="post">
      <div class="form-group">
        <label for="exampleInputEmail1">Adresse Email</label>
        <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Entrer email">
        <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre email avec quelqu'un d'autre.</small>
      </div>
      <button class="btn btn-primary" type="submit">Soumettre</button>
    </form>

    <?php
    // Lorsque le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // soumission de l'authentification de l'opérateur
        loginOperator($operatorUsername, $operatorPassword, $controllerIP, $controllerPort, $controllerID);
        
        // soumission de l'authentification du client
        loginGuest(
            $params['clientMac'], 
            $params['apMac'], 
            $params['ssidName'], 
            $params['radioId'], 
            $params['site'], 
            $params['t'], 
            $controllerIP, 
            $controllerPort, 
            $controllerID
        );
    }
    ?>

    <!-- JavaScript optionnel -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
  </body>
</html>
