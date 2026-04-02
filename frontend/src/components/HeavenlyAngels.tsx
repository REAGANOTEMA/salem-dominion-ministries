import { useEffect, useState } from 'react';

interface Angel {
  id: number;
  x: number;
  y: number;
  size: number;
  speed: number;
  delay: number;
  opacity: number;
}

interface Blessing {
  id: number;
  x: number;
  y: number;
  text: string;
  speed: number;
  delay: number;
  opacity: number;
}

const BLESSINGS = [
  '🙏 Grace', '✨ Peace', '💫 Joy', '🕊️ Love',
  '🌟 Faith', '⭐ Hope', '🙌 Mercy', '👼 Blessings',
  '🌈 Favor', '⭐ Glory', '🔥 Spirit', '💝 Salvation'
];

export const HeavenlyAngels = () => {
  const [angels, setAngels] = useState<Angel[]>([]);
  const [blessings, setBlessings] = useState<Blessing[]>([]);

  useEffect(() => {
    const angelsData: Angel[] = [
      { id: 1, x: Math.random() * 20 + 5, y: Math.random() * 20 + 10, size: Math.random() * 40 + 60, speed: Math.random() * 20 + 30, delay: Math.random() * 5, opacity: Math.random() * 0.3 + 0.1 },
      { id: 2, x: Math.random() * 20 + 40, y: Math.random() * 20 + 5, size: Math.random() * 30 + 50, speed: Math.random() * 25 + 35, delay: Math.random() * 8 + 2, opacity: Math.random() * 0.25 + 0.15 },
      { id: 3, x: Math.random() * 20 + 70, y: Math.random() * 20 + 15, size: Math.random() * 35 + 55, speed: Math.random() * 20 + 25, delay: Math.random() * 6 + 4, opacity: Math.random() * 0.3 + 0.1 },
    ];
    setAngels(angelsData);

    const blessingsData: Blessing[] = [];
    for (let i = 0; i < 12; i++) {
      blessingsData.push({
        id: i, x: Math.random() * 100, y: -20 - Math.random() * 100,
        text: BLESSINGS[i % BLESSINGS.length], speed: Math.random() * 10 + 15,
        delay: Math.random() * 20, opacity: Math.random() * 0.4 + 0.2,
      });
    }
    setBlessings(blessingsData);
  }, []);

  return (
    <div className="fixed inset-0 pointer-events-none overflow-hidden z-0" aria-hidden="true">
      <style>{`
        @keyframes angelFloat1 { 0%,100%{transform:translate(0,0) rotate(-5deg)} 25%{transform:translate(30px,-20px) rotate(5deg)} 50%{transform:translate(60px,-10px) rotate(-3deg)} 75%{transform:translate(30px,-30px) rotate(3deg)} }
        @keyframes angelFloat2 { 0%,100%{transform:translate(0,0) rotate(3deg)} 25%{transform:translate(-40px,20px) rotate(-5deg)} 50%{transform:translate(-20px,40px) rotate(2deg)} 75%{transform:translate(-50px,20px) rotate(-2deg)} }
        @keyframes angelFloat3 { 0%,100%{transform:translate(0,0) rotate(-3deg)} 25%{transform:translate(20px,30px) rotate(5deg)} 50%{transform:translate(50px,10px) rotate(-4deg)} 75%{transform:translate(30px,40px) rotate(2deg)} }
        @keyframes goldenGlow { 0%,100%{filter:drop-shadow(0 0 10px rgba(245,158,11,0.3)) drop-shadow(0 0 20px rgba(251,191,36,0.2))} 50%{filter:drop-shadow(0 0 20px rgba(245,158,11,0.6)) drop-shadow(0 0 40px rgba(251,191,36,0.4)) drop-shadow(0 0 60px rgba(245,158,11,0.2))} }
        @keyframes blessingFall { 0%{transform:translateY(0) translateX(0) scale(0.8);opacity:0} 10%{opacity:0.6} 90%{opacity:0.6} 100%{transform:translateY(120vh) translateX(50px) scale(1.2);opacity:0} }
        .angel-svg { animation: goldenGlow 4s ease-in-out infinite; }
        .blessing-text { font-family: 'Gabriola', cursive; font-size: 1.5rem; font-weight: bold; background: linear-gradient(135deg, #F59E0B, #FBBF24, #F59E0B); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 8px rgba(245,158,11,0.5)); }
      `}</style>

      {blessings.map((b) => (
        <div key={`blessing-${b.id}`} className="absolute blessing-text pointer-events-none"
          style={{ left: `${b.x}%`, top: `${b.y}px`, opacity: b.opacity,
            animation: `blessingFall ${b.speed}s linear infinite`, animationDelay: `${b.delay}s`,
            fontSize: `${Math.random() * 1 + 1.2}rem` }}>{b.text}</div>
      ))}

      {angels.map((angel) => (
        <div key={angel.id} className="absolute angel-svg"
          style={{ left: `${angel.x}%`, top: `${angel.y}%`, width: `${angel.size}px`, height: `${angel.size * 1.3}px`,
            opacity: angel.opacity, animation: `angelFloat${angel.id} ${angel.speed}s ease-in-out infinite`, animationDelay: `${angel.delay}s` }}>
          <svg viewBox="0 0 140 180" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-full h-full">
            {/* Radiant Halo */}
            <circle cx="70" cy="22" r="20" fill="none" stroke="url(#gold)" strokeWidth="2.5" opacity="0.7"/>
            <circle cx="70" cy="22" r="14" fill="none" stroke="url(#gold)" strokeWidth="1" opacity="0.4"/>
            
            {/* Left Wing - Large */}
            <path d="M20 55 Q-5 25 5 8 Q25 -8 55 30 Q45 50 20 55Z" fill="url(#wingGrad)" stroke="url(#gold)" strokeWidth="1.5" opacity="0.5"/>
            <path d="M25 48 Q8 22 14 10 Q30 -4 48 22 Q42 38 25 48Z" fill="url(#wingGrad)" stroke="url(#gold)" strokeWidth="0.8" opacity="0.35"/>
            <path d="M30 42 Q18 20 22 10 Q34 -2 44 18 Q40 30 30 42Z" fill="url(#wingGrad)" stroke="url(#gold)" strokeWidth="0.5" opacity="0.25"/>
            
            {/* Right Wing - Large */}
            <path d="M120 55 Q145 25 135 8 Q115 -8 85 30 Q95 50 120 55Z" fill="url(#wingGrad)" stroke="url(#gold)" strokeWidth="1.5" opacity="0.5"/>
            <path d="M115 48 Q132 22 126 10 Q110 -4 92 22 Q98 38 115 48Z" fill="url(#wingGrad)" stroke="url(#gold)" strokeWidth="0.8" opacity="0.35"/>
            <path d="M110 42 Q122 20 118 10 Q106 -2 96 18 Q100 30 110 42Z" fill="url(#wingGrad)" stroke="url(#gold)" strokeWidth="0.5" opacity="0.25"/>
            
            {/* Human-like Head */}
            <circle cx="70" cy="42" r="14" fill="url(#skinGrad)" stroke="url(#gold)" strokeWidth="1" opacity="0.7"/>
            {/* Face features */}
            <circle cx="65" cy="40" r="1.5" fill="url(#gold)" opacity="0.5"/>
            <circle cx="75" cy="40" r="1.5" fill="url(#gold)" opacity="0.5"/>
            <path d="M68 46 Q70 48 72 46" fill="none" stroke="url(#gold)" strokeWidth="0.8" opacity="0.4"/>
            {/* Hair */}
            <path d="M56 38 Q58 25 70 24 Q82 25 84 38 Q80 30 70 28 Q60 30 56 38Z" fill="url(#hairGrad)" opacity="0.4"/>
            
            {/* Human-like Body */}
            <ellipse cx="70" cy="78" rx="16" ry="24" fill="url(#bodyGrad)" stroke="url(#gold)" strokeWidth="1.2" opacity="0.5"/>
            {/* Arms */}
            <path d="M54 68 Q40 75 35 90 Q40 95 45 90 Q48 78 56 72" fill="url(#bodyGrad)" stroke="url(#gold)" strokeWidth="0.8" opacity="0.35"/>
            <path d="M86 68 Q100 75 105 90 Q100 95 95 90 Q92 78 84 72" fill="url(#bodyGrad)" stroke="url(#gold)" strokeWidth="0.8" opacity="0.35"/>
            
            {/* Flowing Robe */}
            <path d="M54 95 Q48 115 42 155 Q70 172 98 155 Q92 115 86 95 Q70 110 54 95Z" fill="url(#robeGrad)" stroke="url(#gold)" strokeWidth="1" opacity="0.4"/>
            <path d="M58 100 Q54 115 50 145 Q70 158 90 145 Q86 115 82 100 Q70 112 58 100Z" fill="url(#robeGrad)" stroke="url(#gold)" strokeWidth="0.5" opacity="0.25"/>
            
            {/* Trumpet */}
            <path d="M82 62 Q95 50 105 38 Q115 42 110 58 Q105 68 95 64 Q88 62 82 62Z" fill="url(#gold)" opacity="0.3"/>
            
            {/* Stars */}
            <circle cx="25" cy="30" r="2" fill="url(#gold)" opacity="0.4"/>
            <circle cx="115" cy="30" r="2" fill="url(#gold)" opacity="0.4"/>
            <circle cx="70" cy="8" r="1.5" fill="url(#gold)" opacity="0.5"/>
            
            <defs>
              <linearGradient id="gold" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="#F59E0B"/><stop offset="50%" stopColor="#FBBF24"/><stop offset="100%" stopColor="#F59E0B"/>
              </linearGradient>
              <linearGradient id="wingGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="#F59E0B" stopOpacity="0.15"/><stop offset="50%" stopColor="#FBBF24" stopOpacity="0.25"/><stop offset="100%" stopColor="#F59E0B" stopOpacity="0.1"/>
              </linearGradient>
              <linearGradient id="skinGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stopColor="#FBBF24" stopOpacity="0.3"/><stop offset="100%" stopColor="#F59E0B" stopOpacity="0.2"/>
              </linearGradient>
              <linearGradient id="bodyGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stopColor="#FBBF24" stopOpacity="0.25"/><stop offset="100%" stopColor="#F59E0B" stopOpacity="0.15"/>
              </linearGradient>
              <linearGradient id="robeGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stopColor="#F59E0B" stopOpacity="0.12"/><stop offset="50%" stopColor="#FBBF24" stopOpacity="0.18"/><stop offset="100%" stopColor="#D97706" stopOpacity="0.08"/>
              </linearGradient>
              <linearGradient id="hairGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" stopColor="#D97706" stopOpacity="0.3"/><stop offset="100%" stopColor="#B45309" stopOpacity="0.2"/>
              </linearGradient>
            </defs>
          </svg>
        </div>
      ))}
    </div>
  );
};

export default HeavenlyAngels;