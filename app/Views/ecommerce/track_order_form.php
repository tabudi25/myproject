<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order - Fluffy Planet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .track-form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .track-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 40px;
            text-align: center;
        }
        .track-icon {
            background: #ff6b35;
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
        }
        .track-title {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .track-subtitle {
            color: #6c757d;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 15px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        .btn-track {
            background: #ff6b35;
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-track:hover {
            background: #e55a2b;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .back-link {
            color: #6c757d;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }
        .back-link:hover {
            color: #ff6b35;
        }
    </style>
</head>
<body>
    <div class="track-form-container">
        <div class="track-card">
            <div class="track-icon">
                <i class="fas fa-search"></i>
            </div>
            <h1 class="track-title">Track Your Order</h1>
            <p class="track-subtitle">Enter your order number to track your pet's journey</p>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="GET" action="<?= base_url('ecommerce/track') ?>">
                <div class="mb-3">
                    <input type="text" 
                           class="form-control" 
                           name="order_number" 
                           placeholder="Enter your order number (e.g., FP2410190001)"
                           value="<?= $this->request->getGet('order_number') ?? '' ?>"
                           required>
                </div>
                <button type="submit" class="btn btn-track">
                    <i class="fas fa-search"></i> Track Order
                </button>
            </form>

            <a href="<?= base_url('ecommerce') ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
