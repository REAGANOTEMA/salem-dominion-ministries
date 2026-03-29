import React from 'react';
import { Link } from 'react-router-dom';
import { Music, Mic, Guitar, Users, Calendar, Award, Headphones, Radio } from 'lucide-react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SectionHeading from '../components/SectionHeading';

const WorshipMinistry = () => {
  const teams = [
    {
      title: "Vocal Team",
      description: "Lead the congregation in powerful worship through singing and harmonies.",
      icon: Mic,
      requirements: "Strong vocal ability, commitment to practice",
      schedule: "Thursdays 6:00 PM - 8:00 PM"
    },
    {
      title: "Band Team",
      description: "Musicians who create an atmosphere of worship through instruments.",
      icon: Guitar,
      requirements: "Proficiency in musical instrument",
      schedule: "Thursdays 6:00 PM - 8:00 PM"
    },
    {
      title: "Technical Team",
      description: "Sound engineers and technicians who ensure quality worship experience.",
      icon: Headphones,
      requirements: "Technical skills in sound and media",
      schedule: "Saturdays 10:00 AM - 12:00 PM"
    },
    {
      title: "Worship Dance",
      description: "Express worship through movement and choreographed dance.",
      icon: Users,
      requirements: "Passion for dance and worship",
      schedule: "Fridays 5:00 PM - 7:00 PM"
    }
  ];

  const upcomingEvents = [
    {
      title: "Worship Night",
      date: "November 24, 2024",
      description: "An evening dedicated to extended worship and praise.",
      location: "Main Sanctuary"
    },
    {
      title: "Music Workshop",
      date: "December 7, 2024",
      description: "Training for worship team members and aspiring musicians.",
      location: "Fellowship Hall"
    },
    {
      title: "Christmas Concert",
      date: "December 22, 2024",
      description: "Special Christmas celebration with carols and worship.",
      location: "Main Sanctuary"
    }
  ];

  const testimonies = [
    {
      name: "David Lwanga",
      text: "Being part of the worship team has deepened my relationship with God and helped me use my musical gifts for His glory.",
      role: "Band Member"
    },
    {
      name: "Sarah Nankya",
      text: "The worship ministry has transformed my understanding of what it means to truly worship God in spirit and truth.",
      role: "Vocal Team"
    }
  ];

  return (
    <div className="min-h-screen">
      <Navbar />

      {/* Hero Section */}
      <section className="relative min-h-[60vh] bg-gradient-to-br from-purple-600 via-pink-600 to-red-600 overflow-hidden">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-pink-400 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="flex flex-col items-center justify-center min-h-[60vh] py-20 text-center">
            <div className="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-6">
              <Music className="text-white" size={40} />
            </div>
            <h1 className="font-blackadder text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Worship Ministry
            </h1>
            <p className="font-gabriola text-xl text-white/90 mb-8 leading-relaxed max-w-3xl">
              A dedicated team committed to leading the congregation into God's presence through music and praise
            </p>
            <Link 
              to="/contact" 
              className="inline-flex items-center px-8 py-4 bg-white text-purple-700 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
            >
              Join Worship Team
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
              subtitle="Leading people into God's presence through worship"
            />
            <div className="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-10 md:p-14">
              <p className="font-gabriola text-2xl text-gray-700 leading-relaxed mb-6">
                "God is spirit, and his worshipers must worship in the Spirit and in truth."
              </p>
              <p className="text-gray-600 font-medium">- John 4:24</p>
            </div>
          </div>
        </div>
      </section>

      {/* Teams Section */}
      <section className="py-20 bg-gradient-to-br from-purple-50 to-pink-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Worship Teams"
            subtitle="Join a team that fits your gifts and passion"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            {teams.map((team, index) => (
              <div key={index} className="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div className="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-6">
                  <team.icon className="text-white" size={32} />
                </div>
                <h3 className="font-bold text-xl text-gray-900 mb-3">{team.title}</h3>
                <p className="text-gray-600 mb-4">{team.description}</p>
                <div className="space-y-2 text-sm">
                  <div className="flex items-center gap-2">
                    <Award className="text-purple-500" size={16} />
                    <span className="text-gray-700">{team.requirements}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Calendar className="text-purple-500" size={16} />
                    <span className="text-gray-700">{team.schedule}</span>
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
            subtitle="Join us for special worship gatherings"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {upcomingEvents.map((event, index) => (
              <div key={index} className="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div className="flex items-center gap-2 text-purple-600 font-semibold mb-2">
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
      <section className="py-20 bg-gradient-to-br from-pink-50 to-purple-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Worship Team Testimonies"
            subtitle="Stories of worship and transformation"
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
      <section className="py-20 bg-gradient-to-r from-purple-600 to-pink-600">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-white mb-6">
              Ready to Join Worship Ministry?
            </h2>
            <p className="text-white/90 text-lg mb-8">
              Use your musical gifts to lead others into God's presence and experience the joy of worship.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-white text-purple-600 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
              >
                <Users className="mr-2" size={20} />
                Join Worship Team
              </Link>
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-purple-500 text-white font-bold rounded-full border-2 border-white/30 hover:bg-purple-400 transition-all duration-300"
              >
                <Radio className="mr-2" size={20} />
                Attend Worship Night
              </Link>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default WorshipMinistry;
