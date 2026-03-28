import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import SectionHeading from "@/components/SectionHeading";
import heroWorship from "@/assets/hero-worship.jpg";
import heroCommunity from "@/assets/hero-community.jpg";
import heroChoir from "@/assets/hero-choir.jpg";
import pastor from "@/assets/pastor.jpeg";
import halima from "@/assets/halima.png";
import damali from "@/assets/damali.png";
import gerald from "@/assets/gerald.png";
import irene from "@/assets/irene.png";
import joyce from "@/assets/joyce.jpeg";

const photos = [
  { src: heroWorship, alt: "Worship service", caption: "Sunday Worship Service" },
  { src: heroCommunity, alt: "Community gathering", caption: "Community Outreach" },
  { src: heroChoir, alt: "Church choir", caption: "Praise & Worship Team" },
  { src: pastor, alt: "Senior pastor", caption: "Senior Pastor" },
  { src: halima, alt: "Ministry leader Halima", caption: "Evangelism Ministry" },
  { src: damali, alt: "Altar director Damali", caption: "Prayer Ministry" },
  { src: gerald, alt: "Leadership member Gerald", caption: "Leadership Team" },
  { src: irene, alt: "Leadership member Irene", caption: "Women Fellowship" },
  { src: joyce, alt: "Leadership member Joyce", caption: "Children Ministry" },
];

const Gallery = () => {
  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Gallery" subtitle="Moments captured from our services and events" />

      {/* Photos */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4">
          <SectionHeading title="Photos" subtitle="Browse photos from services, conferences, and outreach programs" />
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            {photos.map((photo, i) => (
              <div key={i} className="group relative overflow-hidden rounded-lg shadow-sm border border-border">
                <img
                  src={photo.src}
                  alt={photo.alt}
                  className="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <div className="absolute inset-0 bg-primary/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                  <p className="text-card font-heading font-semibold">{photo.caption}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Videos */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4">
          <SectionHeading title="Videos" subtitle="Watch highlights, testimonies, and special moments" />
          <div className="max-w-3xl mx-auto text-center">
            <div className="bg-card rounded-lg p-12 border border-border shadow-sm">
              <p className="text-muted-foreground text-lg mb-6">
                Watch our latest videos on YouTube and Facebook. Subscribe to stay updated!
              </p>
              <div className="flex flex-wrap gap-4 justify-center">
                <a
                  href="https://youtube.com/@musasizifaty"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="px-6 py-3 bg-gradient-gold text-primary font-semibold rounded-md shadow-gold hover:opacity-90 transition-opacity"
                >
                  YouTube Channel
                </a>
                <a
                  href="https://www.facebook.com/SalemDominionMinistries"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="px-6 py-3 border-2 border-secondary text-secondary font-semibold rounded-md hover:bg-secondary hover:text-secondary-foreground transition-colors"
                >
                  Facebook Page
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Gallery;
