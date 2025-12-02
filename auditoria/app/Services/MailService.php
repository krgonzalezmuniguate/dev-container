<?php

namespace App\Services;

use App\Interfaces\MailServiceInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class MailService implements MailServiceInterface
{
    /**
     * @param string|array $to El destinatario o destinatarios.
     * @param string $subject El asunto del correo.
     * @param string $view La vista de Blade para el contenido.
     * @param array $data Los datos a pasar a la vista.
     * @return void
     */
    public function send($to, string $subject, string $view, array $data = []): void
    {
        Mail::to($to)->send(new class($subject, $view, $data) extends Mailable {
            public $subject;
            public $viewName;
            public $data;

            public function __construct($subject, $view, $data)
            {
                $this->subject = $subject;
                $this->viewName = $view;
                $this->data = $data;
            }

            public function build()
            {
                return $this->subject($this->subject)
                    ->view($this->viewName)
                    ->with($this->data);
            }
        });
    }
}
