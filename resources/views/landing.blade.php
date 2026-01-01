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
            dark: { 950: '#020617', 900: '#0f172a', 800: '#1e293b', 700: '#334155' }
          },
          animation: { 
            'fade-in-up': 'fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards', 
            'float': 'float 6s ease-in-out infinite',
            'float-slow': 'float 8s ease-in-out infinite',
            'pulse-slow': 'pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'spin-very-slow': 'spin 20s linear infinite',
            'spin-reverse-slow': 'spin 25s linear infinite reverse',
          },
          keyframes: {
            fadeInUp: { '0%': { opacity: '0', transform: 'translateY(40px) scale(0.98)', filter: 'blur(10px)' }, '100%': { opacity: '1', transform: 'translateY(0) scale(1)', filter: 'blur(0)' } },
            float: { '0%, 100%': { transform: 'translateY(0) rotate(0deg)' }, '50%': { transform: 'translateY(-20px) rotate(1deg)' } }
          }
        }
      }
    }
  </script>

  <style>
    /* Latar Belakang Dinamis */
    .ambient-light {
        position: fixed;
        top: 50%;
        left: 50%;
        width: 200vw;
        height: 200vh;
        transform: translate(-50%, -50%);
        background: radial-gradient(circle at center, rgba(34, 197, 94, 0.08) 0%, transparent 60%),
                    radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.08) 0%, transparent 50%);
        z-index: -2;
        pointer-events: none;
    }

    .glass-nav { background: rgba(2, 6, 23, 0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.03); }
    
    /* Kartu dengan Efek 3D Hover */
    .glass-card { 
        background: rgba(30, 41, 59, 0.3); 
        backdrop-filter: blur(16px); 
        border: 1px solid rgba(255, 255, 255, 0.05); 
        box-shadow: 0 8px 32px -8px rgba(0, 0, 0, 0.3);
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); /* Efek membal (bouncy) */
        transform-style: preserve-3d;
        perspective: 1000px;
    }
    
    .glass-card:hover {
        transform: translateY(-10px) rotateX(2deg) scale(1.02);
        background: rgba(30, 41, 59, 0.5);
        border-color: rgba(34, 197, 94, 0.3);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.5), 0 0 20px rgba(34, 197, 94, 0.1) inset;
    }

    .text-glow { text-shadow: 0 0 40px rgba(34, 197, 94, 0.7); }

    /* Scroll Reveal Logic yang Lebih Halus */
    .reveal { opacity: 0; transition: opacity 0.8s ease-out; }
    .reveal.active { opacity: 1; }
    .reveal .child-reveal { 
        opacity: 0; 
        transform: translateY(60px) scale(0.9); 
        filter: blur(12px);
        transition: all 1s cubic-bezier(0.16, 1, 0.3, 1); 
    }
    .reveal.active .child-reveal { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    
    /* Stagger Delays */
    .reveal.active .delay-1 { transition-delay: 0.1s; }
    .reveal.active .delay-2 { transition-delay: 0.2s; }
    .reveal.active .delay-3 { transition-delay: 0.3s; }
    .reveal.active .delay-4 { transition-delay: 0.4s; }
    .reveal.active .delay-5 { transition-delay: 0.5s; }
    .reveal.active .delay-6 { transition-delay: 0.6s; }
    .reveal.active .delay-7 { transition-delay: 0.7s; }
  </style>
</head>

<body class="bg-dark-950 text-white font-sans antialiased selection:bg-brand-500 selection:text-white overflow-x-hidden leading-relaxed">

  <div class="ambient-light animate-pulse-slow"></div>
  <div class="fixed top-[-50%] left-[-50%] w-[100vw] h-[100vw] rounded-full bg-brand-900/10 blur-[150px] animate-spin-very-slow z-[-1]"></div>
  <div class="fixed bottom-[-50%] right-[-50%] w-[80vw] h-[80vw] rounded-full bg-blue-900/10 blur-[150px] animate-spin-reverse-slow z-[-1]"></div>


  <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-24 transition-all duration-500" id="nav-container">
        <div class="flex-shrink-0 flex items-center gap-4 group cursor-pointer relative">
          <div class="absolute inset-0 bg-brand-500/30 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
          <img src="{{ asset('images/stockku-logo.png') }}" alt="Stockku Logo" class="w-12 h-12 object-contain group-hover:scale-110 transition-transform duration-500 relative z-10">
          <div>
            <span class="block text-xl font-extrabold tracking-tight text-white leading-none group-hover:text-brand-400 transition-colors duration-300 uppercase">Stockku<span class="text-brand-400 group-hover:text-white transition-colors duration-300">App</span></span>
            <span class="block text-[9px] uppercase tracking-[0.2em] text-slate-400 font-bold italic opacity-80 mt-1">Hibah Pembelajaran Berdampak (Agustus 2025 - Januari 2026)</span>
          </div>
        </div>

        <div class="hidden md:flex items-center space-x-10">
          <a href="#about-app" class="text-slate-300 hover:text-white transition-all text-xs font-bold uppercase tracking-widest relative group overflow-hidden py-1">Mitra<span class="absolute bottom-0 left-0 w-full h-[2px] bg-brand-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span></a>
          <a href="#app-features" class="text-slate-300 hover:text-white transition-all text-xs font-bold uppercase tracking-widest relative group overflow-hidden py-1">Fitur Aplikasi<span class="absolute bottom-0 left-0 w-full h-[2px] bg-brand-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span></a>
          <a href="#features" class="text-slate-300 hover:text-white transition-all text-xs font-bold uppercase tracking-widest relative group overflow-hidden py-1 text-nowrap">Integrasi Keilmuan<span class="absolute bottom-0 left-0 w-full h-[2px] bg-brand-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span></a>
          <a href="#team" class="text-slate-300 hover:text-white transition-all text-xs font-bold uppercase tracking-widest relative group overflow-hidden py-1">Tim<span class="absolute bottom-0 left-0 w-full h-[2px] bg-brand-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300"></span></a>
          
          <div class="flex items-center gap-3 pl-6 border-l border-white/10">
            <a href="{{ route('filament.admin.auth.login') }}" class="px-7 py-3 rounded-full bg-gradient-to-r from-brand-600 to-brand-500 hover:from-brand-500 hover:to-brand-400 text-white text-[11px] font-black uppercase tracking-tighter transition-all shadow-[0_0_25px_rgba(34,197,94,0.4)] hover:shadow-[0_0_35px_rgba(34,197,94,0.6)] transform hover:-translate-y-1 active:scale-95 relative overflow-hidden group">
                <span class="relative z-10">Akses Dashboard</span>
                <div class="absolute inset-0 h-full w-full scale-0 rounded-full group-hover:scale-150 group-active:scale-100 transition-all duration-500 bg-white/20"></div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <section class="relative pt-36 pb-24 lg:pt-52 lg:pb-36 overflow-hidden min-h-[92vh] flex items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid lg:grid-cols-2 gap-16 items-center">
        <div class="text-center lg:text-left animate-fade-in-up">
          <div class="inline-flex items-center px-5 py-2.5 rounded-full glass-card mb-10 border-brand-500/30 hover:border-brand-500/60 transition-colors shadow-xl backdrop-blur-xl cursor-default group">
            <span class="flex relative h-3 w-3 mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-brand-500"></span>
              </span>
            <span class="text-[10px] font-black text-brand-300 tracking-[0.3em] uppercase group-hover:text-brand-200 transition-colors">StockkuApp By Team ID 1600</span>
          </div>

          <h1 class="text-6xl lg:text-8xl font-black tracking-tighter mb-8 leading-[0.95] drop-shadow-lg">
            Optimasi Logistik <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-teal-300 to-blue-500 text-glow">
              The Miracle Tree.
            </span>
          </h1>

          <p class="text-xl text-slate-400 mb-12 max-w-xl mx-auto lg:mx-0 leading-relaxed italic opacity-90 border-l-2 border-brand-500/50 pl-6 bg-brand-900/10 py-4 rounded-r-3xl backdrop-blur-sm">
            "Mendukung visi CV Agrosehat Nusantara membawa manfaat Kelor ke seluruh penjuru negeri melalui efisiensi rantai pasok digital yang terintegrasi."
          </p>

          <div class="flex flex-col sm:flex-row gap-6 justify-center lg:justify-start">
            <a href="#app-features" class="inline-flex justify-center items-center px-10 py-5 rounded-2xl bg-white text-dark-950 font-black text-sm uppercase tracking-widest transition-all hover:bg-brand-500 hover:text-white transform hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(34,197,94,0.4)] active:scale-95 group relative overflow-hidden">
              <span class="relative z-10">Lihat Solusi Kami</span>
            </a>
            <a href="https://moera.co.id" target="_blank" class="inline-flex justify-center items-center px-10 py-5 rounded-2xl glass-card text-white font-black text-sm uppercase tracking-widest transition-all border-slate-700 hover:border-brand-500 hover:bg-brand-500/10 transform hover:-translate-y-2 active:scale-95 hover:shadow-[0_10px_30px_rgba(34,197,94,0.2)]">
              Kunjungi Mitra (Moera)
            </a>
          </div>
        </div>

        <div class="relative hidden lg:flex items-center justify-end animate-float">
          <div class="absolute top-1/2 right-10 w-[600px] h-[600px] bg-brand-500/20 rounded-full blur-[150px] -translate-y-1/2 animate-pulse-slow"></div>
          
          <div class="relative z-10 group perspective-container">
              <div class="absolute inset-0 scale-150 border-2 border-brand-500/10 rounded-full animate-[spin_30s_linear_infinite]"></div>
              <div class="absolute inset-0 scale-125 border border-brand-400/20 rounded-full animate-[spin_20s_linear_infinite_reverse]"></div>
              
              <div class="relative z-20 transition-all duration-1000 group-hover:scale-105 drop-shadow-[0_45px_65px_rgba(0,0,0,0.6)] glass-card rounded-full p-10 border-none bg-brand-900/5">
                  <div class="absolute inset-0 bg-brand-400/30 blur-[80px] rounded-full group-hover:bg-brand-400/50 transition-colors duration-700 animate-pulse"></div>
                  <img src="{{ asset('images/stockku-logo.png') }}" alt="StockkuApp Giant Logo" class="w-[400px] h-[400px] object-contain relative z-20">
              </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section id="about-app" class="py-32 relative reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 gap-20 items-center">
        <div class="child-reveal delay-1">
           <div class="relative inline-block mb-10 group">
               <div class="absolute -inset-10 bg-brand-500/30 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
               <img src="{{ asset('images/mitra-logo.jpg') }}" alt="CV Agrosehat Nusantara Logo" class="h-28 grayscale group-hover:grayscale-0 transition-all duration-700 transform group-hover:scale-110 group-hover:rotate-3 relative z-10 drop-shadow-2xl">
           </div>
           <h2 class="text-5xl font-black mb-8 text-white leading-tight">Mengenal Mitra Kami: <br><span class="text-brand-400 text-glow">CV Agrosehat Nusantara</span></h2>
           
           <p class="text-slate-400 text-xl leading-relaxed mb-8 font-medium">
            Dikenal dengan brand <strong>Moera</strong>, mitra kami adalah produsen olahan Kelor (<i>Moringa</i>) terkemuka di Indonesia yang berdedikasi menghadirkan <i>future superfood</i> berkualitas tinggi dengan standar <strong>ISO 22000:2018</strong>.
           </p>

           <p class="text-slate-500 text-lg leading-relaxed border-l-4 border-brand-500/50 pl-8 italic bg-white/5 py-6 rounded-r-3xl backdrop-blur-md shadow-inner">
            Melalui Program Hibah Pembelajaran Berdampak, kami hadir memberikan dukungan digitalisasi operasional agar setiap manfaat dari <i>Miracle Tree</i> ini dapat terdistribusi dengan lebih efisien dan terukur.
           </p>
        </div>

        <div class="child-reveal delay-3">
            <div class="glass-card p-12 rounded-[3rem] border-brand-500/20 relative group overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-brand-500/20 to-transparent opacity-0 group-hover:opacity-100 w-[200%] h-full -translate-x-full group-hover:translate-x-full transition-all duration-2000 ease-in-out pointer-events-none z-0"></div>
                
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl group-hover:bg-brand-500/30 transition-all duration-1000"></div>
                <h3 class="text-3xl font-black mb-10 text-brand-400 uppercase tracking-widest relative z-10">Visi & Misi Mitra</h3>
                <div class="space-y-10 relative z-10">
                    <div class="transform transition-all duration-700 hover:translate-x-4 group/item">
                        <h4 class="text-white font-black text-xs uppercase tracking-[0.4em] mb-4 border-l-4 border-brand-500 pl-4 group-hover/item:text-brand-400 transition-colors">Visi</h4>
                        <p class="text-slate-400 text-md leading-relaxed group-hover/item:text-white transition-colors font-medium">Menjadi <i>top of mind</i> penyedia produk herbal alami berkualitas, khususnya olahan kelor, demi meningkatkan kesehatan masyarakat secara berkelanjutan.</p>
                    </div>
                    <div class="transform transition-all duration-700 hover:translate-x-4 group/item">
                        <h4 class="text-white font-black text-xs uppercase tracking-[0.4em] mb-4 border-l-4 border-brand-500 pl-4 group-hover/item:text-brand-400 transition-colors">Misi</h4>
                        <p class="text-slate-400 text-md leading-relaxed group-hover/item:text-white transition-colors font-medium">Memprioritaskan kualitas, inovasi, dan edukasi, serta memberdayakan petani lokal untuk menjaga keberlanjutan lingkungan dalam setiap langkah produksi.</p>
                    </div>
                    <div class="pt-10 border-t border-white/10 text-center">
                        <p class="text-brand-400 font-black italic text-xl tracking-tighter animate-pulse shadow-brand-500/20">"Nature Inspires, We Make It"</p>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <section id="app-features" class="py-32 relative reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-24 child-reveal">
            <span class="text-brand-400 font-bold tracking-[0.5em] uppercase text-[10px] mb-4 block">Dashboard Core</span>
            <h2 class="text-5xl md:text-7xl font-black mb-8 text-white tracking-tighter leading-none drop-shadow-xl">Apa yang StockkuApp Lakukan?</h2>
            <p class="text-slate-400 text-xl max-w-3xl mx-auto font-medium opacity-80">Sebuah sistem manajemen inventaris cerdas yang dirancang untuk mempermudah kontrol operasional harian CV Agrosehat Nusantara.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="glass-card p-10 rounded-[2.5rem] border-white/5 group child-reveal delay-1">
                <div class="w-16 h-16 bg-brand-500/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-brand-500 group-hover:scale-110 transition-all duration-700 shadow-[0_10px_30px_rgba(34,197,94,0.3)] group-hover:rotate-12 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 scale-0 group-hover:scale-150 rounded-full transition-all duration-500"></div>
                    <svg class="w-8 h-8 text-brand-400 group-hover:text-white transition-colors relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <h4 class="text-2xl font-black mb-4 text-white group-hover:text-brand-300 transition-colors tracking-tight">Manajemen Stok Real-time</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Pantau keluar-masuk barang, penyesuaian stok, dan sisa produk secara akurat setiap detik.</p>
            </div>
            <div class="glass-card p-10 rounded-[2.5rem] border-white/5 group child-reveal delay-2">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-blue-500 group-hover:scale-110 transition-all duration-700 shadow-[0_10px_30px_rgba(59,130,246,0.3)] group-hover:-rotate-12 relative overflow-hidden">
                     <div class="absolute inset-0 bg-white/20 scale-0 group-hover:scale-150 rounded-full transition-all duration-500"></div>
                    <svg class="w-8 h-8 text-blue-400 group-hover:text-white transition-colors relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
                <h4 class="text-2xl font-black mb-4 text-white group-hover:text-blue-300 transition-colors tracking-tight">Otomasi Pesanan</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Kelola order dari pelanggan dengan status yang terintegrasi langsung dengan pengurangan stok otomatis.</p>
            </div>
            <div class="glass-card p-10 rounded-[2.5rem] border-white/5 group child-reveal delay-3">
                <div class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-purple-500 group-hover:scale-110 transition-all duration-700 shadow-[0_10px_30px_rgba(168,85,247,0.3)] group-hover:rotate-12 relative overflow-hidden">
                     <div class="absolute inset-0 bg-white/20 scale-0 group-hover:scale-150 rounded-full transition-all duration-500"></div>
                    <svg class="w-8 h-8 text-purple-400 group-hover:text-white transition-colors relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <h4 class="text-2xl font-black mb-4 text-white group-hover:text-purple-300 transition-colors tracking-tight">Laporan Penjualan Instan</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Hasilkan laporan penjualan periode tertentu dalam format PDF secara otomatis untuk evaluasi bisnis.</p>
            </div>
            <div class="glass-card p-10 rounded-[2.5rem] border-white/5 group child-reveal delay-4">
                <div class="w-16 h-16 bg-pink-500/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-pink-500 group-hover:scale-110 transition-all duration-700 shadow-[0_10px_30px_rgba(236,72,153,0.3)] group-hover:-rotate-12 relative overflow-hidden">
                     <div class="absolute inset-0 bg-white/20 scale-0 group-hover:scale-150 rounded-full transition-all duration-500"></div>
                    <svg class="w-8 h-8 text-pink-400 group-hover:text-white transition-colors relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                </div>
                <h4 class="text-2xl font-black mb-4 text-white group-hover:text-pink-300 transition-colors tracking-tight">Keamanan Multi-Role</h4>
                <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Akses yang dibedakan untuk Manager, Admin, dan Inventory guna menjaga integritas data perusahaan.</p>
            </div>
        </div>
    </div>
  </section>

  <section id="features" class="py-32 relative reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-24 child-reveal">
        <span class="text-brand-400 font-black tracking-[0.4em] uppercase text-[11px] mb-4 block">Academic Foundation</span>
        <h2 class="text-5xl md:text-7xl font-black mb-8 text-white tracking-tighter drop-shadow-xl">Sinergi 5 Mata Kuliah Teknik Industri</h2>
        <p class="text-slate-400 text-xl max-w-3xl mx-auto leading-relaxed font-medium">Bagaimana StockkuApp menerapkan prinsip rekayasa industri dalam setiap fiturnya.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <div class="glass-card p-10 rounded-[2.5rem] group child-reveal delay-1">
          <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-8 transform group-hover:rotate-[360deg] transition-transform duration-1000 group-hover:bg-blue-500/30 shadow-inner">
            <svg class="w-8 h-8 text-blue-400 group-hover:text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
          </div>
          <h3 class="text-2xl font-black mb-5 text-white group-hover:text-blue-400 transition-colors uppercase tracking-tight">Analitika Data</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Proses pengolahan data transaksi menjadi <i>insight</i> visual untuk memahami tren permintaan produk kelor di pasar.</p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] group child-reveal delay-2">
          <div class="w-16 h-16 rounded-2xl bg-purple-500/10 flex items-center justify-center mb-8 transform group-hover:rotate-[360deg] transition-transform duration-1000 group-hover:bg-purple-500/30 shadow-inner">
            <svg class="w-8 h-8 text-purple-400 group-hover:text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
          </div>
          <h3 class="text-2xl font-black mb-5 text-white group-hover:text-purple-400 transition-colors uppercase tracking-tight">Manajemen Proyek</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Pengelolaan pengembangan perangkat lunak menggunakan siklus hidup proyek yang terencana dari observasi hingga implementasi.</p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] group child-reveal delay-3">
          <div class="w-16 h-16 rounded-2xl bg-brand-500/10 flex items-center justify-center mb-8 transform group-hover:rotate-[360deg] transition-transform duration-1000 group-hover:bg-brand-500/30 shadow-inner">
            <svg class="w-8 h-8 text-brand-400 group-hover:text-brand-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          </div>
          <h3 class="text-2xl font-black mb-5 text-white group-hover:text-brand-400 transition-colors uppercase tracking-tight">Sistem Rantai Pasok</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Optimalisasi aliran informasi stok hulu ke hilir untuk menjamin ketersediaan produk bagi pelanggan setia Moera.</p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] group child-reveal delay-4">
          <div class="w-16 h-16 rounded-2xl bg-orange-500/10 flex items-center justify-center mb-8 transform group-hover:rotate-[360deg] transition-transform duration-1000 group-hover:bg-orange-500/30 shadow-inner">
            <svg class="w-8 h-8 text-orange-400 group-hover:text-orange-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
          </div>
          <h3 class="text-2xl font-black mb-5 text-white group-hover:text-orange-400 transition-colors uppercase tracking-tight">Riset Operasi 2</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Pemodelan stok optimal untuk meminimalkan risiko <i>stockout</i> dan biaya penyimpanan inventaris yang berlebih.</p>
        </div>

        <div class="glass-card p-10 rounded-[2.5rem] group child-reveal delay-5">
          <div class="w-16 h-16 rounded-2xl bg-pink-500/10 flex items-center justify-center mb-8 transform group-hover:rotate-[360deg] transition-transform duration-1000 group-hover:bg-pink-500/30 shadow-inner">
            <svg class="w-8 h-8 text-pink-400 group-hover:text-pink-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
          </div>
          <h3 class="text-2xl font-black mb-5 text-white group-hover:text-pink-400 transition-colors uppercase tracking-tight">Kewirausahaan</h3>
          <p class="text-slate-400 text-sm leading-relaxed group-hover:text-slate-300 transition-colors font-medium">Pengembangan pola pikir inovatif dalam menciptakan solusi teknologi yang memiliki nilai ekonomi bagi kemajuan UMKM.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="team" class="py-32 relative overflow-hidden reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="text-center mb-24 child-reveal">
        <span class="text-brand-400 font-bold uppercase tracking-[0.6em] text-[10px] block mb-4">Development Crew</span>
        <h2 class="text-5xl md:text-7xl font-black mb-8 text-white tracking-tighter drop-shadow-xl">The Dream Team</h2>
        <p class="text-slate-400 text-xl max-w-3xl mx-auto font-medium">Kolaborasi penuh dedikasi dari Departemen Teknik Industri UNS.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-3 lg:w-[60%] lg:mx-auto glass-card p-12 rounded-[4rem] text-center border-brand-500/40 group child-reveal delay-1">
          <div class="relative inline-block mb-10">
            <div class="absolute -inset-4 rounded-full bg-gradient-to-r from-brand-500 via-blue-500 to-brand-500 blur-2xl opacity-0 group-hover:opacity-70 transition-all duration-700 animate-spin-slow"></div>
            <img src="{{ asset('images/team-dosen.jpg') }}" alt="Prof. Lobes Herdiman" class="relative z-10 w-44 h-44 mx-auto rounded-full object-cover border-[6px] border-dark-950 shadow-2xl transition-all duration-700 group-hover:scale-105">
          </div>
          <h3 class="text-4xl font-black text-white mb-3 group-hover:text-brand-300 transition-colors tracking-tighter">Prof. Dr. Ir. Lobes Herdiman, M.T.</h3>
          <p class="text-brand-400 font-black uppercase tracking-[0.4em] text-xs opacity-80">Dosen Pembimbing</p>
        </div>

        <div class="glass-card p-10 rounded-[3rem] text-center group child-reveal delay-2">
          <div class="relative inline-block mb-8">
             <div class="absolute -inset-4 rounded-full bg-brand-500/30 blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="{{ asset('images/team-rafael.png') }}" alt="Muhammad Rafael" class="relative z-10 w-36 h-36 mx-auto rounded-full object-cover border-4 border-transparent group-hover:border-brand-400 transition-all duration-700 group-hover:scale-110 shadow-xl">
          </div>
          <h4 class="text-xl font-black mb-2 text-white group-hover:text-brand-300 transition-colors tracking-tight">Muhammad Rafael Putra A.</h4>
          <p class="text-slate-500 text-[11px] mb-6 font-bold tracking-widest">I0323084</p>
          <span class="px-6 py-2.5 bg-brand-500/10 text-brand-400 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-500 group-hover:bg-brand-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(34,197,94,0.5)]">Project Manager</span>
        </div>

        <div class="glass-card p-10 rounded-[3rem] text-center group child-reveal delay-3">
          <div class="relative inline-block mb-8">
            <div class="absolute -inset-4 rounded-full bg-blue-500/30 blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="{{ asset('images/team-gala.png') }}" alt="Gala Septio" class="relative z-10 w-36 h-36 mx-auto rounded-full object-cover border-4 border-transparent group-hover:border-blue-400 transition-all duration-700 group-hover:scale-110 shadow-xl">
          </div>
          <h4 class="text-xl font-black mb-2 text-white group-hover:text-blue-300 transition-colors tracking-tight text-nowrap">Gala Septio Wamar</h4>
          <p class="text-slate-500 text-[11px] mb-6 font-bold tracking-widest">I0323046</p>
          <span class="px-6 py-2.5 bg-blue-500/10 text-blue-400 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-500 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(59,130,246,0.5)]">Tech Officer</span>
        </div>

        <div class="glass-card p-10 rounded-[3rem] text-center group child-reveal delay-4">
          <div class="relative inline-block mb-8">
            <div class="absolute -inset-4 rounded-full bg-blue-500/30 blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="{{ asset('images/team-angga.png') }}" alt="Angga Adi" class="relative z-10 w-36 h-36 mx-auto rounded-full object-cover border-4 border-transparent group-hover:border-blue-400 transition-all duration-700 group-hover:scale-110 shadow-xl">
          </div>
          <h4 class="text-xl font-black mb-2 text-white group-hover:text-blue-300 transition-colors tracking-tight">Angga Adi Prasetyo</h4>
          <p class="text-slate-500 text-[11px] mb-6 font-bold tracking-widest">I0323015</p>
          <span class="px-6 py-2.5 bg-blue-500/10 text-blue-400 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-500 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(59,130,246,0.5)]">Tech Officer</span>
        </div>

        <div class="glass-card p-10 rounded-[3rem] text-center group child-reveal delay-5">
          <div class="relative inline-block mb-8">
            <div class="absolute -inset-4 rounded-full bg-pink-500/30 blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="{{ asset('images/team-anya.png') }}" alt="Anya Lareina" class="relative z-10 w-36 h-36 mx-auto rounded-full object-cover border-4 border-transparent group-hover:border-pink-400 transition-all duration-700 group-hover:scale-110 shadow-xl">
          </div>
          <h4 class="text-xl font-black mb-2 text-white group-hover:text-pink-300 transition-colors tracking-tight text-nowrap">Anya Lareina C.W.</h4>
          <p class="text-slate-500 text-[11px] mb-6 font-bold tracking-widest">I0323016</p>
          <span class="px-6 py-2.5 bg-pink-500/10 text-pink-400 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-500 group-hover:bg-pink-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(236,72,153,0.5)]">Media & Creative</span>
        </div>

        <div class="glass-card p-10 rounded-[3rem] text-center group child-reveal delay-6">
          <div class="relative inline-block mb-8">
            <div class="absolute -inset-4 rounded-full bg-purple-500/30 blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="{{ asset('images/team-ropita.png') }}" alt="Ropita Sinambela" class="relative z-10 w-36 h-36 mx-auto rounded-full object-cover border-4 border-transparent group-hover:border-purple-400 transition-all duration-700 group-hover:scale-110 shadow-xl">
          </div>
          <h4 class="text-xl font-black mb-2 text-white group-hover:text-purple-300 transition-colors tracking-tight">Ropita Sari Sinambela</h4>
          <p class="text-slate-500 text-[11px] mb-6 font-bold tracking-widest">I0323107</p>
          <span class="px-6 py-2.5 bg-purple-500/10 text-purple-400 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-500 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(168,85,247,0.5)]">Secretary</span>
        </div>

        <div class="glass-card p-10 rounded-[3rem] text-center group child-reveal delay-7">
          <div class="relative inline-block mb-8">
            <div class="absolute -inset-4 rounded-full bg-orange-500/30 blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
            <img src="{{ asset('images/team-zakky.png') }}" alt="Zakky Muhammad" class="relative z-10 w-36 h-36 mx-auto rounded-full object-cover border-4 border-transparent group-hover:border-orange-400 transition-all duration-700 group-hover:scale-110 shadow-xl">
          </div>
          <h4 class="text-xl font-black mb-2 text-white group-hover:text-orange-300 transition-colors tracking-tight">Zakky Muhammad Wildan</h4>
          <p class="text-slate-500 text-[11px] mb-6 font-bold tracking-widest">I0323120</p>
          <span class="px-6 py-2.5 bg-orange-500/10 text-orange-400 rounded-full text-[9px] font-black uppercase tracking-widest transition-all duration-500 group-hover:bg-orange-500 group-hover:text-white group-hover:shadow-[0_0_20px_rgba(249,115,22,0.5)]">Treasurer</span>
        </div>
      </div>
    </div>
  </section>

  <section class="py-40 relative overflow-hidden reveal">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-center text-slate-500 text-xs font-black uppercase tracking-[0.6em] mb-20 child-reveal">Pihak yang Terlibat</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 child-reveal delay-2">
            <a href="https://industri.ft.uns.ac.id/lab-lppd" target="_blank" class="glass-card p-12 rounded-[3rem] flex flex-col items-center justify-center hover:scale-110 group transition-all duration-700 bg-dark-900/50">
                <img src="{{ asset('images/logo-lppd.png') }}" alt="Logo LPPD" class="h-24 mb-8 filter brightness-0 invert opacity-50 group-hover:opacity-100 group-hover:filter-none transition-all duration-1000 transform group-hover:rotate-12 group-hover:scale-110">
                <p class="text-center text-xs font-black text-slate-500 group-hover:text-white transition-colors uppercase tracking-[0.2em] leading-relaxed">Laboratory of Product Planning & Design UNS</p>
            </a>
            <a href="https://moera.co.id" target="_blank" class="glass-card p-12 rounded-[3rem] flex flex-col items-center justify-center hover:scale-110 group transition-all duration-700 bg-dark-900/50">
                <img src="{{ asset('images/logo-mitra.png') }}" alt="Logo Moera" class="h-24 mb-8 filter brightness-0 invert opacity-50 group-hover:opacity-100 group-hover:filter-none transition-all duration-1000 transform group-hover:-rotate-12 group-hover:scale-110">
                <p class="text-center text-xs font-black text-slate-500 group-hover:text-white transition-colors uppercase tracking-[0.2em] leading-relaxed">CV Agrosehat Nusantara (Moera)</p>
            </a>
            <a href="https://uns.ac.id" target="_blank" class="glass-card p-12 rounded-[3rem] flex flex-col items-center justify-center hover:scale-110 group transition-all duration-700 bg-dark-900/50">
                <img src="{{ asset('images/logo-uns.png') }}" alt="Logo UNS" class="h-24 mb-8 filter brightness-0 invert opacity-50 group-hover:opacity-100 group-hover:filter-none transition-all duration-1000 transform group-hover:rotate-12 group-hover:scale-110">
                <p class="text-center text-xs font-black text-slate-500 group-hover:text-white transition-colors uppercase tracking-[0.2em] leading-relaxed">Universitas Sebelas Maret</p>
            </a>
        </div>
    </div>
  </section>

  <footer class="border-t border-white/5 bg-dark-950/80 backdrop-blur-3xl py-24 relative z-10 reveal">
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[80%] h-[300px] bg-brand-500/10 blur-[150px] pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20 child-reveal">
        <div class="group cursor-default col-span-1 md:col-span-2">
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ asset('images/stockku-logo.png') }}" alt="Stockku Logo" class="w-12 h-12 object-contain transition-transform duration-700 group-hover:rotate-[360deg] group-hover:scale-110">
                <span class="text-3xl font-black text-white transition-all group-hover:tracking-tighter text-glow">Stockku<span class="text-brand-400">App</span></span>
            </div>
            <p class="text-slate-400 text-sm leading-relaxed italic mb-8 opacity-90 font-medium max-w-md">
                Digitalisasi efisien hulu ke hilir untuk UMKM Agribisnis Indonesia. <br> Powered by Laboratory of Product Planning and Design UNS.
            </p>
        </div>
        
        <div class="flex flex-col gap-6 col-span-1 justify-start">
            <div class="text-left group/item child-reveal delay-1">
                <p class="text-brand-500 text-[10px] uppercase font-black tracking-[0.4em] mb-4 opacity-80">Program</p>
                <p class="text-white text-sm font-bold tracking-tight">Hibah Pembelajaran Berdampak<br><span class="text-slate-400 font-medium italic text-xs">Periode Agustus 2025 - Januari 2026</span></p>
            </div>
            <div class="text-left group/item child-reveal delay-2">
                <p class="text-brand-500 text-[10px] uppercase font-black tracking-[0.4em] mb-4 opacity-80">Location</p>
                <p class="text-white text-xs font-bold leading-relaxed opacity-90">Gedung 6 Lt. 1 Fakultas Teknik Universitas Sebelas Maret<br>Jl. Ir. Sutami No.36 A, Jebres, Kota Surakarta, Jawa Tengah 57126</p>
            </div>
        </div>

        <div class="flex flex-col gap-6 col-span-1 justify-start child-reveal delay-3">
             <p class="text-brand-500 text-[10px] uppercase font-black tracking-[0.4em] mb-2 opacity-80">Hubungi Kami</p>
             <a href="https://instagram.com/stockkuapp" target="_blank" class="flex items-center gap-3 group/contact hover:bg-white/5 p-3 rounded-2xl transition-all -ml-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center group-hover/contact:bg-brand-500/20 transition-colors">
                    <svg class="w-5 h-5 text-slate-300 group-hover/contact:text-brand-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.012 3.584-.069 4.85c-.148 3.252-1.667 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.07-1.644-.07-4.85s.012-3.584.07-4.85c.148-3.252 1.667-4.771 4.919-4.919 1.266-.058 1.645-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948s-.014-3.667-.073-4.947c-.2-4.354-2.618-6.782-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Instagram</p>
                    <p class="text-white text-sm font-bold group-hover/contact:text-brand-400 transition-colors">@stockkuapp</p>
                </div>
             </a>
             <a href="mailto:teamstockku@gmail.com" class="flex items-center gap-3 group/contact hover:bg-white/5 p-3 rounded-2xl transition-all -ml-3">
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center group-hover/contact:bg-brand-500/20 transition-colors">
                    <svg class="w-5 h-5 text-slate-300 group-hover/contact:text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Email</p>
                    <p class="text-white text-sm font-bold group-hover/contact:text-brand-400 transition-colors">teamstockku@gmail.com</p>
                </div>
             </a>
        </div>
      </div>
      
      <div class="border-t border-white/5 pt-10 flex flex-col md:flex-row justify-between items-center text-[10px] text-slate-500 uppercase tracking-[0.3em] font-black gap-8 child-reveal delay-4">
        <a href="https://instagram.com/stockkuapp" target="_blank" class="hover:text-brand-400 transition-all duration-500 hover:tracking-[0.5em] relative group">
            © 2026 Tim StockkuApp - All Rights Reserved
            <span class="absolute -bottom-2 left-1/2 w-0 h-[1px] bg-brand-400 group-hover:w-full group-hover:left-0 transition-all duration-500"></span>
        </a>
        
        <div class="flex items-center gap-10">
            <a href="https://industri.ft.uns.ac.id/lab-lppd" target="_blank" class="hover:text-white transition-all duration-500 hover:scale-110 relative after:absolute after:bottom-[-4px] after:left-0 after:h-[1px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">LPPD UNS</a>
            <a href="https://moera.co.id" target="_blank" class="hover:text-white transition-all duration-500 hover:scale-110 relative after:absolute after:bottom-[-4px] after:left-0 after:h-[1px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">Moera</a>
            <a href="https://uns.ac.id" target="_blank" class="hover:text-white transition-all duration-500 hover:scale-110 relative after:absolute after:bottom-[-4px] after:left-0 after:h-[1px] after:w-0 after:bg-brand-400 after:transition-all hover:after:w-full">UNS</a>
      </div>
    </div>
  </footer>

  <script>
    // Advanced Staggered Reveal Animation
    const revealSections = document.querySelectorAll('.reveal');
    
    const observerOptions = {
        root: null,
        threshold: 0.1, // Muncul lebih awal
        rootMargin: "0px 0px -100px 0px" // Offset bawah agar animasi mulai sebelum elemen benar-benar masuk viewport penuh
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

    // Navbar scroll effect (Refined)
    const nav = document.querySelector('nav');
    const navContainer = document.getElementById('nav-container');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 80) {
            nav.classList.add('h-20', 'bg-dark-950/80', 'border-b', 'border-brand-500/10', 'backdrop-blur-2xl');
            nav.classList.remove('h-24', 'glass-nav');
            navContainer.classList.add('h-20');
            navContainer.classList.remove('h-24');
        } else {
            nav.classList.add('h-24', 'glass-nav');
            nav.classList.remove('h-20', 'bg-dark-950/80', 'border-b', 'border-brand-500/10', 'backdrop-blur-2xl');
            navContainer.classList.add('h-24');
            navContainer.classList.remove('h-20');
        }
    });
  </script>

</body>
</html>