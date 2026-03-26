<?php

require_once __DIR__ . '/../models/UsuarioDAO.php';
require_once __DIR__ . '/../../libs/phpmailer/Exception.php';
require_once __DIR__ . '/../../libs/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../libs/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class RecuperarController
{
    private $usuarioDAO;

    public function __construct()
    {
        // Inicio a sesión só se aínda non está activa
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Instancio o DAO de usuario para acceder á base de datos
        $this->usuarioDAO = new UsuarioDAO();
    }

    // Amoso o formulario de recuperación e proceso a solicitude cando se envía
    public function solicitar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibo e limpo o correo enviado polo usuario
            $email = trim($_POST['email'] ?? '');

            // Valido que o campo non estea baleiro
            if (empty($email)) {
                $_SESSION['mensaxe_aviso'] = "O campo correo non pode estar baleiro.";
                header("Location: index.php?c=recuperar&a=solicitar");
                exit;
            }

            // Valido que o correo teña un formato correcto
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['mensaxe_aviso'] = "O formato do correo non é válido.";
                header("Location: index.php?c=recuperar&a=solicitar");
                exit;
            }

            // Valido que o correo non supere a lonxitude máxima permitida
            if (strlen($email) > 255) {
                $_SESSION['mensaxe_aviso'] = "O correo é demasiado longo.";
                header("Location: index.php?c=recuperar&a=solicitar");
                exit;
            }

            // Xero un token aleatorio seguro e gardo o seu hash na base de datos
            $tokenPlano = bin2hex(random_bytes(32));
            $tokenHash  = hash('sha256', $tokenPlano);
            // O token caduca en 1 hora
            $caducaEn   = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $ok = $this->usuarioDAO->gardarTokenRecuperacion($email, $tokenHash, $caducaEn);

            // Se o correo non está rexistrado, informo ao usuario
            if (!$ok) {
                $_SESSION['mensaxe_aviso'] = "Non existe ningunha conta asociada a ese correo.";
                header("Location: index.php?c=recuperar&a=solicitar");
                exit;
            }

            $_SESSION['mensaxe_aviso'] = "Se o correo existe, recibirás unha ligazón para cambiar o contrasinal.";

            if ($ok) {
                // Constrúo a URL de reset completa con protocolo, host e token codificado
                $proto    = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $host     = $_SERVER['HTTP_HOST'];
                $base     = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $urlReset = $proto . '://' . $host . $base . '/index.php?c=recuperar&a=resetear&token=' . urlencode($tokenPlano);

                try {
                    // Configuro PHPMailer para enviar por SMTP con Gmail
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'alejandroiglesiassantos@gmail.com';
                    $mail->Password   = 'thar onla qajm grsw';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    $mail->CharSet    = 'UTF-8';

                    // Establece o remitente, destinatario e contido do correo
                    $mail->setFrom('alejandroiglesiassantos@gmail.com', 'A Dorgita');
                    $mail->addAddress($email);
                    $mail->Subject = 'Recuperación de contrasinal - A Dorgita';
                    $mail->Body    = "Ola,\n\nPreme a seguinte ligazón para cambiar o teu contrasinal:\n\n"
                                   . $urlReset . "\n\nEsta ligazón caduca en 1 hora.\n\nSe non fuches ti, ignora este correo.";

                    $mail->send();
                } catch (Exception $e) {
                    // Rexistro o erro no log sen mostralo ao usuario
                    error_log("Erro ao enviar correo de recuperación: " . $mail->ErrorInfo);
                }
            }

            header("Location: index.php?c=recuperar&a=solicitar");
            exit;
        }

       
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/recuperar.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    // Amoso o formulario de nova clave e proceso o cambio
    public function resetear()
    {
        // Recibo o token desde a URL (GET) ou desde o formulario (POST)
        $tokenPlano = trim($_GET['token'] ?? ($_POST['token'] ?? ''));

        // Se non hai token, redirixo ao login
        if (empty($tokenPlano)) {
            $_SESSION['mensaxe_aviso'] = "Ligazón non válida.";
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        // Valido que o token só conteña caracteres hexadecimais válidos (64 hex = 32 bytes)
        if (!ctype_xdigit($tokenPlano) || strlen($tokenPlano) !== 64) {
            $_SESSION['mensaxe_aviso'] = "Ligazón non válida.";
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        // Hasheo o token para buscalo na base de datos
        $tokenHash = hash('sha256', $tokenPlano);
        $tokenData = $this->usuarioDAO->validarTokenRecuperacion($tokenHash);

        // Se o token non é válido ou caducou, redirixo ao login
        if (!$tokenData) {
            $_SESSION['mensaxe_aviso'] = "O token non é válido ou xa caducou.";
            header("Location: index.php?c=usuario&a=login");
            exit;
        }

        $erro = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibo as dúas claves introducidas polo usuario
            $nova   = $_POST['nova_contrasinal']   ?? '';
            $repite = $_POST['repite_contrasinal'] ?? '';

            // Valido que os campos non estean baleiros
            if (empty($nova) || empty($repite)) {
                $erro = "Todos os campos son obrigatorios.";
            // Valido a lonxitude mínima
            } elseif (strlen($nova) < 6) {
                $erro = "A nova clave debe ter polo menos 6 caracteres.";
            // Valido a lonxitude máxima
            } elseif (strlen($nova) > 72) {
                $erro = "A nova clave non pode superar os 72 caracteres.";
            // Valido que ambas claves coincidan
            } elseif ($nova !== $repite) {
                $erro = "As claves non coinciden.";
            } else {
                // Hasheo a nova clave e actualízoa na base de datos
                $hashNovo = password_hash($nova, PASSWORD_DEFAULT);

                $okPass  = $this->usuarioDAO->actualizarContrasinalPorId((int)$tokenData['id_usuario'], $hashNovo);
                // Marco o token como usado para que non se poida reutilizar
                $okToken = $this->usuarioDAO->marcarTokenComoUsado((int)$tokenData['id']);

                if ($okPass && $okToken) {
                    $_SESSION['mensaxe_aviso'] = "Contrasinal actualizado. Xa podes iniciar sesión.";
                    header("Location: index.php?c=usuario&a=login");
                    exit;
                }

                $erro = "Non puiden actualizar o contrasinal. Inténtao de novo.";
            }
        }

        
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/reset_password.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }
}
