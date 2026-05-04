<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; text-align: center; }
        .box { border: 2px solid #333; padding: 30px; }
        h1 { color: #2c3e50; }
    </style>
</head>
<body>

<div class="box">
    <h1>CharityHub</h1>
    <h2>Donation Receipt</h2>

    <p><strong>Name:</strong> {{ $donation->user->name }}</p>
    <p><strong>Amount:</strong> ${{ $donation->amount }}</p>
    <p><strong>Campaign:</strong> {{ $donation->campaign->title }}</p>
    <p><strong>Date:</strong> {{ $donation->created_at }}</p>

    <p style="margin-top:20px;">Thank you ❤️</p>
</div>

</body>
</html>