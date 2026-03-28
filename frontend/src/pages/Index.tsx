import React from 'react';
import { Link } from 'react-router-dom';
import { BookOpen, Heart, Calendar, Clock, MapPin, Phone, Mail, ChevronRight, PlayCircle, Users } from 'lucide-react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import HeroSection from '../components/Hero/HeroSection';
import SectionHeading from '../components/SectionHeading';
import NewsSection from '../components/NewsSection';
import GallerySection from '../components/GallerySection';
import heroWorship from "@/assets/hero-worship.jpg";
import heroCommunity from "@/assets/hero-community.jpg";
import heroChoir from "@/assets/hero-choir.jpg";

const serviceTimes = [
  { day: "1st Service", time: "Sunday 8:00 AM", icon: Clock },
  { day: "2nd Service", time: "Sunday 10:30 AM", icon: Clock },
  { day: "Prayers", time: "Wednesday 5:30 PM", icon: Users },
  { day: "Youth Fellowship", time: "Friday 5:00 PM", icon: Users },
];

const upcomingEvents = [
  { title: "Annual Prayer & Fasting", date: "March 10–15", image: heroWorship },
  { title: "Youth Conference", date: "April 12", image: heroCommunity },
  { title: "Women's Fellowship Breakfast", date: "April 20", image: heroChoir },
  { title: "Community Outreach Day", date: "May 3", image: heroCommunity },
];

const Index = () => {
  return (
    <div className="min-h-screen">
      <Navbar />

      {/* Hero Section with Auth */}
      <HeroSection />

      {/* Welcome Section */}
      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0 bg-gradient-to-br from-gold/3 via-transparent to-navy/3"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-gradient-gold rounded-full opacity-4 blur-2xl floating"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-gradient-navy rounded-full opacity-4 blur-2xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading
            title="Welcome to Our Church Family"
            subtitle="A message from our Pastor"
          />
          <div className="max-w-4xl mx-auto text-center">
            <div className="glassmorphism-enhanced rounded-2xl p-10 md:p-14 shadow-gold-enhanced slide-in-up">
              <p className="font-gabriola text-muted-foreground leading-relaxed text-xl md:text-2xl mb-8 text-shadow-gold">
                We are delighted that you have taken time to visit our website. Our church is a place of
                worship, growth, love, and transformation. Whether you are new to faith, searching for hope,
                or looking for a spiritual home, you are warmly welcome here.
              </p>
              <p className="font-gabriola text-muted-foreground leading-relaxed text-xl md:text-2xl mb-10 text-shadow-gold">
                Our desire is to see lives changed by the power of God's Word and to help every believer
                grow into their God-given purpose. We look forward to worshipping with you.
              </p>
              <p className="font-blackadder text-2xl md:text-3xl text-foreground italic text-gradient-gold animate-fade-in-up">
                — The Pastor, Salem Dominion Ministries
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Ministry Background Photos */}
      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0 bg-gradient-to-tr from-gold/3 via-transparent to-navy/3"></div>
        <div className="absolute top-1/4 left-1/4 w-36 h-36 bg-gradient-gold rounded-full opacity-4 blur-2xl floating"></div>
        <div className="absolute bottom-1/4 right-1/4 w-44 h-44 bg-gradient-navy rounded-full opacity-4 blur-2xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading title="Ministry Highlights" subtitle="A glimpse of worship, outreach, and fellowship" />
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {[
              { title: "Worship Experience", image: heroWorship },
              { title: "Community Outreach", image: heroCommunity },
              { title: "Praise & Choir", image: heroChoir },
            ].map((item, i) => (
              <div key={i} className="glassmorphism-enhanced rounded-2xl overflow-hidden border border-white/8 shadow-gold-enhanced card-hover-3d slide-in-up" style={{ animationDelay: `${i * 0.2}s` }}>
                <img
                  src={item.image}
                  alt={item.title}
                  className="h-72 w-full object-cover group-hover:scale-105 transition-transform duration-700"
                  loading="lazy"
                />
                <div className="p-8 bg-gradient-to-t from-navy/60 to-transparent">
                  <h3 className="font-blackadder text-2xl font-bold text-foreground mb-3 text-gradient-gold">{item.title}</h3>
                  <p className="font-gabriola text-muted-foreground text-base leading-relaxed">Experience the power of worship and community</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="py-20 bg-muted relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-bl from-gold/3 via-transparent to-navy/3"></div>
        <div className="absolute top-20 right-20 w-32 h-32 bg-gradient-gold rounded-full opacity-4 blur-2xl floating"></div>
        <div className="absolute bottom-20 left-20 w-40 h-40 bg-gradient-navy rounded-full opacity-4 blur-2xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="grid md:grid-cols-2 gap-12 max-w-5xl mx-auto">
            <div className="glassmorphism-enhanced rounded-2xl p-10 shadow-gold-enhanced card-hover-3d slide-in-right">
              <div className="w-20 h-20 rounded-full bg-gradient-gold flex items-center justify-center mb-8 shadow-gold-intense pulse-gold">
                <BookOpen className="text-primary" size={32} />
              </div>
              <h3 className="font-blackadder text-2xl md:text-3xl font-bold text-foreground mb-6 text-gradient-gold animate-fade-in-up">Our Mission</h3>
              <p className="font-gabriola text-muted-foreground leading-relaxed text-xl text-shadow-gold animate-fade-in-up" style={{ animationDelay: '0.2s' }}>
                To spread the Gospel of Jesus Christ, disciple believers, and transform communities
                through love, service, and faith.
              </p>
            </div>
            <div className="glassmorphism-enhanced rounded-2xl p-10 shadow-gold-enhanced card-hover-3d slide-in-left" style={{ animationDelay: '0.3s' }}>
              <div className="w-20 h-20 rounded-full bg-gradient-gold flex items-center justify-center mb-8 shadow-gold-intense pulse-gold">
                <Heart className="text-primary" size={32} />
              </div>
              <h3 className="font-blackadder text-2xl md:text-3xl font-bold text-foreground mb-6 text-gradient-gold animate-fade-in-up">Our Vision</h3>
              <p className="font-gabriola text-muted-foreground leading-relaxed text-xl text-shadow-gold animate-fade-in-up" style={{ animationDelay: '0.4s' }}>
                To build a Christ-centered community that impacts generations spiritually, socially,
                and economically.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Service Times */}
      <section className="py-20 bg-gradient-navy relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-br from-gold/10 via-transparent to-navy/20 animate-pulse"></div>
        <div className="absolute top-1/4 left-1/4 w-36 h-36 bg-gradient-gold rounded-full opacity-15 blur-3xl floating"></div>
        <div className="absolute bottom-1/4 right-1/4 w-44 h-44 bg-gradient-navy rounded-full opacity-15 blur-3xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading title="Service Times" subtitle="Join us for worship" light />
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            {serviceTimes.map((service, i) => (
              <div key={i} className="glassmorphism-enhanced rounded-2xl p-6 text-center border border-white/15 shadow-gold-enhanced card-hover-3d slide-in-up" style={{ animationDelay: `${i * 0.15}s` }}>
                <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-4 shadow-gold-intense pulse-gold">
                  <service.icon className="text-primary" size={32} />
                </div>
                <h4 className="font-blackadder text-xl font-bold text-card mb-2">{service.day}</h4>
                <p className="font-gabriola text-gold text-lg font-medium text-shadow-gold">{service.time}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Upcoming Events */}
      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-tr from-gold/5 via-transparent to-navy/5 animate-pulse"></div>
        <div className="absolute top-20 right-20 w-40 h-40 bg-gradient-gold rounded-full opacity-8 blur-3xl floating"></div>
        <div className="absolute bottom-20 left-20 w-48 h-48 bg-gradient-navy rounded-full opacity-8 blur-3xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading title="Upcoming Events" subtitle="Stay connected with what's happening" />
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            {upcomingEvents.map((event, i) => (
              <div key={i} className="glassmorphism-enhanced rounded-2xl p-6 border border-white/10 shadow-gold-enhanced card-hover-3d slide-in-up" style={{ animationDelay: `${i * 0.1}s` }}>
                <img src={event.image} alt={event.title} className="w-full h-36 object-cover rounded-xl mb-4" loading="lazy" />
                <h4 className="font-blackadder text-lg font-bold text-foreground mb-2 text-gradient-gold">{event.title}</h4>
                <p className="font-gabriola text-secondary font-medium flex items-center gap-2 text-shadow-gold">
                  <Calendar size={18} />
                  {event.date}
                </p>
              </div>
            ))}
          </div>
          <div className="text-center mt-10">
            <Link to="/events" className="inline-flex items-center px-8 py-4 bg-gradient-gold text-primary font-bold rounded-full shadow-gold-enhanced hover:shadow-gold-intense hover:scale-105 transition-all duration-300 button-glow font-algerian">
              View All Events
            </Link>
          </div>
        </div>
      </section>

      {/* Mobile App Download Section */}
      <section className="py-20 bg-gradient-to-br from-gold/10 via-background to-navy/10 relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-tr from-gold/5 via-transparent to-navy/5"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-gradient-gold rounded-full opacity-4 blur-2xl floating"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-gradient-navy rounded-full opacity-4 blur-2xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading title="Download Our App" subtitle="Stay connected with our ministry on the go" />
          <div className="max-w-4xl mx-auto">
            <div className="glassmorphism-enhanced rounded-3xl p-8 md:p-12 shadow-gold-enhanced">
              <div className="grid md:grid-cols-2 gap-8 items-center">
                <div className="text-center md:text-left">
                  <h3 className="font-blackadder text-2xl md:text-3xl font-bold text-foreground mb-4 text-gradient-gold">
                    Salem Dominion Ministries Mobile
                  </h3>
                  <p className="font-gabriola text-muted-foreground text-lg leading-relaxed mb-6">
                    Access sermons, events, prayers, and community updates anytime, anywhere. 
                    Join our digital fellowship and grow in faith daily.
                  </p>
                  <div className="flex flex-col sm:flex-row gap-4 justify-center md:justify-start mb-8">
                    <button 
                      onClick={() => window.open('#', '_blank')}
                      className="inline-flex items-center justify-center px-6 py-3 bg-black text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors"
                    >
                      <svg className="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                      </svg>
                      Download for iOS
                    </button>
                    <button 
                      onClick={() => window.open('#', '_blank')}
                      className="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors"
                    >
                      <svg className="w-6 h-6 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 20.5v-17c0-.83.67-1.5 1.5-1.5h3c.28 0 .5.22.5.5s-.22.5-.5.5h-3c-.28 0-.5.22-.5.5v17c0 .28.22.5.5.5h3c.28 0 .5-.22.5-.5s-.22-.5-.5-.5h-3zm14.5-9.5l-4-4v3c0 .55-.45 1-1 1s-1-.45-1-1v-3l-4 4 4 4v-3c0 .55.45 1 1 1s1-.45 1-1v3l4-4z"/>
                      </svg>
                      Download for Android
                    </button>
                  </div>
                  <div className="flex items-center justify-center md:justify-start gap-4 text-sm text-muted-foreground">
                    <div className="flex items-center gap-2">
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm3.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L7.586 10 5.293 7.707a1 1 0 010-1.414z" clipRule="evenodd" />
                      </svg>
                      <span>4.8★</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fillRule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100 4H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2v.01a1 1 0 002 0V9a1 1 0 10-2 0z" clipRule="evenodd" />
                      </svg>
                      <span>50K+ Downloads</span>
                    </div>
                  </div>
                </div>
                <div className="flex justify-center">
                  <div className="relative">
                    <div className="w-48 h-48 md:w-64 md:h-64 bg-gradient-to-br from-gold/20 to-navy/20 rounded-3xl flex items-center justify-center shadow-gold-enhanced animate-float-slow">
                      <div className="text-6xl md:text-8xl animate-pulse-slow">
                        📱</div>
                    </div>
                    <div className="absolute -top-4 -right-4 w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center shadow-gold-intense animate-bounce-in">
                      <span className="text-2xl font-bold text-primary">✨</span>
                    </div>
                  </div>
                </div>
              </div>
              
              {/* App Features */}
              <div className="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="text-center p-6 rounded-xl border border-gold/20 bg-gold/5">
                  <div className="w-12 h-12 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <span className="text-2xl">🙏</span>
                  </div>
                  <h4 className="font-semibold text-foreground mb-2">Daily Prayers</h4>
                  <p className="text-sm text-muted-foreground">Receive daily prayer notifications and devotionals</p>
                </div>
                <div className="text-center p-6 rounded-xl border border-gold/20 bg-gold/5">
                  <div className="w-12 h-12 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <span className="text-2xl">📺</span>
                  </div>
                  <h4 className="font-semibold text-foreground mb-2">Live Streaming</h4>
                  <p className="text-sm text-muted-foreground">Watch services live and access sermon archives</p>
                </div>
                <div className="text-center p-6 rounded-xl border border-gold/20 bg-gold/5">
                  <div className="w-12 h-12 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <span className="text-2xl">👥</span>
                  </div>
                  <h4 className="font-semibold text-foreground mb-2">Community</h4>
                  <p className="text-sm text-muted-foreground">Connect with fellow believers and share testimonies</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-muted relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-bl from-gold/5 via-transparent to-navy/5 animate-pulse"></div>
        <div className="absolute top-1/4 left-1/4 w-44 h-44 bg-gradient-gold rounded-full opacity-6 blur-3xl floating"></div>
        <div className="absolute bottom-1/4 right-1/4 w-52 h-52 bg-gradient-navy rounded-full opacity-6 blur-3xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <Link to="/contact" className="group glassmorphism-enhanced rounded-2xl p-8 text-center shadow-gold-enhanced card-hover-3d border border-white/10 slide-in-up">
              <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-gold-intense pulse-gold">
                <span className="text-3xl">👉</span>
              </div>
              <h3 className="font-blackadder text-xl font-bold text-foreground mb-2 text-gradient-gold">Plan a Visit</h3>
              <p className="font-gabriola text-muted-foreground text-sm">Let us know you're coming!</p>
            </Link>
            <Link to="/sermons" className="group glassmorphism-enhanced rounded-2xl p-8 text-center shadow-gold-enhanced card-hover-3d border border-white/10 slide-in-up" style={{ animationDelay: '0.2s' }}>
              <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-gold-intense pulse-gold">
                <PlayCircle className="text-primary" size={32} />
              </div>
              <h3 className="font-blackadder text-xl font-bold text-foreground mb-2 text-gradient-gold">Watch Online</h3>
              <p className="font-gabriola text-muted-foreground text-sm">Join our live services</p>
            </Link>
            <Link to="/donate" className="group glassmorphism-enhanced rounded-2xl p-8 text-center shadow-gold-enhanced card-hover-3d border border-white/10 slide-in-up" style={{ animationDelay: '0.4s' }}>
              <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-gold-intense pulse-gold">
                <Heart className="text-primary" size={32} />
              </div>
              <h3 className="font-blackadder text-xl font-bold text-foreground mb-2 text-gradient-gold">Give Online</h3>
              <p className="font-gabriola text-muted-foreground text-sm">Support the ministry</p>
            </Link>
          </div>
        </div>
      </section>

      {/* News Section */}
      <section className="py-20 bg-gradient-to-br from-gold/5 via-background to-navy/5 relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-tr from-gold/3 via-transparent to-navy/3"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-gradient-gold rounded-full opacity-4 blur-2xl floating"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-gradient-navy rounded-full opacity-4 blur-2xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading title="Latest News & Updates" subtitle="Stay informed with church announcements and events" />
          <NewsSection showBreaking={true} limit={6} />
        </div>
      </section>

      {/* Gallery Section */}
      <section className="py-20 bg-gradient-to-br from-navy/5 via-background to-gold/5 relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-bl from-navy/3 via-transparent to-gold/3"></div>
        <div className="absolute top-20 right-20 w-36 h-36 bg-gradient-gold rounded-full opacity-4 blur-2xl floating"></div>
        <div className="absolute bottom-20 left-20 w-44 h-44 bg-gradient-navy rounded-full opacity-4 blur-2xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <SectionHeading title="Church Gallery" subtitle="Experience our community through images and videos" />
          <GallerySection showFilters={true} limit={8} />
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Index;
