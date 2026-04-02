import React from 'react';
import { Link } from 'react-router-dom';
import { Shield, Users, BookOpen, Calendar, Award, Target, Briefcase, Handshake } from 'lucide-react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SectionHeading from '../components/SectionHeading';

const MenMinistry = () => {
  const programs = [
    {
      title: "Men's Fellowship",
      description: "Monthly gatherings for men to connect, share, and grow together in faith.",
      icon: Users,
      schedule: "First Saturday of each month 6:00 PM - 8:00 PM",
      location: "Fellowship Hall"
    },
    {
      title: "Bible Study Groups",
      description: "In-depth study of God's Word with practical applications for daily living.",
      icon: BookOpen,
      schedule: "Thursdays 7:00 PM - 8:30 PM",
      location: "Conference Room"
    },
    {
      title: "Leadership Training",
      description: "Equipping men to lead with integrity at home, church, and workplace.",
      icon: Shield,
      schedule: "Monthly Workshops",
      location: "Main Hall"
    },
    {
      title: "Mentorship Program",
      description: "Experienced men mentoring younger men in faith, career, and family life.",
      icon: Handshake,
      schedule: "By Appointment",
      location: "Various Locations"
    }
  ];

  const upcomingEvents = [
    {
      title: "Men's Retreat 2024",
      date: "December 6-8, 2024",
      description: "Annual men's retreat with powerful teachings, outdoor activities, and brotherhood.",
      location: "Church Camp Grounds"
    },
    {
      title: "Father's Day Celebration",
      date: "June 16, 2024",
      description: "Honoring fathers and father figures with special service and fellowship.",
      location: "Main Sanctuary"
    },
    {
      title: "Business & Faith Seminar",
      date: "October 19, 2024",
      description: "Integrating biblical principles into business and professional life.",
      location: "Conference Hall"
    }
  ];

  const testimonies = [
    {
      name: "Robert Ssekandi",
      text: "Men's fellowship has helped me become a better husband, father, and leader in my community.",
      role: "Business Owner"
    },
    {
      name: "James Mwesigwa",
      text: "The mentorship program guided me through career challenges with biblical wisdom.",
      role: "Young Professional"
    }
  ];

  return (
    <div className="min-h-screen">
      <Navbar />

      {/* Hero Section */}
      <section className="relative min-h-[60vh] bg-gradient-to-br from-blue-600 via-indigo-600 to-gray-800 overflow-hidden">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-blue-400 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-gray-600 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="flex flex-col items-center justify-center min-h-[60vh] py-20 text-center">
            <div className="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-6">
              <Shield className="text-white" size={40} />
            </div>
            <h1 className="font-blackadder text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Men's Ministry
            </h1>
            <p className="font-gabriola text-xl text-white/90 mb-8 leading-relaxed max-w-3xl">
              Equipping men to lead with integrity at home, church, and workplace through fellowship and mentorship programs
            </p>
            <Link 
              to="/contact" 
              className="inline-flex items-center px-8 py-4 bg-white text-blue-700 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
            >
              Join Our Brotherhood
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
              subtitle="Building strong men of faith and character"
            />
            <div className="bg-gradient-to-r from-blue-50 to-gray-50 rounded-3xl p-10 md:p-14">
              <p className="font-gabriola text-2xl text-gray-700 leading-relaxed mb-6">
                "Be watchful, stand firm in the faith, act like men, be strong. Let all that you do be done in love."
              </p>
              <p className="text-gray-600 font-medium">- 1 Corinthians 16:13-14</p>
            </div>
          </div>
        </div>
      </section>

      {/* Programs Section */}
      <section className="py-20 bg-gradient-to-br from-blue-50 to-gray-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Our Programs"
            subtitle="Developing spiritual leadership and integrity"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            {programs.map((program, index) => (
              <div key={index} className="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div className="w-16 h-16 bg-gradient-to-r from-blue-500 to-gray-600 rounded-full flex items-center justify-center mb-6">
                  <program.icon className="text-white" size={32} />
                </div>
                <h3 className="font-bold text-xl text-gray-900 mb-3">{program.title}</h3>
                <p className="text-gray-600 mb-4">{program.description}</p>
                <div className="space-y-2 text-sm">
                  <div className="flex items-center gap-2">
                    <Calendar className="text-blue-500" size={16} />
                    <span className="text-gray-700">{program.schedule}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Target className="text-blue-500" size={16} />
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
            subtitle="Join us for men's gatherings and conferences"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {upcomingEvents.map((event, index) => (
              <div key={index} className="bg-gradient-to-br from-blue-50 to-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div className="flex items-center gap-2 text-blue-600 font-semibold mb-2">
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
      <section className="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Brotherhood Testimonies"
            subtitle="Stories of transformation and leadership"
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
      <section className="py-20 bg-gradient-to-r from-blue-600 to-gray-800">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-white mb-6">
              Ready to Join Our Brotherhood?
            </h2>
            <p className="text-white/90 text-lg mb-8">
              Connect with men of faith, grow spiritually, and develop your leadership potential.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-700 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
              >
                <Users className="mr-2" size={20} />
                Join Men's Ministry
              </Link>
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-blue-500 text-white font-bold rounded-full border-2 border-white/30 hover:bg-blue-400 transition-all duration-300"
              >
                <Briefcase className="mr-2" size={20} />
                Become a Mentor
              </Link>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default MenMinistry;
