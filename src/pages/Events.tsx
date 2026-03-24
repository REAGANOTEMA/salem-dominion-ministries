import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { Calendar, MapPin, Clock } from "lucide-react";
import heroWorship from "@/assets/hero-worship.jpg";
import heroCommunity from "@/assets/hero-community.jpg";
import heroChoir from "@/assets/hero-choir.jpg";

const events = [
  { title: "Annual Prayer & Fasting", date: "March 10–15, 2026", time: "6:00 AM Daily", location: "Main Church", desc: "A week of seeking God's face through prayer and fasting for breakthrough and direction.", image: heroWorship },
  { title: "Youth Conference", date: "April 12, 2026", time: "9:00 AM – 5:00 PM", location: "Church Hall", desc: "An empowering conference for young people featuring worship, teaching, and fellowship.", image: heroCommunity },
  { title: "Women's Fellowship Breakfast", date: "April 20, 2026", time: "8:00 AM", location: "Fellowship Hall", desc: "A special morning of bonding, prayer, and encouragement for all women.", image: heroChoir },
  { title: "Community Outreach Day", date: "May 3, 2026", time: "7:00 AM", location: "Iganga Community", desc: "Serving our community through evangelism, free medical check-ups, and charity.", image: heroCommunity },
  { title: "Annual Conference", date: "June 15–20, 2026", time: "All Day", location: "Main Church", desc: "Our flagship annual conference with guest speakers and powerful ministry.", image: heroWorship },
  { title: "Healing & Deliverance Crusade", date: "August 8–10, 2026", time: "5:00 PM", location: "Open Grounds", desc: "Three nights of powerful ministry, miracles, and salvation.", image: heroChoir },
];

const Events = () => {
  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Events" subtitle="Stay connected with what's happening at Salem Dominion Ministries" />

      <section className="py-20 bg-background">
        <div className="container mx-auto px-4 max-w-5xl">
          <div className="grid gap-6">
            {events.map((e, i) => (
              <div key={i} className="bg-card rounded-lg p-6 md:p-8 border border-border shadow-sm hover:shadow-md transition-shadow">
                <div className="flex flex-col md:flex-row md:items-start gap-4">
                  <img src={e.image} alt={e.title} className="w-full md:w-40 h-28 object-cover rounded-md border border-border" loading="lazy" />
                  <div className="flex-1">
                    <h3 className="font-heading text-xl font-bold text-foreground mb-2 flex items-center gap-2">
                      <Calendar className="text-secondary" size={18} /> {e.title}
                    </h3>
                    <p className="text-muted-foreground mb-3">{e.desc}</p>
                    <div className="flex flex-wrap gap-4 text-sm text-muted-foreground">
                      <span className="flex items-center gap-1"><Calendar size={14} className="text-secondary" /> {e.date}</span>
                      <span className="flex items-center gap-1"><Clock size={14} className="text-secondary" /> {e.time}</span>
                      <span className="flex items-center gap-1"><MapPin size={14} className="text-secondary" /> {e.location}</span>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Events;
