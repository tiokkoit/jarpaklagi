<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockkuApp - CV Agrosehat Nusantara</title>
  <meta name="description" content="Sistem Manajemen Inventaris Premium untuk CV Agrosehat Nusantara.">
  <link rel="icon" type="image/png" href="{{ asset('images/stockku-favicon.png') }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            brand: {
              50: '#f0fdf4',
              100: '#dcfce7',
              200: '#bbf7d0',
              300: '#86efac',
              400: '#4ade80',
              500: '#22c55e', // Emerald 500
              600: '#16a34a',
              700: '#15803d',
              800: '#166534',
              900: '#14532d',
              950: '#052e16',
            },
            dark: {
              900: '#0f172a', // Slate 900
              800: '#1e293b',
              700: '#334155',
            }
          },
          animation: {
            'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
            'float': 'float 6s ease-in-out infinite',
          },
          keyframes: {
            fadeInUp: {
              '0%': { opacity: '0', transform: 'translateY(20px)' },
              '100%': { opacity: '1', transform: 'translateY(0)' },
            },
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-10px)' },
            }
          }
        }
      }
    }
  </script>

  <style>
    /* Custom Utilities */
    .glass-nav {
      background: rgba(15, 23, 42, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .glass-card {
      background: rgba(30, 41, 59, 0.4);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .mesh-gradient {
      background-color: #0f172a;
      background-image:
        radial-gradient(at 0% 0%, hsla(158, 82%, 25%, 0.3) 0px, transparent 50%),
        radial-gradient(at 100% 0%, hsla(250, 70%, 30%, 0.3) 0px, transparent 50%),
        radial-gradient(at 100% 100%, hsla(330, 80%, 30%, 0.2) 0px, transparent 50%),
        radial-gradient(at 0% 100%, hsla(190, 80%, 30%, 0.2) 0px, transparent 50%);
    }

    .text-glow {
      text-shadow: 0 0 20px rgba(34, 197, 94, 0.3);
    }
  </style>
</head>

<body class="bg-dark-900 text-white font-sans antialiased selection:bg-brand-500 selection:text-white">

  <!-- Background Decoration -->
  <div class="fixed inset-0 z-[-1] mesh-gradient"></div>

  <!-- Navbar -->
  <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-400 to-blue-500 flex items-center justify-center shadow-lg shadow-brand-500/20">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div>
            <span class="block text-xl font-bold tracking-tight text-white leading-none">Stockku<span
                class="text-brand-400">App</span></span>
            <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-medium">By Agrosehat</span>
          </div>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <a href="#features" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Fitur</a>
          <a href="#about" class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Tentang
            Kami</a>
          <div class="flex items-center gap-3 pl-4 border-l border-slate-700">
            <a href="{{ route('filament.admin.auth.login') }}"
              class="text-slate-300 hover:text-white transition-colors text-sm font-medium">Masuk</a>
            <a href="#"
              class="px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-500 text-white text-sm font-semibold transition-all hover:shadow-[0_0_20px_rgba(34,197,94,0.3)] transform hover:-translate-y-0.5">
              Mulai Sekarang
            </a>
          </div>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button class="text-slate-300 hover:text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
        <!-- Text Content -->
        <div class="text-center lg:text-left animate-fade-in-up">
          <div class="inline-flex items-center px-4 py-2 rounded-full glass-card mb-8 border-brand-500/20">
            <span class="flex h-2 w-2 relative mr-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
            </span>
            <span class="text-sm font-medium text-brand-300 tracking-wide uppercase">Industrial Engineering
              Project</span>
          </div>

          <h1 class="text-5xl lg:text-7xl font-bold tracking-tight mb-6 leading-tight">
            Manajemen Stok <br>
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-emerald-300 to-blue-400 text-glow">
              Level Selanjutnya.
            </span>
          </h1>

          <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
            Solusi terintegrasi untuk CV Agrosehat Nusantara. Optimalkan rantai pasok, analisis data real-time, dan
            tingkatkan efisiensi operasional dengan StockkuApp.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
            <a href="{{ route('filament.admin.auth.login') }}"
              class="inline-flex justify-center items-center px-8 py-4 rounded-2xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-lg transition-all hover:shadow-[0_0_30px_rgba(34,197,94,0.3)] transform hover:-translate-y-1">
              Akses Dashboard
              <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>
            <a href="#features"
              class="inline-flex justify-center items-center px-8 py-4 rounded-2xl glass-card hover:bg-slate-800/50 text-white font-semibold text-lg transition-all border-slate-700 hover:border-slate-500">
              Pelajari Fitur
            </a>
          </div>
        </div>

        <!-- Visual Content -->
        <div class="relative lg:h-[600px] flex items-center justify-center animate-float">
          <!-- Setup decorative blobs behind -->
          <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/20 rounded-full blur-[100px]">
          </div>
          <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-[80px]">
          </div>

          <!-- Main Dashboard Card -->
          <div
            class="relative w-full max-w-md glass-card rounded-3xl p-6 border-slate-700/50 transform rotate-[-5deg] hover:rotate-0 transition-transform duration-500">
            <!-- Card Header -->
            <div class="flex items-center justify-between mb-6">
              <div class="flex space-x-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
              </div>
              <div class="h-2 w-20 bg-slate-700 rounded-full"></div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-4 mb-6">
              <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700">
                <p class="text-xs text-slate-400 mb-1">Total Stok</p>
                <p class="text-2xl font-bold text-white">1,248</p>
                <p class="text-xs text-brand-400 flex items-center mt-1">
                  <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                  +12.5%
                </p>
              </div>
              <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700">
                <p class="text-xs text-slate-400 mb-1">Perputaran</p>
                <p class="text-2xl font-bold text-white">86%</p>
                <p class="text-xs text-blue-400 flex items-center mt-1">
                  <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                  </svg>
                  Stabil
                </p>
              </div>
            </div>

            <!-- Chart Placeholder -->
            <div
              class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700 h-32 flex items-end justify-between space-x-2">
              <div class="w-full bg-brand-500/20 rounded-t-sm h-[40%]"></div>
              <div class="w-full bg-brand-500/40 rounded-t-sm h-[60%]"></div>
              <div class="w-full bg-brand-500/60 rounded-t-sm h-[85%]"></div>
              <div class="w-full bg-brand-500/80 rounded-t-sm h-[50%]"></div>
              <div class="w-full bg-brand-500 rounded-t-sm h-[75%]"></div>
            </div>
          </div>

          <!-- Floating Badge -->
          <div class="absolute -right-4 top-20 glass-card p-4 rounded-2xl animate-float" style="animation-delay: 1s;">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-bold text-white">Sistem Efektif</p>
                <p class="text-xs text-slate-400">Terverifikasi</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section (Bento Grid) -->
  <section id="features" class="py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-brand-400 font-semibold tracking-wider uppercase text-sm">Kapabilitas Sistem</span>
        <h2 class="text-3xl md:text-5xl font-bold mt-2 mb-4 text-white">5 Pilar Teknik Industri</h2>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
          Diimplementasikan secara komprehensif dalam satu platform terpadu.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Data Analytics -->
        <div class="md:col-span-2 glass-card p-8 rounded-3xl hover:bg-slate-800/50 transition-colors group">
          <div
            class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-2xl font-semibold mb-3 text-white">Data Analytics</h3>
          <p class="text-slate-400">Analisis mendalam tren data inventaris untuk pengambilan keputusan berbasis fakta,
            bukan asumsi.</p>
        </div>

        <!-- Project Management -->
        <div class="glass-card p-8 rounded-3xl hover:bg-slate-800/50 transition-colors group">
          <div
            class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-white">Project Management</h3>
          <p class="text-slate-400 text-sm">Pengelolaan alur kerja yang terstruktur dan termonitor dengan baik.</p>
        </div>

        <!-- Operations Research -->
        <div class="glass-card p-8 rounded-3xl hover:bg-slate-800/50 transition-colors group">
          <div
            class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-white">Operations Research 2</h3>
          <p class="text-slate-400 text-sm">Optimasi stok probabilistik untuk meminimalkan biaya penyimpanan dan
            kekurangan.</p>
        </div>

        <!-- Supply Chain -->
        <div class="glass-card p-8 rounded-3xl hover:bg-slate-800/50 transition-colors group">
          <div
            class="w-12 h-12 rounded-2xl bg-brand-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-white">Supply Chain System</h3>
          <p class="text-slate-400 text-sm">Integrasi aliran informasi dan barang dari hulu ke hilir secara efisien.</p>
        </div>

        <!-- Entrepreneurship -->
        <div class="glass-card p-8 rounded-3xl hover:bg-slate-800/50 transition-colors group">
          <div
            class="w-12 h-12 rounded-2xl bg-pink-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-6 h-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-3 text-white">Entrepreneurship</h3>
          <p class="text-slate-400 text-sm">Penciptaan nilai tambah bisnis melalui inovasi teknologi tepat guna.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section id="about" class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-30">
      <div class="absolute top-20 right-0 w-[600px] h-[600px] bg-brand-900/40 rounded-full blur-[120px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="text-center mb-16">
        <span class="text-brand-400 font-semibold tracking-wider uppercase text-sm">Tim Kami</span>
        <h2 class="text-3xl md:text-5xl font-bold mt-2 mb-4 text-white">The Minds Behind</h2>
        <p class="text-slate-400 text-lg">Kolaborasi Dosen dan Mahasiswa Teknik Industri UNS</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Lecturer Card (Featured) -->
        <div
          class="lg:col-span-3 lg:w-1/2 lg:mx-auto glass-card p-6 rounded-3xl text-center group hover:border-brand-500/50 transition-colors">
          <div class="w-32 h-32 mx-auto rounded-full bg-slate-700 mb-6 p-1 relative">
            <!-- Avatar Placeholder -->
            <div
              class="w-full h-full rounded-full bg-gradient-to-br from-brand-400 to-blue-600 flex items-center justify-center text-3xl font-bold text-white">
              EA
            </div>
          </div>
          <h3 class="text-2xl font-bold text-white">Eko Aquarinto, S.T., M.Sc.</h3>
          <p class="text-brand-400 font-medium mb-4">Dosen Pembimbing</p>
          <p class="text-slate-400 text-sm italic">"Mengintegrasikan teori akademik dengan solusi praktis industri."</p>
        </div>

        <!-- Students -->
        <!-- Student 1 -->
        <div
          class="glass-card p-6 rounded-3xl text-center hover:bg-slate-800/50 transition-transform hover:-translate-y-2 duration-300">
          <div
            class="w-20 h-20 mx-auto rounded-full bg-slate-700 mb-4 flex items-center justify-center bg-gradient-to-br from-slate-600 to-slate-800 text-white font-bold text-xl">
            A
          </div>
          <h4 class="text-lg font-bold text-white">Arya</h4>
          <p class="text-slate-500 text-sm">I0322xxx</p>
          <div class="mt-3 inline-block px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Developer</div>
        </div>

        <!-- Student 2 -->
        <div
          class="glass-card p-6 rounded-3xl text-center hover:bg-slate-800/50 transition-transform hover:-translate-y-2 duration-300">
          <div
            class="w-20 h-20 mx-auto rounded-full bg-slate-700 mb-4 flex items-center justify-center bg-gradient-to-br from-slate-600 to-slate-800 text-white font-bold text-xl">
            F
          </div>
          <h4 class="text-lg font-bold text-white">Fauzan</h4>
          <p class="text-slate-500 text-sm">I0322xxx</p>
          <div class="mt-3 inline-block px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Analyst</div>
        </div>

        <!-- Student 3 -->
        <div
          class="glass-card p-6 rounded-3xl text-center hover:bg-slate-800/50 transition-transform hover:-translate-y-2 duration-300">
          <div
            class="w-20 h-20 mx-auto rounded-full bg-slate-700 mb-4 flex items-center justify-center bg-gradient-to-br from-slate-600 to-slate-800 text-white font-bold text-xl">
            H
          </div>
          <h4 class="text-lg font-bold text-white">Hafizh</h4>
          <p class="text-slate-500 text-sm">I0322xxx</p>
          <div class="mt-3 inline-block px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Designer</div>
        </div>

        <!-- Student 4 -->
        <div
          class="glass-card p-6 rounded-3xl text-center hover:bg-slate-800/50 transition-transform hover:-translate-y-2 duration-300">
          <div
            class="w-20 h-20 mx-auto rounded-full bg-slate-700 mb-4 flex items-center justify-center bg-gradient-to-br from-slate-600 to-slate-800 text-white font-bold text-xl">
            N
          </div>
          <h4 class="text-lg font-bold text-white">Naufal</h4>
          <p class="text-slate-500 text-sm">I0322xxx</p>
          <div class="mt-3 inline-block px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Researcher</div>
        </div>

        <!-- Student 5 -->
        <div
          class="glass-card p-6 rounded-3xl text-center hover:bg-slate-800/50 transition-transform hover:-translate-y-2 duration-300">
          <div
            class="w-20 h-20 mx-auto rounded-full bg-slate-700 mb-4 flex items-center justify-center bg-gradient-to-br from-slate-600 to-slate-800 text-white font-bold text-xl">
            R
          </div>
          <h4 class="text-lg font-bold text-white">Rafi</h4>
          <p class="text-slate-500 text-sm">I0322xxx</p>
          <div class="mt-3 inline-block px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Op. Research</div>
        </div>

        <!-- Student 6 -->
        <div
          class="glass-card p-6 rounded-3xl text-center hover:bg-slate-800/50 transition-transform hover:-translate-y-2 duration-300">
          <div
            class="w-20 h-20 mx-auto rounded-full bg-slate-700 mb-4 flex items-center justify-center bg-gradient-to-br from-slate-600 to-slate-800 text-white font-bold text-xl">
            Z
          </div>
          <h4 class="text-lg font-bold text-white">Zidan</h4>
          <p class="text-slate-500 text-sm">I0322xxx</p>
          <div class="mt-3 inline-block px-3 py-1 bg-slate-800 rounded-full text-xs text-slate-300">Quality Control
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="border-t border-slate-800 bg-dark-900/50 backdrop-blur-xl relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center gap-2 mb-4">
            <div
              class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400 to-blue-500 flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <span class="text-xl font-bold text-white">Stockku<span class="text-brand-400">App</span></span>
          </div>
          <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
            Dedikasi kami untuk memajukan industri pertanian Indonesia melalui efisiensi manajemen inventaris modern.
          </p>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-4">Navigasi</h4>
          <ul class="space-y-2">
            <li><a href="#" class="text-slate-400 hover:text-brand-400 text-sm transition-colors">Beranda</a></li>
            <li><a href="#features" class="text-slate-400 hover:text-brand-400 text-sm transition-colors">Fitur</a></li>
            <li><a href="#about" class="text-slate-400 hover:text-brand-400 text-sm transition-colors">Tim</a></li>
          </ul>
        </div>

        <div>
          <h4 class="text-white font-semibold mb-4">Kontak</h4>
          <ul class="space-y-2">
            <li class="text-slate-400 text-sm flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Surakarta, Indonesia
            </li>
            <li class="text-slate-400 text-sm flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              lab.tik.uns@gmail.com
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-slate-500 text-sm text-center md:text-left">
          &copy; {{ date('Y') }} Laboratorium Teknik Industri UNS. All rights reserved.
        </p>
        <div class="flex items-center gap-4">
          <span class="text-slate-600 text-xs uppercase tracking-wider">Powered by Laravel & Filament</span>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>