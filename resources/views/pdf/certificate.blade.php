<!DOCTYPE html>
<html>
<head>
    <title>Donation Certificate</title>

    <style>
        body {
            font-family: DejaVu Sans;
            text-align: center;
            margin: 0;
            padding: 0;
            background: #f9fafb;
        }

        .container {
            margin: 50px auto;
            padding: 40px;
            max-width: 700px;
            background: white;
            border-radius: 12px;
            border: 3px solid #2563eb; /* 🔥 BLUE FRAME */
        }

        h1 {
            font-size: 36px;
            color: #2563eb; /* 🔥 BLUE TITLE */
            margin-bottom: 10px;
        }

        h2 {
            font-size: 26px;
            margin: 10px 0;
            color: #111827;
        }

        p {
            font-size: 18px;
            color: #4b5563;
            margin: 8px 0;
        }

        .amount {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
        }

        .box {
            margin-top: 25px;
            padding: 15px;
            background: #f3f4f6;
            border-radius: 8px;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* 🔥 FIX LONG TRANSACTION ID */
        .id-text {
            font-size: 12px;
            color: #374151;
            word-break: break-all; /* THIS IS THE IMPORTANT FIX */
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- TITLE -->
        <h1>CharityHub</h1>
        <p><strong>Certificate of Donation</strong></p>

        <br>

        <p>This certifies that</p>

        <h2>{{ $donation->user->name }}</h2>

        <p>has generously donated</p>

        <div class="amount">
            ${{ number_format($donation->amount, 2) }}
        </div>

        <p>to the campaign</p>

        <h2>{{ $donation->campaign->title }}</h2>

        <!-- 🔥 FIXED DETAILS BOX -->
        <div class="box">
            <p><strong>Tracking ID:</strong> {{ $donation->tracking_id ?? 'N/A' }}</p>

            <p><strong>Transaction ID:</strong></p>
            <p class="id-text">
                {{ $donation->transaction_id ?? 'N/A' }}
            </p>

            <p><strong>Date:</strong> {{ $donation->created_at->format('Y-m-d') }}</p>
        </div>

        <p class="footer">
            Thank you for your generosity<br>
            CharityHub • Secure Donation Platform
        </p>

    </div>

</body>
</html>