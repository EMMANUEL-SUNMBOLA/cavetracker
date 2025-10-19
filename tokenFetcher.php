<?php

function getToken($CA)
{
    if (empty($CA)) {
        return ["error" => "Contract address cannot be empty"];
    }

    $url = "https://api.dexscreener.com/latest/dex/tokens/" . urlencode($CA);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        return ["error" => "Curl error: $error"];
    }

    curl_close($curl);

    $data = json_decode($response, true);

    if (!$data || !isset($data['pairs'][0])) {
        return ["error" => "Invalid or empty response from Dexscreener"];
    }

    $pair = $data['pairs'][0];

    $reply["tokenDetails"] = $pair['baseToken'];
    $reply["marketCap"] = $pair['marketCap'] ?? $pair['fdv'] ?? 0;

    return $reply;
}


function setTarget($CA, $MCP)
{
    $MCP = floatval($MCP);
    $attempts = 0;

    while (true) {
        $data = getToken($CA);

        if (isset($data['error'])) {
            echo $data['error'] . "\n";
            return;
        }

        $current = floatval($data["marketCap"]);
        $name = $data["tokenDetails"]["name"];
        $symbol = $data["tokenDetails"]["symbol"];

        echo "Current market cap of $symbol ($name): $" . number_format($current) . "\n";

        if ($current >= $MCP) {
            return "🎯 Target met! $name ($symbol) has reached $" . number_format($current) . " market cap.\n";
        }

        $attempts++;
        echo "Attempt $attempts — target not yet reached.\n";
        sleep(60); // wait 1 minute before checking again
    }
}


// Example usage
$token = readline("Input your token contract address: ");
print_r(getToken($token));

$target = readline("What's your target market cap (USD)? ");
echo setTarget($token, $target);
