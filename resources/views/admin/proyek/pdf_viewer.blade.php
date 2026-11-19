<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview PDF — {{ $proyek->nama_proyek }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #1e3352; display: flex; flex-direction: column; height: 100vh; }

        .toolbar {
            background: #1e3352;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
            border-bottom: 3px solid #f7bd55;
        }

        .toolbar .title {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .toolbar .title span {
            color: #f7bd55;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f7bd55;
            color: #1e3352;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .btn-download:hover { background: #e6ac44; }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .btn-back:hover { background: rgba(255,255,255,0.2); }

        iframe {
            flex: 1;
            width: 100%;
            border: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/LogoNIT.png') }}?v=2">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/LogoNIT.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('images/LogoNIT.png') }}?v=2">
</head>
<body>

    <div class="toolbar">
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">
            <a href="javascript:history.back()" class="btn-back">
                ← Kembali
            </a>
            <div class="title">
                Preview PDF — <span>{{ $proyek->nama_proyek }}</span>
            </div>
        </div>
        <a href="data:application/pdf;base64,{{ $pdfBase64 }}"
           download="{{ $filename }}"
           class="btn-download">
            ⬇ Unduh PDF
        </a>
    </div>

    <iframe
        src="data:application/pdf;base64,{{ $pdfBase64 }}"
        title="Preview PDF {{ $proyek->nama_proyek }}">
    </iframe>

    <script src="{{ asset('js/mobile-ux.js') }}" defer></script>
</body>
</html>
