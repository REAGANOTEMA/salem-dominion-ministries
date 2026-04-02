import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight, Church, Users, Calendar, Heart, BookOpen } from 'lucide-react';
import AuthModal from '@/components/Auth/AuthModal';
import DonationModal from '@/components/Donation/DonationModal';
import { Button } from '@/components/ui/button';
import { HeroAngels } from '@/components/HeroAngels';

const HeroSection: React.FC = () => {
  const [isAuthModalOpen, setIsAuthModalOpen] = useState(false);
  const [isDonationModalOpen, setIsDonationModalOpen] = useState(false);
  const [user, setUser] = useState(null);

  const handleAuthSuccess = (userData: { email: string; name?: string }) => {
    setUser(userData);
  };

  return (
    <>
      <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        {/* Heavenly Angels Decoration */}
        <HeroAngels show={true} />
        
        {/* Background */}
        <div className="absolute inset-0 bg-gradient-to-br from-navy via-navy/90 to-navy/80">
          <div className="absolute inset-0 bg-[url('/salem-dominion-ministries/assets/hero-worship-CWyaH0tr.jpg')] bg-cover bg-center opacity-30"></div>
        </div>

        {/* Content */}
        <div className="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
          <div className="space-y-8">
            {/* Church Icon */}
            <div className="flex justify-center">
              <div className="w-24 h-24 bg-gradient-gold rounded-full flex items-center justify-center shadow-gold-enhanced">
                <Church className="w-12 h-12 text-white" />
              </div>
            </div>

            {/* Main Title */}
            <div className="space-y-4">
              <h1 className="text-5xl sm:text-6xl lg:text-7xl font-blackadder font-bold text-white leading-tight">
                <span className="block text-gradient-gold">Salem Dominion</span>
                <span className="block">Ministries</span>
              </h1>
              
              <p className="text-xl sm:text-2xl text-gold/90 font-gabriola max-w-3xl mx-auto leading-relaxed">
                Welcome to our spiritual home where faith flourishes, 
                communities grow, and lives are transformed through the love of Christ.
              </p>
            </div>

            {/* Call to Action Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
              <Button
                onClick={() => setIsAuthModalOpen(true)}
                size="lg"
                className="bg-gradient-gold text-primary hover:opacity-90 px-8 py-4 text-lg font-semibold shadow-gold-enhanced hover:scale-105 transition-all"
              >
                <Users className="w-5 h-5 mr-2" />
                Get Started
                <ArrowRight className="w-5 h-5 ml-2" />
              </Button>
              
              <Button
                asChild
                size="lg"
                variant="outline"
                className="border-gold text-gold hover:bg-gold hover:text-primary px-8 py-4 text-lg font-semibold transition-all"
              >
                <Link to="/about">
                  <BookOpen className="w-5 h-5 mr-2" />
                  Learn More
                </Link>
              </Button>
            </div>

            {/* Quick Stats */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-16 max-w-4xl mx-auto">
              <div className="glassmorphism-enhanced rounded-2xl p-6 text-center group hover:scale-105 transition-transform">
                <div className="w-12 h-12 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gold/30 transition">
                  <Users className="w-6 h-6 text-gold" />
                </div>
                <h3 className="text-2xl font-bold text-white mb-1">500+</h3>
                <p className="text-gold/80">Active Members</p>
              </div>
              
              <div className="glassmorphism-enhanced rounded-2xl p-6 text-center group hover:scale-105 transition-transform">
                <div className="w-12 h-12 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gold/30 transition">
                  <Calendar className="w-6 h-6 text-gold" />
                </div>
                <h3 className="text-2xl font-bold text-white mb-1">Weekly</h3>
                <p className="text-gold/80">Services & Events</p>
              </div>
              
              <div className="glassmorphism-enhanced rounded-2xl p-6 text-center group hover:scale-105 transition-transform">
                <div className="w-12 h-12 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-gold/30 transition">
                  <Heart className="w-6 h-6 text-gold" />
                </div>
                <h3 className="text-2xl font-bold text-white mb-1">15+</h3>
                <p className="text-gold/80">Years of Service</p>
              </div>
            </div>
          </div>
        </div>

        {/* Scroll Indicator */}
        <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white/60 animate-bounce">
          <div className="flex flex-col items-center">
            <span className="text-sm mb-2">Scroll to explore</span>
            <ArrowRight className="w-5 h-5 rotate-90" />
          </div>
        </div>
      </section>

      {/* Service Times Section */}
      <section className="py-20 bg-gradient-to-b from-navy to-navy/90">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-4xl font-blackadder font-bold text-white mb-4">
              <span className="text-gradient-gold">Service Times</span>
            </h2>
            <p className="text-gold/90 text-lg font-gabriola">
              Join us for worship, fellowship, and spiritual growth
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {/* 1st Service */}
            <div className="glassmorphism-enhanced rounded-2xl p-6 text-center hover:scale-105 transition-transform border border-gold/20">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <Church className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2 font-playfair">1st Service</h3>
              <p className="text-gold font-semibold mb-1">Sunday</p>
              <p className="text-2xl font-bold text-white mb-2">8:00 AM</p>
              <p className="text-gold/80 text-sm">Morning Worship & Praise</p>
            </div>

            {/* 2nd Service */}
            <div className="glassmorphism-enhanced rounded-2xl p-6 text-center hover:scale-105 transition-transform border border-gold/20">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <Users className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2 font-playfair">2nd Service</h3>
              <p className="text-gold font-semibold mb-1">Sunday</p>
              <p className="text-2xl font-bold text-white mb-2">10:30 AM</p>
              <p className="text-gold/80 text-sm">Main Worship Service</p>
            </div>

            {/* Prayers */}
            <div className="glassmorphism-enhanced rounded-2xl p-6 text-center hover:scale-105 transition-transform border border-gold/20">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <Heart className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2 font-playfair">Prayers</h3>
              <p className="text-gold font-semibold mb-1">Wednesday</p>
              <p className="text-2xl font-bold text-white mb-2">5:30 PM</p>
              <p className="text-gold/80 text-sm">Mid-week Prayer Meeting</p>
            </div>

            {/* Youth Fellowship */}
            <div className="glassmorphism-enhanced rounded-2xl p-6 text-center hover:scale-105 transition-transform border border-gold/20">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <Users className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2 font-playfair">Youth Fellowship</h3>
              <p className="text-gold font-semibold mb-1">Friday</p>
              <p className="text-2xl font-bold text-white mb-2">5:00 PM</p>
              <p className="text-gold/80 text-sm">Youth Ministry Gathering</p>
            </div>
          </div>
        </div>
      </section>

      {/* Modals */}
      <AuthModal
        isOpen={isAuthModalOpen}
        onClose={() => setIsAuthModalOpen(false)}
        onAuthSuccess={handleAuthSuccess}
      />
      
      <DonationModal
        isOpen={isDonationModalOpen}
        onClose={() => setIsDonationModalOpen(false)}
      />
    </>
  );
};

export default HeroSection;
