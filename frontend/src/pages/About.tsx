import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { BookOpen, Heart, Shield, Users, Star, Lightbulb } from "lucide-react";

const coreValues = [
  { icon: BookOpen, title: "Biblical Teaching", desc: "Rooted in the Word of God" },
  { icon: Heart, title: "Prayer & Worship", desc: "Seeking God's presence always" },
  { icon: Shield, title: "Integrity & Accountability", desc: "Walking in truth and transparency" },
  { icon: Users, title: "Love & Compassion", desc: "Serving with the heart of Christ" },
  { icon: Lightbulb, title: "Community Impact", desc: "Transforming our neighborhoods" },
  { icon: Star, title: "Excellence in Service", desc: "Giving God our very best" },
];

const About = () => {
  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="About Us" subtitle="Learn about our history, mission, and values" />

      {/* History */}
      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0 bg-gradient-to-br from-gold/5 via-transparent to-navy/5 animate-pulse"></div>
        <div className="absolute top-10 left-10 w-32 h-32 bg-gradient-gold rounded-full opacity-10 blur-3xl floating"></div>
        <div className="absolute bottom-10 right-10 w-48 h-48 bg-gradient-navy rounded-full opacity-10 blur-3xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 max-w-4xl relative z-10">
          <div className="glassmorphism-enhanced rounded-2xl p-8 md:p-12 shadow-gold-enhanced slide-in-left">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-foreground mb-6 text-gradient-gold animate-fade-in-up">Church History</h2>
            <div className="w-16 h-1 bg-gradient-gold rounded-full mb-8 shadow-gold-enhanced scale-in"></div>
            <p className="font-gabriola text-muted-foreground leading-relaxed text-lg mb-6 fade-in-up" style={{ animationDelay: '0.2s' }}>
              Founded in 2015, our church began as a small home fellowship with a passion for prayer and
              evangelism. Through God's grace and faithful members, we have grown into a vibrant community
              serving people of all ages in Iganga Municipality and beyond.
            </p>
            <p className="font-gabriola text-muted-foreground leading-relaxed text-lg fade-in-up" style={{ animationDelay: '0.4s' }}>
              Today, we continue to expand our outreach programs, discipleship initiatives, and community
              development projects. Our commitment remains steadfast — to glorify God and make disciples
              of all nations.
            </p>
          </div>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="py-20 bg-muted relative overflow-hidden">
        {/* Animated background */}
        <div className="absolute inset-0 bg-gradient-to-tr from-navy/5 via-transparent to-gold/5 animate-pulse"></div>
        <div className="absolute top-20 right-20 w-40 h-40 bg-gradient-gold rounded-full opacity-8 blur-3xl floating"></div>
        <div className="absolute bottom-20 left-20 w-36 h-36 bg-gradient-navy rounded-full opacity-8 blur-3xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 max-w-5xl relative z-10">
          <div className="grid md:grid-cols-2 gap-12">
            <div className="glassmorphism-enhanced rounded-2xl p-8 shadow-gold-enhanced card-hover-3d slide-in-right">
              <h2 className="font-blackadder text-3xl font-bold text-foreground mb-4 text-gradient-gold animate-fade-in-up">Our Mission</h2>
              <div className="w-16 h-1 bg-gradient-gold rounded-full mb-6 shadow-gold-enhanced scale-in"></div>
              <p className="font-gabriola text-muted-foreground leading-relaxed text-lg fade-in-up" style={{ animationDelay: '0.3s' }}>
                To spread the Gospel of Jesus Christ, disciple believers, and transform communities
                through love, service, and faith.
              </p>
            </div>
            <div className="glassmorphism-enhanced rounded-2xl p-8 shadow-gold-enhanced card-hover-3d slide-in-left" style={{ animationDelay: '0.2s' }}>
              <h2 className="font-blackadder text-3xl font-bold text-foreground mb-4 text-gradient-gold animate-fade-in-up">Our Vision</h2>
              <div className="w-16 h-1 bg-gradient-gold rounded-full mb-6 shadow-gold-enhanced scale-in"></div>
              <p className="font-gabriola text-muted-foreground leading-relaxed text-lg fade-in-up" style={{ animationDelay: '0.5s' }}>
                To build a Christ-centered community that impacts generations spiritually, socially,
                and economically.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Core Values */}
      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0 bg-gradient-to-bl from-gold/5 via-transparent to-navy/5 animate-pulse"></div>
        <div className="absolute top-1/4 left-1/4 w-44 h-44 bg-gradient-gold rounded-full opacity-6 blur-3xl floating"></div>
        <div className="absolute bottom-1/4 right-1/4 w-52 h-52 bg-gradient-navy rounded-full opacity-6 blur-3xl floating-delayed"></div>
        <div className="absolute top-1/2 left-1/3 w-36 h-36 bg-gradient-gold rounded-full opacity-4 blur-3xl floating"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-16">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-foreground mb-4 text-gradient-gold animate-fade-in-up">Core Values</h2>
            <div className="w-20 h-1 bg-gradient-gold mx-auto mb-12 rounded-full shadow-gold-enhanced scale-in"></div>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {coreValues.map((value, i) => (
              <div key={i} className="glassmorphism-enhanced rounded-2xl p-6 border border-white/10 shadow-gold-enhanced card-hover-3d bounce-in" style={{ animationDelay: `${i * 0.1}s` }}>
                <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-4 shadow-gold-intense pulse-gold">
                  <value.icon className="text-primary" size={28} />
                </div>
                <h3 className="font-blackadder text-xl font-bold text-foreground mb-2 text-gradient-gold">{value.title}</h3>
                <p className="font-gabriola text-muted-foreground text-sm leading-relaxed">{value.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default About;
