<?php

namespace App\Listeners;

use App\Events\EmailEvent;
use App\Interfaces\MailServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class EmailListener implements ShouldQueue
{
     use InteractsWithQueue, SerializesModels;
    private MailServiceInterface $mailService;
    public function __construct(MailServiceInterface $mailService)
    {
        $this->mailService = $mailService;
    }
    public function handle(EmailEvent $event): void
    {
        try {
            // dd('Listener EmailListener manejando el evento con el payload:', $event->payload);
            Log::info('Listener EmailListener manejando el evento con el payload:', $event->payload);
            $payload = $event->payload;
            // Asegúrate de que los datos mínimos están presentes
            if (!isset($payload['to']) || !isset($payload['subject']) || !isset($payload['view']) || !isset($payload['data'])) {
                Log::error('Datos de correo incompletos en el evento EmailEvent.', ['payload' => $payload]);
                return;
            }
            Log::info('Ejecutando envío de correo para: ' . (is_array($payload['to']) ? json_encode($payload['to']) : $payload['to']));
            $this->mailService->send(
                $payload['to'],
                $payload['subject'],
                $payload['view'],
                $payload['data']
            );
            Log::info('Correo enviado con éxito.');
        } catch (\Throwable $e) {
            Log::error('Error en EmailListener: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}
