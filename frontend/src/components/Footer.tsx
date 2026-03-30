import { Link } from "react-router-dom";
import { Phone, Mail, MapPin } from "lucide-react"; // keep only valid icons
import { FaFacebook, FaYoutube, FaInstagram } from "react-icons/fa"; // brand icons
import logo from "../assets/logo.jpeg";
import { EXTERNAL_LINKS } from "@/utils/api";

const Footer = () => {
  return (
    <footer className="bg-gradient-navy relative overflow-hidden">
      <div className="absolute inset-0 faith-pattern opacity-20"></div>
      <div className="container mx-auto px-4 py-16 relative z-10">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          {/* About */}
          <div className="animate-fade-in">
            <div className="flex items-center gap-3 mb-4">
              <div className="relative group">
                <div className="absolute -inset-1 bg-gradient-gold rounded-xl opacity-0 group-hover:opacity-75 transition-opacity duration-300"></div>
                <img
                  src={logo}
                  alt="Salem Dominion Ministries Logo"
                  className="relative w-12 h-12 object-contain rounded-xl border-2 border-white/20"
                />
              </div>
              <div>
                <h3 className="font-heading text-xl font-bold text-gradient-gold leading-tight">
                  Salem Dominion
                </h3>
                <span className="text-gold text-xs tracking-widest uppercase font-semibold">
                  Ministries
                </span>
              </div>
            </div>
            <p className="text-primary-foreground/80 text-sm leading-relaxed">
              Building a Christ-centered community that impacts generations spiritually, socially, and economically.
            </p>
          </div>

          {/* Quick Links */}
          <div className="animate-fade-in" style={{animationDelay: '0.1s'}}>
            <h4 className="font-heading text-gradient-gold text-lg mb-4 font-semibold">Quick Links</h4>
            <div className="flex flex-col gap-2">
              {[
                { name: "About Us", path: "/about" },
                { name: "Sermons", path: "/sermons" },
                { name: "Events", path: "/events" },
                { name: "Prayer Request", path: "/prayer-request" },
                { name: "Book Prayer Meeting", path: "/book-prayer-meeting" },
                { name: "Donate", path: "/donate" },
                { name: "Contact", path: "/contact" },
              ].map((link) => (
                <Link
                  key={link.path}
                  to={link.path}
                  className="text-primary-foreground/80 hover:text-gradient-gold text-sm transition-all duration-300 hover:translate-x-1 group"
                >
                  <span className="group-hover:text-gradient-gold transition-colors duration-300">{link.name}</span>
                </Link>
              ))}
            </div>
          </div>

          {/* Service Times */}
          <div className="animate-fade-in" style={{animationDelay: '0.2s'}}>
            <h4 className="font-heading text-gradient-gold text-lg mb-4 font-semibold">Service Times</h4>
            <div className="flex flex-col gap-3 text-sm">
              <div className="group hover:bg-white/5 p-2 rounded-lg transition-all duration-300">
                <p className="font-medium text-primary-foreground group-hover:text-gradient-gold transition-colors duration-300">Sunday First Service</p>
                <p className="text-primary-foreground/70">8:00 AM</p>
              </div>
              <div className="group hover:bg-white/5 p-2 rounded-lg transition-all duration-300">
                <p className="font-medium text-primary-foreground group-hover:text-gradient-gold transition-colors duration-300">Sunday Second Service</p>
                <p className="text-primary-foreground/70">10:30 AM</p>
              </div>
              <div className="group hover:bg-white/5 p-2 rounded-lg transition-all duration-300">
                <p className="font-medium text-primary-foreground group-hover:text-gradient-gold transition-colors duration-300">Midweek Prayer</p>
                <p className="text-primary-foreground/70">Wednesday 5:30 PM</p>
              </div>
            </div>
          </div>

          {/* Contact */}
          <div className="animate-fade-in" style={{animationDelay: '0.3s'}}>
            <h4 className="font-heading text-gradient-gold text-lg mb-4 font-semibold">Contact Us</h4>
            <div className="flex flex-col gap-3 text-sm">
              <div className="flex items-start gap-2 group hover:bg-white/5 p-2 rounded-lg transition-all duration-300">
                <MapPin size={16} className="text-gold mt-0.5 group-hover:animate-pulse-slow" />
                <span className="text-primary-foreground/80 group-hover:text-primary-foreground transition-colors duration-300">Iganga Municipality, Uganda</span>
              </div>

              <div className="flex items-center gap-2 group hover:bg-white/5 p-2 rounded-lg transition-all duration-300">
                <Phone size={16} className="text-gold group-hover:animate-pulse-slow" />
                <a
                  href={EXTERNAL_LINKS.PHONE}
                  className="text-primary-foreground/80 hover:text-gradient-gold transition-all duration-300"
                >
                  +256 753 244480
                </a>
              </div>

              <div className="flex items-center gap-2 group hover:bg-white/5 p-2 rounded-lg transition-all duration-300">
                <Mail size={16} className="text-gold group-hover:animate-pulse-slow" />
                <a
                  href={EXTERNAL_LINKS.EMAIL}
                  className="text-primary-foreground/80 hover:text-gradient-gold transition-all duration-300"
                >
                  visit@salemdominionministries.com
                </a>
              </div>

              <div className="flex items-center gap-4 mt-4">
                <a
                  href={EXTERNAL_LINKS.FACEBOOK}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-gradient-gold hover:shadow-divine transition-all duration-300 group"
                  aria-label="Visit our Facebook page"
                >
                  <FaFacebook size={18} className="text-white group-hover:scale-110 transition-transform duration-300" />
                </a>
                <a
                  href={EXTERNAL_LINKS.YOUTUBE}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-gradient-gold hover:shadow-divine transition-all duration-300 group"
                  aria-label="Visit our YouTube channel"
                >
                  <FaYoutube size={18} className="text-white group-hover:scale-110 transition-transform duration-300" />
                </a>
                <a
                  href={EXTERNAL_LINKS.INSTAGRAM}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-gradient-gold hover:shadow-divine transition-all duration-300 group"
                  aria-label="Visit our Instagram page"
                >
                  <FaInstagram size={18} className="text-white group-hover:scale-110 transition-transform duration-300" />
                </a>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="border-t border-navy-light/30 mt-12 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-primary-foreground/50 text-sm">
            2025 Salem Dominion Ministries. All rights reserved.
          </p>
        </div>
      </div>

      {/* Church WhatsApp Floating */}
      <a
        href={EXTERNAL_LINKS.WHATSAPP}
        target="_blank"
        rel="noopener noreferrer"
        className="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform"
        aria-label="Chat with Church on WhatsApp"
      >
        {/* WhatsApp SVG */}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" className="w-7 h-7 fill-white">
          <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
      </a>

      {/* Developer Support WhatsApp */}
      <a
        href={EXTERNAL_LINKS.DEVELOPER_WHATSAPP}
        target="_blank"
        rel="noopener noreferrer"
        className="fixed bottom-6 left-6 z-50 w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform"
        aria-label="Contact Developer for Support"
        title="Contact Developer for Technical Support"
      >
        {/* Support Icon SVG */}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" className="w-5 h-5 fill-white">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
        </svg>
      </a>

    </footer>
  );
};

export default Footer;