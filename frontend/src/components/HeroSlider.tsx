import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { ChevronLeft, ChevronRight } from "lucide-react";
import heroWorship from "@/assets/hero-worship.jpg";
import heroCommunity from "@/assets/hero-community.jpg";
import heroChoir from "@/assets/hero-choir.jpg";

const slides = [
  {
    image: heroWorship,
    title: "Welcome to Salem Dominion Ministries",
    subtitle: "A place of worship, growth, love, and transformation",
    cta: { text: "Plan a Visit", link: "/contact" },
  },
  {
    image: heroCommunity,
    title: "Building a Christ-Centered Community",
    subtitle: "Impacting generations spiritually, socially, and economically",
    cta: { text: "Learn More", link: "/about" },
  },
  {
    image: heroChoir,
    title: "Experience the Power of Worship",
    subtitle: "Join us every Sunday and let God's presence transform your life",
    cta: { text: "Watch Online", link: "/sermons" },
  },
];

const HeroSlider = () => {
  const [current, setCurrent] = useState(0);
  const [isResurrected, setIsResurrected] = useState(false);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrent((prev) => (prev + 1) % slides.length);
    }, 6000);
    return () => clearInterval(timer);
  }, []);

  useEffect(() => {
    // Auto-trigger resurrection after 10 seconds
    const resurrectionTimer = setTimeout(() => {
      toggleResurrection();
    }, 10000);
    return () => clearTimeout(resurrectionTimer);
  }, []);

  const toggleResurrection = () => {
    setIsResurrected(!isResurrected);
    const thornCrown = document.getElementById('thornCrown');
    const resurrectionSun = document.getElementById('resurrectionSun');
    const resurrectionRays = document.querySelector('.resurrection-rays');
    
    if (thornCrown && resurrectionSun && resurrectionRays) {
      if (!isResurrected) {
        thornCrown.style.opacity = '0';
        resurrectionSun.classList.add('active');
        resurrectionRays.classList.add('active');
      } else {
        thornCrown.style.opacity = '0.6';
        resurrectionSun.classList.remove('active');
        resurrectionRays.classList.remove('active');
      }
    }
  };

  const prev = () => setCurrent((c) => (c - 1 + slides.length) % slides.length);
  const next = () => setCurrent((c) => (c + 1) % slides.length);

  return (
    <div className="relative h-[90vh] min-h-[600px] overflow-hidden cross-sun-pattern">
      {slides.map((slide, i) => (
        <div
          key={i}
          className={`absolute inset-0 transition-opacity duration-1000 ${
            i === current ? "opacity-100" : "opacity-0"
          }`}
        >
          <img
            src={slide.image}
            alt={slide.title}
            className="w-full h-full object-cover"
          />
          <div className="absolute inset-0 bg-hero-overlay" />
        </div>
      ))}

      {/* Golgotha Scene */}
      <div className="absolute bottom-0 left-0 right-0 h-64 golgotha-scene">
        <div className="golgotha-crosses">
          <div className="golgotha-cross cross-side"></div>
          <div className="golgotha-cross cross-center"></div>
          <div className="golgotha-cross cross-side"></div>
        </div>
      </div>

      <div className="absolute inset-0 flex items-center justify-center">
        <div className="text-center px-4 max-w-4xl animate-fade-in">
          {/* Religious Symbolism */}
          <div className="mb-6 relative">
            <div className="thorn-crown" id="thornCrown"></div>
            <div className="resurrection-sun" id="resurrectionSun">
              <div className="resurrection-rays"></div>
            </div>
          </div>
          
          <div className="mb-4 flex items-center justify-center gap-8 text-gold/90 text-2xl">
            <span className="animate-float-slow">✦</span>
            <span className="animate-float-medium text-3xl">✧</span>
            <span className="animate-float-slow">✦</span>
          </div>
          <h1 className="font-heading text-4xl md:text-6xl lg:text-7xl text-card font-bold mb-6 leading-tight">
            {slides[current].title}
          </h1>
          <p className="text-card/80 text-lg md:text-xl mb-8 max-w-2xl mx-auto">
            {slides[current].subtitle}
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              to={slides[current].cta.link}
              className="inline-flex items-center justify-center px-8 py-4 bg-gradient-gold text-primary font-semibold rounded-md shadow-gold hover:opacity-90 transition-opacity text-lg"
            >
              {slides[current].cta.text}
            </Link>
            <button
              onClick={toggleResurrection}
              className="inline-flex items-center justify-center px-8 py-4 border-2 border-gold text-gold font-semibold rounded-md hover:bg-gold/10 transition-colors text-lg"
            >
              {isResurrected ? '⚡ View Resurrection' : '🙏 Witness the Sacrifice'}
            </button>
          </div>
        </div>
      </div>

      {/* Arrows */}
      <button
        onClick={prev}
        className="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-primary/50 hover:bg-primary/70 text-card flex items-center justify-center transition-colors"
        aria-label="Previous slide"
      >
        <ChevronLeft size={24} />
      </button>
      <button
        onClick={next}
        className="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-primary/50 hover:bg-primary/70 text-card flex items-center justify-center transition-colors"
        aria-label="Next slide"
      >
        <ChevronRight size={24} />
      </button>

      {/* Dots */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3">
        {slides.map((_, i) => (
          <button
            key={i}
            onClick={() => setCurrent(i)}
            className={`w-3 h-3 rounded-full transition-all ${
              i === current ? "bg-gold w-8" : "bg-card/50 hover:bg-card/80"
            }`}
            aria-label={`Go to slide ${i + 1}`}
          />
        ))}
      </div>

      {/* Decorative faith accents */}
      <span className="absolute left-10 top-28 text-gold/70 text-2xl animate-float-slow hidden md:block">✦</span>
      <span className="absolute right-10 top-32 text-card/70 text-2xl animate-float-medium hidden md:block">✧</span>
      <span className="absolute left-16 bottom-28 text-card/60 text-2xl animate-float-medium hidden md:block">✧</span>
      <span className="absolute right-14 bottom-24 text-gold/70 text-2xl animate-float-slow hidden md:block">✦</span>
    </div>
  );
};

export default HeroSlider;
