<?php
const TOKEN_ANDERCODE = "CLAVESECRETA";
const WEBHOOK_URL = "https://sgequito.ism.edu.ec/desarrollo/backend/web/webhook.php";


function verificarToken($req, $res)
{
    try {
        $token = $req['hub_verify_token'];
        $challenge = $req['hub_challenge'];

        if (isset($challenge) && isset($token) && $token == TOKEN_ANDERCODE) {
            $res->send($challenge);
        } else {
            $res->status(400)->send();
        }
    } catch (Exception $e) {
        $res->status(400)->send();
    }
}

function recibirMensajes($req, $res)
{
    try {
        $res->send("EVENT RECEIVED");
    } catch (Exception $e) {
        $res->send("EVENT RECEIVED");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $data = json_decode($input, true);

        recibirMensajes($data, http_response_code());
    } elseif ($_REQUEST['REQUEST_METHOD'] === 'GET') {
        if (
            isset($_GET['hub_mode']) && isset($_GET['hub_verify_token'])
            && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe'
            && $_GET['hub_verify_token'] === TOKEN_ANDERCODE
        ) {

            echo $_GET['hub_challenge'];
        }
    } else {
        http_response_code(403);
    }
}
