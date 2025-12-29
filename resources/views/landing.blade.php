<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockkuApp - CV Agrosehat Nusantara</title>
  <meta name="description"
    content="Sistem Manajemen Inventaris untuk CV Agrosehat Nusantara - Proyek Hibah Pembelajaran Berdampak oleh Tim Teknik Industri UNS">
  <link rel="icon" type="image/png" href="{{ asset('images/stockku-favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary: #10b981;
      --primary-light: #34d399;
      --primary-dark: #059669;
      --secondary: #f59e0b;
      --accent: #06b6d4;
      --purple: #8b5cf6;
      --pink: #ec4899;
      --dark: #0f172a;
      --dark-light: #1e293b;
      --light: #f8fafc;
      --gray: #64748b;
      --gradient-1: linear-gradient(135deg, #10b981 0%, #06b6d4 50%, #8b5cf6 100%);
      --gradient-2: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      --gradient-3: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
      --gradient-hero: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
      --glass: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--dark);
      color: white;
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* Animated Background */
    .bg-animation {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: var(--dark);
      overflow: hidden;
    }

    .bg-animation::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle at 20% 80%, rgba(16, 185, 129, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(6, 182, 212, 0.1) 0%, transparent 40%);
      animation: float 20s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translate(0, 0) rotate(0deg);
      }

      33% {
        transform: translate(30px, -30px) rotate(5deg);
      }

      66% {
        transform: translate(-20px, 20px) rotate(-5deg);
      }
    }

    /* Navbar */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      padding: 1rem 2rem;
      transition: all 0.3s ease;
      background: rgba(15, 23, 42, 0.8);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--glass-border);
    }

    .navbar.scrolled {
      background: rgba(15, 23, 42, 0.95);
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .nav-container {
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
    }

    .logo-img {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      object-fit: contain;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: 800;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .nav-links {
      display: flex;
      gap: 2.5rem;
      list-style: none;
    }

    .nav-links a {
      text-decoration: none;
      color: rgba(255, 255, 255, 0.7);
      font-weight: 500;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      position: relative;
    }

    .nav-links a:hover {
      color: var(--primary-light);
    }

    .nav-links a::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--gradient-1);
      transition: width 0.3s ease;
    }

    .nav-links a:hover::after {
      width: 100%;
    }

    .btn {
      padding: 0.85rem 2rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.95rem;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      position: relative;
      overflow: hidden;
    }

    .btn-primary {
      background: var(--gradient-1);
      color: white;
      box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
      left: 100%;
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(16, 185, 129, 0.5);
    }

    .btn-outline {
      background: transparent;
      color: white;
      border: 2px solid var(--glass-border);
      backdrop-filter: blur(10px);
    }

    .btn-outline:hover {
      background: var(--glass);
      border-color: var(--primary);
      transform: translateY(-3px);
    }

    /* Hero Section */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 8rem 2rem 4rem;
      position: relative;
      overflow: hidden;
    }

    .hero-container {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--glass);
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-border);
      color: var(--primary-light);
      padding: 0.6rem 1.25rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      animation: fadeInUp 0.6s ease;
    }

    .hero-badge::before {
      content: '🎓';
      font-size: 1rem;
    }

    .hero-title {
      font-size: 4rem;
      font-weight: 900;
      line-height: 1.1;
      margin-bottom: 1.5rem;
      animation: fadeInUp 0.6s ease 0.1s both;
    }

    .hero-title .gradient-text {
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .hero-description {
      font-size: 1.2rem;
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 2.5rem;
      max-width: 520px;
      animation: fadeInUp 0.6s ease 0.2s both;
    }

    .hero-buttons {
      display: flex;
      gap: 1rem;
      animation: fadeInUp 0.6s ease 0.3s both;
    }

    .hero-visual {
      position: relative;
      z-index: 2;
      animation: fadeInRight 0.8s ease 0.3s both;
    }

    .hero-card {
      background: var(--glass);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 2rem;
      position: relative;
      overflow: hidden;
    }

    .hero-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gradient-1);
    }

    .hero-card-header {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }

    .hero-card-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }

    .hero-card-dot:nth-child(1) {
      background: #ef4444;
    }

    .hero-card-dot:nth-child(2) {
      background: #fbbf24;
    }

    .hero-card-dot:nth-child(3) {
      background: #22c55e;
    }

    .dashboard-preview {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .stat-card {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--glass-border);
      padding: 1.25rem;
      border-radius: 16px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-5px);
    }

    .stat-card-icon {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .stat-card-value {
      font-size: 1.75rem;
      font-weight: 800;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .stat-card-label {
      font-size: 0.8rem;
      color: rgba(255, 255, 255, 0.6);
    }

    .chart-placeholder {
      height: 140px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--glass-border);
      border-radius: 16px;
      display: flex;
      align-items: flex-end;
      justify-content: space-around;
      padding: 1rem;
    }

    .chart-bar {
      width: 24px;
      border-radius: 6px 6px 0 0;
      animation: growUp 2s ease infinite alternate;
    }

    .chart-bar:nth-child(1) {
      height: 40%;
      background: var(--primary);
      animation-delay: 0s;
    }

    .chart-bar:nth-child(2) {
      height: 70%;
      background: var(--accent);
      animation-delay: 0.1s;
    }

    .chart-bar:nth-child(3) {
      height: 50%;
      background: var(--purple);
      animation-delay: 0.2s;
    }

    .chart-bar:nth-child(4) {
      height: 85%;
      background: var(--pink);
      animation-delay: 0.3s;
    }

    .chart-bar:nth-child(5) {
      height: 60%;
      background: var(--primary-light);
      animation-delay: 0.4s;
    }

    .chart-bar:nth-child(6) {
      height: 90%;
      background: var(--secondary);
      animation-delay: 0.5s;
    }

    .chart-bar:nth-child(7) {
      height: 45%;
      background: var(--accent);
      animation-delay: 0.6s;
    }

    @keyframes growUp {
      from {
        transform: scaleY(0.7);
        opacity: 0.7;
      }

      to {
        transform: scaleY(1);
        opacity: 1;
      }
    }

    /* Section Styles */
    .section {
      padding: 6rem 2rem;
      position: relative;
    }

    .section-container {
      max-width: 1400px;
      margin: 0 auto;
    }

    .section-header {
      text-align: center;
      margin-bottom: 4rem;
    }

    .section-badge {
      display: inline-block;
      background: var(--glass);
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-border);
      color: var(--primary-light);
      padding: 0.5rem 1.25rem;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .section-title {
      font-size: 3rem;
      font-weight: 900;
      margin-bottom: 1rem;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .section-subtitle {
      font-size: 1.15rem;
      color: rgba(255, 255, 255, 0.6);
      max-width: 600px;
      margin: 0 auto;
    }

    /* About Section */
    .about-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: start;
    }

    .about-text h3 {
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 1.5rem;
    }

    .about-text p {
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 1.5rem;
      line-height: 1.8;
    }

    .about-features {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .about-feature {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 1rem;
      background: var(--glass);
      border: 1px solid var(--glass-border);
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .about-feature:hover {
      background: rgba(16, 185, 129, 0.1);
      border-color: var(--primary);
      transform: translateX(5px);
    }

    .about-feature-icon {
      width: 45px;
      height: 45px;
      background: var(--gradient-1);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      flex-shrink: 0;
    }

    .about-feature-text {
      font-weight: 600;
      font-size: 0.95rem;
    }

    .course-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 1.25rem;
      width: 100%;
    }

    .course-card {
      background: var(--glass);
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      padding: 1.75rem;
      text-align: center;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
    }

    .course-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gradient-1);
      opacity: 0;
      transition: opacity 0.4s ease;
    }

    .course-card:hover::before {
      opacity: 0.1;
    }

    .course-card:hover {
      transform: translateY(-10px);
      border-color: var(--primary);
      box-shadow: 0 20px 40px rgba(16, 185, 129, 0.2);
    }

    .course-icon {
      width: 70px;
      height: 70px;
      margin: 0 auto 1rem;
      background: var(--gradient-1);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.75rem;
      position: relative;
      z-index: 1;
    }

    .course-name {
      font-weight: 700;
      font-size: 0.95rem;
      position: relative;
      z-index: 1;
    }

    /* Team Section */
    .team {
      background: linear-gradient(180deg, var(--dark) 0%, rgba(16, 185, 129, 0.05) 50%, var(--dark) 100%);
    }

    .team-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
      justify-items: center;
    }

    .team-grid .team-card:first-child {
      grid-column: 1 / -1;
      max-width: 450px;
      width: 100%;
      margin-bottom: 1rem;
    }

    .team-card {
      background: var(--glass);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      overflow: hidden;
      transition: all 0.4s ease;
      position: relative;
      width: 100%;
    }

    .team-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gradient-1);
      opacity: 0;
      transition: opacity 0.4s ease;
      pointer-events: none;
    }

    .team-card:hover::before {
      opacity: 0.05;
    }

    .team-card:hover {
      transform: translateY(-10px);
      border-color: var(--primary);
      box-shadow: 0 25px 50px rgba(16, 185, 129, 0.15);
    }

    .team-card-image {
      height: 140px;
      background: var(--gradient-1);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .team-card:first-child .team-card-image {
      background: var(--gradient-3);
      height: 160px;
    }

    .team-card-image::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 60%;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.5));
    }

    .team-avatar {
      width: 90px;
      height: 90px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      position: relative;
      z-index: 2;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
      border: 4px solid rgba(255, 255, 255, 0.2);
    }

    .team-card:first-child .team-avatar {
      width: 110px;
      height: 110px;
      font-size: 3rem;
    }

    .team-card-content {
      padding: 1.25rem;
      text-align: center;
      position: relative;
      z-index: 1;
    }

    .team-name {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      line-height: 1.3;
    }

    .team-card:first-child .team-name {
      font-size: 1.15rem;
    }

    .team-role {
      display: inline-block;
      padding: 0.35rem 1rem;
      background: var(--gradient-1);
      border-radius: 50px;
      font-weight: 600;
      font-size: 0.75rem;
      margin-bottom: 0.5rem;
    }

    .team-card:first-child .team-role {
      background: var(--gradient-3);
      font-size: 0.8rem;
    }

    .team-nim {
      color: rgba(255, 255, 255, 0.5);
      font-size: 0.8rem;
    }

    /* Partner Section */
    .partner-content {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 4rem;
      align-items: center;
    }

    .partner-info {
      background: var(--glass);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 3rem;
      position: relative;
      overflow: hidden;
    }

    .partner-info::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gradient-1);
    }

    .partner-logo {
      width: 80px;
      height: 80px;
      background: var(--gradient-1);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      font-size: 2rem;
    }

    .partner-name {
      font-size: 1.75rem;
      font-weight: 800;
      margin-bottom: 1rem;
    }

    .partner-description {
      color: rgba(255, 255, 255, 0.7);
      line-height: 1.8;
      margin-bottom: 1.5rem;
    }

    .partner-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }

    .partner-stat {
      text-align: center;
      padding: 1.25rem;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--glass-border);
      border-radius: 16px;
    }

    .partner-stat-value {
      font-size: 1.75rem;
      font-weight: 800;
      background: var(--gradient-1);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .partner-stat-label {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.6);
    }

    .features-list {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .feature-item {
      display: flex;
      gap: 1rem;
      padding: 1.5rem;
      background: var(--glass);
      border: 1px solid var(--glass-border);
      border-radius: 16px;
      transition: all 0.3s ease;
    }

    .feature-item:hover {
      background: rgba(16, 185, 129, 0.1);
      border-color: var(--primary);
      transform: translateX(10px);
    }

    .feature-icon {
      width: 55px;
      height: 55px;
      background: var(--gradient-1);
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      flex-shrink: 0;
    }

    .feature-content h4 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 0.3rem;
    }

    .feature-content p {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.6);
    }

    /* CTA Section */
    .cta {
      padding: 6rem 2rem;
      position: relative;
      overflow: hidden;
    }

    .cta::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gradient-1);
      opacity: 0.1;
    }

    .cta-content {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
      position: relative;
      z-index: 2;
    }

    .cta-title {
      font-size: 3rem;
      font-weight: 900;
      margin-bottom: 1rem;
    }

    .cta-description {
      font-size: 1.2rem;
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 2rem;
    }

    .btn-white {
      background: white;
      color: var(--dark);
      box-shadow: 0 4px 20px rgba(255, 255, 255, 0.2);
    }

    .btn-white:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
    }

    /* Footer */
    .footer {
      background: rgba(0, 0, 0, 0.3);
      padding: 3rem 2rem 1.5rem;
      border-top: 1px solid var(--glass-border);
    }

    .footer-content {
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .footer-logo-img {
      width: 35px;
      height: 35px;
      border-radius: 8px;
    }

    .footer-text {
      color: rgba(255, 255, 255, 0.5);
      font-size: 0.9rem;
    }

    .footer-links {
      display: flex;
      gap: 2rem;
    }

    .footer-links a {
      color: rgba(255, 255, 255, 0.5);
      text-decoration: none;
      font-size: 0.9rem;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--primary-light);
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .course-cards {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 1024px) {

      .hero-container,
      .about-content,
      .partner-content {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .hero-description {
        margin: 0 auto 2rem;
      }

      .hero-buttons {
        justify-content: center;
        flex-wrap: wrap;
      }

      .hero-visual {
        max-width: 500px;
        margin: 0 auto;
      }

      .about-features {
        justify-content: center;
      }

      .course-cards {
        grid-template-columns: repeat(3, 1fr);
        max-width: 600px;
        margin: 0 auto;
      }

      .team-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
      }

      .hero-title {
        font-size: 3rem;
      }
    }

    @media (max-width: 768px) {
      .nav-links {
        display: none;
      }

      .navbar .btn-primary {
        display: none;
      }

      .hero-title {
        font-size: 2.25rem;
      }

      .section-title {
        font-size: 1.75rem;
      }

      .section-subtitle {
        font-size: 1rem;
      }

      .course-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
      }

      .course-icon {
        width: 55px;
        height: 55px;
        font-size: 1.4rem;
      }

      .course-name {
        font-size: 0.85rem;
      }

      .about-features {
        grid-template-columns: 1fr;
      }

      .dashboard-preview {
        grid-template-columns: repeat(3, 1fr);
      }

      .stat-card {
        padding: 0.75rem;
      }

      .stat-card-value {
        font-size: 1.25rem;
      }

      .stat-card-label {
        font-size: 0.7rem;
      }

      .partner-stats {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
      }

      .partner-stat {
        padding: 0.75rem;
      }

      .partner-stat-value {
        font-size: 1.25rem;
      }

      .partner-stat-label {
        font-size: 0.75rem;
      }

      .footer-content {
        flex-direction: column;
        text-align: center;
      }

      .team-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
      }

      .team-grid .team-card:first-child {
        max-width: 100%;
      }

      .team-card-image {
        height: 120px;
      }

      .team-avatar {
        width: 70px;
        height: 70px;
        font-size: 2rem;
      }

      .team-card:first-child .team-card-image {
        height: 140px;
      }

      .team-card:first-child .team-avatar {
        width: 90px;
        height: 90px;
        font-size: 2.5rem;
      }

      .team-card-content {
        padding: 1rem;
      }

      .team-name {
        font-size: 0.9rem;
      }

      .team-card:first-child .team-name {
        font-size: 1rem;
      }

      .section {
        padding: 4rem 1.5rem;
      }

      .hero {
        padding: 6rem 1.5rem 3rem;
      }
    }

    @media (max-width: 480px) {
      .course-cards {
        grid-template-columns: repeat(2, 1fr);
      }

      .course-card {
        padding: 1.25rem 0.75rem;
      }

      .team-grid {
        grid-template-columns: 1fr;
        max-width: 320px;
        margin: 0 auto;
      }

      .hero-title {
        font-size: 1.85rem;
      }

      .hero-badge {
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
      }

      .hero-description {
        font-size: 1rem;
      }

      .btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
      }

      .cta-title {
        font-size: 2rem;
      }

      .cta-description {
        font-size: 1rem;
      }
    }

    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: white;
      padding: 0.5rem;
    }

    @media (max-width: 768px) {
      .mobile-menu-btn {
        display: block;
      }
    }
  </style>
</head>

<body>
  <div class="bg-animation"></div>

  <!-- Navbar -->
  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a href="#" class="logo">
        <img src="{{ asset('images/stockku-logo.png') }}" alt="StockkuApp Logo" class="logo-img">
        <span class="logo-text">StockkuApp</span>
      </a>

      <ul class="nav-links">
        <li><a href="#about">Tentang</a></li>
        <li><a href="#courses">Mata Kuliah</a></li>
        <li><a href="#team">Tim Kami</a></li>
        <li><a href="#partner">Partner</a></li>
      </ul>

      <a href="{{ url('/admin/login') }}" class="btn btn-primary">
        🔐 Login Dashboard
      </a>

      <button class="mobile-menu-btn">☰</button>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="hero-container">
      <div class="hero-content">
        <div class="hero-badge">Hibah Pembelajaran Berdampak 2024</div>
        <h1 class="hero-title">
          Sistem Manajemen<br>
          <span class="gradient-text">Inventaris Modern</span>
        </h1>
        <p class="hero-description">
          StockkuApp adalah solusi dashboard inventaris yang dibangun untuk
          CV Agrosehat Nusantara oleh mahasiswa Teknik Industri UNS Semester 5.
        </p>
        <div class="hero-buttons">
          <a href="{{ url('/admin/login') }}" class="btn btn-primary">
            🚀 Masuk Dashboard
          </a>
          <a href="#about" class="btn btn-outline">Pelajari Lebih Lanjut</a>
        </div>
      </div>

      <div class="hero-visual">
        <div class="hero-card">
          <div class="hero-card-header">
            <div class="hero-card-dot"></div>
            <div class="hero-card-dot"></div>
            <div class="hero-card-dot"></div>
          </div>
          <div class="dashboard-preview">
            <div class="stat-card">
              <div class="stat-card-icon">📦</div>
              <div class="stat-card-value">1,234</div>
              <div class="stat-card-label">Total Produk</div>
            </div>
            <div class="stat-card">
              <div class="stat-card-icon">📊</div>
              <div class="stat-card-value">856</div>
              <div class="stat-card-label">Stok Aktif</div>
            </div>
            <div class="stat-card">
              <div class="stat-card-icon">🛒</div>
              <div class="stat-card-value">128</div>
              <div class="stat-card-label">Pesanan</div>
            </div>
          </div>
          <div class="chart-placeholder">
            <div class="chart-bar"></div>
            <div class="chart-bar"></div>
            <div class="chart-bar"></div>
            <div class="chart-bar"></div>
            <div class="chart-bar"></div>
            <div class="chart-bar"></div>
            <div class="chart-bar"></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="section" id="about">
    <div class="section-container">
      <div class="section-header">
        <span class="section-badge">Tentang Proyek</span>
        <h2 class="section-title">Apa itu StockkuApp?</h2>
        <p class="section-subtitle">
          Sebuah proyek nyata yang menggabungkan teori dan praktik dalam program
          Hibah Pembelajaran Berdampak.
        </p>
      </div>

      <div class="about-content">
        <div class="about-text">
          <h3>Program Hibah Pembelajaran Berdampak</h3>
          <p>
            StockkuApp dikembangkan sebagai bagian dari program Hibah Pembelajaran Berdampak
            oleh tim mahasiswa Teknik Industri Universitas Sebelas Maret (UNS) Semester 5.
            Proyek ini mengintegrasikan 5 mata kuliah utama untuk memberikan solusi nyata
            bagi CV Agrosehat Nusantara.
          </p>
          <p>
            Dengan pendekatan berbasis praktik, kami membangun sistem manajemen inventaris
            yang komprehensif mencakup pengelolaan stok, pemrosesan pesanan, analitik data,
            dan pelaporan penjualan.
          </p>
          <div class="about-features">
            <div class="about-feature">
              <div class="about-feature-icon">📦</div>
              <span class="about-feature-text">Manajemen Stok</span>
            </div>
            <div class="about-feature">
              <div class="about-feature-icon">📊</div>
              <span class="about-feature-text">Analitik Data</span>
            </div>
            <div class="about-feature">
              <div class="about-feature-icon">🛒</div>
              <span class="about-feature-text">Pemrosesan Order</span>
            </div>
            <div class="about-feature">
              <div class="about-feature-icon">📑</div>
              <span class="about-feature-text">Laporan Penjualan</span>
            </div>
          </div>
        </div>

        <div class="course-cards" id="courses">
          <div class="course-card">
            <div class="course-icon">📈</div>
            <div class="course-name">Analitika Data</div>
          </div>
          <div class="course-card">
            <div class="course-icon">📋</div>
            <div class="course-name">Manajemen Proyek</div>
          </div>
          <div class="course-card">
            <div class="course-icon">🔢</div>
            <div class="course-name">Riset Operasi 2</div>
          </div>
          <div class="course-card">
            <div class="course-icon">🔗</div>
            <div class="course-name">Sistem Rantai Pasok</div>
          </div>
          <div class="course-card">
            <div class="course-icon">💼</div>
            <div class="course-name">Kewirausahaan</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section class="section team" id="team">
    <div class="section-container">
      <div class="section-header">
        <span class="section-badge">Tim Kami</span>
        <h2 class="section-title">Meet The Team</h2>
        <p class="section-subtitle">
          Mahasiswa Teknik Industri UNS Semester 5 yang berdedikasi untuk
          menghadirkan solusi teknologi terbaik.
        </p>
      </div>

      <div class="team-grid">
        <!-- Dosen Pembimbing -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👨‍🏫</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Prof. Dr. Ir. Lobes Herdiman, M.T.</h3>
            <span class="team-role">Dosen Pembimbing</span>
            <p class="team-nim">Program Studi Teknik Industri UNS</p>
          </div>
        </div>

        <!-- Team Member 1 -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👨‍💼</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Muhammad Rafael Putra Anggara</h3>
            <span class="team-role">Project Manager</span>
            <p class="team-nim">NIM: I0323081</p>
          </div>
        </div>

        <!-- Team Member 2 -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👨‍💻</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Gala Septio Wamar</h3>
            <span class="team-role">Tech Officer</span>
            <p class="team-nim">NIM: I0323046</p>
          </div>
        </div>

        <!-- Team Member 3 -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👩‍💼</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Ropita Sinambela</h3>
            <span class="team-role">Sekretaris</span>
            <p class="team-nim">NIM: I0323091</p>
          </div>
        </div>

        <!-- Team Member 4 -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👨‍💻</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Angga Adi Prasetyo</h3>
            <span class="team-role">Tech Officer</span>
            <p class="team-nim">NIM: I0323017</p>
          </div>
        </div>

        <!-- Team Member 5 -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👩‍🎨</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Anya Lareina Wardhana</h3>
            <span class="team-role">Media</span>
            <p class="team-nim">NIM: I0323019</p>
          </div>
        </div>

        <!-- Team Member 6 -->
        <div class="team-card">
          <div class="team-card-image">
            <div class="team-avatar">👨‍💰</div>
          </div>
          <div class="team-card-content">
            <h3 class="team-name">Zakky Muhammad Wildan</h3>
            <span class="team-role">Bendahara</span>
            <p class="team-nim">NIM: I0323120</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Partner Section -->
  <section class="section" id="partner">
    <div class="section-container">
      <div class="section-header">
        <span class="section-badge">Partner Proyek</span>
        <h2 class="section-title">CV Agrosehat Nusantara</h2>
        <p class="section-subtitle">
          Mitra industri yang menjadi objek implementasi solusi manajemen inventaris kami.
        </p>
      </div>

      <div class="partner-content">
        <div class="partner-info">
          <div class="partner-logo">🌿</div>
          <h3 class="partner-name">CV Agrosehat Nusantara</h3>
          <p class="partner-description">
            CV Agrosehat Nusantara adalah perusahaan yang bergerak di bidang
            pertanian dan kesehatan. Dengan sistem manajemen inventaris yang kami
            kembangkan, proses operasional perusahaan menjadi lebih efisien dan terukur.
          </p>
          <div class="partner-stats">
            <div class="partner-stat">
              <div class="partner-stat-value">50+</div>
              <div class="partner-stat-label">Produk</div>
            </div>
            <div class="partner-stat">
              <div class="partner-stat-value">100+</div>
              <div class="partner-stat-label">Order/bulan</div>
            </div>
            <div class="partner-stat">
              <div class="partner-stat-value">24/7</div>
              <div class="partner-stat-label">Monitoring</div>
            </div>
          </div>
        </div>

        <div class="features-list">
          <div class="feature-item">
            <div class="feature-icon">📦</div>
            <div class="feature-content">
              <h4>Manajemen Stok Real-time</h4>
              <p>Pantau pergerakan stok masuk dan keluar secara real-time dengan sistem tracking yang akurat.</p>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">📊</div>
            <div class="feature-content">
              <h4>Dashboard Analitik</h4>
              <p>Visualisasi data penjualan dan inventaris dengan grafik yang informatif.</p>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">🛒</div>
            <div class="feature-content">
              <h4>Pemrosesan Order Otomatis</h4>
              <p>Sistem order dengan workflow status yang jelas dari NEW hingga SELESAI.</p>
            </div>
          </div>
          <div class="feature-item">
            <div class="feature-icon">📑</div>
            <div class="feature-content">
              <h4>Laporan Komprehensif</h4>
              <p>Generate laporan penjualan dan inventaris untuk pengambilan keputusan bisnis.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta">
    <div class="cta-content">
      <h2 class="cta-title">Siap Melihat Dashboard?</h2>
      <p class="cta-description">
        Masuk ke sistem untuk mengakses fitur lengkap manajemen inventaris StockkuApp.
      </p>
      <a href="{{ url('/admin/login') }}" class="btn btn-white">
        🔐 Login Sekarang
      </a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-logo">
        <img src="{{ asset('images/stockku-logo.png') }}" alt="StockkuApp" class="footer-logo-img">
        <span
          style="font-weight:700; background: var(--gradient-1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">StockkuApp</span>
      </div>
      <p class="footer-text">
        © 2024 Tim Hibah Pembelajaran Berdampak - Teknik Industri UNS
      </p>
      <div class="footer-links">
        <a href="#about">Tentang</a>
        <a href="#team">Tim</a>
        <a href="#partner">Partner</a>
      </div>
    </div>
  </footer>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function () {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>
</body>

</html>