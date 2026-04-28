<?php
// Mostrar errores en pantalla
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'core/Database.php'; 
require_once 'core/Model.php';    
require_once 'app/models/Admin.php'; 

$email = 'master@fegesis.cl';
$pass  = 'AdminFegesis#';

echo "<div style='font-family: sans-serif; padding: 20px;'>";
echo "<h2>🕵️‍♂️ Diagnóstico de Login Admin</h2>";

try {
    // 1. Conectar a la BD con la estructura correcta que exige tu clase Database
    $config = [
        'db' => [
            'host' => 'localhost',
            'name' => 'fegesis_db', // Asegúrate de que este es el nombre de tu base de datos
            'user' => 'root',
            'pass' => ''
        ]
    ];
    
    $pdo = Database::pdo($config);

    // 2. Forzar el cambio de clave a la que tú quieres
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $upd = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE email = ?");
    $upd->execute([$hash, $email]);
    echo "<p>✅ <b>Paso 1:</b> Contraseña actualizada en la BD a: <code>$pass</code></p>";

    // 3. Probar si el Modelo encuentra al admin
    $admin = Admin::findByEmail($email);
    
    if (!$admin) {
        echo "<p style='color:red;'>❌ <b>Paso 2 FALLÓ:</b> El modelo no encontró el correo en la tabla <code>admins</code>.</p>";
    } else {
        echo "<p style='color:green;'>✅ <b>Paso 2:</b> El modelo encontró al usuario en la BD.</p>";
        
        // 4. Probar la verificación
        if (password_verify($pass, $admin['password_hash'])) {
            echo "<p style='color:green;'>✅ <b>Paso 3:</b> Las contraseñas coinciden perfectamente.</p>";
            echo "<h3 style='color: #004aad;'>🎉 ¡Todo está arreglado! Ya puedes ir al panel e iniciar sesión.</h3>";
        } else {
            echo "<p style='color:red;'>❌ <b>Paso 3 FALLÓ:</b> Hay un problema con cómo se está validando el hash.</p>";
        }
    }

} catch (Exception $e) {
    echo "<p style='color:red;'>❌ <b>ERROR:</b> " . $e->getMessage() . "</p>";
}

echo "</div>";