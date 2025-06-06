<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: #f5f5f5;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #FF8C00; 
            border-bottom: 2px solid #FF8C00;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #1E1E1E;
            margin-bottom: 10px;
            padding: 15px;
            border-left: 4px solid #FF8C00;
            border-radius: 0 5px 5px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        li:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        li:nth-child(1) {
            border-left-color: #4CAF50;
        }
        
        li:nth-child(2) {
            border-left-color: #FF8C00; 
        }
        
        li:nth-child(3) {
            border-left-color: #F44336; 
        }
    </style>
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    
    <div class="container">
        <h2>Your Orders</h2>
        <ul>
            <li>Order #001 - Delivered</li>
            <li>Order #002 - On the way</li>
            <li>Order #003 - Cancelled</li>
        </ul>
    </div>
    
    <?php include '../components/footer.php'; ?>
</body>
</html>