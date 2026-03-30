import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";

import pastorImg from "@/assets/pastor.jpeg";
import pastorJoyceImg from "@/assets/PASTOR-NABULYA-JOYCE.jpeg";
import apostleIreneImg from "@/assets/APOSTLE-IRENE-MIREMBE.jpeg";
import evangelistHalimaImg from "@/assets/Evangelist-kisakye-Halima.jpeg";
import pastorDamaliImg from "@/assets/Pastor-damali-namwuma.png";
import pastorMiriamImg from "@/assets/Pastor-miriam-Gerald.jpeg";
import pastorJothamImg from "@/assets/Pastor-jotham-Bright-Mulinde.jpeg";
import pastorJonathanImg from "@/assets/pastor-jonathan-Ngobi.jpeg";

const Leadership = () => {
  const leaders = [
    {
      name: "Pastor Faty Musasizi",
      role: "Senior Pastor & Founder",
      img: pastorImg,
      bio: "Visionary leader and founder of Salem Dominion Ministries, dedicated to spreading God's word and building a Christ-centered community. Provides overall spiritual direction and guidance.",
    },
    {
      name: "Pastor Miriam Gerald",
      role: "Associate Senior Pastor",
      img: pastorMiriamImg,
      bio: "Senior spiritual leader with over 15 years of ministry experience and biblical teaching. Provides overall vision and spiritual direction.",
    },
    {
      name: "Pastor Nabulya Joyce",
      role: "Church Treasurer",
      img: pastorJoyceImg,
      bio: "Dedicated treasurer with over 10 years of service in financial stewardship and church administration. Ensures transparency and integrity in all church financial matters.",
    },
    {
      name: "Apostle Irene Mirembe",
      role: "Church Administrator",
      img: apostleIreneImg,
      bio: "Experienced church administrator with a passion for organizational excellence and spiritual leadership. Oversees daily operations and administrative functions.",
    },
    {
      name: "Evangelist Kisakye Halima",
      role: "Mission Director",
      img: evangelistHalimaImg,
      bio: "Passionate evangelist committed to spreading the gospel and leading mission initiatives. Coordinates outreach programs and community evangelism.",
    },
    {
      name: "Pastor Damali Namwima",
      role: "Altars Director",
      img: pastorDamaliImg,
      bio: "Spiritual leader overseeing altar ministries and prayer services with deep biblical knowledge. Guides worship and spiritual formation.",
    },
    {
      name: "Pastor Jotham Bright Mulinde",
      role: "Church Elder",
      img: pastorJothamImg,
      bio: "Respected church elder providing wisdom and guidance to the congregation. Offers spiritual counsel and leadership support.",
    },
    {
      name: "Pastor Jonathan Ngobi",
      role: "Bulanga Branch Pastor",
      img: pastorJonathanImg,
      bio: "Dedicated branch pastor serving the Bulanga community with spiritual leadership. Provides pastoral care and community outreach.",
    },
  ];

  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Leadership" subtitle="Meet the team leading Salem Dominion Ministries" />

      {/* Senior Pastor */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="bg-card rounded-lg p-8 md:p-12 border border-border shadow-sm">
            <div className="flex flex-col md:flex-row items-center gap-8">
              <img
                src={pastorImg}
                alt="Senior Pastor"
                className="w-40 h-40 rounded-full object-cover shrink-0"
              />

              <div>
                <span className="text-secondary font-medium text-sm uppercase tracking-wider">
                  Senior Pastor & Founder
                </span>

                <h2 className="font-heading text-3xl font-bold text-foreground mt-1 mb-4">
                  Pastor [Main Pastor Name]
                </h2>

                <p className="text-muted-foreground leading-relaxed">
                  Visionary leader and founder of Salem Dominion Ministries, dedicated to spreading God's word and building a Christ-centered community. Provides overall spiritual direction and guidance for the church and its leadership team.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Other Leaders */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4 max-w-6xl">
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {leaders.slice(1).map((leader, i) => (
              <div key={i} className="bg-card rounded-lg p-8 border border-border shadow-sm text-center">
                <img
                  src={leader.img}
                  alt={leader.name}
                  className="w-28 h-28 rounded-full object-cover mx-auto mb-4"
                />

                <h3 className="font-heading text-xl font-bold text-foreground">
                  {leader.name}
                </h3>

                <p className="text-secondary text-sm mb-3">{leader.role}</p>

                <p className="text-muted-foreground text-sm leading-relaxed">
                  {leader.bio}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Leadership;