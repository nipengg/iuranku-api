<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iuranku | Account Activated</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #3A4D39;
        }
        .content {
            margin-top: 20px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 12px 25px;
            background-color: #3A4D39;
            color: #fff;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .button:hover {
            background-color: #2C3C2C;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Iuranku</h1>
        </div>
        <div class="content">
            <p>Thank you for signing up. Your account has been activated.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Iuranku. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
