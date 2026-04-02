import { useEffect, useState } from 'react';

interface HeroAngel {
  id: number;
  position: 'left' | 'center' | 'right';
  size: 'small' | 'medium' | 'large';
  animationDelay: number;
  opacity: number;
}

interface HeroAngelsProps {
  show?: boolean;
}

export const HeroAngels = ({ show = true }: HeroAngelsProps) => {
  const [angels, setAngels] = useState<HeroAngel[]>([]);

  useEffect(() => {
    if (!show) return;
    
    setAngels([
      {
        id: 1,
        position: 'left',
        size: 'medium',
        animationDelay: 0,
        opacity: 0.15,
      },
      {
        id: 2,
        position: 'center',
        size: 'large',
        animationDelay: 2,
        opacity: 0.2,
      },
      {
        id: 3,
        position: 'right',
        size: 'medium',
        animationDelay: 4,
        opacity: 0.15,
      },
    ]);
  }, [show]);

  if (!show || angels.length === 0) return null;

  return (
    <div className="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
      <style>{`
        @keyframes heroAngelFloat {
          0%, 100% { 
            transform: translateY(0px) translateX(0px) rotate(-3deg);
            opacity: 0.1;
          }
          25% { 
            transform: translateY(-30px) translateX(20px) rotate(2deg);
            opacity: 0.25;
          }
          50% { 
            transform: translateY(-15px) translateX(-10px) rotate(-1deg);
            opacity: 0.15;
          }
          75% { 
            transform: translateY(-45px) translateX(15px) rotate(3deg);
            opacity: 0.3;
          }
        }
        
        @keyframes heroAngelGlow {
          0%, 100% { 
            filter: drop-shadow(0 0 15px rgba(245, 158, 11, 0.4)) 
                    drop-shadow(0 0 30px rgba(251, 191, 36, 0.2))
                    drop-shadow(0 0 45px rgba(245, 158, 11, 0.1));
          }
          50% { 
            filter: drop-shadow(0 0 25px rgba(245, 158, 11, 0.7)) 
                    drop-shadow(0 0 50px rgba(251, 191, 36, 0.4))
                    drop-shadow(0 0 75px rgba(245, 158, 11, 0.2))
                    drop-shadow(0 0 100px rgba(251, 191, 36, 0.1));
          }
        }
        
        .hero-angel-svg {
          animation: heroAngelGlow 5s ease-in-out infinite;
        }
      `}</style>

      {angels.map((angel) => {
        const sizeMap = { small: 80, medium: 120, large: 160 };
        const size = sizeMap[angel.size];
        const positionMap = {
          left: { x: '8%', y: '20%' },
          center: { x: '50%', y: '15%' },
          right: { x: '88%', y: '25%' },
        };
        const pos = positionMap[angel.position];

        return (
          <div
            key={angel.id}
            className="absolute hero-angel-svg"
            style={{
              left: pos.x,
              top: pos.y,
              transform: 'translate(-50%, -50%)',
              width: `${size}px`,
              height: `${size * 1.3}px`,
              opacity: angel.opacity,
              animation: `heroAngelFloat ${8 + angel.id * 2}s ease-in-out infinite`,
              animationDelay: `${angel.animationDelay}s`,
            }}
          >
            {/* Detailed Angel SVG for Hero */}
            <svg
              viewBox="0 0 120 156"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              className="w-full h-full"
            >
              {/* Radiant halo */}
              <circle
                cx="60"
                cy="20"
                r="25"
                fill="none"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="2"
                opacity="0.6"
              />
              <circle
                cx="60"
                cy="20"
                r="18"
                fill="none"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="1"
                opacity="0.4"
              />
              
              {/* Majestic left wing - larger */}
              <path
                d="M15 50 Q-5 20 10 5 Q30 -10 55 25 Q45 45 15 50Z"
                fill="url(#heroWingGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="1.5"
                opacity="0.5"
              />
              <path
                d="M20 45 Q5 18 15 6 Q30 -5 48 18 Q42 35 20 45Z"
                fill="url(#heroWingGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="0.8"
                opacity="0.35"
              />
              <path
                d="M25 40 Q15 20 20 10 Q32 -2 42 15 Q38 28 25 40Z"
                fill="url(#heroWingGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="0.5"
                opacity="0.25"
              />
              
              {/* Majestic right wing - larger */}
              <path
                d="M105 50 Q125 20 110 5 Q90 -10 65 25 Q75 45 105 50Z"
                fill="url(#heroWingGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="1.5"
                opacity="0.5"
              />
              <path
                d="M100 45 Q115 18 105 6 Q90 -5 72 18 Q78 35 100 45Z"
                fill="url(#heroWingGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="0.8"
                opacity="0.35"
              />
              <path
                d="M95 40 Q105 20 100 10 Q88 -2 78 15 Q82 28 95 40Z"
                fill="url(#heroWingGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="0.5"
                opacity="0.25"
              />
              
              {/* Angel body - more defined */}
              <ellipse
                cx="60"
                cy="70"
                rx="15"
                ry="25"
                fill="url(#heroBodyGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="1.2"
                opacity="0.5"
              />
              
              {/* Angel head */}
              <circle
                cx="60"
                cy="38"
                r="12"
                fill="url(#heroBodyGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="1"
                opacity="0.6"
              />
              
              {/* Flowing robe - more elegant */}
              <path
                d="M45 85 Q40 110 35 145 Q60 156 85 145 Q80 110 75 85 Q60 100 45 85Z"
                fill="url(#heroRobeGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="1"
                opacity="0.4"
              />
              
              {/* Inner robe detail */}
              <path
                d="M50 90 Q48 105 45 130 Q60 140 75 130 Q72 105 70 90 Q60 100 50 90Z"
                fill="url(#heroRobeGradient)"
                stroke="url(#heroGoldenGradient)"
                strokeWidth="0.5"
                opacity="0.25"
              />
              
              {/* Trumpet for archangel */}
              <path
                d="M72 55 Q85 45 95 35 Q105 40 100 55 Q95 65 85 60 Q80 58 72 55Z"
                fill="url(#heroGoldenGradient)"
                opacity="0.3"
              />
              
              {/* Stars around angel */}
              <circle cx="30" cy="30" r="2" fill="url(#heroGoldenGradient)" opacity="0.4" />
              <circle cx="90" cy="30" r="2" fill="url(#heroGoldenGradient)" opacity="0.4" />
              <circle cx="60" cy="10" r="1.5" fill="url(#heroGoldenGradient)" opacity="0.5" />
              <circle cx="25" cy="60" r="1" fill="url(#heroGoldenGradient)" opacity="0.3" />
              <circle cx="95" cy="60" r="1" fill="url(#heroGoldenGradient)" opacity="0.3" />
              
              {/* Gradient definitions */}
              <defs>
                <linearGradient id="heroGoldenGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stopColor="#F59E0B" />
                  <stop offset="30%" stopColor="#FBBF24" />
                  <stop offset="60%" stopColor="#F59E0B" />
                  <stop offset="100%" stopColor="#D97706" />
                </linearGradient>
                
                <linearGradient id="heroWingGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stopColor="#F59E0B" stopOpacity="0.15" />
                  <stop offset="40%" stopColor="#FBBF24" stopOpacity="0.25" />
                  <stop offset="70%" stopColor="#F59E0B" stopOpacity="0.15" />
                  <stop offset="100%" stopColor="#D97706" stopOpacity="0.1" />
                </linearGradient>
                
                <linearGradient id="heroBodyGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                  <stop offset="0%" stopColor="#FBBF24" stopOpacity="0.3" />
                  <stop offset="50%" stopColor="#F59E0B" stopOpacity="0.25" />
                  <stop offset="100%" stopColor="#D97706" stopOpacity="0.15" />
                </linearGradient>
                
                <linearGradient id="heroRobeGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                  <stop offset="0%" stopColor="#F59E0B" stopOpacity="0.12" />
                  <stop offset="40%" stopColor="#FBBF24" stopOpacity="0.18" />
                  <stop offset="100%" stopColor="#D97706" stopOpacity="0.08" />
                </linearGradient>
              </defs>
            </svg>
          </div>
        );
      })}
    </div>
  );
};

export default HeroAngels;