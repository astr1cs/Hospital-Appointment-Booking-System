<?php
require_once 'includes/init.php';
SessionManager::start();

// If already logged in, redirect to dashboard
// if (SessionManager::isLoggedIn()) {
//     header('Location: dashboard.php');
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Appointment System - Quality Healthcare at Your Fingertips</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .logo i {
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #555;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: transform 0.3s !important;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4rem 8%;
            min-height: calc(100vh - 70px);
            gap: 3rem;
        }

        .hero-content {
            flex: 1;
            color: white;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .hero-image {
            flex: 1;
            text-align: center;
        }

        .hero-image i {
            font-size: 20rem;
            color: rgba(255, 255, 255, 0.9);
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2));
        }

        .btn-get-started {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-get-started:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Features Section */
        .features {
            background: white;
            padding: 5rem 8%;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #333;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-card i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* How It Works */
        .how-it-works {
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
            padding: 5rem 8%;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .step {
            text-align: center;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .step h4 {
            margin-bottom: 0.5rem;
            color: #333;
        }

        .step p {
            color: #666;
        }

        /* Footer */
        .footer {
            background: #1a1a2e;
            color: white;
            padding: 3rem 8% 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
            color: #667eea;
        }

        .footer-section p, .footer-section a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            line-height: 1.8;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 3rem 5%;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-image i {
                font-size: 12rem;
            }

            .nav-links {
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .hero-content h1 {
                font-size: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-hospital-user"></i>
            <span>MediBook</span>
        </div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="login.php" class="btn-login">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Quality Healthcare <br>at Your Fingertips</h1>
            <p>Book appointments with top doctors, manage your health records, 
               and get the care you deserve - all from one platform.</p>
            <a href="login.php" class="btn-get-started">
                Get Started <i class="fas fa-arrow-right"></i>
            </a>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Expert Doctors</div>
                </div>
                <div class="stat">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Happy Patients</div>
                </div>
                <div class="stat">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <i class="fas fa-stethoscope"></i>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Why Choose Us?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-calendar-check"></i>
                <h3>Easy Booking</h3>
                <p>Book appointments with your preferred doctors in just a few clicks.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-user-md"></i>
                <h3>Top Doctors</h3>
                <p>Access to experienced and qualified medical professionals.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Health Records</h3>
                <p>Keep track of your medical history and prescriptions online.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headset"></i>
                <h3>24/7 Support</h3>
                <p>Round-the-clock assistance for all your healthcare needs.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <h2 class="section-title">How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h4>Create Account</h4>
                <p>Sign up as a patient in minutes</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h4>Find a Doctor</h4>
                <p>Search by specialization or name</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h4>Book Appointment</h4>
                <p>Choose your preferred time slot</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h4>Get Treated</h4>
                <p>Visit and receive quality care</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>MediBook</h4>
                <p>Your trusted partner in healthcare. We make booking appointments simple and convenient.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <p><a href="#home">Home</a></p>
                <p><a href="#features">Features</a></p>
                <p><a href="#how-it-works">How It Works</a></p>
                <p><a href="login.php">Login</a></p>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <p><i class="fas fa-phone"></i> +1 234 567 890</p>
                <p><i class="fas fa-envelope"></i> info@medibook.com</p>
                <p><i class="fas fa-map-marker-alt"></i> 123 Healthcare Ave, Medical City</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <p><i class="fab fa-facebook"></i> Facebook</p>
                <p><i class="fab fa-twitter"></i> Twitter</p>
                <p><i class="fab fa-instagram"></i> Instagram</p>
                <p><i class="fab fa-linkedin"></i> LinkedIn</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 MediBook. All rights reserved. | Your Health, Our Priority</p>
        </div>
    </footer>
</body>
</html>