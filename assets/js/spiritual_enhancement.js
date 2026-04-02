// Spiritual Enhancement System for Salem Dominion Ministries
// Makes the website more spiritual, divine, and real

class SpiritualEnhancement {
    constructor() {
        this.init();
    }
    
    init() {
        this.addSpiritualElements();
        this.setupDivineInteractions();
        this.addBibleVerses();
        this.setupPrayerReminders();
        this.addHeavenlyEffects();
        this.setupSpiritualGuidance();
    }
    
    addSpiritualElements() {
        // Add floating scripture verses
        this.addFloatingScriptures();
        
        // Add divine light effects
        this.addDivineLight();
        
        // Add angelic presence indicators
        this.addAngelicPresence();
        
        // Add prayer counters
        this.addPrayerCounters();
    }
    
    addFloatingScriptures() {
        const verses = [
            { text: "The Lord is my shepherd; I shall not want.", reference: "Psalm 23:1" },
            { text: "For God so loved the world that He gave His only begotten Son.", reference: "John 3:16" },
            { text: "I can do all things through Christ who strengthens me.", reference: "Philippians 4:13" },
            { text: "The Lord your God is with you wherever you go.", reference: "Joshua 1:9" },
            { text: "Be still, and know that I am God.", reference: "Psalm 46:10" },
            { text: "The Lord will fight for you; you need only to be still.", reference: "Exodus 14:14" },
            { text: "Trust in the Lord with all your heart.", reference: "Proverbs 3:5" },
            { text: "The Lord is near to all who call upon Him.", reference: "Psalm 145:18" },
            { text: "God is our refuge and strength.", reference: "Psalm 46:1" },
            { text: "The Lord bless you and keep you.", reference: "Numbers 6:24" }
        ];
        
        // Create floating scripture elements
        verses.forEach((verse, index) => {
            setTimeout(() => {
                this.createFloatingScripture(verse, index);
            }, index * 5000);
        });
        
        // Continue adding verses periodically
        setInterval(() => {
            const randomVerse = verses[Math.floor(Math.random() * verses.length)];
            this.createFloatingScripture(randomVerse, Math.floor(Math.random() * 1000));
        }, 30000);
    }
    
    createFloatingScripture(verse, index) {
        const element = document.createElement('div');
        element.className = 'floating-scripture';
        element.innerHTML = `
            <div class="scripture-content">
                <div class="scripture-text">"${verse.text}"</div>
                <div class="scripture-reference">- ${verse.reference}</div>
                <button class="scripture-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Add styles
        element.style.cssText = `
            position: fixed;
            top: ${Math.random() * 60 + 10}%;
            left: ${Math.random() * 80 + 10}%;
            z-index: 1000;
            animation: floatScripture 15s ease-in-out infinite;
            pointer-events: auto;
        `;
        
        document.body.appendChild(element);
        
        // Auto-remove after 20 seconds
        setTimeout(() => {
            if (element.parentNode) {
                element.remove();
            }
        }, 20000);
        
        // Add animation keyframes if not already added
        if (!document.querySelector('#scripture-keyframes')) {
            const style = document.createElement('style');
            style.id = 'scripture-keyframes';
            style.textContent = `
                @keyframes floatScripture {
                    0%, 100% { 
                        transform: translateY(0px) translateX(0px) scale(1); 
                        opacity: 0.8; 
                    }
                    25% { 
                        transform: translateY(-20px) translateX(10px) scale(1.05); 
                        opacity: 1; 
                    }
                    50% { 
                        transform: translateY(-10px) translateX(-10px) scale(1); 
                        opacity: 0.9; 
                    }
                    75% { 
                        transform: translateY(-30px) translateX(5px) scale(1.02); 
                        opacity: 1; 
                    }
                }
                
                .floating-scripture {
                    background: linear-gradient(135deg, rgba(255, 215, 0, 0.9), rgba(255, 165, 0, 0.9));
                    color: #1a1a2e;
                    padding: 1.5rem;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
                    max-width: 300px;
                    font-family: 'Playfair Display', serif;
                    border: 2px solid rgba(255, 255, 255, 0.3);
                }
                
                .scripture-text {
                    font-size: 1rem;
                    font-weight: 600;
                    margin-bottom: 0.5rem;
                    line-height: 1.4;
                }
                
                .scripture-reference {
                    font-size: 0.9rem;
                    font-style: italic;
                    opacity: 0.8;
                }
                
                .scripture-close {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: rgba(255, 255, 255, 0.3);
                    border: none;
                    width: 25px;
                    height: 25px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }
                
                .scripture-close:hover {
                    background: rgba(255, 255, 255, 0.6);
                    transform: scale(1.1);
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    addDivineLight() {
        // Add divine light rays to important elements
        const importantElements = document.querySelectorAll('.hero-section, .church-logo, .section-title');
        
        importantElements.forEach(element => {
            const lightRay = document.createElement('div');
            lightRay.className = 'divine-light-ray';
            lightRay.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(45deg, 
                    transparent 30%, 
                    rgba(255, 215, 0, 0.1) 50%, 
                    transparent 70%);
                pointer-events: none;
                animation: divineShimmer 4s ease-in-out infinite;
            `;
            
            element.style.position = 'relative';
            element.appendChild(lightRay);
        });
        
        // Add animation keyframes
        if (!document.querySelector('#divine-light-keyframes')) {
            const style = document.createElement('style');
            style.id = 'divine-light-keyframes';
            style.textContent = `
                @keyframes divineShimmer {
                    0%, 100% { opacity: 0.3; transform: translateX(-100%); }
                    50% { opacity: 0.6; transform: translateX(100%); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    addAngelicPresence() {
        // Add subtle angelic presence indicators
        const angelicSymbols = ['🕊️', '✨', '👼', '💫', '🌟'];
        
        setInterval(() => {
            const symbol = angelicSymbols[Math.floor(Math.random() * angelicSymbols.length)];
            this.createAngelicPresence(symbol);
        }, 15000);
    }
    
    createAngelicPresence(symbol) {
        const presence = document.createElement('div');
        presence.className = 'angelic-presence';
        presence.textContent = symbol;
        presence.style.cssText = `
            position: fixed;
            top: ${Math.random() * 80 + 10}%;
            left: ${Math.random() * 90 + 5}%;
            font-size: ${Math.random() * 1.5 + 1}rem;
            opacity: 0;
            animation: angelicAppear 3s ease-in-out;
            pointer-events: none;
            z-index: 999;
        `;
        
        document.body.appendChild(presence);
        
        setTimeout(() => {
            presence.remove();
        }, 3000);
        
        // Add animation keyframes
        if (!document.querySelector('#angelic-keyframes')) {
            const style = document.createElement('style');
            style.id = 'angelic-keyframes';
            style.textContent = `
                @keyframes angelicAppear {
                    0% { opacity: 0; transform: scale(0) rotate(0deg); }
                    50% { opacity: 0.6; transform: scale(1) rotate(180deg); }
                    100% { opacity: 0; transform: scale(0.5) rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    addPrayerCounters() {
        // Add prayer counters to show community engagement
        const prayerCounters = [
            { id: 'prayers-today', label: 'Prayers Today', count: Math.floor(Math.random() * 50) + 100 },
            { id: 'blessings-received', label: 'Blessings Received', count: Math.floor(Math.random() * 30) + 75 },
            { id: 'souls-touched', label: 'Souls Touched', count: Math.floor(Math.random() * 20) + 200 }
        ];
        
        prayerCounters.forEach(counter => {
            this.createPrayerCounter(counter);
        });
    }
    
    createPrayerCounter(counter) {
        const element = document.createElement('div');
        element.className = 'prayer-counter';
        element.innerHTML = `
            <div class="counter-icon">
                <i class="fas fa-praying-hands"></i>
            </div>
            <div class="counter-content">
                <div class="counter-number">${counter.count}</div>
                <div class="counter-label">${counter.label}</div>
            </div>
        `;
        
        element.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.9), rgba(255, 165, 0, 0.9));
            color: #1a1a2e;
            padding: 1rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(255, 215, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 998;
            animation: counterPulse 3s ease-in-out infinite;
        `;
        
        document.body.appendChild(element);
        
        // Add animation keyframes
        if (!document.querySelector('#counter-keyframes')) {
            const style = document.createElement('style');
            style.id = 'counter-keyframes';
            style.textContent = `
                @keyframes counterPulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                }
                
                .prayer-counter {
                    font-family: 'Montserrat', sans-serif;
                }
                
                .counter-icon {
                    font-size: 1.5rem;
                }
                
                .counter-number {
                    font-size: 1.2rem;
                    font-weight: 700;
                }
                
                .counter-label {
                    font-size: 0.8rem;
                    font-weight: 600;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    setupDivineInteractions() {
        // Add divine hover effects to buttons and links
        const interactiveElements = document.querySelectorAll('.btn, .nav-link, .card');
        
        interactiveElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                this.addDivineGlow(element);
            });
            
            element.addEventListener('mouseleave', () => {
                this.removeDivineGlow(element);
            });
        });
    }
    
    addDivineGlow(element) {
        element.style.boxShadow = '0 0 20px rgba(255, 215, 0, 0.5)';
        element.style.transition = 'all 0.3s ease';
    }
    
    removeDivineGlow(element) {
        element.style.boxShadow = '';
    }
    
    addBibleVerses() {
        // Add daily verse to footer
        const dailyVerses = [
            "Today's verse: The Lord is my light and my salvation. - Psalm 27:1",
            "Today's verse: The Lord will guide you continually. - Isaiah 58:11",
            "Today's verse: Trust in the Lord forever. - Psalm 125:1",
            "Today's verse: The Lord is merciful and gracious. - Psalm 145:8",
            "Today's verse: God is love. - 1 John 4:8"
        ];
        
        const footer = document.querySelector('.footer-bottom');
        if (footer) {
            const verseElement = document.createElement('div');
            verseElement.className = 'daily-verse';
            verseElement.textContent = dailyVerses[new Date().getDate() % dailyVerses.length];
            verseElement.style.cssText = `
                text-align: center;
                color: #FFD700;
                font-style: italic;
                margin: 1rem 0;
                font-family: 'Playfair Display', serif;
            `;
            
            footer.appendChild(verseElement);
        }
    }
    
    setupPrayerReminders() {
        // Set up prayer reminders at specific times
        const prayerTimes = [6, 12, 18, 21]; // 6AM, 12PM, 6PM, 9PM
        
        const currentHour = new Date().getHours();
        const nextPrayerTime = prayerTimes.find(time => time > currentHour) || prayerTimes[0];
        
        if (nextPrayerTime) {
            this.schedulePrayerReminder(nextPrayerTime);
        }
    }
    
    schedulePrayerReminder(hour) {
        const now = new Date();
        const reminderTime = new Date();
        reminderTime.setHours(hour, 0, 0, 0);
        
        if (reminderTime <= now) {
            reminderTime.setDate(reminderTime.getDate() + 1);
        }
        
        const timeUntilReminder = reminderTime - now;
        
        setTimeout(() => {
            this.showPrayerReminder(hour);
            // Schedule next day's reminder
            this.schedulePrayerReminder(hour);
        }, timeUntilReminder);
    }
    
    showPrayerReminder(hour) {
        const reminder = document.createElement('div');
        reminder.className = 'prayer-reminder';
        reminder.innerHTML = `
            <div class="reminder-content">
                <div class="reminder-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="reminder-text">
                    <h4>Prayer Time</h4>
                    <p>It's time for prayer. Join us in communing with God.</p>
                </div>
                <button class="reminder-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        reminder.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a1a2e;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
            z-index: 1001;
            max-width: 300px;
            animation: reminderSlide 0.5s ease-out;
        `;
        
        document.body.appendChild(reminder);
        
        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (reminder.parentNode) {
                reminder.remove();
            }
        }, 10000);
        
        // Add animation keyframes
        if (!document.querySelector('#reminder-keyframes')) {
            const style = document.createElement('style');
            style.id = 'reminder-keyframes';
            style.textContent = `
                @keyframes reminderSlide {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                .prayer-reminder {
                    font-family: 'Montserrat', sans-serif;
                }
                
                .reminder-content {
                    position: relative;
                }
                
                .reminder-icon {
                    font-size: 2rem;
                    margin-bottom: 1rem;
                    text-align: center;
                }
                
                .reminder-text h4 {
                    margin-bottom: 0.5rem;
                    font-weight: 700;
                }
                
                .reminder-text p {
                    margin-bottom: 0;
                    font-size: 0.9rem;
                }
                
                .reminder-close {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: rgba(26, 26, 46, 0.2);
                    border: none;
                    width: 25px;
                    height: 25px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }
                
                .reminder-close:hover {
                    background: rgba(26, 26, 46, 0.4);
                    transform: scale(1.1);
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    addHeavenlyEffects() {
        // Add heavenly particle effects
        this.createHeavenlyParticles();
        
        // Add divine sound effects on scroll
        this.setupDivineSounds();
        
        // Add angelic animations
        this.addAngelicAnimations();
    }
    
    createHeavenlyParticles() {
        const canvas = document.createElement('canvas');
        canvas.id = 'heavenly-particles';
        canvas.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        `;
        
        document.body.appendChild(canvas);
        
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        
        const particles = [];
        const particleCount = 50;
        
        // Create particles
        for (let i = 0; i < particleCount; i++) {
            particles.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                size: Math.random() * 3 + 1,
                speedX: (Math.random() - 0.5) * 0.5,
                speedY: (Math.random() - 0.5) * 0.5,
                opacity: Math.random() * 0.5 + 0.2
            });
        }
        
        // Animation loop
        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach(particle => {
                particle.x += particle.speedX;
                particle.y += particle.speedY;
                
                // Wrap around screen
                if (particle.x < 0) particle.x = canvas.width;
                if (particle.x > canvas.width) particle.x = 0;
                if (particle.y < 0) particle.y = canvas.height;
                if (particle.y > canvas.height) particle.y = 0;
                
                // Draw particle
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 215, 0, ${particle.opacity})`;
                ctx.fill();
            });
            
            requestAnimationFrame(animate);
        }
        
        animate();
        
        // Handle window resize
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    }
    
    setupDivineSounds() {
        // Add subtle sound effects to divine interactions
        let lastSoundTime = 0;
        
        document.addEventListener('click', (e) => {
            const now = Date.now();
            if (now - lastSoundTime > 5000 && Math.random() > 0.9) {
                this.playDivineSound();
                lastSoundTime = now;
            }
        });
    }
    
    playDivineSound() {
        if (window.heavenlyGuidance) {
            window.heavenlyGuidance.playGentleChime();
        }
    }
    
    addAngelicAnimations() {
        // Add subtle animations to create angelic presence
        const angelicElements = document.querySelectorAll('.floating-angel, .hero-angel');
        
        angelicElements.forEach(element => {
            element.style.animation = 'angelicFloat 6s ease-in-out infinite';
        });
        
        // Add animation keyframes
        if (!document.querySelector('#angelic-animation-keyframes')) {
            const style = document.createElement('style');
            style.id = 'angelic-animation-keyframes';
            style.textContent = `
                @keyframes angelicFloat {
                    0%, 100% { 
                        transform: translateY(0px) rotate(0deg) scale(1); 
                        opacity: 0.3; 
                    }
                    25% { 
                        transform: translateY(-20px) rotate(5deg) scale(1.05); 
                        opacity: 0.5; 
                    }
                    50% { 
                        transform: translateY(-30px) rotate(0deg) scale(1.1); 
                        opacity: 0.7; 
                    }
                    75% { 
                        transform: translateY(-20px) rotate(-5deg) scale(1.05); 
                        opacity: 0.5; 
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    setupSpiritualGuidance() {
        // Add spiritual guidance messages
        const guidanceMessages = [
            "God is with you always.",
            "Trust in His divine plan.",
            "You are loved beyond measure.",
            "His grace is sufficient.",
            "Walk by faith, not by sight.",
            "His mercies are new every morning.",
            "You are fearfully and wonderfully made.",
            "God has a purpose for your life.",
            "His love endures forever.",
            "You are His beloved child."
        ];
        
        // Show guidance messages periodically
        setInterval(() => {
            const message = guidanceMessages[Math.floor(Math.random() * guidanceMessages.length)];
            this.showSpiritualGuidance(message);
        }, 45000);
    }
    
    showSpiritualGuidance(message) {
        const guidance = document.createElement('div');
        guidance.className = 'spiritual-guidance';
        guidance.innerHTML = `
            <div class="guidance-content">
                <div class="guidance-icon">
                    <i class="fas fa-dove"></i>
                </div>
                <div class="guidance-text">
                    <p>${message}</p>
                </div>
                <button class="guidance-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        guidance.style.cssText = `
            position: fixed;
            top: 150px;
            right: 20px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 248, 255, 0.95));
            color: #1a1a2e;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            max-width: 300px;
            border: 2px solid rgba(255, 215, 0, 0.3);
            animation: guidanceSlide 0.5s ease-out;
        `;
        
        document.body.appendChild(guidance);
        
        // Auto-remove after 8 seconds
        setTimeout(() => {
            if (guidance.parentNode) {
                guidance.remove();
            }
        }, 8000);
        
        // Add animation keyframes
        if (!document.querySelector('#guidance-keyframes')) {
            const style = document.createElement('style');
            style.id = 'guidance-keyframes';
            style.textContent = `
                @keyframes guidanceSlide {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                .spiritual-guidance {
                    font-family: 'Playfair Display', serif;
                }
                
                .guidance-icon {
                    font-size: 2rem;
                    color: #FFD700;
                    margin-bottom: 1rem;
                    text-align: center;
                }
                
                .guidance-text p {
                    margin-bottom: 0;
                    font-style: italic;
                    text-align: center;
                    line-height: 1.4;
                }
                
                .guidance-close {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    background: rgba(255, 215, 0, 0.2);
                    border: none;
                    width: 25px;
                    height: 25px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                }
                
                .guidance-close:hover {
                    background: rgba(255, 215, 0, 0.4);
                    transform: scale(1.1);
                }
            `;
            document.head.appendChild(style);
        }
    }
}

// Initialize spiritual enhancement when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.spiritualEnhancement = new SpiritualEnhancement();
    console.log('Spiritual Enhancement initialized - Making the website more divine and real');
});

// Export for external use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SpiritualEnhancement;
}
