import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { Baby, GraduationCap, Heart, Shield, Music, HandHeart } from "lucide-react";

const ministries = [
  {
    icon: Baby,
    title: "Children's Ministry",
    desc: "A safe and joyful environment where children learn biblical principles through storytelling, music, and interactive lessons.",
  },
  {
    icon: GraduationCap,
    title: "Youth Ministry",
    desc: "Empowering young people to live boldly for Christ through mentorship, fellowship, and leadership training.",
  },
  {
    icon: Heart,
    title: "Women's Ministry",
    desc: "Encouraging women to grow spiritually, emotionally, and socially through prayer meetings, conferences, and community service.",
  },
  {
    icon: Shield,
    title: "Men's Ministry",
    desc: "Equipping men to lead with integrity at home, church, and workplace through fellowship and mentorship programs.",
  },
  {
    icon: Music,
    title: "Worship Team",
    desc: "A dedicated team committed to leading the congregation into God's presence through music and praise.",
  },
  {
    icon: HandHeart,
    title: "Outreach / Evangelism",
    desc: "We actively engage in community evangelism, charity work, and missions to share God's love beyond church walls.",
  },
];

const Ministries = () => {
  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Ministries" subtitle="Serving God and community through various departments" />

      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0 bg-gradient-to-br from-gold/5 via-transparent to-navy/5 animate-pulse"></div>
        <div className="absolute top-20 left-20 w-40 h-40 bg-gradient-gold rounded-full opacity-8 blur-3xl floating"></div>
        <div className="absolute bottom-20 right-20 w-48 h-48 bg-gradient-navy rounded-full opacity-8 blur-3xl floating-delayed"></div>
        <div className="absolute top-1/2 left-1/3 w-36 h-36 bg-gradient-gold rounded-full opacity-6 blur-3xl floating"></div>
        <div className="absolute bottom-1/3 right-1/4 w-44 h-44 bg-gradient-navy rounded-full opacity-6 blur-3xl floating-delayed"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {ministries.map((m, i) => (
              <div key={i} className="glassmorphism-enhanced rounded-2xl p-8 border border-white/10 shadow-gold-enhanced card-hover-3d slide-in-up" style={{ animationDelay: `${i * 0.15}s` }}>
                <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mb-5 shadow-gold-intense pulse-gold">
                  <m.icon className="text-primary" size={28} />
                </div>
                <h3 className="font-blackadder text-xl font-bold text-foreground mb-3 text-gradient-gold">{m.title}</h3>
                <p className="font-gabriola text-muted-foreground leading-relaxed">{m.desc}</p>
                
                {/* Interactive button */}
                <button className="mt-6 w-full py-3 bg-gradient-gold text-navy font-bold rounded-full hover:scale-105 transition-all duration-300 shadow-gold-enhanced hover:shadow-gold-intense button-glow">
                  Learn More
                </button>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Ministries;
