<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone - Premium Gym Membership</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #DC143C;
            --secondary: #1a1a1a;
            --accent: #FFD700;
            --light-bg: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--secondary);
            color: #fff;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            box-shadow: 0 4px 15px rgba(220, 20, 60, 0.3);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            font-size: 2rem;
        }

        .nav-link {
            color: #fff !important;
            margin: 0 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, var(--primary) 100%);
            padding: 100px 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(220, 20, 60, 0.1);
            border-radius: 50%;
            animation: pulse 8s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: slideInDown 0.8s ease;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            color: #e0e0e0;
            animation: slideInUp 0.8s ease;
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 12px 40px;
            font-size: 1.1rem;
            font-weight: 700;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 20, 60, 0.4);
        }

        .btn-primary:hover {
            background: #b01030;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(220, 20, 60, 0.6);
        }

        /* Section Styles */
        .section {
            padding: 80px 2rem;
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .section-title h2 {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            color: #fff;
        }

        .section-title .subtitle {
            font-size: 1.1rem;
            color: #bbb;
            margin-bottom: 1.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }

        /* Membership Plans */
        .plans-section {
            background: linear-gradient(135deg, #1a1a1a 0%, #252525 100%);
        }

        .plan-card {
            background: #2d2d2d;
            border: 2px solid #404040;
            border-radius: 15px;
            padding: 30px;
            margin: 20px 0;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .plan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .plan-card:hover {
            border-color: var(--primary);
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(220, 20, 60, 0.3);
        }

        .plan-card:hover::before {
            transform: scaleX(1);
        }

        .plan-card.featured {
            border-color: var(--primary);
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            transform: scale(1.05);
        }

        .plan-badge {
            position: absolute;
            top: 20px;
            right: -35px;
            background: var(--primary);
            color: white;
            padding: 5px 40px;
            transform: rotate(45deg);
            font-weight: bold;
            font-size: 0.8rem;
        }

        .plan-name {
            font-size: 1.8rem;
            font-weight: 900;
            margin-bottom: 10px;
            color: var(--primary);
        }

        .plan-duration {
            color: #888;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .plan-price {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--accent);
            margin: 20px 0;
        }

        .plan-price small {
            font-size: 0.5em;
            color: #888;
        }

        .plan-features {
            list-style: none;
            margin: 25px 0;
            flex-grow: 1;
        }

        .plan-features li {
            padding: 10px 0;
            border-bottom: 1px solid #404040;
            color: #ddd;
            display: flex;
            align-items: center;
        }

        .plan-features li::before {
            content: '✓';
            color: var(--primary);
            font-weight: bold;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .plan-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .plan-btn:hover {
            background: #b01030;
            transform: translateY(-2px);
        }

        /* Equipment Section */
        .equipment-section {
            background: #1a1a1a;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .equipment-card {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #404040;
        }

        .equipment-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(220, 20, 60, 0.3);
            border-color: var(--primary);
        }

        .equipment-icon {
            background: linear-gradient(135deg, var(--primary) 0%, #ff6b6b 100%);
            padding: 30px;
            text-align: center;
            font-size: 3rem;
        }

        .equipment-info {
            padding: 20px;
        }

        .equipment-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #fff;
        }

        .equipment-desc {
            color: #888;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Trainers Section */
        .trainers-section {
            background: #1a1a1a;
        }

        .trainer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .trainer-card {
            background: #2d2d2d;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            border: 1px solid #404040;
            transition: all 0.3s ease;
        }

        .trainer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(220, 20, 60, 0.3);
            border-color: var(--primary);
        }

        .trainer-avatar {
            background: linear-gradient(135deg, var(--primary) 0%, #ff6b6b 100%);
            padding: 40px 20px;
            font-size: 3rem;
        }

        .trainer-info {
            padding: 25px;
        }

        .trainer-name {
            font-size: 1.4rem;
            font-weight: 900;
            margin-bottom: 5px;
            color: #fff;
        }

        .trainer-spec {
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .trainer-exp {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .trainer-cert {
            background: #1a1a1a;
            padding: 10px;
            border-radius: 8px;
            color: #ddd;
            font-size: 0.8rem;
            margin: 15px 0;
        }

        /* Footer */
        footer {
            background: #0a0a0a;
            padding: 40px 2rem;
            border-top: 2px solid var(--primary);
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
            text-align: left;
        }

        .footer-section h4 {
            color: var(--primary);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 8px;
        }

        .footer-section a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 20px;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .plan-card.featured {
                transform: scale(1);
            }

            .navbar-brand {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-dumbbell"></i>
                FitZone
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#plans">Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#equipment">Equipment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#trainers">Trainers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.login') }}">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Transform Your Body, Transform Your Life</h1>
            <p>Join FitZone - Your Premier Gym & Fitness Destination</p>
            <a href="{{ route('customer.register') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-play-fill"></i> Start Your Journey
            </a>
        </div>
    </section>

    <!-- Membership Plans Section -->
    <section class="plans-section section" id="plans">
        <div class="container">
            <div class="section-title">
                <h2>MEMBERSHIP</h2>
                <p class="subtitle">Choose your training pass</p>
            </div>

            <div class="row">
                @forelse ($plans as $index => $plan)
                    <div class="col-lg-4 col-md-6">
                        <div class="plan-card {{ $index == 4 ? 'featured' : '' }}">
                            @if ($index == 4)
                                <div class="plan-badge">BEST VALUE</div>
                            @endif
                            <div class="plan-name">{{ $plan->name }}</div>
                            <div class="plan-duration">
                                <i class="bi bi-calendar-event"></i> {{ $plan->duration_days }} Days
                            </div>
                            <div class="plan-price">₱{{ number_format($plan->price, 2) }}</div>
                            <p style="color: #888; font-size: 0.9rem; margin-bottom: 20px;">{{ $plan->description }}</p>
                            <ul class="plan-features">
                                @php
                                    $features = $plan->features ? explode(',', $plan->features) : [];
                                @endphp
                                @forelse ($features as $feature)
                                    <li>{{ trim($feature) }}</li>
                                @empty
                                    <li>Gym access</li>
                                @endforelse
                            </ul>
                            <a href="{{ route('customer.register') }}" class="plan-btn">
                                <i class="bi bi-check-circle"></i> Choose Plan
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center" style="color: #888; padding: 40px;">
                        No membership plans available
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Equipment Section -->
    <section class="equipment-section section" id="equipment">
        <div class="container">
            <div class="section-title">
                <h2>FACILITIES</h2>
                <p class="subtitle">Equipment available</p>
            </div>

            <div class="equipment-grid">
                @forelse ($equipment as $item)
                    <div class="equipment-card">
                        <div class="equipment-icon">
                            <i class="bi bi-hammer"></i>
                        </div>
                        <div class="equipment-info">
                            <div class="equipment-name">{{ $item->name }}</div>
                            <div class="equipment-desc">
                                <strong>{{ $item->brand ?? 'Generic' }}</strong><br>
                                Model: {{ $item->model ?? 'N/A' }}<br>
                                <span style="color: var(--accent);">Qty: {{ $item->quantity }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center" style="color: #888; padding: 40px;">
                        No equipment available
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Trainers Section -->
    <section class="trainers-section section" id="trainers">
        <div class="container">
            <div class="section-title">
                <h2>OUR COACHES</h2>
                <p class="subtitle">Professional and experienced trainers</p>
            </div>

            <div class="trainer-grid">
                @forelse ($trainers as $trainer)
                    <div class="trainer-card">
                        <div class="trainer-avatar">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="trainer-info">
                            <div class="trainer-name">{{ $trainer->first_name }} {{ $trainer->last_name }}</div>
                            <div class="trainer-spec">{{ $trainer->specialization ?? 'Fitness Coach' }}</div>
                            <div class="trainer-exp">
                                <i class="bi bi-briefcase"></i> {{ $trainer->experience_years }} Years Experience
                            </div>
                            <div class="trainer-cert">
                                <strong>Certified:</strong> {{ $trainer->certification ?? 'Professional Trainer' }}
                            </div>
                            <p style="color: #ddd; font-size: 0.9rem;">
                                📞 {{ $trainer->phone }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center" style="color: #888; padding: 40px;">
                        No trainers available
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4><i class="bi bi-dumbbell"></i> FitZone</h4>
                <p style="color: #888;">Your Premium Gym & Fitness Destination</p>
                <p style="color: #666; font-size: 0.9rem; margin-top: 15px;">Transform your body, transform your life.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#plans">Membership Plans</a></li>
                    <li><a href="#equipment">Equipment</a></li>
                    <li><a href="#trainers">Trainers</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact Info</h4>
                <ul>
                    <li><i class="bi bi-telephone-fill"></i> 082-123-4567</li>
                    <li><i class="bi bi-envelope-fill"></i> info@fitzone.com</li>
                    <li><i class="bi bi-geo-alt-fill"></i> Davao City, Philippines</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Hours</h4>
                <ul>
                    <li>Mon - Fri: 6AM - 10PM</li>
                    <li>Saturday: 6AM - 8PM</li>
                    <li>Sunday: 8AM - 6PM</li>
                    <li style="margin-top: 15px; color: var(--primary);"><strong>24/7 Member Access</strong></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 FitZone Gym. All rights reserved. | DORSU ITC 121 Final Project</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>