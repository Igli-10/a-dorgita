<?php
require_once __DIR__ . '/../models/ContactoDAO.php';

class ContactoController
{
    private ContactoDAO $model;

    // Inicializa a sesión e o modelo DAO
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new ContactoDAO();
    }

    // Acción pa mostrar a páxina de contacto
    public function index()
    {
        require_once __DIR__ . '/../../includes/header.php';
        require_once __DIR__ . '/../../views/contacto.php';
        require_once __DIR__ . '/../../includes/footer.php';
    }

    // Acción para procesar o envío do formulario de contacto
    public function enviar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validación do formulario
            $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $mensaxe = isset($_POST['mensaxe']) ? trim($_POST['mensaxe']) : '';

            // Valido que todos os campos estean preenchidos
            if (empty($nome) || empty($email) || empty($mensaxe)) {
                $_SESSION['mensaje'] = "Por favor, rella todos os campos.";
                $_SESSION['tipo_mensaje'] = "danger";
            } 
            // Valido o formato do email
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['mensaje'] = "Por favor, introduce un correo electrónico válido.";
                $_SESSION['tipo_mensaje'] = "danger";
            }
            // Valido a lonxitude mínima da mensaxe
            elseif (strlen($mensaxe) < 10) {
                $_SESSION['mensaje'] = "A mensaxe debe ter alomenos 10 caracteres.";
                $_SESSION['tipo_mensaje'] = "danger";
            }
            else {
                // Limpo os datos para evitar inxección SQL
                $nome = htmlspecialchars($nome, ENT_QUOTES, 'UTF-8');
                $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
                $mensaxe = htmlspecialchars($mensaxe, ENT_QUOTES, 'UTF-8');

                // Intento gardar a mensaxe
                if ($this->model->gardarMensaxe($nome, $email, $mensaxe)) {
                    $_SESSION['mensaje'] = "¡Grazas pola túa mensaxe! Poñeremosnos en contacto contigo pronto.";
                    $_SESSION['tipo_mensaje'] = "success";
                } else {
                    $_SESSION['mensaje'] = "Erro ao enviar a mensaxe. Por favor, intenta de novo.";
                    $_SESSION['tipo_mensaje'] = "danger";
                }
            }

            // Redirixo á páxina de contacto
            header("Location: index.php?c=contacto&a=index");
            exit;
        }
    }
}

