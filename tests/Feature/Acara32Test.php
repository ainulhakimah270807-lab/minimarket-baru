<?php

namespace Tests\Feature;

use App\Mail\NotifyUser;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Notifications\InvoicePaid;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class Acara32Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_acara32_index_page()
    {
        $response = $this->get(route('acara32.index'));
        $response->assertStatus(200);
        $response->assertSee('Kirim Email dengan Laravel');
        $response->assertSee('Welcome Mail');
        $response->assertSee('Markdown Email');
        $response->assertSee('Notifikasi Invoice');
        $response->assertSee('MustVerifyEmail');
    }

    public function test_can_send_welcome_mail_via_mail_facade()
    {
        Mail::fake();

        $response = $this->post(route('acara32.send.welcome'), [
            'name'  => 'Ainul Hakimah',
            'email' => 'ainul@example.com',
        ]);

        $response->assertRedirect(route('acara32.index'));
        $response->assertSessionHas('success');

        Mail::assertSent(WelcomeMail::class, function ($mail) {
            return $mail->hasTo('ainul@example.com') &&
                   $mail->user->name === 'Ainul Hakimah';
        });
    }

    public function test_send_welcome_mail_validates_inputs()
    {
        $response = $this->post(route('acara32.send.welcome'), [
            'name'  => '',
            'email' => 'bukan-email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_can_queue_welcome_mail()
    {
        Mail::fake();

        $response = $this->post(route('acara32.queue.welcome'), [
            'name'  => 'Budi Kasir',
            'email' => 'budi@example.com',
        ]);

        $response->assertRedirect(route('acara32.index'));
        $response->assertSessionHas('success');

        Mail::assertQueued(WelcomeMail::class, function ($mail) {
            return $mail->hasTo('budi@example.com');
        });
    }

    public function test_can_send_markdown_notify_user_email()
    {
        Mail::fake();

        $response = $this->post(route('acara32.send.markdown'), [
            'name'  => 'Citra Supervisor',
            'email' => 'citra@example.com',
            'pesan' => 'Laporan penjualan hari ini siap ditinjau.',
        ]);

        $response->assertRedirect(route('acara32.index'));
        $response->assertSessionHas('success');

        Mail::assertSent(NotifyUser::class, function ($mail) {
            return $mail->hasTo('citra@example.com') &&
                   $mail->pesan === 'Laporan penjualan hari ini siap ditinjau.';
        });
    }

    public function test_can_send_invoice_paid_notification()
    {
        Notification::fake();

        $response = $this->post(route('acara32.send.notification'), [
            'name'         => 'Doni Pelanggan',
            'email'        => 'doni@example.com',
            'invoice_code' => 'INV-202610-099',
            'total'        => 250000,
        ]);

        $response->assertRedirect(route('acara32.index'));
        $response->assertSessionHas('success');

        Notification::assertSentOnDemand(
            InvoicePaid::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'doni@example.com';
            }
        );
    }

    public function test_can_preview_welcome_email_in_browser()
    {
        $response = $this->get(route('acara32.preview.welcome'));
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
    }

    public function test_can_preview_markdown_email_in_browser()
    {
        $response = $this->get(route('acara32.preview.markdown'));
        $response->assertStatus(200);
        $response->assertSee('Pemberitahuan');
    }

    public function test_can_preview_notification_in_browser()
    {
        $response = $this->get(route('acara32.preview.notification'));
        $response->assertStatus(200);
        $response->assertSee('INV-202610-0089');
    }

    public function test_user_implements_must_verify_email_contract()
    {
        $user = new User();
        $this->assertInstanceOf(\Illuminate\Contracts\Auth\MustVerifyEmail::class, $user);
    }

    public function test_registration_triggers_registered_event_for_email_verification()
    {
        Event::fake([Registered::class]);

        $response = $this->post('/register', [
            'name'                  => 'Mahasiswa Baru',
            'email'                 => 'mhs@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        Event::assertDispatched(Registered::class);
    }
}
