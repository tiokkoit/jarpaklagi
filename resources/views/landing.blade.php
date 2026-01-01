<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockkuApp - Solusi Inventaris Digital untuk CV Agrosehat Nusantara</title>
  <meta name="description" content="Sistem Manajemen Inventaris Premium hasil Program Hibah Pembelajaran Berdampak 2025.">
  <link rel="icon" type="image/png" href="{{ asset('images/stockku-favicon.png') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
          colors: {
            brand: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d', 950: '#052e16' },
            dark: { 900: '#0f172a', 800: '#1e293b', 700: '#334155' }
          },
          animation: { 
            'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards', 
            'float': 'float 6s ease-in-out infinite',
            'float-slow': 'float 8s ease-in-out infinite',
            'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          },
          keyframes: {
            fadeInUp: { '0%': { opacity: '0', transform: 'translateY(30px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
            float: { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-20px)' } }
          }
        }
      }
    }
  </script>

  <style>
    .glass-nav { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    .glass-card { 
        background: rgba(30, 41, 59, 0.4); 
        backdrop-filter: blur(12px); 
        -webkit-backdrop-filter: blur(12px); 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-12px) scale(1.02);
        background: rgba(30, 41, 59, 0.7);
        border-color: rgba(34, 197, 94, 0.4);
        box-shadow: 0 25px 30px -10px rgba(0, 0, 0, 0.3), 0 0 20px rgba(34, 197, 94, 0.15);
    }
    .mesh-gradient { background-color: #0f172a; background-image: radial-gradient(at 0% 0%, hsla(158, 82%, 25%, 0.3) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(250, 70%, 30%, 0.3) 0px, transparent 50%), radial-gradient(at 100% 100%, hsla(330, 80%, 30%, 0.2) 0px, transparent 50%), radial-gradient(at 0% 100%, hsla(190, 80%, 30%, 0.2) 0px, transparent 50%); }
    .text-glow { text-shadow: 0 0 30px rgba(34, 197, 94, 0.5); }

    /* --- Advanced Scroll Reveal Animation --- */
    /* State awal section (tersembunyi) */
    .reveal { opacity: 0; transition: opacity 0.8s ease-out; }
    /* State aktif section (muncul) */
    .reveal.active { opacity: 1; }

    /* State awal elemen anak di dalam section */
    .reveal .child-reveal { 
        opacity: 0; 
        transform: translateY(40px) scale(0.95); 
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    /* State aktif elemen anak (muncul berurutan) */
    .reveal.active .child-reveal { opacity: 1; transform: translateY(0) scale(1); }
    
    /* Delay untuk efek staggered (berurutan) */
    .reveal.active .delay-1 { transition-delay: 0.1s; }
    .reveal.active .delay-2 { transition-delay: 0.2s; }
    .reveal.active .delay-3 { transition-delay: 0.3s; }
    .reveal.active .delay-4 { transition-delay: 0.4s; }
    .reveal.active .delay-5 { transition-delay: 0.5s; }
    .reveal.active .delay-6 { transition-delay: 0.6s; }
    .reveal.active .delay-7 { transition-delay: 0.7s; }
  </style>
</head>

<body class="bg-dark-900 text-white font-sans antialiased selection:bg-brand-500 selection:text-white overflow-x-hidden">

  <div class="fixed inset-0 z-[-1] mesh-gradient animate-pulse-slow opacity-70"></div>

  <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <div class="flex-shrink-0 flex items-center gap-3 group cursor-pointer">
          <img src="{{ asset('images/stockku-logo.png') }}" alt="Stockku Logo" class="w-12 h-12 object-contain group-hover:rotate-[20deg] transition-transform duration-500 ease-out">
          <div>
            <span class="block text-xl font-bold tracking-tight text-white leading-none group-hover:text-brand-400 transition-colors duration-300">Stockku<span class="text-brand-400 group-hover:text-white transition-colors duration-300">App</span></span>
            <span class="block text-[10px] uppercase tracking-wider text-slate-400 font-medium italic">Hibah Pembelajaran Berdampak (Agustus 2025 - Januari 2026)</span>
          </div>
        </div>

        <div class="hidden md:flex items-center space-x-8">
          <a href="#about-app" class="text-slate-300 hover:text-brand-400 transition-colors text-sm font-medium relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">Mitra</a>
          <a href="#app-features" class="text-slate-300 hover:text-brand-400 transition-colors text-sm font-medium relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">Fitur Aplikasi</a>
          <a href="#features" class="text-slate-300 hover:text-brand-400 transition-colors text-sm font-medium relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">Integrasi Keilmuan</a>
          <a href="#team" class="text-slate-300 hover:text-brand-400 transition-colors text-sm font-medium relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">Tim</a>
          <div class="flex items-center gap-3 pl-4 border-l border-slate-700">
            <a href="{{ route('filament.admin.auth.login') }}" class="px-5 py-2.5 rounded-full bg-brand-600 hover:bg-brand-500 text-white text-sm font-semibold transition-all shadow-lg hover:shadow-brand-500/50 transform hover:-translate-y-1 active:scale-95">Akses Dashboard</a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div class="text-center lg:text-left animate-fade-in-up">
          <div class="inline-flex items-center px-4 py-2 rounded-full glass-card mb-8 border-brand-500/20 hover:border-brand-500/50 transition-colors cursor-default">
            <span class="text-sm font-medium text-brand-300 tracking-wide uppercase font-bold">StockkuApp</span>
          </div>

          <h1 class="text-5xl lg:text-7xl font-bold tracking-tight mb-6 leading-tight">
            Optimasi Logistik <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-emerald-300 to-blue-400 text-glow">
              The Miracle Tree.
            </span>
          </h1>

          <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed italic">
            "Mendukung visi CV Agrosehat Nusantara membawa manfaat Kelor Indonesia ke seluruh penjuru negeri melalui efisiensi rantai pasok digital yang terintegrasi."
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
            <a href="#app-features" class="inline-flex justify-center items-center px-8 py-4 rounded-2xl bg-white text-dark-900 font-bold text-lg transition-all hover:bg-brand-500 hover:text-white transform hover:-translate-y-2 hover:shadow-xl shadow-brand-500/20">
              Lihat Solusi Kami
            </a>
            <a href="https://moera.co.id" target="_blank" class="inline-flex justify-center items-center px-8 py-4 rounded-2xl glass-card text-white font-semibold text-lg transition-all border-slate-700 hover:border-brand-500 hover:bg-brand-500/10 transform hover:-translate-y-2">
              Kunjungi Mitra (Moera)
            </a>
          </div>
        </div>

        <div class="relative hidden lg:flex items-center justify-end animate-float-slow">
          <div class="absolute top-1/2 right-0 w-[600px] h-[600px] bg-brand-500/20 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/4"></div>
          
          <div class="relative z-10 transform hover:scale-105 transition-transform duration-700 ease-in-out">
              <div class="absolute inset-0 bg-brand-400/30 blur-[80px] rounded-full animate-pulse-slow"></div>
              <img src="{{ asset('images/stockku-logo.png') }}" alt="StockkuApp Giant Logo" class="w-[450px] h-[450px] object-contain drop-shadow-2xl relative z-20 relative">
          </div>
        </div>

      </div>
    </div>
  </section>

  <section id="about-app" class="py-24 bg-dark-900/50 relative reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 gap-16 items-center">
        <div class="child-reveal delay-1">
           <img src="{{ asset('images/mitra-logo.jpg') }}" alt="CV Agrosehat Nusantara Logo" class="h-24 mb-8 grayscale hover:grayscale-0 transition-all duration-500 transform hover:scale-110 hover:rotate-3">
           <h2 class="text-4xl font-bold mb-6 text-white leading-tight">Mengenal Mitra Kami: <br><span class="text-brand-400 text-glow">CV Agrosehat Nusantara</span></h2>
           
           <p class="text-slate-400 text-lg leading-relaxed mb-6">
            Dikenal dengan brand <strong>Moera</strong>, mitra kami adalah produsen olahan Kelor (<i>Moringa</i>) terkemuka di Indonesia yang berdedikasi menghadirkan <i>future superfood</i> berkualitas tinggi dengan standar <strong>ISO 22000:2018</strong>.
           </p>

           <p class="text-slate-400 text-md leading-relaxed border-l-4 border-brand-500/50 pl-4 italic">
            Melalui Program Hibah Pembelajaran Berdampak, kami hadir memberikan dukungan digitalisasi operasional agar setiap manfaat dari <i>Miracle Tree</i> ini dapat terdistribusi dengan lebih efisien dan terukur.
           </p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] border-brand-500/20 relative overflow-hidden group child-reveal delay-3">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-brand-500/10 rounded-full blur-3xl group-hover:bg-brand-500/30 transition-all duration-700"></div>
            <h3 class="text-3xl font-bold mb-8 text-brand-400">Visi & Misi Mitra</h3>
            <div class="space-y-8 relative z-10">
                <div class="transform transition-all duration-500 hover:translate-x-4 group/item">
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-2 border-l-4 border-brand-500 pl-3 group-hover/item:text-brand-300 transition-colors">Visi</h4>
                    <p class="text-slate-300 text-sm leading-relaxed group-hover/item:text-white transition-colors">Menjadi <i>top of mind</i> penyedia produk herbal alami berkualitas, khususnya olahan kelor, demi meningkatkan kesehatan masyarakat secara berkelanjutan.</p>
                </div>
                <div class="transform transition-all duration-500 hover:translate-x-4 group/item">
                    <h4 class="text-white font-bold text-sm uppercase tracking-widest mb-2 border-l-4 border-brand-500 pl-3 group-hover/item:text-brand-300 transition-colors">Misi</h4>
                    <p class="text-slate-300 text-sm leading-relaxed group-hover/item:text-white transition-colors">Memprioritaskan kualitas, inovasi, dan edukasi, serta memberdayakan petani lokal untuk menjaga keberlanjutan lingkungan dalam setiap langkah produksi.</p>
                </div>
                <div class="pt-6 border-t border-white/10 text-center">
                    <p class="text-brand-400 font-bold italic text-lg animate-pulse">"Nature Inspires, We Make It"</p>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <section id="app-features" class="py-32 relative bg-dark-900 reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20 child-reveal">
            <span class="text-brand-400 font-semibold tracking-[0.2em] uppercase text-sm">Dashboard Capabilities</span>
            <h2 class="text-4xl md:text-6xl font-bold mt-3 mb-6 text-white">Apa yang StockkuApp Lakukan?</h2>
            <p class="text-slate-400 text-xl max-w-3xl mx-auto leading-relaxed">Sebuah sistem manajemen inventaris cerdas yang dirancang untuk mempermudah kontrol operasional harian CV Agrosehat Nusantara.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="glass-card p-8 rounded-3xl border-white/5 group child-reveal delay-1">
                <div class="w-14 h-14 bg-brand-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-brand-500 group-hover:text-white transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 shadow-lg shadow-brand-500/20">
                    <svg class="w-7 h-7 text-brand-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <h4 class="text-xl font-bold mb-3 text-white group-hover:text-brand-300 transition-colors">Manajemen Stok Real-time</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Pantau keluar-masuk barang, penyesuaian stok, dan sisa produk secara akurat setiap detik.</p>
            </div>
            <div class="glass-card p-8 rounded-3xl border-white/5 group child-reveal delay-2">
                <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-blue-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h4 class="text-xl font-bold mb-3 text-white group-hover:text-blue-300 transition-colors">Otomasi Pesanan</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Kelola order dari pelanggan dengan status yang terintegrasi langsung dengan pengurangan stok otomatis.</p>
            </div>
            <div class="glass-card p-8 rounded-3xl border-white/5 group child-reveal delay-3">
                <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-500 group-hover:text-white transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 shadow-lg shadow-purple-500/20">
                    <svg class="w-7 h-7 text-purple-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <h4 class="text-xl font-bold mb-3 text-white group-hover:text-purple-300 transition-colors">Laporan Penjualan Instan</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Hasilkan laporan penjualan periode tertentu dalam format PDF secara otomatis untuk evaluasi bisnis.</p>
            </div>
            <div class="glass-card p-8 rounded-3xl border-white/5 group child-reveal delay-4">
                <div class="w-14 h-14 bg-pink-500/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-pink-500 group-hover:text-white transition-all duration-500 group-hover:rotate-6 group-hover:scale-110 shadow-lg shadow-pink-500/20">
                    <svg class="w-7 h-7 text-pink-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h4 class="text-xl font-bold mb-3 text-white group-hover:text-pink-300 transition-colors">Keamanan Multi-Role</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Akses yang dibedakan untuk Manager, Admin, dan Inventory guna menjaga integritas data perusahaan.</p>
            </div>
        </div>
    </div>
  </section>

  <section id="features" class="py-32 relative reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-20 child-reveal">
        <span class="text-brand-400 font-semibold tracking-[0.2em] uppercase text-sm">Integrasi Kurikulum Teknik Industri</span>
        <h2 class="text-4xl md:text-6xl font-bold mt-3 mb-6 text-white">Sinergi 5 Mata Kuliah</h2>
        <p class="text-slate-400 text-xl max-w-3xl mx-auto leading-relaxed">Bagaimana StockkuApp menerapkan prinsip rekayasa industri dalam setiap fiturnya.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="glass-card p-8 rounded-3xl group child-reveal delay-1">
          <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-6 transform group-hover:rotate-[360deg] transition-transform duration-1000 ease-in-out group-hover:bg-blue-500/20">
            <svg class="w-7 h-7 text-blue-400 group-hover:text-blue-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 group-hover:text-blue-400 transition-colors">Analitika Data</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Proses pengolahan data transaksi menjadi <i>insight</i> visual untuk memahami tren permintaan produk kelor di pasar.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl group child-reveal delay-2">
          <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center mb-6 transform group-hover:rotate-[360deg] transition-transform duration-1000 ease-in-out group-hover:bg-purple-500/20">
            <svg class="w-7 h-7 text-purple-400 group-hover:text-purple-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 group-hover:text-purple-400 transition-colors">Manajemen Proyek</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Pengelolaan pengembangan perangkat lunak menggunakan siklus hidup proyek yang terencana dari observasi hingga implementasi.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl group child-reveal delay-3">
          <div class="w-14 h-14 rounded-2xl bg-brand-500/10 flex items-center justify-center mb-6 transform group-hover:rotate-[360deg] transition-transform duration-1000 ease-in-out group-hover:bg-brand-500/20">
            <svg class="w-7 h-7 text-brand-400 group-hover:text-brand-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 group-hover:text-brand-400 transition-colors">Sistem Rantai Pasok</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Optimalisasi aliran informasi stok hulu ke hilir untuk menjamin ketersediaan produk bagi pelanggan setia Moera.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl group child-reveal delay-4">
          <div class="w-14 h-14 rounded-2xl bg-orange-500/10 flex items-center justify-center mb-6 transform group-hover:rotate-[360deg] transition-transform duration-1000 ease-in-out group-hover:bg-orange-500/20">
            <svg class="w-7 h-7 text-orange-400 group-hover:text-orange-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 group-hover:text-orange-400 transition-colors">Riset Operasi 2</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Pemodelan stok optimal untuk meminimalkan risiko <i>stockout</i> dan biaya penyimpanan inventaris yang berlebih.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl group child-reveal delay-5">
          <div class="w-14 h-14 rounded-2xl bg-pink-500/10 flex items-center justify-center mb-6 transform group-hover:rotate-[360deg] transition-transform duration-1000 ease-in-out group-hover:bg-pink-500/20">
            <svg class="w-7 h-7 text-pink-400 group-hover:text-pink-300 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <h3 class="text-2xl font-bold mb-4 group-hover:text-pink-400 transition-colors">Kewirausahaan</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors">Pengembangan pola pikir inovatif dalam menciptakan solusi teknologi yang memiliki nilai ekonomi bagi kemajuan UMKM.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="team" class="py-32 relative overflow-hidden reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="text-center mb-20 child-reveal">
        <span class="text-brand-400 font-semibold tracking-[0.2em] uppercase text-sm">Wajah di Balik Inovasi</span>
        <h2 class="text-4xl md:text-6xl font-bold mt-3 mb-6 text-white">The Dream Team</h2>
        <p class="text-slate-400 text-xl max-w-3xl mx-auto">Kolaborasi penuh dedikasi dari Departemen Teknik Industri UNS.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-3 lg:w-1/2 lg:mx-auto glass-card p-10 rounded-[2.5rem] text-center border-brand-500/30 group child-reveal delay-1">
          <div class="relative inline-block">
            <div class="absolute inset-0 rounded-full bg-brand-500/20 blur-2xl group-hover:blur-3xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-dosen.jpg') }}" alt="Prof. Lobes Herdiman" class="relative z-10 w-40 h-40 mx-auto rounded-full object-cover mb-8 border-[6px] border-brand-500/20 shadow-2xl transition-all duration-500 group-hover:scale-105 group-hover:border-brand-400 group-hover:rotate-3">
          </div>
          <h3 class="text-3xl font-bold text-white mb-2 group-hover:text-brand-300 transition-colors">Prof. Dr. Ir. Lobes Herdiman, M.T.</h3>
          <p class="text-brand-400 font-bold uppercase tracking-[0.2em] text-sm">Dosen Pembimbing</p>
        </div>

        <div class="glass-card p-8 rounded-3xl text-center group child-reveal delay-2">
          <div class="relative inline-block mb-6">
             <div class="absolute inset-0 rounded-2xl bg-brand-500/10 blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-rafael.jpg') }}" alt="Muhammad Rafael" class="relative z-10 w-28 h-28 mx-auto rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110 shadow-xl group-hover:rotate-3">
          </div>
          <h4 class="text-xl font-bold mb-2 text-white group-hover:text-brand-300 transition-colors">Muhammad Rafael Putra A.</h4>
          <p class="text-slate-500 text-xs mb-4 tracking-wider">I0323084</p>
          <span class="px-4 py-2 bg-brand-500/10 text-brand-400 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-brand-500 group-hover:text-white group-hover:shadow-lg shadow-brand-500/20">Project Manager</span>
        </div>

        <div class="glass-card p-8 rounded-3xl text-center group child-reveal delay-3">
          <div class="relative inline-block mb-6">
            <div class="absolute inset-0 rounded-2xl bg-blue-500/10 blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-gala.jpg') }}" alt="Gala Septio" class="relative z-10 w-28 h-28 mx-auto rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110 shadow-xl group-hover:-rotate-3">
          </div>
          <h4 class="text-xl font-bold mb-2 text-white group-hover:text-blue-300 transition-colors">Gala Septio Wamar</h4>
          <p class="text-slate-500 text-xs mb-4 tracking-wider">I0323046</p>
          <span class="px-4 py-2 bg-blue-500/10 text-blue-400 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-lg shadow-blue-500/20">Tech Officer</span>
        </div>

        <div class="glass-card p-8 rounded-3xl text-center group child-reveal delay-4">
          <div class="relative inline-block mb-6">
            <div class="absolute inset-0 rounded-2xl bg-blue-500/10 blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-angga.jpg') }}" alt="Angga Adi" class="relative z-10 w-28 h-28 mx-auto rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110 shadow-xl group-hover:rotate-3">
          </div>
          <h4 class="text-xl font-bold mb-2 text-white group-hover:text-blue-300 transition-colors">Angga Adi Prasetyo</h4>
          <p class="text-slate-500 text-xs mb-4 tracking-wider">I0323015</p>
          <span class="px-4 py-2 bg-blue-500/10 text-blue-400 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-lg shadow-blue-500/20">Tech Officer</span>
        </div>

        <div class="glass-card p-8 rounded-3xl text-center group child-reveal delay-5">
          <div class="relative inline-block mb-6">
            <div class="absolute inset-0 rounded-2xl bg-pink-500/10 blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-anya.jpg') }}" alt="Anya Lareina" class="relative z-10 w-28 h-28 mx-auto rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110 shadow-xl group-hover:-rotate-3">
          </div>
          <h4 class="text-xl font-bold mb-2 text-white group-hover:text-pink-300 transition-colors">Anya Lareina C.W.</h4>
          <p class="text-slate-500 text-xs mb-4 tracking-wider">I0323016</p>
          <span class="px-4 py-2 bg-pink-500/10 text-pink-400 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-pink-500 group-hover:text-white group-hover:shadow-lg shadow-pink-500/20">Media & Creative</span>
        </div>

        <div class="glass-card p-8 rounded-3xl text-center group child-reveal delay-6">
          <div class="relative inline-block mb-6">
            <div class="absolute inset-0 rounded-2xl bg-purple-500/10 blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-ropita.jpg') }}" alt="Ropita Sinambela" class="relative z-10 w-28 h-28 mx-auto rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110 shadow-xl group-hover:rotate-3">
          </div>
          <h4 class="text-xl font-bold mb-2 text-white group-hover:text-purple-300 transition-colors">Ropita Sari Sinambela</h4>
          <p class="text-slate-500 text-xs mb-4 tracking-wider">I0323107</p>
          <span class="px-4 py-2 bg-purple-500/10 text-purple-400 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-lg shadow-purple-500/20">Secretary</span>
        </div>

        <div class="glass-card p-8 rounded-3xl text-center group child-reveal delay-7">
          <div class="relative inline-block mb-6">
            <div class="absolute inset-0 rounded-2xl bg-orange-500/10 blur-xl group-hover:blur-2xl transition-all duration-500 opacity-0 group-hover:opacity-100"></div>
            <img src="{{ asset('images/team-zakky.jpg') }}" alt="Zakky Muhammad" class="relative z-10 w-28 h-28 mx-auto rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110 shadow-xl group-hover:-rotate-3">
          </div>
          <h4 class="text-xl font-bold mb-2 text-white group-hover:text-orange-300 transition-colors">Zakky Muhammad Wildan</h4>
          <p class="text-slate-500 text-xs mb-4 tracking-wider">I0323120</p>
          <span class="px-4 py-2 bg-orange-500/10 text-orange-400 rounded-full text-[11px] font-bold uppercase tracking-widest transition-all duration-300 group-hover:bg-orange-500 group-hover:text-white group-hover:shadow-lg shadow-orange-500/20">Treasurer</span>
        </div>
      </div>
    </div>
  </section>

  <section class="py-32 relative overflow-hidden bg-dark-950/80 reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-center text-slate-400 text-sm font-bold uppercase tracking-[0.4em] mb-16 child-reveal">Pihak yang Terlibat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <a href="https://industri.ft.uns.ac.id/lab-lppd" target="_blank" class="glass-card p-8 rounded-3xl flex flex-col items-center justify-center hover:scale-105 group transition-all duration-500 child-reveal delay-1">
                <img src="{{ asset('images/logo-lppd.png') }}" alt="Logo LPPD" class="h-20 mb-6 filter brightness-0 invert opacity-40 group-hover:opacity-100 group-hover:filter-none transition-all duration-700 transform group-hover:rotate-6">
                <p class="text-center text-sm font-bold text-slate-400 group-hover:text-white transition-colors uppercase tracking-widest">Laboratory of Product Planning & Design UNS</p>
            </a>
            <a href="https://moera.co.id" target="_blank" class="glass-card p-8 rounded-3xl flex flex-col items-center justify-center hover:scale-105 group transition-all duration-500 child-reveal delay-2">
                <img src="{{ asset('images/logo-mitra.png') }}" alt="Logo Moera" class="h-20 mb-6 filter brightness-0 invert opacity-40 group-hover:opacity-100 group-hover:filter-none transition-all duration-700 transform group-hover:rotate-6">
                <p class="text-center text-sm font-bold text-slate-400 group-hover:text-white transition-colors uppercase tracking-widest">CV Agrosehat Nusantara (Moera)</p>
            </a>
            <a href="https://uns.ac.id" target="_blank" class="glass-card p-8 rounded-3xl flex flex-col items-center justify-center hover:scale-105 group transition-all duration-500 child-reveal delay-3">
                <img src="{{ asset('images/logo-uns.png') }}" alt="Logo UNS" class="h-20 mb-6 filter brightness-0 invert opacity-40 group-hover:opacity-100 group-hover:filter-none transition-all duration-700 transform group-hover:rotate-6">
                <p class="text-center text-sm font-bold text-slate-400 group-hover:text-white transition-colors uppercase tracking-widest">Universitas Sebelas Maret</p>
            </a>
        </div>
    </div>
  </section>

  <footer class="border-t border-white/5 bg-dark-950 backdrop-blur-xl py-16 relative z-10 reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between gap-12 mb-12">
        <div class="group cursor-default md:w-1/3 child-reveal">
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ asset('images/stockku-logo.png') }}" alt="Stockku Logo" class="w-10 h-10 object-contain transition-transform duration-500 group-hover:rotate-12">
                <span class="text-2xl font-bold text-white transition-all group-hover:tracking-wider">Stockku<span class="text-brand-400">App</span></span>
            </div>
            <p class="text-slate-500 text-sm leading-relaxed italic mb-6">
                Digitalisasi efisien hulu ke hilir untuk UMKM Agribisnis Indonesia. <br> Powered by Laboratory of Product Planning and Design UNS.
            </p>
        </div>
        <div class="flex flex-col md:flex-row gap-12 md:w-2/3 justify-end">
            <div class="text-left hover:translate-x-2 transition-transform duration-300 group child-reveal delay-1">
                <p class="text-slate-500 text-[11px] uppercase font-bold tracking-[0.2em] mb-3 group-hover:text-brand-400 transition-colors">Program</p>
                <p class="text-white text-sm font-medium">Hibah Pembelajaran Berdampak<br>Periode Agustus 2025 - Januari 2026</p>
            </div>
            <div class="text-left hover:translate-x-2 transition-transform duration-300 group child-reveal delay-2">
                <p class="text-slate-500 text-[11px] uppercase font-bold tracking-[0.2em] mb-3 group-hover:text-brand-400 transition-colors">Lokasi</p>
                <p class="text-white text-sm font-medium leading-relaxed max-w-xs">Gedung 6 Lt. 1 Fakultas Teknik Universitas Sebelas Maret<br>Jl. Ir. Sutami No.36 A, Jebres, Kota Surakarta, Jawa Tengah 57126</p>
            </div>
        </div>
      </div>
      <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center text-[11px] text-slate-500 uppercase tracking-[0.2em] font-bold gap-6 child-reveal delay-3">
        <p class="hover:text-slate-300 transition-colors text-center md:text-left">© 2025 Laboratory of Product Planning and Design UNS</p>
        <div class="flex items-center gap-8">
            <a href="https://industri.ft.uns.ac.id/lab-lppd" target="_blank" class="hover:text-brand-400 transition-colors relative after:absolute after:bottom-[-4px] after:left-0 after:h-[1px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">LPPD UNS</a>
            <a href="https://moera.co.id" target="_blank" class="hover:text-brand-400 transition-colors relative after:absolute after:bottom-[-4px] after:left-0 after:h-[1px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">Moera</a>
            <a href="https://uns.ac.id" target="_blank" class="hover:text-brand-400 transition-colors relative after:absolute after:bottom-[-4px] after:left-0 after:h-[1px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">UNS</a>
      </div>
    </div>
  </footer>

  <script>
    // Advanced Staggered Reveal Animation
    const revealSections = document.querySelectorAll('.reveal');
    
    const observerOptions = {
        root: null,
        threshold: 0.15, // Muncul ketika 15% section terlihat
        rootMargin: "0px"
    };

    const sectionObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('active');
          // Optional: Stop observing after reveal for performance if needed
          // observer.unobserve(entry.target); 
        }
      });
    }, observerOptions);

    revealSections.forEach(section => {
      sectionObserver.observe(section);
    });

    // Navbar scroll effect
    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.add('h-16', 'bg-dark-900/90', 'backdrop-blur-xl', 'border-b', 'border-white/5');
            nav.classList.remove('h-20', 'glass-nav');
        } else {
            nav.classList.add('h-20', 'glass-nav');
            nav.classList.remove('h-16', 'bg-dark-900/90', 'border-b', 'border-white/5');
        }
    });
  </script>

</body>
</html>