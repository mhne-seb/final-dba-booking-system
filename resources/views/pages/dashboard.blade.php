@extends('layouts.app')

@section('content')
<div class="p-8">
    
    <header class="mb-10">
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Dashboard</h1>
        <p class="text-sm text-slate-400 mt-1">Welcome back! Here's an overview of today's activities.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-[#0f172a] border border-slate-800 p-6 rounded-2xl hover:border-blue-500/40 transition-all shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Bookings Today</p>
                    <h3 class="text-3xl font-black text-white mt-3">12</h3>
                    <p class="text-[10px] text-blue-400 mt-2">+3 from yesterday</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-xl text-blue-500">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="bg-[#0f172a] border border-slate-800 p-6 rounded-2xl shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pending Requests</p>
                    <h3 class="text-3xl font-black text-white mt-3">5</h3>
                    <p class="text-[10px] text-orange-400 mt-2">2 need attention</p>
                </div>
                <div class="bg-orange-500/10 p-3 rounded-xl text-orange-500">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="bg-[#0f172a] border border-slate-800 p-6 rounded-2xl shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Completed Today</p>
                    <h3 class="text-3xl font-black text-white mt-3">7</h3>
                    <p class="text-[10px] text-emerald-400 mt-2">+2 from yesterday</p>
                </div>
                <div class="bg-emerald-500/10 p-3 rounded-xl text-emerald-500">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="bg-[#0f172a] border border-slate-800 p-6 rounded-2xl shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Customers</p>
                    <h3 class="text-3xl font-black text-white mt-3">248</h3>
                    <p class="text-[10px] text-purple-400 mt-2">+15 this month</p>
                </div>
                <div class="bg-purple-500/10 p-3 rounded-xl text-purple-500">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-[#0f172a] border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
            <div class="p-6 border-b border-slate-800 flex items-center gap-3">
                <i data-lucide="car" class="w-5 h-5 text-blue-500"></i>
                <h2 class="text-xs font-black text-white uppercase tracking-widest">Today's Bookings</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-slate-800/20 p-4 rounded-2xl flex items-center justify-between border border-slate-700/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white">JD</div>
                        <div>
                            <p class="text-sm font-bold text-white">Juan dela Cruz</p>
                            <p class="text-xs text-slate-500">Toyota Vios • Tire Vulcanizing</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-xs text-slate-400 font-mono">10:30 AM</span>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase">In Progress</span>
                    </div>
                </div>

                <div class="bg-slate-800/20 p-4 rounded-2xl flex items-center justify-between border border-slate-700/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white">MS</div>
                        <div>
                            <p class="text-sm font-bold text-white">Maria Santos</p>
                            <p class="text-xs text-slate-500">Honda Civic • Wheel Alignment</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-xs text-slate-400 font-mono">11:00 AM</span>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700 uppercase">Not Started</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-[#0f172a] border border-slate-800 rounded-3xl p-6 shadow-xl">
                <h2 class="text-[10px] font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-4 h-4 text-blue-500"></i> Weekly Overview
                </h2>
                <div class="space-y-5">
                    <div>
                        <div class="flex justify-between text-[10px] text-slate-400 mb-2"><span>Mon</span><span>90%</span></div>
                        <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 w-[90%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] text-slate-400 mb-2"><span>Tue</span><span>65%</span></div>
                        <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 w-[65%]"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#0f172a] border border-slate-800 rounded-3xl p-6 shadow-xl">
                <h2 class="text-[10px] font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i data-lucide="zap" class="w-4 h-4 text-yellow-500"></i> Popular Services
                </h2>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-[11px] mb-2"><span class="text-slate-300">Tire Vulcanizing</span><span class="text-white font-bold">42%</span></div>
                        <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 w-[42%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>
@endsection