<section class="relative py-28 px-6 bg-gradient-to-br from-black-900 via-black-800 to-black-700 overflow-hidden text-white">
    <!-- Background animated shapes -->
    <div class="absolute -top-20 -left-20 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl animate-pulse-slow"></div>
    <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-blue-600/20 rounded-full blur-2xl animate-pulse-slow"></div>
    <div class="absolute top-1/2 left-1/2 w-72 h-72 bg-cyan-400/10 rounded-full blur-[100px] transform -translate-x-1/2 -translate-y-1/2 animate-spin-slow"></div>

    <!-- Floating particles -->
    <canvas id="particles" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

    <div class="container mx-auto relative z-10">
        <div class="flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-20">
            <!-- Image -->
            <div class="lg:w-1/2 mt-12 lg:mt-0 relative group">
                <img src="/img/global-availability.jpg" alt="Global Availability" class="rounded-3xl w-full shadow-2xl transform transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 rounded-3xl bg-gradient-to-tr from-red-500/20 via-red-700/10 to-transparent opacity-50 pointer-events-none"></div>
            </div>

            <!-- Content -->
            <div class="lg:w-1/2 lg:pl-12 text-center lg:text-left space-y-8">
                <h3 class="text-6xl font-extrabold text-white mb-6 neon-heading animate-fade-in-up">Global Availability</h3>
                <p class="text-2xl text-red-100 leading-relaxed animate-fade-in-up delay-200">
                    No matter where you are, our services are designed to meet your needs. We ensure worldwide
                    availability to help you connect with your audience and scale your business effortlessly.
                </p>
                <ul class="mt-8 space-y-6">
                    <li class="flex items-center gap-4 animate-fade-in-up delay-400">
                        <div class="flex items-center justify-center w-14 h-14 bg-red-600 rounded-full shadow-lg transition-transform transform hover:scale-110 neon-icon">
                            <i class="fas fa-globe text-2xl"></i>
                        </div>
                        <span class="text-xl text-red-50">Accounts tailored for global markets.</span>
                    </li>
                    <li class="flex items-center gap-4 animate-fade-in-up delay-600">
                        <div class="flex items-center justify-center w-14 h-14 bg-red-600 rounded-full shadow-lg transition-transform transform hover:scale-110 neon-icon">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <span class="text-xl text-red-50">Round-the-clock availability across time zones.</span>
                    </li>
                    <li class="flex items-center gap-4 animate-fade-in-up delay-800">
                        <div class="flex items-center justify-center w-14 h-14 bg-red-600 rounded-full shadow-lg transition-transform transform hover:scale-110 neon-icon">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <span class="text-xl text-red-50">Optimized for international campaigns.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<style>
/* Keyframes */
@keyframes pulse-slow {
  0%,100% {opacity:0.6; transform:scale(1);}
  50% {opacity:1; transform:scale(1.05);}
}
.animate-pulse-slow { animation: pulse-slow 10s ease-in-out infinite; }

@keyframes spin-slow {
  0% { transform: translate(-50%, -50%) rotate(0deg);}
  100% { transform: translate(-50%, -50%) rotate(360deg);}
}
.animate-spin-slow { animation: spin-slow 60s linear infinite; }

@keyframes fade-in-up {
  0% { opacity: 0; transform: translateY(20px);}
  100% { opacity: 1; transform: translateY(0);}
}
.animate-fade-in-up { animation: fade-in-up 1s forwards; }
.animate-fade-in-up.delay-200 { animation-delay: 0.2s; }
.animate-fade-in-up.delay-400 { animation-delay: 0.4s; }
.animate-fade-in-up.delay-600 { animation-delay: 0.6s; }
.animate-fade-in-up.delay-800 { animation-delay: 0.8s; }

/* Neon styles */
.neon-heading {
  text-shadow: 0 0 5px #947171, 0 0 10px #a7dd69, 0 0 20px #80b6e2, 0 0 40px #c966b0;
}
.neon-icon i {
  color: #fff;
  text-shadow: 0 0 5px #ff0000, 0 0 10px #ff4c4c, 0 0 15px #ff6c6c;
}

/* Floating icon pulse */
@keyframes icon-pulse {
  0%,100% { transform: scale(1);}
  50% { transform: scale(1.2);}
}
.animate-pulse { animation: icon-pulse 1.5s ease-in-out infinite; }
</style>

<script>
// Simple floating particles effect
const canvas = document.getElementById('particles');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const particles = [];
const particleCount = 80;

for(let i=0; i<particleCount; i++){
    particles.push({
        x: Math.random()*canvas.width,
        y: Math.random()*canvas.height,
        r: Math.random()*3 + 1,
        dx: (Math.random()-0.5)*0.5,
        dy: (Math.random()-0.5)*0.5,
        opacity: Math.random()
    });
}

function animate(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    particles.forEach(p=>{
        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fillStyle = `rgba(255,100,100,${p.opacity})`;
        ctx.fill();
        p.x += p.dx;
        p.y += p.dy;
        if(p.x<0||p.x>canvas.width) p.dx*=-1;
        if(p.y<0||p.y>canvas.height) p.dy*=-1;
    });
    requestAnimationFrame(animate);
}
animate();

window.addEventListener('resize',()=>{
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});
</script>
