import { useEffect, useRef } from 'react';

interface PageHeroProps {
  title: string;
  subtitle?: string;
  heroImage?: string;
}

const PageHero = ({ title, subtitle, heroImage }: PageHeroProps) => {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const particles: Array<{
      x: number;
      y: number;
      vx: number;
      vy: number;
      size: number;
      opacity: number;
      color: string;
    }> = [];

    // Create particles
    for (let i = 0; i < 80; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3,
        size: Math.random() * 2 + 1,
        opacity: Math.random() * 0.3 + 0.1,
        color: Math.random() > 0.5 ? 'hsl(38, 85%, 52%)' : 'hsl(221, 63%, 20%)'
      });
    }

    const animate = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      
      particles.forEach(particle => {
        particle.x += particle.vx;
        particle.y += particle.vy;
        
        if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
        if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
        
        ctx.globalAlpha = particle.opacity;
        ctx.fillStyle = particle.color;
        ctx.beginPath();
        ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
        ctx.fill();
      });
      
      requestAnimationFrame(animate);
    };

    animate();

    const handleResize = () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    };

    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  // Default hero images for different pages
  const getHeroImage = () => {
    if (heroImage) return heroImage;
    
    const imageMap: { [key: string]: string } = {
      'About Us': 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1920&h=1080&fit=crop&auto=format',
      'Contact Us': 'https://images.unsplash.com/photo-1516478177715-4ece44cd79c2?w=1920&h=1080&fit=crop&auto=format',
      'Ministries': 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop&auto=format',
      'Leadership': 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&h=1080&fit=crop&auto=format',
      'Sermons': 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1920&h=1080&fit=crop&auto=format',
      'Events': 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=1920&h=1080&fit=crop&auto=format',
      'Donate': 'https://images.unsplash.com/photo-1593115057322-e94e7b75d401?w=1920&h=1080&fit=crop&auto=format',
      'Gallery': 'https://images.unsplash.com/photo-1515238152791-8499a8e85d5a?w=1920&h=1080&fit=crop&auto=format',
      'Blog': 'https://images.unsplash.com/photo-1481627834876-b7833e2f5573?w=1920&h=1080&fit=crop&auto=format'
    };
    
    return imageMap[title] || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&h=1080&fit=crop&auto=format';
  };

  return (
    <section className="relative overflow-hidden min-h-screen flex items-center justify-center">
      {/* Hero Image Background */}
      <div className="absolute inset-0">
        <img 
          src={getHeroImage()} 
          alt={title}
          className="w-full h-full object-cover"
        />
        {/* Dark overlay for text readability */}
        <div className="absolute inset-0 bg-gradient-to-b from-navy/80 via-navy/60 to-navy/90"></div>
        {/* Additional gradient overlays */}
        <div className="absolute inset-0 bg-gradient-to-r from-navy/40 via-transparent to-gold/20"></div>
        <div className="absolute inset-0 bg-gradient-to-t from-navy/60 via-transparent to-navy/40"></div>
      </div>
      
      {/* Animated particle background */}
      <canvas 
        ref={canvasRef}
        className="absolute inset-0 z-10"
        style={{ opacity: 0.4 }}
      />
      
      {/* Enhanced decorative elements */}
      <span className="absolute left-8 top-24 text-gold/60 text-2xl animate-float-slow hidden md:block opacity-70">✦</span>
      <span className="absolute right-10 top-24 text-gold/50 text-2xl animate-float-medium hidden md:block">✧</span>
      <span className="absolute left-16 bottom-10 text-gold/45 text-2xl animate-float-medium hidden md:block">✧</span>
      <span className="absolute right-14 bottom-10 text-gold/60 text-2xl animate-float-slow hidden md:block opacity-70">✦</span>
      
      {/* Additional floating elements */}
      <span className="absolute left-1/4 top-1/3 text-gold/25 text-xl animate-float-slow hidden lg:block">✧</span>
      <span className="absolute right-1/4 top-1/2 text-gold/35 text-lg animate-float-medium hidden lg:block">✧</span>
      <span className="absolute left-1/3 bottom-1/4 text-gold/30 text-2xl animate-float-slow hidden lg:block">✦</span>
      <span className="absolute right-1/3 bottom-1/3 text-gold/40 text-xl animate-float-medium hidden lg:block">✦</span>
      
      {/* Animated light beams */}
      <div className="absolute inset-0 overflow-hidden z-5">
        <div className="absolute top-0 left-1/4 w-1 h-full bg-gradient-to-b from-gold/30 via-transparent to-transparent animate-slide-down opacity-40"></div>
        <div className="absolute top-0 right-1/4 w-1 h-full bg-gradient-to-b from-gold/25 via-transparent to-transparent animate-slide-down opacity-30" style={{ animationDelay: '1s' }}></div>
        <div className="absolute top-0 left-1/2 w-1 h-full bg-gradient-to-b from-gold/28 via-transparent to-transparent animate-slide-down opacity-35" style={{ animationDelay: '2s' }}></div>
      </div>
      
      {/* Main content with glassmorphism */}
      <div className="relative container mx-auto px-4 z-20">
        <div className="glassmorphism-enhanced rounded-3xl p-8 md:p-12 border border-white/15 shadow-gold-enhanced max-w-4xl mx-auto">
          <div className="mb-6 flex items-center justify-center gap-6 text-gold/85 text-2xl">
            <span className="animate-float-slow text-5xl md:text-6xl">✦</span>
            <span className="animate-float-medium text-3xl">✧</span>
            <span className="animate-float-slow text-5xl md:text-6xl">✦</span>
          </div>
          
          <h1 className="font-algerian text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 text-shadow-gold animate-fade-in-up text-center">
            {title}
          </h1>
          
          <div className="w-32 h-1.5 bg-gradient-gold mx-auto mb-6 rounded-full shadow-gold-enhanced animate-scale-in"></div>
          
          {subtitle && (
            <p className="text-gold/85 text-xl md:text-2xl max-w-3xl mx-auto font-gabriola text-shadow-gold animate-fade-in-up leading-relaxed text-center" style={{ animationDelay: '0.3s' }}>
              {subtitle}
            </p>
          )}
          
          {/* Call-to-action buttons */}
          <div className="mt-8 flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style={{ animationDelay: '0.6s' }}>
            <button className="px-8 py-4 bg-gradient-gold text-navy font-bold rounded-full hover:scale-105 transition-all duration-300 shadow-gold-enhanced hover:shadow-gold-intense button-glow font-algerian">
              Get Started
            </button>
            <button className="px-8 py-4 border-2 border-gold text-gold font-bold rounded-full hover:bg-gold hover:text-navy transition-all duration-300 hover:scale-105 font-algerian">
              Learn More
            </button>
          </div>
        </div>
      </div>
    </section>
  );
};

export default PageHero;
