<?php

namespace App\Notifications;

use App\Models\LabBorrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class BorrowingStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $borrowing;
    protected $previousStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(LabBorrowing $borrowing, $previousStatus = null)
    {
        $this->borrowing = $borrowing;
        $this->previousStatus = $previousStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $statusMessages = [
            'menunggu' => 'Permohonan peminjaman lab telah diterima dan sedang menunggu persetujuan',
            'disetujui' => 'Permohonan peminjaman lab Anda telah disetujui',
            'ditolak' => 'Maaf, permohonan peminjaman lab Anda ditolak',
            'dibatalkan' => 'Peminjaman lab Anda telah dibatalkan',
            'digunakan' => 'Peminjaman lab Anda telah dimulai',
            'selesai' => 'Peminjaman lab Anda telah selesai',
            'kadaluarsa' => 'Peminjaman lab Anda telah kadaluarsa'
        ];

        $subject = 'Status Peminjaman Lab: ' . ucfirst($this->borrowing->status);
        $message = $statusMessages[$this->borrowing->status] ?? 'Status peminjaman lab telah diperbarui';

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($message);

        // Tambahkan detail peminjaman
        $mail->line('Detail peminjaman:')
            ->line('Lab: ' . $this->borrowing->lab->name)
            ->line('Tanggal: ' . Carbon::parse($this->borrowing->borrow_date)->format('d F Y'))
            ->line('Waktu: ' . substr($this->borrowing->start_time, 0, 5) . ' - ' . substr($this->borrowing->end_time, 0, 5))
            ->line('Keperluan: ' . $this->borrowing->event);

        // Tambahkan catatan jika ada
        if ($this->borrowing->notes && $this->borrowing->status === 'ditolak') {
            $mail->line('Alasan Penolakan:')
                ->line($this->borrowing->notes);
        }

        // Tambahkan tombol tindakan
        if ($this->borrowing->status === 'disetujui') {
            $mail->action('Lihat Detail & Cetak Bukti', route('borrowing.lab.show', $this->borrowing->id));
        } else {
            $mail->action('Lihat Detail', route('borrowing.lab.show', $this->borrowing->id));
        }

        return $mail;
    }

    public function toDatabase($notifiable)
    {
        $statusIcons = [
            'menunggu' => 'clock',
            'disetujui' => 'check-circle',
            'ditolak' => 'times-circle',
            'dibatalkan' => 'ban',
            'digunakan' => 'play-circle',
            'selesai' => 'check-double',
            'kadaluarsa' => 'calendar-times'
        ];

        return [
            'borrowing_id' => $this->borrowing->id,
            'title' => 'Status Peminjaman Lab: ' . ucfirst($this->borrowing->status),
            'message' => 'Lab ' . $this->borrowing->lab->name . ' pada ' .
                Carbon::parse($this->borrowing->borrow_date)->format('d/m/Y') . ', ' .
                substr($this->borrowing->start_time, 0, 5) . ' - ' . substr($this->borrowing->end_time, 0, 5),
            'status' => $this->borrowing->status,
            'previous_status' => $this->previousStatus,
            'icon' => $statusIcons[$this->borrowing->status] ?? 'info-circle',
            'url' => route('borrowing.lab.show', $this->borrowing->id)
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
