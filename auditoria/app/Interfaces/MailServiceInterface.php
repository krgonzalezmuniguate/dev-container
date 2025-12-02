<?php

namespace App\Interfaces;

interface MailServiceInterface
{
    /**
     * Envía un correo electrónico.
     *
     * @param string|array $to El destinatario o destinatarios.
     * @param string $subject El asunto del correo.
     * @param string $view La vista de Blade para el contenido.
     * @param array $data Los datos a pasar a la vista.
     * @return void
     */
    public function send($to, string $subject, string $view, array $data = []): void;
}
