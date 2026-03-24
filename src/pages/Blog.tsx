import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { BookOpen, Bell } from "lucide-react";
import { useState } from "react";

const devotionals = [
  {
    title: "Walking in God's Promises",
    date: "Feb 23, 2025",
    excerpt: "God's promises are sure and steadfast. As we meditate on His Word, we find strength for each day...",
    content:
      "God does not fail. Even when circumstances are difficult, His promises remain true. Hold onto His Word daily, pray in faith, and walk in obedience. In every season, He is faithful.",
  },
  {
    title: "The Power of Gratitude",
    date: "Feb 16, 2025",
    excerpt: "When we choose gratitude, we position ourselves to receive more of God's blessings...",
    content:
      "Gratitude changes perspective. When we thank God for what we have, we make room for joy and peace. Begin each day with thanksgiving and watch how your heart is renewed.",
  },
  {
    title: "Faith Over Fear",
    date: "Feb 9, 2025",
    excerpt: "In times of uncertainty, God calls us to trust Him completely. Fear has no place where faith abides...",
    content:
      "Fear focuses on problems, but faith focuses on God. Speak God's promises over your life, reject anxious thoughts, and move forward with confidence that God is with you.",
  },
];

const announcements = [
  { title: "New Members Orientation", date: "March 1, 2025", content: "All new members are invited to attend our orientation session after the second service." },
  { title: "Choir Rehearsals Resume", date: "Feb 28, 2025", content: "Choir rehearsals will resume every Saturday at 3:00 PM in the church hall." },
  { title: "Volunteer Registration Open", date: "Feb 25, 2025", content: "We are looking for volunteers for the upcoming community outreach. Sign up at the information desk." },
];

const Blog = () => {
  const [expandedPost, setExpandedPost] = useState<number | null>(null);

  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Blog & News" subtitle="Devotionals, announcements, and updates from Salem Dominion Ministries" />

      {/* Devotionals */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="flex items-center gap-3 mb-8">
            <BookOpen className="text-secondary" size={28} />
            <h2 className="font-heading text-3xl font-bold text-foreground">Devotionals</h2>
          </div>
          <div className="flex flex-col gap-6">
            {devotionals.map((d, i) => (
              <article key={i} className="bg-card rounded-lg p-6 border border-border shadow-sm hover:shadow-md transition-shadow">
                <p className="text-secondary text-sm font-medium mb-2">{d.date}</p>
                <h3 className="font-heading text-xl font-bold text-foreground mb-3">{d.title}</h3>
                <p className="text-muted-foreground leading-relaxed">{d.excerpt}</p>
                {expandedPost === i && <p className="text-muted-foreground leading-relaxed mt-3">{d.content}</p>}
                <button
                  onClick={() => setExpandedPost(expandedPost === i ? null : i)}
                  className="mt-4 text-secondary font-medium hover:underline text-sm"
                >
                  {expandedPost === i ? "Show Less ↑" : "Read More →"}
                </button>
              </article>
            ))}
          </div>
        </div>
      </section>

      {/* Announcements */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="flex items-center gap-3 mb-8">
            <Bell className="text-secondary" size={28} />
            <h2 className="font-heading text-3xl font-bold text-foreground">Announcements</h2>
          </div>
          <div className="flex flex-col gap-6">
            {announcements.map((a, i) => (
              <div key={i} className="bg-card rounded-lg p-6 border border-border shadow-sm">
                <p className="text-secondary text-sm font-medium mb-2">{a.date}</p>
                <h3 className="font-heading text-lg font-bold text-foreground mb-2">{a.title}</h3>
                <p className="text-muted-foreground">{a.content}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Blog;
