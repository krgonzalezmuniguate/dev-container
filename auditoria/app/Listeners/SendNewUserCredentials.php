<?php

namespace App\Listeners;

use App\Events\NewUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Interfaces\MailServiceInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNewUserCredentials implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private MailServiceInterface $mailService;

    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }
public function handle(NewUserCreated $event): void
{
    try {
        Log::info('Listener ejecutando envío de correo para: ' . $event->user->email);

        $this->mailService->send(
            $event->user->email,
            'Tus credenciales de acceso',
            'emails.new-user-credentials',
            [
                'user'     => $event->user,
                'password' => $event->password,
            ]
        );

        Log::info('Correo enviado con éxito');

    } catch (\Throwable $e) {
        Log::error('Error enviando correo en listener: ' . $e->getMessage());
        Log::error($e->getTraceAsString());
        // Opcional: vuelve a lanzar para que Laravel registre el fallo y lo reintente si quieres
        throw $e;
    }
}
}
