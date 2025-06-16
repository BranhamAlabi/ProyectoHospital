<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTech! - Sistema de Gestión Hospitalaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #12153b;
            --primary-blue: #1a4b8c;
            --accent-blue: #3a7bd5;
            --success-green: #2e6136;
            --light-green: #a8e6cf;
            --cyan: #00d2ff;
            --white: #ffffff;
            --light-gray: #f8f9fa;
            --text-dark: #2d3748;
            --text-light: #718096;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 2rem 0;
        }

        .hero-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .hero-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--light-green), var(--cyan), var(--accent-blue));
        }

        .logo-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            display: block;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
            animation: logoFloat 3s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .welcome-text {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        .description {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--success-green), #3a7d46);
            color: var(--white);
            box-shadow: 0 8px 20px rgba(46, 97, 54, 0.3);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #3a7d46, var(--success-green));
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(46, 97, 54, 0.4);
            color: var(--white);
        }

        .btn-secondary-custom {
            background: transparent;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-secondary-custom:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 75, 140, 0.3);
        }

        .features {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .feature-item i {
            color: var(--success-green);
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-size: 2rem;
            animation: float 6s ease-in-out infinite;
        }

        .floating-icon:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-icon:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-icon:nth-child(3) {
            bottom: 30%;
            left: 5%;
            animation-delay: 4s;
        }

        .floating-icon:nth-child(4) {
            bottom: 10%;
            right: 10%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @media (max-width: 768px) {
            .hero-content {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }

            .brand-title {
                font-size: 2rem;
            }

            .logo {
                width: 100px;
                height: 100px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-custom {
                width: 100%;
                max-width: 280px;
            }
        }

        @media (max-width: 576px) {
            .hero-content {
                padding: 1.5rem 1rem;
            }

            .brand-title {
                font-size: 1.8rem;
            }

            .welcome-text {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <i class="fas fa-stethoscope floating-icon"></i>
        <i class="fas fa-heartbeat floating-icon"></i>
        <i class="fas fa-user-md floating-icon"></i>
        <i class="fas fa-hospital floating-icon"></i>
    </div>

    <div class="hero-section">
        <div class="hero-content">
            <div class="logo-container">
                <img src="{{ asset('image/meditech_logo.png') }}" alt="MediTech Logo" class="logo">
                <h1 class="brand-title">MediTech!</h1>
                <p class="brand-subtitle">Sistema de Gestión Hospitalaria</p>
            </div>

            <h2 class="welcome-text">¡Bienvenido al Futuro de la Medicina!</h2>
            <p class="description">
                Gestiona eficientemente tu clínica con nuestra plataforma integral. 
                Administra citas, pacientes, médicos y obtén información en tiempo real 
                para tomar las mejores decisiones médicas.
            </p>

            <div class="action-buttons">
                <a href="{{ route('login') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-user-plus me-2"></i>Registrarse
                </a>
            </div>

            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Gestión integral de citas y pacientes</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Panel de administración avanzado</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Notificaciones automáticas por correo</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Interfaz moderna y responsive</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animación adicional para la interactividad
        document.addEventListener('DOMContentLoaded', function() {
            const logo = document.querySelector('.logo');
            const heroContent = document.querySelector('.hero-content');

            // Animación de entrada
            heroContent.style.opacity = '0';
            heroContent.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                heroContent.style.transition = 'all 0.8s ease';
                heroContent.style.opacity = '1';
                heroContent.style.transform = 'translateY(0)';
            }, 200);

            // Efecto hover en el logo
            logo.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
                this.style.transition = 'transform 0.3s ease';
            });

            logo.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });
    </script>
</body>
</html>
