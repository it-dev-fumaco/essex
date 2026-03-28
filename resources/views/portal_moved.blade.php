<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Essex Employee Portal Moved</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #ffffff;
        }

        .card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 560px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.35);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2563eb, #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 22px;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        p {
            font-size: 15px;
            color: rgba(255,255,255,0.85);
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .new-url {
            margin: 20px 0;
            font-size: 20px;
            font-weight: 700;
            color: #93c5fd;
            letter-spacing: 0.5px;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 14px 28px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(37, 99, 235, 0.6);
        }

        .support {
            margin-top: 25px;
            font-size: 13px;
            color: rgba(255,255,255,0.75);
            line-height: 1.5;
        }

        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Essex is Moved to a New Server!</h1>

        <p>
            The <strong>Essex Employee Portal</strong> is now running on a new server for improved performance and security.
        </p>

        <p>
            Please use the new access URL below:
        </p>

        <div class="new-url">http://essex.fumaco.com</div>

        <a class="btn" href="http://essex.fumaco.com">
            Go to New Portal
        </a>

        <div class="support">
            If you encounter any issues or have questions, please contact <strong>it@fumaco.com</strong>.<br>
            For urgent concerns, call local <strong>3201</strong>.
        </div>

        <div class="footer">
            &copy; Fumaco IT • Secure Access Portal
        </div>
    </div>
</body>
</html>
