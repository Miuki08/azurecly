<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $ticket->Title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0284c7;
            margin-bottom: 8px;
        }
        .meta {
            font-size: 10px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-right: 5px;
        }
        .badge-sentiment {
            background: {{ $ticket->Sentiment == 'positive' ? '#dcfce7' : ($ticket->Sentiment == 'negative' ? '#fee2e2' : '#fef3c7') }};
            color: {{ $ticket->Sentiment == 'positive' ? '#166534' : ($ticket->Sentiment == 'negative' ? '#991b1b' : '#854d0e') }};
        }
        .badge-priority {
            background: {{ $ticket->Priority == 'high' ? '#fee2e2' : ($ticket->Priority == 'medium' ? '#fef3c7' : '#dcfce7') }};
            color: {{ $ticket->Priority == 'high' ? '#991b1b' : ($ticket->Priority == 'medium' ? '#854d0e' : '#166534') }};
        }
        .content {
            margin-bottom: 20px;
        }
        .description {
            white-space: pre-wrap;
            margin-top: 10px;
        }
        .images {
            margin-top: 20px;
        }
        .images-title {
            font-size: 11px;
            font-weight: bold;
            color: #0284c7;
            margin-bottom: 10px;
        }
        .image-grid {
            display: table;
            width: 100%;
            border-spacing: 8px;
        }
        .image-row {
            display: table-row;
        }
        .image-cell {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
            padding: 4px;
        }
        .image-card {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            overflow: hidden;
            background: #fafafa;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .image-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid #e5e5e5;
        }
        .image-caption {
            font-size: 9px;
            color: #666;
            padding: 6px 8px;
            text-align: center;
            min-height: 28px;
            line-height: 1.3;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $ticket->Title }}</div>
        <div class="meta">
            <span class="badge badge-sentiment">{{ ucfirst($ticket->Sentiment) }}</span>
            <span class="badge badge-priority">Priority: {{ ucfirst($ticket->Priority) }}</span>
            <span>Category: {{ $ticket->Category }}</span> |
            <span>Published: {{ $ticket->PublishedDate ? $ticket->PublishedDate->format('d F Y') : '-' }}</span>
        </div>
    </div>

    <div class="content">
        <div class="description">
            {{ $ticket->Description }}
        </div>

        @if($ticket->images && $ticket->images->count())
            <div class="images">
                <div class="images-title">📎 Attachments ({{ $ticket->images->count() }})</div>
                <div class="image-grid">
                    @php
                        $chunkedImages = $ticket->images->chunk(3);
                    @endphp
                    
                    @foreach($chunkedImages as $chunk)
                        <div class="image-row">
                            @foreach($chunk as $img)
                                <div class="image-cell">
                                    <div class="image-card">
                                        <img src="{{ public_path('storage/'.$img->Path) }}" alt="Image">
                                        <div class="image-caption">
                                            {{ $img->Description ?: 'Attachment' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            {{-- Fill empty cells if less than 3 in row --}}
                            @for($i = $chunk->count(); $i < 3; $i++)
                                <div class="image-cell"></div>
                            @endfor
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="footer">
        Generated by Azurecly on {{ $generated_at }}
    </div>
</body>
</html>