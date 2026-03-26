import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { Play, Video, Headphones } from "lucide-react";

// Sermons data (combined, ready to use)
const sermons = [
  {
    title: "Walking in Faith",
    speaker: "Pastor Musasizi Faty",
    date: "Feb 20, 2026",
    type: "video",
    url: "https://youtube.com/watch?v=example1",
  },
  {
    title: "The Power of Prayer",
    speaker: "Evangelist Halima",
    date: "Feb 13, 2026",
    type: "audio",
    url: "https://example.com/audio/the-power-of-prayer.mp3",
  },
  {
    title: "Living with Purpose",
    speaker: "Pastor Musasizi Faty",
    date: "Feb 6, 2026",
    type: "video",
    url: "https://youtube.com/watch?v=example2",
  },
  {
    title: "Grace That Transforms",
    speaker: "Altar Director Damali",
    date: "Jan 26, 2026",
    type: "audio",
    url: "https://example.com/audio/grace-that-transforms.mp3",
  },
  {
    title: "The Anointing",
    speaker: "Pastor Musasizi Faty",
    date: "Feb 22, 2026",
    type: "video",
    url: "https://www.youtube.com/live/Pk--fT_DovE?si=rbGeu2FLnhhe0XbG",
  },
  {
    title: "Covenant of Blood",
    speaker: "Pastor Musasizi Faty",
    date: "Feb 15, 2026",
    type: "video",
    url: "https://www.youtube.com/live/af5XWo2HGfw?si=W8C6LUftQtYUNL4c",
  },
  {
    title: "Tuesday Intersession Service",
    speaker: "Pastor Musasizi Faty",
    date: "Feb 11, 2026",
    type: "video",
    url: "https://www.youtube.com/live/g9bxpL_LX8U?si=Uuyl_5f-iQiVbLqZ",
  },
];

const Sermons = () => {
  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Sermons" subtitle="Be blessed by the Word of God" />

      {/* Watch Live Banner */}
      <section className="py-12 bg-gradient-navy">
        <div className="container mx-auto px-4 text-center">
          <h2 className="font-heading text-2xl text-card font-bold mb-3">
            Watch Live Every Sunday
          </h2>
          <p className="text-card/70 mb-6">
            Join us at 10:30 AM via Facebook Live or YouTube Live
          </p>
          <div className="flex flex-wrap gap-4 justify-center">
            {/* Facebook Live Button */}
            <a
              href="https://www.facebook.com/SalemDominionMinistries/live"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-6 py-3 bg-gradient-gold text-primary font-semibold rounded-md shadow-gold hover:opacity-90 transition-opacity"
            >
              <Play size={18} /> Facebook Live
            </a>

            {/* YouTube Live Button */}
            <a
              href="https://youtube.com/@musasizifaty?si=a-VP5-Qen45nV1Jf"
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 px-6 py-3 border-2 border-gold text-gold font-semibold rounded-md hover:bg-gold/10 transition-colors"
            >
              <Video size={18} /> YouTube Live
            </a>
          </div>
        </div>
      </section>

      {/* Latest Sermons */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4 max-w-4xl">
          <h2 className="font-heading text-3xl font-bold text-foreground mb-8">
            Latest Sermons
          </h2>

          <div className="flex flex-col gap-4">
            {sermons.map((s, i) => (
              <div
                key={i}
                className="bg-card rounded-lg p-6 border border-border shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow"
              >
                {/* Icon */}
                <div className="w-12 h-12 rounded-full bg-gradient-gold flex items-center justify-center shrink-0">
                  {s.type === "video" ? (
                    <Video className="text-primary" size={20} />
                  ) : (
                    <Headphones className="text-primary" size={20} />
                  )}
                </div>

                {/* Sermon Info */}
                <div className="flex-1 min-w-0">
                  <h3 className="font-heading text-lg font-semibold text-foreground truncate">
                    {s.title}
                  </h3>
                  <p className="text-muted-foreground text-sm truncate">
                    by {s.speaker} • {s.date}
                  </p>
                </div>

                {/* Action Button */}
                <a
                  href={s.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="shrink-0 px-4 py-2 text-sm font-medium text-secondary border border-secondary rounded-md hover:bg-secondary hover:text-secondary-foreground transition-colors"
                >
                  {s.type === "video" ? "Watch" : "Listen"}
                </a>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Sermons;