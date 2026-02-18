<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\MedicinaOcupacional\Dotacion;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class NuevaDotacionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $dotacion;
    public $qrCode;

    public function __construct(Dotacion $dotacion)
    {
        $this->dotacion = $dotacion;
        // Generamos el QR en Base64 aquí mismo para embeberlo en el HTML
        $this->qrCode = base64_encode(
            QrCode::format('png')->size(150)->generate(route('medicina.dotaciones.validar', $dotacion->qr_token))
        );
    }

    public function build()
    {
        return $this->subject('🔔 Nueva Solicitud de EPP - ' . $this->dotacion->paciente->nombre_completo)
                    ->view('MedicinaOcupacional.dotaciones.emails.nueva_dotacion');
    }
}