import { useState, type FormEvent, type ReactNode } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { MapPin, Phone, Mail, Clock, Send, Loader2 } from "lucide-react";
import { toast } from "@/hooks/use-toast";

type ContactForm = {
  name: string;
  email: string;
  subject: string;
  message: string;
};

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const API_ENDPOINT = import.meta.env.VITE_CONTACT_API_URL || "/api/contact";

const Contact = () => {
  const [form, setForm] = useState<ContactForm>({
    name: "",
    email: "",
    subject: "",
    message: "",
  });

  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState("");
  const [error, setError] = useState("");

  const validate = () => {
    if (form.name.trim().length < 2) return "Please enter your full name.";
    if (!EMAIL_REGEX.test(form.email)) return "Please enter a valid email address.";
    if (form.subject.trim().length < 3) return "Please enter a clear subject.";
    if (form.message.trim().length < 10) return "Your message is too short.";
    return "";
  };

  const openMailFallback = () => {
    const subject = encodeURIComponent(`[Website Contact] ${form.subject}`);
    const body = encodeURIComponent(
      `Name: ${form.name}\nEmail: ${form.email}\n\nMessage:\n${form.message}`,
    );
    window.open(`mailto:info@salemdominionministries.org?subject=${subject}&body=${body}`, "_blank");
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();

    const validationError = validate();
    if (validationError) {
      setError(validationError);
      setSuccess("");
      return;
    }

    setLoading(true);
    setSuccess("");
    setError("");

    try {
      const controller = new AbortController();
      const timeout = setTimeout(() => controller.abort(), 10000);

      const res = await fetch(API_ENDPOINT, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(form),
        signal: controller.signal,
      });
      clearTimeout(timeout);

      if (!res.ok) throw new Error("Failed to submit contact form");

      setSuccess("Message sent successfully ✅");
      setForm({ name: "", email: "", subject: "", message: "" });
      toast({
        title: "Message sent",
        description: "Thank you for contacting us. We will get back to you soon.",
      });
    } catch {
      setError("Online form is unavailable right now. Please use email fallback below.");
      toast({
        title: "Form unavailable",
        description: "We opened your email app so you can still send your message.",
        variant: "destructive",
      });
      openMailFallback();
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero title="Contact Us" subtitle="We'd love to hear from you" />

      <section className="py-20 bg-background relative overflow-hidden">
        {/* Animated background elements */}
        <div className="absolute inset-0 bg-gradient-to-br from-gold/5 via-transparent to-navy/5 animate-pulse"></div>
        <div className="absolute top-20 left-20 w-40 h-40 bg-gradient-gold rounded-full opacity-8 blur-3xl floating"></div>
        <div className="absolute bottom-20 right-20 w-48 h-48 bg-gradient-navy rounded-full opacity-8 blur-3xl floating-delayed"></div>

        <div className="container mx-auto px-4 max-w-6xl relative z-10">
          <div className="grid lg:grid-cols-2 gap-12">

            {/* CONTACT INFO */}
            <div className="glassmorphism-enhanced rounded-2xl p-8 shadow-gold-enhanced slide-in-left">
              <h2 className="font-blackadder text-3xl font-bold mb-8 text-gradient-gold animate-fade-in-up">Get in Touch</h2>

              <div className="space-y-6 mb-10">
                {/* Location */}
                <Info icon={<MapPin size={20} />} title="Location">
                  Salem Dominion Ministries, Iganga Municipality, Uganda
                </Info>

                {/* Phone */}
                <Info icon={<Phone size={20} />} title="Phone">
                  <a href="tel:+256753244480" className="hover:underline text-gold hover:text-gold/80 transition-colors">
                    +256 753 244480
                  </a>
                  <p className="text-sm">
                    <a href="https://wa.me/256753244480" target="_blank" rel="noopener noreferrer" className="text-gold hover:text-gold/80 transition-colors">
                      WhatsApp Chat
                    </a>
                  </p>
                </Info>

                {/* Email */}
                <Info icon={<Mail size={20} />} title="Email">
                  <a href="mailto:info@salemdominionministries.org" className="hover:underline text-gold hover:text-gold/80 transition-colors">
                    info@salemdominionministries.org
                  </a>
                </Info>

                {/* Bank */}
                <Info icon={<span>💳</span>} title="Bank Details">
                  <div className="text-sm">Bank Name: Equity Bank</div>
                  <div className="text-sm">A/c Name: Salem Dominion Ministries</div>
                  <div className="font-semibold text-gold">A/c No: 1017203227662</div>
                </Info>

                {/* Service */}
                <Info icon={<Clock size={20} />} title="Service Times">
                  Sunday – 8:00 AM & 10:30 AM  
                  <div>Wednesday – 5:30 PM</div>
                </Info>
              </div>

              {/* Map */}
              <div className="rounded-xl overflow-hidden border shadow-gold-enhanced">
                <iframe
                  src="https://www.google.com/maps?q=Iganga%20Uganda&output=embed"
                  width="100%"
                  height="250"
                  style={{ border: 0 }}
                  loading="lazy"
                  title="Map of Iganga, Uganda"
                  className="rounded-xl"
                />
              </div>
            </div>

            {/* FORM */}
            <div className="glassmorphism-enhanced rounded-2xl p-8 shadow-gold-enhanced slide-in-right">
              <h2 className="font-blackadder text-3xl font-bold mb-8 text-gradient-gold animate-fade-in-up">Send a Message</h2>

              <form
                onSubmit={handleSubmit}
                className="space-y-5"
              >
                <Input label="Full Name" value={form.name}
                  onChange={(v)=>setForm({...form,name:v})} />

                <Input label="Email" type="email" value={form.email}
                  onChange={(v)=>setForm({...form,email:v})} />

                <Input label="Subject" value={form.subject}
                  onChange={(v)=>setForm({...form,subject:v})} />

                <Textarea label="Message" value={form.message}
                  onChange={(v)=>setForm({...form,message:v})} />

                {success && <p className="text-green-600 text-sm animate-fade-in-up">{success}</p>}
                {error && <p className="text-red-600 text-sm animate-fade-in-up">{error}</p>}

                <p className="text-xs text-muted-foreground font-gabriola">
                  If the form does not submit, you can email us directly at{" "}
                  <a href="mailto:info@salemdominionministries.org" className="underline text-gold hover:text-gold/80 transition-colors">
                    info@salemdominionministries.org
                  </a>
                  .
                </p>

                <button
                  disabled={loading}
                  className="w-full flex items-center justify-center gap-2 px-6 py-4 bg-gradient-gold text-navy font-bold rounded-full hover:scale-105 transition-all duration-300 shadow-gold-enhanced hover:shadow-gold-intense button-glow disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {loading ? <Loader2 className="animate-spin" /> : <Send size={18} />}
                  {loading ? "Sending..." : "Send Message"}
                </button>
              </form>
            </div>

          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Contact;

/* ---------- SMALL COMPONENTS ---------- */

type InfoProps = {
  icon: ReactNode;
  title: string;
  children: ReactNode;
};

type InputProps = {
  label: string;
  value: string;
  onChange: (value: string) => void;
  type?: "text" | "email";
};

type TextareaProps = {
  label: string;
  value: string;
  onChange: (value: string) => void;
};

const Info = ({ icon, title, children }: InfoProps) => (
  <div className="flex gap-4">
    <div className="w-12 h-12 rounded-full bg-gradient-gold flex items-center justify-center shrink-0">
      {icon}
    </div>
    <div>
      <h4 className="font-blackadder font-semibold">{title}</h4>
      <div className="font-gabriola text-muted-foreground">{children}</div>
    </div>
  </div>
);

const Input = ({ label, value, onChange, type = "text" }: InputProps) => {
  const id = label.toLowerCase().replace(/\s+/g, "-");

  return (
  <div>
    <label htmlFor={id} className="block text-sm mb-2">{label}</label>
    <input
      id={id}
      type={type}
      required
      value={value}
      onChange={(e)=>onChange(e.target.value)}
      placeholder={label}
      className="w-full px-4 py-3 rounded-md border"
    />
  </div>
  );
};

const Textarea = ({ label, value, onChange }: TextareaProps) => {
  const id = label.toLowerCase().replace(/\s+/g, "-");

  return (
  <div>
    <label htmlFor={id} className="block text-sm mb-2">{label}</label>
    <textarea
      id={id}
      rows={5}
      required
      value={value}
      onChange={(e)=>onChange(e.target.value)}
      placeholder={label}
      className="w-full px-4 py-3 rounded-md border resize-none"
    />
  </div>
  );
};