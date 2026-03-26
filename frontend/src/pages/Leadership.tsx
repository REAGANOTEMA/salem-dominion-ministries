import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";

import pastorImg from "@/assets/pastor.jpeg";
import halimaImg from "@/assets/halima.png";
import damaliImg from "@/assets/damali.png";
import geraldImg from "@/assets/gerald.png";
import ireneImg from "@/assets/irene.png";
import joyceImg from "@/assets/joyce.png";

const Leadership = () => {
  const leaders = [
    {
      name: "Halima",
      role: "Evangelist & Missions Director",
      img: halimaImg,
      bio: "Halima passionately leads evangelism and missions, coordinating outreach programs that impact communities spiritually and socially. Her leadership inspires members to share faith boldly while building meaningful connections that reflect Christ’s love and compassion.",
    },
    {
      name: "Damali",
      role: "Altar Director",
      img: damaliImg,
      bio: "Damali oversees altar ministry with deep spiritual sensitivity, guiding prayer moments and personal ministry. She creates an atmosphere where individuals encounter God, receive encouragement, and experience transformation through dedicated spiritual support.",
    },
    {
      name: "Gerald",
      role: "Men Fellowship Leader",
      img: geraldImg,
      bio: "Gerald leads the men’s fellowship by encouraging spiritual growth, accountability, and strong Christian leadership. He mentors men to live purposefully, strengthen families, and serve faithfully within both church and community.",
    },
    {
      name: "Irene",
      role: "Church Administrator",
      img: ireneImg,
      bio: "Irene ensures smooth church operations through strong organization, communication, and coordination. Her administrative leadership supports ministry effectiveness, helping teams function efficiently while maintaining excellence across church activities.",
    },
    {
      name: "Joyce",
      role: "Church Treasurer",
      img: joyceImg,
      bio: "Joyce faithfully manages church finances with integrity, transparency, and stewardship. She ensures resources are handled responsibly, supporting ministry sustainability and enabling the church to fulfill its mission effectively.",
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
                alt="Pastor Musasizi Faty Stu"
                className="w-40 h-40 rounded-full object-cover shrink-0"
              />

              <div>
                <span className="text-secondary font-medium text-sm uppercase tracking-wider">
                  Senior Pastor
                </span>

                <h2 className="font-heading text-3xl font-bold text-foreground mt-1 mb-4">
                  Pastor Musasizi Faty Stu
                </h2>

                <p className="text-muted-foreground leading-relaxed">
                  Pastor Musasizi Faty Stu is a visionary spiritual leader dedicated to teaching God’s Word with clarity, compassion, and practical impact. With years of ministry experience, he focuses on raising mature believers, empowering leaders, and building a Christ-centered community that transforms lives spiritually, socially, and economically while advancing the mission of the Kingdom.
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
            {leaders.map((leader, i) => (
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