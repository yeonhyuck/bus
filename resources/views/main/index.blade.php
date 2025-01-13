<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .logo-container {
            text-align: center;
            padding: 2rem 0;
        }
        .bus-icon {
            width: 120px;
            margin-bottom: 1rem;
        }
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .bus-title {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .bus-title h1 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        .search-box {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 0.5rem;
        }
        .search-button {
            background: #4a4a4a;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            width: 80px;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="assets/img/mainbus.png" alt="Bus" class="bus-icon" style="width: 130px; height: 130px;">
        <div class="bus-title">
            <h1>모두의 버스</h1>
        </div>
    </div>
    
    <div class="search-container">
        <div class="search-box">
            <div class="flex-grow-1 mx-3">
                    <a href="/bus/search" style="text-decoration: none;">
                        <div class="form-control" style="background-color: #f5f5f5; color: #999;">
                            버스 또는 정류장 검색
                        </div>
                    </a>
                </div>
        </div>
    </div>

</body>
</html>