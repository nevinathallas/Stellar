<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Rental #{{ $rental->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        
        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 28px;
            color: #1e40af;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .header .tagline {
            font-size: 14px;
            color: #666;
            font-style: italic;
        }
        
        .invoice-title {
            background: #1e40af;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        /* Info Section */
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .info-box {
            margin-bottom: 15px;
        }
        
        .info-box h3 {
            font-size: 12px;
            color: #1e40af;
            text-transform: uppercase;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        
        .info-box p {
            margin: 3px 0;
            font-size: 11px;
        }
        
        .info-box .label {
            display: inline-block;
            width: 110px;
            font-weight: bold;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-ongoing {
            background: #fbbf24;
            color: #000;
        }
        
        .status-returned {
            background: #10b981;
            color: white;
        }
        
        .status-overdue {
            background: #ef4444;
            color: white;
        }
        
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        table th {
            background: #1e40af;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        table tr:last-child td {
            border-bottom: 2px solid #000;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Total Section */
        .total-section {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        
        .total-row {
            display: table;
            width: 100%;
            padding: 5px 0;
        }
        
        .total-label {
            display: table-cell;
            text-align: right;
            padding-right: 20px;
            font-weight: bold;
        }
        
        .total-value {
            display: table-cell;
            text-align: right;
            width: 120px;
        }
        
        .grand-total {
            background: #1e40af;
            color: white;
            padding: 10px;
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .warning-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 10px;
            margin: 20px 0;
            text-align: center;
        }
        
        .warning-box strong {
            color: #dc2626;
            font-size: 13px;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #000;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 200px;
            display: inline-block;
        }
        
        .notes {
            background: #f3f4f6;
            padding: 15px;
            margin-top: 20px;
            font-size: 10px;
            border-left: 4px solid #1e40af;
        }
        
        .notes h4 {
            font-size: 11px;
            margin-bottom: 5px;
            color: #1e40af;
        }
        
        .notes ul {
            margin-left: 20px;
        }
        
        .notes li {
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🌍 Stellar Rent</h1>
            <p class="tagline">Sistem Penyewaan Planet Terpercaya</p>
            <p style="font-size: 10px; margin-top: 5px;">
                Jl. Galaksi Bima Sakti No. 123, Solar System 12345<br>
                Tel: (021) 1234-5678 | Email: info@stellarrent.com
            </p>
        </div>
        
        <!-- Invoice Title -->
        <div class="invoice-title">
            Invoice Penyewaan Planet
        </div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-box">
                    <h3>Informasi Penyewa</h3>
                    <p><span class="label">Nama</span>: {{ $rental->user->name }}</p>
                    <p><span class="label">Email</span>: {{ $rental->user->email }}</p>
                    <p><span class="label">Terdaftar</span>: {{ $rental->user->created_at->format('d F Y') }}</p>
                </div>
            </div>
            <div class="info-right">
                <div class="info-box">
                    <h3>Detail Invoice</h3>
                    <p><span class="label">No. Invoice</span>: <strong>#INV-{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                    <p><span class="label">Tanggal Cetak</span>: {{ now()->format('d F Y, H:i') }} WIB</p>
                    <p><span class="label">Status</span>: 
                        @if($rental->status == 'ongoing')
                            @if($rental->due_date && $rental->due_date->isPast())
                                <span class="status-badge status-overdue">Terlambat</span>
                            @else
                                <span class="status-badge status-ongoing">Sedang Sewa</span>
                            @endif
                        @else
                            <span class="status-badge status-returned">Dikembalikan</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Planet Details -->
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Planet</th>
                    <th width="20%">Kategori</th>
                    <th width="15%" class="text-center">Durasi</th>
                    <th width="15%" class="text-right">Harga/Hari</th>
                    <th width="15%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong style="font-size: 12px;">{{ $rental->unit->name }}</strong><br>
                        <small style="color: #666;">Kode: {{ $rental->unit->code }}</small>
                    </td>
                    <td>
                        @foreach($rental->unit->categories as $category)
                            <span style="background: #dbeafe; padding: 2px 6px; border-radius: 3px; font-size: 9px; display: inline-block; margin: 2px 0;">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </td>
                    <td class="text-center">
                        <strong>{{ $rental->duration_days }} hari</strong><br>
                        <small style="color: #666;">
                            {{ $rental->rental_date ? $rental->rental_date->format('d/m/Y') : '-' }} - 
                            {{ $rental->due_date ? $rental->due_date->format('d/m/Y') : '-' }}
                        </small>
                    </td>
                    <td class="text-right">Rp {{ number_format($rental->unit->price_per_day, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($rentalPrice, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Late Fee Warning -->
        @if($fine > 0)
            <div class="warning-box">
                <strong>⚠️ DENDA KETERLAMBATAN</strong><br>
                @if($rental->status == 'ongoing')
                    Planet ini terlambat dikembalikan selama <strong>{{ $rental->calculateDaysLate() }} hari</strong>.<br>
                @else
                    Planet dikembalikan terlambat <strong>{{ $rental->calculateDaysLate() }} hari</strong>.<br>
                @endif
                Denda: Rp 100.000 per hari x {{ $rental->calculateDaysLate() }} hari = <strong>Rp {{ number_format($fine, 0, ',', '.') }}</strong>
            </div>
        @endif
        
        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Subtotal Sewa:</div>
                <div class="total-value">Rp {{ number_format($rentalPrice, 0, ',', '.') }}</div>
            </div>
            @if($fine > 0)
                <div class="total-row" style="color: #dc2626;">
                    <div class="total-label">Denda Keterlambatan:</div>
                    <div class="total-value">Rp {{ number_format($fine, 0, ',', '.') }}</div>
                </div>
            @endif
            <div class="grand-total">
                <div class="total-row">
                    <div class="total-label">TOTAL PEMBAYARAN:</div>
                    <div class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
        
        <div style="clear: both;"></div>
        
        <!-- Payment Proof Info -->
        @if($rental->payment_proof)
            <div style="margin-top: 30px; padding: 10px; background: #f0fdf4; border: 1px solid #10b981;">
                <p style="margin: 0; font-size: 11px;">
                    <strong style="color: #10b981;">✓ Bukti Pembayaran:</strong> Telah diupload oleh member pada {{ $rental->return_requested_at ? $rental->return_requested_at->format('d F Y, H:i') : '-' }} WIB
                </p>
                @if($rental->notes)
                    <p style="margin: 5px 0 0 0; font-size: 10px; color: #666;">
                        <strong>Catatan:</strong> "{{ $rental->notes }}"
                    </p>
                @endif
            </div>
        @endif
        
        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p><strong>Penyewa</strong></p>
                <div class="signature-line"></div>
                <p style="margin-top: 5px;">{{ $rental->user->name }}</p>
            </div>
            <div class="signature-box">
                <p><strong>Admin Stellar Rent</strong></p>
                <div class="signature-line"></div>
                <p style="margin-top: 5px;">{{ now()->format('d F Y') }}</p>
            </div>
        </div>
        
        <!-- Notes -->
        <div class="notes">
            <h4>Syarat & Ketentuan:</h4>
            <ul>
                <li>Invoice ini merupakan bukti sah penyewaan planet.</li>
                <li>Maksimal masa sewa adalah 5 hari. Keterlambatan dikenakan denda Rp 100.000 per hari.</li>
                <li>Planet harus dikembalikan dalam kondisi baik sesuai saat penyewaan.</li>
                <li>Pembayaran harus dilakukan sebelum pengambilan planet.</li>
                <li>Untuk pertanyaan, hubungi customer service kami di (021) 1234-5678.</li>
            </ul>
        </div>
        
        <!-- Footer -->
        <div class="footer text-center">
            <p style="font-size: 10px; color: #666;">
                Dokumen ini digenerate otomatis oleh sistem Stellar Rent<br>
                © {{ now()->year }} Stellar Rent. All Rights Reserved.
            </p>
        </div>
    </div>
</body>
</html>
