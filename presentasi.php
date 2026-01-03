<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presentasi PPDB SMK Kota Padang</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #8B5CF6;
            --primary-dark: #7C3AED;
            --primary-light: #A78BFA;
            --secondary: #06B6D4;
            --accent-coral: #F472B6;
            --accent-blue: #38BDF8;
            --accent-yellow: #FBBF24;
            --accent-green: #34D399;
            --text-primary: #1e1b4b;
            --text-secondary: #4338ca;
            --text-muted: #6366f1;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.9);
            --radius-lg: 24px;
            --radius-xl: 32px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 50%, #F5F3FF 100%);
            background-attachment: fixed;
            min-height: 100vh;
            overflow: hidden;
            color: var(--text-primary);
        }

        /* Simple subtle pattern */
        .floating-orbs {
            display: none;
        }

        /* Presentation Container */
        .presentation-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            z-index: 1;
        }

        /* URL Bar - Subtle */
        .url-bar {
            position: fixed;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: var(--radius-xl);
            border: 1px solid rgba(255, 255, 255, 0.4);
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        .url-bar:hover {
            opacity: 1;
        }

        .url-bar i {
            color: rgba(139, 92, 246, 0.6);
            font-size: 0.85rem;
        }

        .url-bar span {
            font-size: 0.8rem;
            color: rgba(67, 56, 202, 0.6);
            font-weight: 400;
        }

        /* Slides Container */
        .slides-container {
            display: flex;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* Individual Slide */
        .slide {
            min-width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 60px 70px;
        }

        .slide-content {
            background: transparent;
            padding: 20px 60px;
            max-width: 1100px;
            width: 100%;
            max-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: visible;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .slide-number {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .slide-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, #FF6B6B 50%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }

        .slide-subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Content Styles */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .info-card {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            padding: 14px;
            border: 1px solid rgba(139, 92, 246, 0.08);
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        .info-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            margin-bottom: 8px;
        }

        .info-card-icon.purple {
            background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        }

        .info-card-icon.blue {
            background: linear-gradient(135deg, #3BADE3, #7DD3FC);
        }

        .info-card-icon.green {
            background: linear-gradient(135deg, #10B981, #34D399);
        }

        .info-card-icon.yellow {
            background: linear-gradient(135deg, #F59E0B, #FBBF24);
        }

        .info-card-icon.pink {
            background: linear-gradient(135deg, #EC4899, #F472B6);
        }

        .info-card-icon.cyan {
            background: linear-gradient(135deg, #06B6D4, #22D3EE);
        }

        .info-card h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 3px;
        }

        .info-card p {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 22px;
            margin-top: 10px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--primary), var(--accent-blue), var(--accent-green));
            border-radius: 3px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 12px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 3px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(139, 92, 246, 0.2);
        }

        .timeline-item h5 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1px;
        }

        .timeline-item p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .timeline-item .date {
            font-size: 0.65rem;
            color: var(--primary);
            font-weight: 500;
        }

        /* Formula Box */
        .formula-box {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1), rgba(56, 189, 248, 0.1));
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin: 25px 0;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .formula-box h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .formula {
            font-size: 1.3rem;
            font-family: 'Times New Roman', serif;
            color: var(--primary-dark);
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.1);
        }

        .formula-note {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 15px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .stat-box {
            text-align: center;
            padding: 25px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            border: 1px solid rgba(139, 92, 246, 0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* Navigation - Subtle */
        .slide-nav {
            position: fixed;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 100;
            opacity: 0.4;
            transition: opacity 0.3s ease;
        }

        .slide-nav:hover {
            opacity: 1;
        }

        .nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: rgba(139, 92, 246, 0.15);
            backdrop-filter: blur(10px);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .nav-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .nav-btn.primary {
            background: rgba(139, 92, 246, 0.4);
            color: white;
            width: 44px;
            height: 44px;
        }

        .nav-btn.primary:hover:not(:disabled) {
            background: var(--primary);
            transform: scale(1.15);
        }

        /* Slide Indicators - Subtle */
        .slide-indicators {
            display: flex;
            gap: 6px;
        }

        .indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(139, 92, 246, 0.25);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            width: 20px;
            border-radius: 3px;
            background: rgba(139, 92, 246, 0.6);
        }

        .indicator:hover:not(.active) {
            background: rgba(139, 92, 246, 0.4);
        }

        /* Slide Counter - Subtle */
        .slide-counter {
            font-size: 0.75rem;
            color: rgba(99, 102, 241, 0.5);
            font-weight: 400;
        }

        /* List Styles */
        .content-list {
            list-style: none;
            margin-top: 20px;
        }

        .content-list li {
            padding: 12px 0;
            padding-left: 35px;
            position: relative;
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .content-list li:last-child {
            border-bottom: none;
        }

        .content-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            background: linear-gradient(135deg, var(--accent-green), #86EFBD);
            border-radius: 50%;
        }

        .content-list li::after {
            content: '✓';
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
        }

        /* Zone Badges */
        .zone-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .zone-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .zone-badge.green {
            background: rgba(16, 185, 129, 0.15);
            color: #059669;
        }

        .zone-badge.yellow {
            background: rgba(245, 158, 11, 0.15);
            color: #D97706;
        }

        .zone-badge.red {
            background: rgba(239, 68, 68, 0.15);
            color: #DC2626;
        }

        /* Hero Slide Special */
        .hero-slide .slide-content {
            text-align: center;
            padding: 30px 50px;
        }

        .hero-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.2);
        }

        .hero-title {
            font-size: 2.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, #FF6B6B 50%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .hero-badges {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .hero-badge {
            padding: 6px 14px;
            background: rgba(139, 92, 246, 0.08);
            border-radius: 18px;
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .slide {
                padding: 70px 20px 100px;
            }

            .slide-content {
                padding: 30px 25px;
            }

            .slide-title {
                font-size: 1.8rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .url-bar {
                font-size: 0.75rem;
                padding: 8px 15px;
            }

            .nav-btn {
                width: 45px;
                height: 45px;
            }

            .nav-btn.primary {
                width: 50px;
                height: 50px;
            }
        }

        /* Scrollbar Styling */
        .slide-content::-webkit-scrollbar {
            width: 6px;
        }

        .slide-content::-webkit-scrollbar-track {
            background: rgba(139, 92, 246, 0.1);
            border-radius: 3px;
        }

        .slide-content::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 3px;
        }
    </style>
</head>

<body>
    <!-- Floating Orbs Background -->
    <div class="floating-orbs">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>

    <!-- URL Bar -->
    <div class="url-bar">
        <i class="bi bi-link-45deg"></i>
        <span>ppdb-smk.padang.go.id/presentasi</span>
    </div>

    <!-- Presentation Container -->
    <div class="presentation-container">
        <div class="slides-container" id="slidesContainer">

            <!-- Slide 1: Hero / Overview -->
            <div class="slide hero-slide">
                <div class="slide-content">
                    <div class="hero-logo">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <h1 class="hero-title">PPDB SMK Kota Padang</h1>
                    <p class="hero-subtitle">Sistem Penerimaan Peserta Didik Baru Berbasis Digital</p>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">
                        Transparansi • Keadilan • Akuntabilitas
                    </p>
                    <div class="hero-badges">
                        <span class="hero-badge"><i class="bi bi-building"></i> 10+ SMK</span>
                        <span class="hero-badge"><i class="bi bi-people"></i> 1000+ Pendaftar</span>
                        <span class="hero-badge"><i class="bi bi-geo-alt"></i> Berbasis Zonasi</span>
                        <span class="hero-badge"><i class="bi bi-calendar-check"></i> Tahun 2025/2026</span>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Peraturan PPDB (Part 1) -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">02 / 09</span>
                        <h2 class="slide-title">Peraturan PPDB</h2>
                        <p class="slide-subtitle">Dasar hukum dan jalur penerimaan peserta didik baru</p>
                    </div>

                    <div class="content-grid">
                        <div class="info-card">
                            <div class="info-card-icon purple">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                            <h4>Permendikbud No. 1/2021</h4>
                            <p>Tentang PPDB pada TK, SD, SMP, SMA, dan SMK</p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon blue">
                                <i class="bi bi-map"></i>
                            </div>
                            <h4>Jalur Zonasi (50%)</h4>
                            <p>Berdasarkan jarak tempat tinggal ke sekolah</p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon green">
                                <i class="bi bi-heart"></i>
                            </div>
                            <h4>Jalur Afirmasi (15%)</h4>
                            <p>Untuk keluarga ekonomi tidak mampu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Peraturan PPDB (Part 2) -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">03 / 09</span>
                        <h2 class="slide-title">Peraturan PPDB</h2>
                        <p class="slide-subtitle">Jalur seleksi lainnya dan syarat umum</p>
                    </div>

                    <div class="content-grid">
                        <div class="info-card">
                            <div class="info-card-icon yellow">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <h4>Jalur Prestasi (30%)</h4>
                            <p>Berdasarkan prestasi akademik/non-akademik</p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon pink">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                            <h4>Jalur Perpindahan (5%)</h4>
                            <p>Untuk perpindahan tugas orang tua/wali</p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon cyan">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <h4>Syarat Umum</h4>
                            <p>Ijazah SMP, usia max 21 tahun, dokumen lengkap</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 4: Tahapan Seleksi (Part 1) -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">04 / 09</span>
                        <h2 class="slide-title">Tahapan Seleksi</h2>
                        <p class="slide-subtitle">Proses pendaftaran hingga pelaksanaan tes</p>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item">
                            <span class="date">1 - 6 Januari 2026</span>
                            <h5>Pendaftaran Online</h5>
                            <p>Pengisian data diri dan upload dokumen</p>
                        </div>
                        <div class="timeline-item">
                            <span class="date">7 Januari 2026</span>
                            <h5>Verifikasi Berkas</h5>
                            <p>Pengecekan kelengkapan dokumen oleh admin</p>
                        </div>
                        <div class="timeline-item">
                            <span class="date">8 Januari 2026</span>
                            <h5>Tes Minat & Bakat</h5>
                            <p>Pelaksanaan tes sesuai jurusan pilihan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 5: Tahapan Seleksi (Part 2) -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">05 / 09</span>
                        <h2 class="slide-title">Tahapan Seleksi</h2>
                        <p class="slide-subtitle">Pengumuman dan pendaftaran ulang</p>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item">
                            <span class="date">9 Januari 2026</span>
                            <h5>Pengumuman Hasil</h5>
                            <p>Hasil seleksi dapat dilihat via website resmi</p>
                        </div>
                        <div class="timeline-item">
                            <span class="date">10 - 15 Januari 2026</span>
                            <h5>Daftar Ulang</h5>
                            <p>Registrasi ulang bagi yang lolos seleksi</p>
                        </div>
                        <div class="timeline-item">
                            <span class="date">16 Januari 2026</span>
                            <h5>Pendaftaran Tahap 2</h5>
                            <p>Untuk kuota yang belum terpenuhi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 6: Perhitungan Jarak -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">06 / 09</span>
                        <h2 class="slide-title">Perhitungan Jarak</h2>
                        <p class="slide-subtitle">Metode penghitungan jarak Haversine</p>
                    </div>

                    <div class="formula-box">
                        <h4><i class="bi bi-calculator me-2"></i>Rumus Haversine</h4>
                        <div class="formula">
                            d = 2r × arcsin(√[sin²(Δφ/2) + cos(φ1) × cos(φ2) × sin²(Δλ/2)])
                        </div>
                        <p class="formula-note">
                            d = jarak, r = radius bumi (6371 km), φ = latitude, λ = longitude
                        </p>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-number">6371</div>
                            <div class="stat-label">Radius Bumi (km)</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">±10m</div>
                            <div class="stat-label">Akurasi GPS</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number">3 km</div>
                            <div class="stat-label">Radius Zonasi</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 7: Zona Prioritas -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">07 / 09</span>
                        <h2 class="slide-title">Zona Prioritas</h2>
                        <p class="slide-subtitle">Pembagian zona berdasarkan jarak</p>
                    </div>

                    <div class="content-grid">
                        <div class="info-card">
                            <div class="info-card-icon green">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <h4>Prioritas 1 (0-3 km)</h4>
                            <p>Jarak terdekat, nilai tertinggi dalam seleksi zonasi</p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon yellow">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <h4>Prioritas 2 (3-5 km)</h4>
                            <p>Jarak menengah, nilai sedang dalam seleksi</p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-icon pink">
                                <i class="bi bi-geo"></i>
                            </div>
                            <h4>Prioritas 3 (> 5 km)</h4>
                            <p>Jarak jauh, prioritas terendah untuk zonasi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 8: Sistem Perangkingan -->
            <div class="slide">
                <div class="slide-content">
                    <div class="slide-header">
                        <span class="slide-number">08 / 09</span>
                        <h2 class="slide-title">Sistem Perangkingan</h2>
                        <p class="slide-subtitle">Bobot penilaian untuk penentuan kelulusan</p>
                    </div>

                    <div class="content-grid" style="grid-template-columns: repeat(4, 1fr);">
                        <div class="info-card" style="text-align: center;">
                            <div class="info-card-icon blue" style="margin: 0 auto 8px;">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <h4>Jarak</h4>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary);">40%</div>
                        </div>
                        <div class="info-card" style="text-align: center;">
                            <div class="info-card-icon yellow" style="margin: 0 auto 8px;">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <h4>Akademik</h4>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary);">30%</div>
                        </div>
                        <div class="info-card" style="text-align: center;">
                            <div class="info-card-icon green" style="margin: 0 auto 8px;">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <h4>Usia</h4>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary);">15%</div>
                        </div>
                        <div class="info-card" style="text-align: center;">
                            <div class="info-card-icon purple" style="margin: 0 auto 8px;">
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <h4>Tes Bakat</h4>
                            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary);">15%</div>
                        </div>
                    </div>

                    <div class="formula-box" style="margin-top: 20px;">
                        <h4><i class="bi bi-graph-up me-2"></i>Formula Skor Total</h4>
                        <div class="formula" style="font-size: 1rem;">
                            Skor = (0.4 × Jarak) + (0.3 × Akademik) + (0.15 × Usia) + (0.15 × Tes)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 9: Penutup -->
            <div class="slide hero-slide">
                <div class="slide-content">
                    <div class="hero-logo" style="background: linear-gradient(135deg, var(--accent-green), var(--secondary));">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h1 class="hero-title">Terima Kasih</h1>
                    <p class="hero-subtitle">Sistem PPDB SMK Kota Padang</p>
                    <p style="color: var(--text-muted); margin-bottom: 20px; max-width: 500px; margin-left: auto; margin-right: auto; font-size: 0.85rem;">
                        Sistem penerimaan peserta didik baru yang transparan, adil, dan akuntabel
                    </p>
                    <div class="hero-badges">
                        <span class="hero-badge"><i class="bi bi-globe"></i> ppdb-smk.padang.go.id</span>
                        <span class="hero-badge"><i class="bi bi-telephone"></i> 0751-123456</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Navigation -->
    <div class="slide-nav">
        <button class="nav-btn" id="prevBtn" disabled>
            <i class="bi bi-chevron-left"></i>
        </button>

        <div class="slide-indicators" id="slideIndicators">
            <div class="indicator active" data-slide="0"></div>
            <div class="indicator" data-slide="1"></div>
            <div class="indicator" data-slide="2"></div>
            <div class="indicator" data-slide="3"></div>
            <div class="indicator" data-slide="4"></div>
            <div class="indicator" data-slide="5"></div>
            <div class="indicator" data-slide="6"></div>
            <div class="indicator" data-slide="7"></div>
            <div class="indicator" data-slide="8"></div>
        </div>

        <span class="slide-counter"><span id="currentSlide">1</span> / 9</span>

        <button class="nav-btn primary" id="nextBtn">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <script>
        const slidesContainer = document.getElementById('slidesContainer');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const indicators = document.querySelectorAll('.indicator');
        const currentSlideEl = document.getElementById('currentSlide');
        const totalSlides = 9;
        let currentSlide = 0;

        function updateSlide() {
            slidesContainer.style.transform = `translateX(-${currentSlide * 100}vw)`;

            // Update buttons
            prevBtn.disabled = currentSlide === 0;
            nextBtn.disabled = currentSlide === totalSlides - 1;

            // Update indicators
            indicators.forEach((ind, index) => {
                ind.classList.toggle('active', index === currentSlide);
            });

            // Update counter
            currentSlideEl.textContent = currentSlide + 1;
        }

        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateSlide();
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                updateSlide();
            }
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlide();
        }

        // Event listeners
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);

        indicators.forEach(ind => {
            ind.addEventListener('click', () => {
                goToSlide(parseInt(ind.dataset.slide));
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight' || e.key === ' ') {
                e.preventDefault();
                nextSlide();
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                prevSlide();
            } else if (e.key === 'Home') {
                e.preventDefault();
                goToSlide(0);
            } else if (e.key === 'End') {
                e.preventDefault();
                goToSlide(totalSlides - 1);
            }
        });

        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
        }

        // Initial state
        updateSlide();
    </script>
</body>

</html>