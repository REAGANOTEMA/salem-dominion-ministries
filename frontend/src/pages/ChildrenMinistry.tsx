import React from 'react';
import { Link } from 'react-router-dom';
import { Heart, BookOpen, Users, Calendar, Clock, MapPin, Phone, Mail, Star, Award, Music, Palette, Gamepad2, Church, Baby, User, GraduationCap } from 'lucide-react';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';
import SectionHeading from '../components/SectionHeading';

// Import all children-related images
import kidShowingKindness from '@/assets/a-kid-showing-how-kindness-isgood.jpeg';
import childrenCelebrating from '@/assets/children-celebrating.jpeg';
import childrenEatingWithPastor from '@/assets/children-eating-withpastor.jpeg';
import childrenFood from '@/assets/children-food.jpeg';
import childrenWithBooks from '@/assets/children-with-books.jpeg';
import supportChildren from '@/assets/support-children-now.jpeg';

const ChildrenMinistry = () => {
  const classes = [
    {
      name: "Nursery Class",
      age: "0-2 years",
      description: "A safe and nurturing environment where infants experience God's love through gentle care and simple songs.",
      icon: Baby,
      color: "from-pink-400 to-rose-400",
      features: ["Safe Environment", "Gentle Care", "Simple Songs", "Sensory Activities"]
    },
    {
      name: "Beginner Class",
      age: "3-5 years",
      description: "Preschoolers learn about Jesus through interactive Bible stories, songs, games, and hands-on activities.",
      icon: User,
      color: "from-purple-400 to-indigo-400",
      features: ["Interactive Stories", "Fun Games", "Creative Activities", "Memory Verses"]
    },
    {
      name: "Primary Class",
      age: "6-8 years",
      description: "Early elementary children explore foundational Bible truths through engaging lessons and creative activities.",
      icon: BookOpen,
      color: "from-blue-400 to-cyan-400",
      features: ["Bible Lessons", "Creative Crafts", "Group Activities", "Character Building"]
    },
    {
      name: "Junior Class",
      age: "9-11 years",
      description: "Upper elementary children dive deeper into God's Word with practical applications and service projects.",
      icon: GraduationCap,
      color: "from-green-400 to-emerald-400",
      features: ["Deep Bible Study", "Service Projects", "Leadership Training", "Prayer Partners"]
    },
    {
      name: "Pre-Teen Class",
      age: "12-13 years",
      description: "Tweens navigate faith and life challenges through relevant Bible studies and meaningful discussions.",
      icon: Users,
      color: "from-orange-400 to-amber-400",
      features: ["Relevant Topics", "Mentorship", "Life Applications", "Peer Support"]
    }
  ];

  const activities = [
    {
      title: "Bible Storytelling",
      description: "Interactive storytelling sessions that bring Bible stories to life through drama, props, and participation.",
      icon: BookOpen,
      image: childrenWithBooks
    },
    {
      title: "Praise & Worship",
      description: "Age-appropriate worship songs and dance that help children express their love for God joyfully.",
      icon: Music,
      image: childrenCelebrating
    },
    {
      title: "Creative Arts & Crafts",
      description: "Hands-on activities that reinforce biblical truths through creativity and artistic expression.",
      icon: Palette,
      image: childrenCelebrating
    },
    {
      title: "Games & Activities",
      description: "Fun and educational games that teach biblical principles and build Christian character.",
      icon: Gamepad2,
      image: kidShowingKindness
    },
    {
      title: "Prayer Time",
      description: "Teaching children to pray and develop their personal relationship with God.",
      icon: Heart,
      image: childrenEatingWithPastor
    },
    {
      title: "Fellowship & Snacks",
      description: "Building community through shared meals and positive social interactions.",
      icon: Users,
      image: childrenFood
    }
  ];

  const upcomingEvents = [
    {
      title: "Children's Christmas Celebration",
      date: "December 15, 2024",
      time: "10:00 AM",
      description: "Special Christmas celebration with carols, nativity play, and festive activities.",
      image: childrenCelebrating
    },
    {
      title: "Vacation Bible School",
      date: "August 5-9, 2024",
      time: "9:00 AM - 12:00 PM",
      description: "A week-long adventure filled with Bible stories, games, and friendship.",
      image: childrenWithBooks
    },
    {
      title: "Children's Sports Day",
      date: "September 20, 2024",
      time: "9:00 AM",
      description: "Fun-filled day of sports and team-building activities.",
      image: kidShowingKindness
    }
  ];

  const galleryImages = [
    {
      src: childrenCelebrating,
      alt: "Children celebrating together",
      caption: "Joyful celebrations in God's presence"
    },
    {
      src: childrenWithBooks,
      alt: "Children learning with books",
      caption: "Learning God's Word together"
    },
    {
      src: childrenEatingWithPastor,
      alt: "Children having meal with pastor",
      caption: "Fellowship and spiritual nourishment"
    },
    {
      src: childrenFood,
      alt: "Children enjoying meals",
      caption: "Sharing meals and friendship"
    },
    {
      src: kidShowingKindness,
      alt: "Child demonstrating kindness",
      caption: "Learning to love and serve others"
    },
    {
      src: supportChildren,
      alt: "Support children ministry",
      caption: "Investing in the next generation"
    }
  ];

  const testimonials = [
    {
      name: "Sarah Nakato",
      role: "Parent",
      content: "The children's ministry has transformed my kids' lives. They love coming to church and have grown so much in their faith.",
      rating: 5
    },
    {
      name: "David Mugisha",
      role: "Parent",
      content: "The teachers are amazing with children. My son has learned so much about God and made great friends.",
      rating: 5
    },
    {
      name: "Grace Namutebi",
      role: "Volunteer Teacher",
      content: "Teaching children about Jesus is the most rewarding experience. Seeing their faith grow is incredible!",
      rating: 5
    }
  ];

  return (
    <div className="min-h-screen">
      <Navbar />

      {/* Hero Section */}
      <section className="relative min-h-[80vh] bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-yellow-400 rounded-full opacity-20 blur-3xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-pink-400 rounded-full opacity-20 blur-3xl animate-pulse delay-1000"></div>
        
        <div className="container mx-auto px-4 relative z-10">
          <div className="flex flex-col lg:flex-row items-center justify-between min-h-[80vh] py-20">
            <div className="lg:w-1/2 mb-10 lg:mb-0">
              <div className="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                <Star className="w-4 h-4" />
                Children's Ministry
              </div>
              <h1 className="font-blackadder text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Where Faith
                <span className="text-gradient-gold"> Begins</span>
              </h1>
              <p className="font-gabriola text-xl text-gray-600 mb-8 leading-relaxed">
                A safe and joyful environment where children learn biblical principles through storytelling, 
                music, and interactive lessons. We're raising the next generation to love and serve God.
              </p>
              <div className="flex flex-col sm:flex-row gap-4">
                <Link 
                  to="/contact" 
                  className="inline-flex items-center justify-center px-8 py-4 bg-gradient-gold text-primary font-bold rounded-full shadow-gold-enhanced hover:shadow-gold-intense hover:scale-105 transition-all duration-300"
                >
                  <Church className="mr-2" size={20} />
                  Register Your Child
                </Link>
                <Link 
                  to="#classes" 
                  className="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-800 font-bold rounded-full border-2 border-gray-200 hover:border-gold hover:text-gold transition-all duration-300"
                >
                  Explore Classes
                </Link>
              </div>
            </div>
            <div className="lg:w-1/2 lg:pl-12">
              <div className="relative">
                <img
                  src={childrenCelebrating}
                  alt="Children celebrating in ministry"
                  className="rounded-3xl shadow-2xl w-full h-auto"
                />
                <div className="absolute -bottom-6 -left-6 bg-white rounded-2xl p-6 shadow-xl">
                  <div className="flex items-center gap-4">
                    <div className="w-12 h-12 bg-gradient-gold rounded-full flex items-center justify-center">
                      <Users className="text-primary" size={24} />
                    </div>
                    <div>
                      <p className="font-bold text-gray-900">500+ Children</p>
                      <p className="text-sm text-gray-600">Growing in faith weekly</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Mission Statement */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <SectionHeading
              title="Our Mission"
              subtitle="Nurturing young hearts to know and love God"
            />
            <div className="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl p-10 md:p-14">
              <p className="font-gabriola text-2xl text-gray-700 leading-relaxed mb-8">
                "Train up a child in the way he should go; even when he is old he will not depart from it."
              </p>
              <p className="text-gray-600 font-medium">- Proverbs 22:6</p>
            </div>
          </div>
        </div>
      </section>

      {/* Classes Section */}
      <section id="classes" className="py-20 bg-gradient-to-br from-blue-50 to-purple-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Age-Appropriate Classes"
            subtitle="Tailored programs for every developmental stage"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {classes.map((classItem, index) => (
              <div key={index} className="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div className={`w-16 h-16 bg-gradient-to-r ${classItem.color} rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform`}>
                  <classItem.icon className="text-white" size={32} />
                </div>
                <h3 className="font-blackadder text-2xl font-bold text-gray-900 mb-2">{classItem.name}</h3>
                <p className="text-gold font-semibold mb-4">{classItem.age}</p>
                <p className="text-gray-600 mb-6">{classItem.description}</p>
                <div className="space-y-2">
                  {classItem.features.map((feature, idx) => (
                    <div key={idx} className="flex items-center gap-2">
                      <div className="w-2 h-2 bg-gradient-gold rounded-full"></div>
                      <span className="text-sm text-gray-600">{feature}</span>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Activities Section */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Our Activities"
            subtitle="Fun, faith, and friendship in every session"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            {activities.map((activity, index) => (
              <div key={index} className="group">
                <div className="relative overflow-hidden rounded-2xl mb-6">
                  <img
                    src={activity.image}
                    alt={activity.title}
                    className="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                  <div className="absolute bottom-4 left-4">
                    <div className="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                      <activity.icon className="text-gold" size={24} />
                    </div>
                  </div>
                </div>
                <h3 className="font-blackadder text-xl font-bold text-gray-900 mb-2">{activity.title}</h3>
                <p className="text-gray-600">{activity.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Gallery Section */}
      <section className="py-20 bg-gradient-to-br from-purple-50 to-pink-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Gallery"
            subtitle="Moments of joy, learning, and friendship"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            {galleryImages.map((image, index) => (
              <div key={index} className="group relative overflow-hidden rounded-2xl">
                <img
                  src={image.src}
                  alt={image.alt}
                  className="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <div className="absolute bottom-0 left-0 right-0 p-6">
                    <p className="text-white font-semibold">{image.caption}</p>
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
            subtitle="Special moments your child won't want to miss"
          />
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {upcomingEvents.map((event, index) => (
              <div key={index} className="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <img
                  src={event.image}
                  alt={event.title}
                  className="w-full h-40 object-cover rounded-xl mb-4"
                />
                <div className="flex items-center gap-2 text-gold font-semibold mb-2">
                  <Calendar size={16} />
                  {event.date}
                </div>
                <div className="flex items-center gap-2 text-gray-600 text-sm mb-3">
                  <Clock size={14} />
                  {event.time}
                </div>
                <h3 className="font-blackadder text-xl font-bold text-gray-900 mb-2">{event.title}</h3>
                <p className="text-gray-600 text-sm">{event.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-20 bg-gradient-to-br from-yellow-50 to-orange-50">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="What Parents Say"
            subtitle="Real stories from our church family"
          />
          <div className="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {testimonials.map((testimonial, index) => (
              <div key={index} className="bg-white rounded-2xl p-8 shadow-lg">
                <div className="flex mb-4">
                  {[...Array(testimonial.rating)].map((_, i) => (
                    <Star key={i} className="w-5 h-5 text-yellow-400 fill-current" />
                  ))}
                </div>
                <p className="text-gray-600 mb-6 italic">"{testimonial.content}"</p>
                <div>
                  <p className="font-bold text-gray-900">{testimonial.name}</p>
                  <p className="text-sm text-gray-500">{testimonial.role}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section className="py-20 bg-gradient-gold">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <h2 className="font-blackadder text-3xl md:text-4xl font-bold text-primary mb-6">
              Ready to Join Our Children's Ministry?
            </h2>
            <p className="text-primary/90 text-lg mb-8">
              Give your child the gift of faith, friendship, and fun in a safe, loving environment.
            </p>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-white text-gold font-bold rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300"
              >
                <Users className="mr-2" size={20} />
                Register Your Child
              </Link>
              <Link 
                to="/contact" 
                className="inline-flex items-center justify-center px-8 py-4 bg-primary/20 text-primary font-bold rounded-full border-2 border-primary/30 hover:bg-primary/30 transition-all duration-300"
              >
                <Mail className="mr-2" size={20} />
                Learn More
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Information */}
      <section className="py-20 bg-white">
        <div className="container mx-auto px-4">
          <SectionHeading
            title="Get in Touch"
            subtitle="We'd love to hear from you"
          />
          <div className="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <Phone className="text-primary" size={24} />
              </div>
              <h3 className="font-bold text-gray-900 mb-2">Call Us</h3>
              <p className="text-gray-600">+256 753 244480</p>
            </div>
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <Mail className="text-primary" size={24} />
              </div>
              <h3 className="font-bold text-gray-900 mb-2">Email Us</h3>
              <a href="mailto:childrenministry@salemdominionministries.com" className="text-gold hover:underline">
                childrenministry@salemdominionministries.com
              </a>
            </div>
            <div className="text-center">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center mx-auto mb-4">
                <MapPin className="text-primary" size={24} />
              </div>
              <h3 className="font-bold text-gray-900 mb-2">Visit Us</h3>
              <p className="text-gray-600">Iganga Municipality, Uganda</p>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default ChildrenMinistry;
