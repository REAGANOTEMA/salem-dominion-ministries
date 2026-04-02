import { useEffect, useState } from 'react';

interface Particle {
  id: number;
  x: number;
  y: number;
  size: number;
  speed: number;
  delay: number;
  opacity: number;
  type: 'star' | 'sparkle' | 'light';
}

export const HeavenlyEffects = () => {
  const [particles, setParticles] = useState<Particle[]>([]);

  useEffect(() => {
    // Create various heavenly particles
    const particlesData: Particle[] = [];
    
    // Golden stars/sparkles
    for (let i = 0; i < 20; i++) {
      particlesData.push({
        id: i,
        x: Math.random() * 100,
        y: Math.random() * 100,
        size: Math.random() * 3 + 1,
        speed: Math.random() * 3 + 2,
        delay: Math.random() * 5,
        opacity: Math.random() * 0.5 + 0.2,
        type: Math.random() > 0.5 ? 'star' : 'sparkle',
      });
    }
    
    setParticles(particlesData);
  }, []);

  return (
    <div className="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
      <style>{`
        @keyframes twinkle {
          0%, 100% { opacity: 0.2; transform: scale(1); }
          50% { opacity: 0.8; transform: scale(1.5); }
        }
        @keyframes sparkle {
          0%, 100% { 
            opacity: 0; 
            transform: scale(0) rotate(0deg);
          }
          50% { 
            opacity: 0.6; 
            transform: scale(1) rotate(180deg);
          }
        }
        @keyframes divineLight {
          0%, 100% { 
            opacity: 0.05;
            transform: translateY(0) scaleY(1);
          }
          50% { 
            opacity: 0.15;
            transform: translateY(-20px) scaleY(1.1);
          }
        }
        @keyframes floatUp {
          0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
          10% { opacity: 0.6; }
          90% { opacity: 0.6; }
          100% { transform: translateY(-100vh) translateX(30px); opacity: 0; }
        }
        .heavenly-particle {
          animation-timing-function: ease-in-out;
          animation-iteration-count: infinite;
        }
      `}</style>

      {/* Divine light rays from top */}
      <div className="absolute inset-0 overflow-hidden">
        {[...Array(5)].map((_, i) => (
          <div
            key={`light-${i}`}
            className="absolute top-0 heavenly-particle"
            style={{
              left: `${15 + i * 18}%`,
              width: '2px',
              height: '100vh',
              background: 'linear-gradient(to bottom, rgba(245, 158, 11, 0.3), transparent)',
              animation: `divineLight ${4 + i * 2}s ease-in-out infinite`,
              animationDelay: `${i * 0.5}s`,
              filter: 'blur(1px)',
            }}
          />
        ))}
      </div>

      {/* Golden particles */}
      {particles.map((particle) => (
        <div
          key={particle.id}
          className="absolute heavenly-particle"
          style={{
            left: `${particle.x}%`,
            top: `${particle.y}%`,
            animation: particle.type === 'star' 
              ? `twinkle ${particle.speed}s ease-in-out infinite`
              : `sparkle ${particle.speed + 1}s ease-in-out infinite`,
            animationDelay: `${particle.delay}s`,
          }}
        >
          {particle.type === 'star' ? (
            // Star shape
            <svg
              width={particle.size}
              height={particle.size}
              viewBox="0 0 24 24"
              fill="url(#starGradient)"
              style={{ opacity: particle.opacity }}
            >
              <defs>
                <radialGradient id="starGradient">
                  <stop offset="0%" stopColor="#FBBF24" />
                  <stop offset="100%" stopColor="#F59E0B" stopOpacity="0" />
                </radialGradient>
              </defs>
              <polygon points="12,2 15,8.5 22,9 17,14 18.5,21 12,17 5.5,21 7,14 2,9 9,8.5" />
            </svg>
          ) : (
            // Sparkle/cross shape
            <svg
              width={particle.size * 2}
              height={particle.size * 2}
              viewBox="0 0 24 24"
              fill="none"
              stroke="url(#sparkleGradient)"
              strokeWidth="1"
              style={{ opacity: particle.opacity }}
            >
              <defs>
                <linearGradient id="sparkleGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stopColor="#FBBF24" />
                  <stop offset="100%" stopColor="#F59E0B" />
                </linearGradient>
              </defs>
              <line x1="12" y1="2" x2="12" y2="22" />
              <line x1="2" y1="12" x2="22" y2="12" />
              <line x1="5" y1="5" x2="19" y2="19" />
              <line x1="19" y1="5" x2="5" y2="19" />
            </svg>
          )}
        </div>
      ))}

      {/* Subtle golden overlay at top */}
      <div 
        className="absolute top-0 left-0 right-0 h-32 pointer-events-none"
        style={{
          background: 'linear-gradient(to bottom, rgba(245, 158, 11, 0.05), transparent)',
        }}
      />

      {/* Floating golden dust particles */}
      {[...Array(15)].map((_, i) => (
        <div
          key={`dust-${i}`}
          className="absolute rounded-full heavenly-particle"
          style={{
            left: `${Math.random() * 100}%`,
            bottom: '-10px',
            width: `${Math.random() * 3 + 1}px`,
            height: `${Math.random() * 3 + 1}px`,
            background: `radial-gradient(circle, rgba(251, 191, 36, ${Math.random() * 0.3 + 0.1}), transparent)`,
            animation: `floatUp ${Math.random() * 10 + 15}s linear infinite`,
            animationDelay: `${Math.random() * 15}s`,
            filter: 'blur(0.5px)',
          }}
        />
      ))}
    </div>
  );
};

export default HeavenlyEffects;