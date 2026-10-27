@component('mail::message')
# Halo {{ is_object($user) ? $user->name : ($user['name'] ?? 'Pengguna') }},

{{ $pesan }}

@component('mail::panel')
Pemberitahuan resmi dari sistem Minimarket Baru terkait status akun, transaksi kasir, dan layanan operasional toko.
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Lihat Dashboard
@endcomponent

Jika Anda memiliki pertanyaan atau kendala, silakan hubungi tim dukungan kami.

Terima kasih,<br>
**{{ config('app.name', 'Minimarket POS') }} Team**
@endcomponent
