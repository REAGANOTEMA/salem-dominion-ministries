import { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { Menu, X } from "lucide-react";
import logo from "@/assets/logo.jpeg"; // ✅ FIXED

const navLinks = [
  { name: "Home", path: "/" },
  { name: "About", path: "/about" },
  { name: "Leadership", path: "/leadership" },
  { name: "Ministries", path: "/ministries" },
  { name: "Children's Ministry", path: "/children-ministry" },
  { name: "Sermons", path: "/sermons" },
  { name: "Events", path: "/events" },
  { name: "Prayer Request", path: "/prayer-request" },
  { name: "Book Pastor Call", path: "/book-pastor-call" },
  { name: "Donate", path: "/donate" },
  { name: "Gallery", path: "/gallery" },
  { name: "Blog", path: "/blog" },
  { name: "Contact", path: "/contact" },
];

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const location = useLocation();

  useEffect(() => {
    setIsOpen(false);
  }, [location.pathname]);

  useEffect(() => {
    const onEscape = (e: KeyboardEvent) => {
      if (e.key === "Escape") setIsOpen(false);
    };

    window.addEventListener("keydown", onEscape);
    return () => window.removeEventListener("keydown", onEscape);
  }, []);

  return (
    <nav className="fixed top-0 left-0 right-0 z-50 bg-brand-ribbon backdrop-blur-md border-b border-gold/30 shadow-[0_6px_24px_-16px_rgba(0,0,0,0.65)]">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-20">
          
          {/* ✅ Logo */}
          <Link to="/" className="flex items-center gap-3 group">
            <div className="logo-frame p-1.5 transition-transform duration-300 group-hover:scale-[1.03]">
              <img
                src={logo}
                alt="Salem Dominion Ministries Logo"
                className="h-11 w-11 object-cover rounded-lg"
              />
            </div>

            <div className="flex flex-col">
              <span className="font-heading text-primary-foreground text-lg font-bold leading-tight tracking-[0.01em]">
                Salem Dominion
              </span>
              <span className="text-gold-light text-xs tracking-[0.22em] uppercase">
                Ministries
              </span>
            </div>
          </Link>

          {/* Desktop Nav */}
          <div className="hidden lg:flex items-center gap-1">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                className={`px-3 py-2 text-sm font-medium rounded-md transition-colors ${
                  location.pathname === link.path
                    ? "bg-gradient-gold text-primary shadow-gold"
                    : "text-primary-foreground/90 hover:text-gold-light hover:bg-navy-light/45"
                }`}
              >
                {link.name}
              </Link>
            ))}
          </div>

          {/* Mobile Toggle */}
          <button
            onClick={() => setIsOpen(!isOpen)}
            className="lg:hidden text-primary-foreground p-2"
            aria-label="Toggle menu"
            aria-expanded={isOpen ? "true" : "false"}
            aria-controls="mobile-nav"
          >
            {isOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>
      </div>

      {/* Mobile Nav */}
      {isOpen && (
        <div id="mobile-nav" className="lg:hidden bg-brand-ribbon border-t border-gold/25 animate-fade-in">
          <div className="container mx-auto px-4 py-4 flex flex-col gap-1">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                onClick={() => setIsOpen(false)}
                className={`px-4 py-3 rounded-md text-sm font-medium transition-colors ${
                  location.pathname === link.path
                    ? "bg-gradient-gold text-primary shadow-gold"
                    : "text-primary-foreground/90 hover:text-gold-light hover:bg-navy-light/45"
                }`}
              >
                {link.name}
              </Link>
            ))}
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;