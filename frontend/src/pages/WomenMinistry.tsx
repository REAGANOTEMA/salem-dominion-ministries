import React from 'react';
import { Link } from 'react-router-dom';
import { Heart, Users, BookOpen, Calendar, Award, Coffee, Home, Sparkles } from 'lucide-react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SectionHeading from '../components/SectionHeading';

const WomenMinistry = () => {
  const programs = [
    {
      title: "Weekly Prayer Meetings",
      description: "Join us for powerful prayer sessions where we lift up our families, church, and community.",
      icon: Heart,
      schedule: "Tuesdays 6:00 PM - 7:30 PM",
      location: "Prayer Room"
    },
    {
      title: "Bible Study Groups",
      description: "Deep dive into God's Word with fellow women seeking spiritual growth and understanding.",
      icon: BookOpen,
      schedule: "Thursdays 5:30 PM - 7:00 PM",
      location: "Fellowship Hall"
    },
    {
      title: "Mentorship Program",
      description: "Experienced women mentoring younger women in faith, marriage, and life skills.",
      icon: Users,
      schedule: "Monthly Meetings",
      location: "Conference Room"
    },
    {
      title: "Crafts & Skills Workshop",
      description: "Learning practical skills while building friendships and sharing testimonies.",
      icon: Sparkles,
      schedule: "Saturdays 2:00 PM - 4:00 PM",
      location: "Crafts Room"
    }
  ];

  const upcomingEvents = [
    {
      title: "Women's Conference 2024",
      date: "November 8-10, 2024",
      description: "Annual conference with powerful speakers, worship, and ministry workshops.",
      location: "Main Church Hall"
    },
    {
      title: "Mother's Day Celebration",
      date: "May 12, 2024",
      description: "Honoring mothers and mother figures with special service and fellowship.",
      location: "Main Sanctuary"
    },
    {
      title: "Marriage Enrichment Seminar",
      date: "September 21-22, 2024",
      description: "Building stronger marriages based on biblical principles.",
      location: "Fellowship Hall"
    }
  ];

  const testimonies = [
    {
      name: "Grace Nakato",
      text: "Women's ministry has been my spiritual home. The prayer meetings and Bible studies have transformed my walk with God.",
      role: "Mother of 3"
    },
    {
      name: "Sarah Namuli",
      text: "The mentorship program helped me navigate through difficult seasons with wisdom and grace.",
      role: "Young Professional"
    }
  ];

  return (
    <div className="min-h-screen">
      <Navbar />

      {/* Hero Section */}
      <section className="relative min-h-[60vh] bg-gradient-to-br from-pink-500 via-rose-500 to-purple-600 overflow-hidden">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-pink-300 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="flex flex-col items-center justify-center min-h-[60vh] py-20 text-center">
            <div className="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-6">
              <Heart className="text-white" size={40} />
            </div>
            <h1 className="font-blackadder text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Women's Ministry
            </h1>
            <p className="font-gabriola text-xl text-white/90 mb-8 leading-relaxed max-w-3xl">
              Encouraging women to grow spiritually, emotionally, and socially through prayer meetings, conferences, and community service
            </p>
            <Link 
              to="/contact" 
              className="inline-flex items-center px-8 py-4 bg-white text-pink-600 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
            >
              Join Our Sisterhood
            </Link>
          </div>
        </div>
      </section>

      {/* Mission Statement */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <SectionHeading
              title="Our Mission"
              subtitle="Building strong women of faith and character"
            />
            <div className="bg-gradient-to-r from-pink-50 to-purple-50 rounded-3xl p-10 md:p-14">
              <p className="font-gabriola text-2xl text-gray-700 leading-relaxed mb-6">
                "She is clothed with strength and dignity; she can laugh at the days to come. She speaks with wisdom, and faithful instruction is on her tongue."
              </p>
              <p className="text-gray-600 font-medium">- Proverbs 31:25-26</p>
            </div>
          </div>
        </div>
      </section>

      {/* Programs Section */}
      <section className="py-20 bg-gradient-to-br from-pink-50 to-purple-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Our Programs"
            subtitle="Nurturing spiritual growth and community"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            {programs.map((program, index) => (
              <div key={index} className="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div className="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full flex items-center justify-center mb-6">
                  <program.icon className="text-white" size={32} />
                </div>
                <h3 className="font-bold text-xl text-gray-900 mb-3">{program.title}</h3>
                <p className="text-gray-600 mb-4">{program.description}</p>
                <div className="space-y-2 text-sm">
                  <div className="flex items-center gap-2">
                    <Calendar className="text-pink-500" size={16} />
                    <span className="text-gray-700">{program.schedule}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Home className="text-pink-500" size={16} />
                    <span className="text-gray-700">{program.location}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Upcoming Events */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Upcoming Events"
            subtitle="Join us for special gatherings and conferences"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {upcomingEvents.map((event, index) => (
              <div key={index} className="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div className="flex items-center gap-2 text-pink-600 font-semibold mb-2">
                  <Calendar size={16} />
                  {event.date}
                </div>
                <h3 className="font-bold text-xl text-gray-900 mb-2">{event.title}</h3>
                <p className="text-gray-600 mb-3">{event.description}</p>
                <p className="text-sm text-gray-500">
                  <strong>Location:</strong> {event.location}
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonies */}
      <section className="py-20 bg-gradient-to-br from-purple-50 to-pink-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Sisterhood Testimonies"
            subtitle="Stories of grace and transformation"
          />
          <div className="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            {testimonies.map((testimony, index) => (
              <div key={index} className="bg-white rounded-2xl p-8 shadow-lg">
                <p className="text-gray-600 mb-6 italic">"{testimony.text}"</p>
                <div>
                  <p className="font-bold text-gray-900">{testimony.name}</p>
                  <p className="text-sm text-gray-500">{testimony.role}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section className="py-20 bg-gradient-to-r from-pink-600 to-purple-600">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-white mb-6">
              Ready to Join Our Sisterhood?
            </h2>
            <p className="text-white/90 text-lg mb-8">
              Experience the joy of Christian fellowship, spiritual growth, and meaningful relationships.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-white text-pink-600 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
              >
                <Users className="mr-2" size={20} />
                Join Women's Ministry
              </Link>
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-pink-500 text-white font-bold rounded-full border-2 border-white/30 hover:bg-pink-400 transition-all duration-300"
              >
                <Coffee className="mr-2" size={20} />
                Attend Prayer Meeting
              </Link>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default WomenMinistry;
