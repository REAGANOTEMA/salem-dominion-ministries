import React from 'react';
import { Link } from 'react-router-dom';
import { Users, Calendar, Heart, Award, BookOpen, Target, Music, Gamepad2 } from 'lucide-react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SectionHeading from '../components/SectionHeading';

const YouthMinistry = () => {
  const programs = [
    {
      title: "Bible Study Groups",
      description: "Weekly gatherings where youth dive deep into God's Word and apply it to their daily lives.",
      icon: BookOpen,
      schedule: "Fridays 5:00 PM - 7:00 PM",
      ageGroup: "13-18 years"
    },
    {
      title: "Youth Worship Team",
      description: "Opportunities for young people to use their musical gifts to lead worship.",
      icon: Music,
      schedule: "Saturdays 3:00 PM - 5:00 PM",
      ageGroup: "14-25 years"
    },
    {
      title: "Mentorship Program",
      description: "One-on-one mentoring with mature Christian adults for guidance and support.",
      icon: Users,
      schedule: "By Appointment",
      ageGroup: "13-21 years"
    },
    {
      title: "Leadership Training",
      description: "Developing young leaders through workshops and practical ministry experience.",
      icon: Award,
      schedule: "Monthly Workshops",
      ageGroup: "15-25 years"
    }
  ];

  const upcomingEvents = [
    {
      title: "Youth Camp 2024",
      date: "December 20-23, 2024",
      description: "Annual youth camp with powerful teachings, fun activities, and lifelong friendships.",
      location: "Church Camp Grounds"
    },
    {
      title: "Talent Show",
      date: "November 15, 2024",
      description: "Showcasing the amazing talents of our youth in music, drama, and arts.",
      location: "Main Church Hall"
    },
    {
      title: "Career Guidance Workshop",
      date: "October 28, 2024",
      description: "Helping youth make informed career decisions from a Christian perspective.",
      location: "Youth Center"
    }
  ];

  const testimonies = [
    {
      name: "John Mukasa",
      text: "Youth ministry helped me discover my purpose and build a strong foundation in Christ.",
      role: "University Student"
    },
    {
      name: "Sarah Nalwoga",
      text: "The mentorship program guided me through difficult teenage years with biblical wisdom.",
      role: "High School Student"
    }
  ];

  return (
    <div className="min-h-screen">
      <Navbar />

      {/* Hero Section */}
      <section className="relative min-h-[60vh] bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 overflow-hidden">
        <div className="absolute inset-0 bg-black/20"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-pink-400 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="flex flex-col items-center justify-center min-h-[60vh] py-20 text-center">
            <div className="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mb-6">
              <Users className="text-white" size={40} />
            </div>
            <h1 className="font-blackadder text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
              Youth Ministry
            </h1>
            <p className="font-gabriola text-xl text-white/90 mb-8 leading-relaxed max-w-3xl">
              Empowering young people to live boldly for Christ through mentorship, fellowship, and leadership training
            </p>
            <Link 
              to="/contact" 
              className="inline-flex items-center px-8 py-4 bg-white text-purple-700 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
            >
              Join Our Youth Community
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
              subtitle="Raising the next generation of Christian leaders"
            />
            <div className="bg-gradient-to-r from-purple-50 to-blue-50 rounded-3xl p-10 md:p-14">
              <p className="font-gabriola text-2xl text-gray-700 leading-relaxed mb-6">
                "Don't let anyone look down on you because you are young, but set an example for the believers in speech, in conduct, in love, in faith and in purity."
              </p>
              <p className="text-gray-600 font-medium">- 1 Timothy 4:12</p>
            </div>
          </div>
        </div>
      </section>

      {/* Programs Section */}
      <section className="py-20 bg-gradient-to-br from-purple-50 to-blue-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Our Programs"
            subtitle="Tailored programs for spiritual growth and development"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
            {programs.map((program, index) => (
              <div key={index} className="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div className="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center mb-6">
                  <program.icon className="text-white" size={32} />
                </div>
                <h3 className="font-bold text-xl text-gray-900 mb-3">{program.title}</h3>
                <p className="text-gray-600 mb-4">{program.description}</p>
                <div className="space-y-2 text-sm">
                  <div className="flex items-center gap-2">
                    <Calendar className="text-purple-500" size={16} />
                    <span className="text-gray-700">{program.schedule}</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <Target className="text-purple-500" size={16} />
                    <span className="text-gray-700">{program.ageGroup}</span>
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
            subtitle="Join us for exciting youth gatherings"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {upcomingEvents.map((event, index) => (
              <div key={index} className="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
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
      <section className="py-20 bg-gradient-to-br from-blue-50 to-purple-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Youth Testimonies"
            subtitle="Real stories of transformation"
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
      <section className="py-20 bg-gradient-to-r from-purple-600 to-blue-600">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-white mb-6">
              Ready to Join Our Youth Community?
            </h2>
            <p className="text-white/90 text-lg mb-8">
              Discover your purpose, build lasting friendships, and grow in your faith with us.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-white text-purple-600 font-bold rounded-full shadow-xl hover:scale-105 transition-all duration-300"
              >
                <Users className="mr-2" size={20} />
                Join Youth Ministry
              </Link>
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-purple-500 text-white font-bold rounded-full border-2 border-white/30 hover:bg-purple-400 transition-all duration-300"
              >
                <Heart className="mr-2" size={20} />
                Volunteer as Mentor
              </Link>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default YouthMinistry;
