<!DOCTYPE html>
<html>
<head>
    <title>Donation Certificate</title>
    <style>
        body {
            font-family: DejaVu Sans;
            text-align: center;
            margin-top: 100px;
        }
        h1 {
            font-size: 40px;
        }
        p {
            font-size: 20px;
        }
    </style>
</head>
<body>

    <h1>Certificate of Donation</h1>

    <p>This certifies that</p>

    <h2>{{ $donation->user->name }}</h2>

    <p>has donated</p>

    <h2>${{ $donation->amount }}</h2>

    <p>to</p>

    <h2>{{ $donation->campaign->title }}</h2>

    <p>Thank you for your generosity ❤️</p>

</body>
</html>