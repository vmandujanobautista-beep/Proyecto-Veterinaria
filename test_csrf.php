<?php
// Test completo: GET login -> captura token -> POST login
$cookieFile = sys_get_temp_dir() . '/vetcare_test_' . time() . '.txt';

// 1. GET al login para obtener token y cookie
$ch = curl_init('http://localhost:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$r = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "PASO 1 - GET /login: HTTP $code\n";

// Buscar CSRF token
preg_match('/name="_token" value="([^"]+)"/', $r, $m);
$token = $m[1] ?? null;
echo "  CSRF token: " . ($token ? substr($token, 0, 15) . '...' : 'NO ENCONTRADO') . "\n";

// Buscar cookie vetcare_session
preg_match_all('/Set-Cookie:\s*([^\r\n]+)/i', $r, $cookies);
$sessionFound = false;
foreach ($cookies[1] as $c) {
    if (strpos($c, 'vetcare_session') !== false || strpos($c, 'laravel-session') !== false) {
        $sessionFound = true;
        echo "  Cookie sesion: RECIBIDA (" . substr($c, 0, 40) . "...)\n";
    }
}
if (!$sessionFound) {
    echo "  Cookie sesion: NO RECIBIDA\n";
}

if (!$token) {
    echo "\nERROR: No se pudo obtener el CSRF token\n";
    exit(1);
}

// 2. POST al login con token y cookies
$ch2 = curl_init('http://localhost:8000/login');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HEADER, true);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookieFile);
curl_setopt($ch2, CURLOPT_POSTFIELDS, http_build_query([
    '_token'   => $token,
    'email'    => 'admin@vetcare.com',
    'password' => 'password',
]));
$r2 = curl_exec($ch2);
$code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo "\nPASO 2 - POST /login: HTTP $code2\n";
if ($code2 === 419) {
    echo "  ❌ RESULTADO: 419 - CSRF AÚN FALLA\n";
} elseif ($code2 === 302) {
    echo "  ✅ RESULTADO: 302 Redirect - CSRF OK, LOGIN EXITOSO!\n";
} elseif ($code2 === 422) {
    echo "  ✅ RESULTADO: 422 - CSRF OK (credenciales incorrectas, pero token válido)\n";
} else {
    echo "  ⚠️  HTTP $code2 - revisar\n";
}

// Limpieza
@unlink($cookieFile);
