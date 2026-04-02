import { useEffect, useState } from 'react';

export const WelcomeVoice = () => {
  const [isSpeaking, setIsSpeaking] = useState(false);
  const [hasSpoken, setHasSpoken] = useState(false);

  useEffect(() => {
    if (!('speechSynthesis' in window)) return;
    if (hasSpoken || sessionStorage.getItem('welcomeSpoken')) return;

    const timer = setTimeout(() => {
      speakWelcome();
    }, 2000);

    return () => clearTimeout(timer);
  }, [hasSpoken]);

  const speakWelcome = () => {
    window.speechSynthesis.cancel();

    const message = "Welcome to Salem Dominion Ministries! May God's blessings pour upon you today. You are loved, you are cherished, and you are part of our heavenly family.";

    const utterance = new SpeechSynthesisUtterance(message);
    utterance.rate = 0.85;
    utterance.pitch = 1.0;
    utterance.volume = 0.6;

    const voices = window.speechSynthesis.getVoices();
    const preferredVoice = voices.find(voice => 
      voice.name.includes('Samantha') ||
      voice.name.includes('Google US English') ||
      voice.lang === 'en-US'
    );
    
    if (preferredVoice) utterance.voice = preferredVoice;

    utterance.onstart = () => {
      setIsSpeaking(true);
      setHasSpoken(true);
      sessionStorage.setItem('welcomeSpoken', 'true');
    };

    utterance.onend = () => setIsSpeaking(false);
    utterance.onerror = () => setIsSpeaking(false);

    window.speechSynthesis.speak(utterance);
  };

  const handleReplay = (e: React.MouseEvent) => {
    e.stopPropagation();
    speakWelcome();
  };

  return (
    <>
      <button
        onClick={handleReplay}
        className="fixed bottom-20 right-4 z-50 p-3 rounded-full bg-gradient-to-r from-amber-500/20 to-amber-600/20 border border-amber-500/30 backdrop-blur-sm hover:from-amber-500/30 hover:to-amber-600/30 transition-all duration-300 group"
        title="Play welcome message"
        aria-label="Play welcome message"
      >
        {isSpeaking ? (
          <div className="flex items-center gap-2">
            <div className="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
            <span className="text-xs text-amber-600 font-medium">Playing...</span>
          </div>
        ) : (
          <div className="flex items-center gap-2">
            <svg className="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
              <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
            </svg>
            <span className="text-xs text-amber-600 font-medium group-hover:text-amber-700">Welcome</span>
          </div>
        )}
      </button>

      {isSpeaking && (
        <div className="fixed inset-0 pointer-events-none z-40 flex items-center justify-center">
          <div className="text-center animate-fade-in">
            <div className="text-4xl mb-4">🕊️</div>
            <p className="text-amber-600 font-gabriola text-xl italic animate-pulse">
              "Welcome to God's family..."
            </p>
          </div>
        </div>
      )}
    </>
  );
};

export default WelcomeVoice;