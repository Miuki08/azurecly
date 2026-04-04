@component('mail::message')
# Eskalasi Berita

Yth,

Berikut detail eskalasi berita:

**Judul:** {{ $ticket->Title }}

**Kategori:** {{ $ticket->Category }}  
**Sentimen:** {{ ucfirst($ticket->Sentiment) }}  
**Prioritas:** {{ ucfirst($ticket->Priority) }}

@if($ticket->PublishedDate)
**Tanggal Publikasi:** {{ $ticket->PublishedDate->format('d F Y H:i') }}
@endif

@if($ticket->Location)
**Lokasi:** {{ $ticket->Location }}
@endif

---

**Pesan Eskalasi:**

{{ $log->Message }}

@component('mail::button', ['url' => route('tickets.show', $ticket->id)])
Lihat Detail Berita
@endcomponent

Salam hormat,  
{{ config('app.name') }}
@endcomponent