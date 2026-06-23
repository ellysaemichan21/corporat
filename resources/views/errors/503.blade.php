<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance in Progress | LaundryApp</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 4rem 3rem;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 450px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.5);
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-wrapper {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Animated Washing Machine CSS Art */
        .washing-machine {
            width: 80px;
            height: 100px;
            background: white;
            border: 4px solid #4a90e2;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 10px 20px rgba(74, 144, 226, 0.2);
        }

        .washing-machine::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            height: 15px;
            background: #e2e8f0;
            border-radius: 4px;
        }

        .drum {
            width: 50px;
            height: 50px;
            border: 4px solid #cbd5e1;
            border-radius: 50%;
            position: absolute;
            bottom: 12px;
            left: 50%;
            transform: translateX(-50%);
            background: #f1f5f9;
            overflow: hidden;
        }

        .water {
            position: absolute;
            bottom: -5px;
            left: -20px;
            width: 100px;
            height: 30px;
            background: rgba(74, 144, 226, 0.6);
            animation: spin 3s infinite linear;
            border-radius: 40%;
            transform-origin: center top;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1e293b;
        }

        p {
            font-size: 1.05rem;
            line-height: 1.6;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-wrapper">
            <div class="washing-machine">
                <div class="drum">
                    <div class="water"></div>
                </div>
            </div>
        </div>
        <div class="badge">System Update</div>
        <h1>We're Freshening Up!</h1>
        <p>Our laundry app is currently undergoing scheduled maintenance to bring you a smoother and cleaner experience. We'll be back online shortly.</p>
        <p style="font-size: 0.9rem; color: #94a3b8;">Thank you for your patience!</p>
    </div>
</body>
</html>
