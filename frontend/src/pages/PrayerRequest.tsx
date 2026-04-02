import { useState, type FormEvent } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { Mail, Phone, User, MessageSquare, Send, Loader2, Heart, Church } from "lucide-react";
import { toast } from "@/hooks/use-toast";

type PrayerForm = {
  name: string;
  email: string;
  phone: string;
  prayerRequest: string;
  isAnonymous: boolean;
};

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_REGEX = /^\+?[\d\s\-\(\)]+$/;

const PrayerRequest = () => {
  const [form, setForm] = useState<PrayerForm>({
    name: "",
    email: "",
    phone: "",
    prayerRequest: "",
    isAnonymous: false,
  });

  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState("");
  const [error, setError] = useState("");

  const validate = () => {
    if (!form.isAnonymous && form.name.trim().length < 2) {
      return "Please enter your full name.";
    }
    if (form.email && !EMAIL_REGEX.test(form.email)) {
      return "Please enter a valid email address.";
    }
    if (form.phone && !PHONE_REGEX.test(form.phone)) {
      return "Please enter a valid phone number.";
    }
    if (form.prayerRequest.trim().length < 10) {
      return "Please provide more details for your prayer request (minimum 10 characters).";
    }
    if (form.prayerRequest.trim().length > 1000) {
      return "Prayer request is too long (maximum 1000 characters).";
    }
    return "";
  };

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();

    const validationError = validate();
    if (validationError) {
      setError(validationError);
      return;
    }

    setLoading(true);
    setError("");
    setSuccess("");

    try {
      const response = await fetch("/api/prayers", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name: form.isAnonymous ? "Anonymous" : form.name,
          email: form.email,
          phone: form.phone,
          prayerRequest: form.prayerRequest,
          isAnonymous: form.isAnonymous,
          submittedAt: new Date().toISOString(),
        }),
      });

      if (!response.ok) {
        throw new Error("Failed to submit prayer request");
      }

      setSuccess("Your prayer request has been received. Our prayer team will be praying for you!");
      setForm({
        name: "",
        email: "",
        phone: "",
        prayerRequest: "",
        isAnonymous: false,
      });

      toast({
        title: "Prayer Request Submitted",
        description: "Your prayer request has been received successfully.",
      });
    } catch (err) {
      setError("Failed to submit prayer request. Please try again or contact us directly.");
      
      // Fallback to email
      const subject = encodeURIComponent("[Prayer Request] " + (form.isAnonymous ? "Anonymous" : form.name));
      const body = encodeURIComponent(
        `Name: ${form.isAnonymous ? "Anonymous" : form.name}\nEmail: ${form.email || "Not provided"}\nPhone: ${form.phone || "Not provided"}\n\nPrayer Request:\n${form.prayerRequest}`
      );
      window.open(`mailto:visit@salemdominionministries.com?subject=${subject}&body=${body}`, "_blank");
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (field: keyof PrayerForm, value: string | boolean) => {
    setForm(prev => ({ ...prev, [field]: value }));
    setError("");
    setSuccess("");
  };

  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero 
        title="Prayer Request" 
        subtitle="Let us pray with you and for you" 
      />

      <section className="py-20 bg-gradient-heavenly relative overflow-hidden">
        <div className="absolute inset-0 faith-pattern opacity-30"></div>
        <div className="container mx-auto px-4 max-w-4xl relative z-10">
          {/* Prayer Request Form */}
          <div className="bg-gradient-premium rounded-2xl p-8 md:p-12 border border-border shadow-premium card-hover-3d">
            <div className="text-center mb-8">
              <div className="w-20 h-20 bg-gradient-divine rounded-full flex items-center justify-center mx-auto mb-4 shadow-divine animate-float-slow">
                <Heart className="w-10 h-10 text-white" />
              </div>
              <h2 className="font-heading text-3xl font-bold text-gradient-gold mb-3 animate-fade-in">
                Submit Your Prayer Request
              </h2>
              <p className="text-muted-foreground text-lg animate-slide-up">
                Our prayer team is here to stand with you in prayer. Share your needs and concerns with us.
              </p>
            </div>

            {error && (
              <div className="mb-6 p-4 bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg animate-fade-in">
                <p className="text-red-800 text-sm font-medium">{error}</p>
              </div>
            )}

            {success && (
              <div className="mb-6 p-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg animate-fade-in">
                <p className="text-green-800 text-sm font-medium">{success}</p>
              </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-6">
              {/* Anonymous Checkbox */}
              <div className="flex items-center gap-3">
                <input
                  type="checkbox"
                  id="isAnonymous"
                  checked={form.isAnonymous}
                  onChange={(e) => handleInputChange("isAnonymous", e.target.checked)}
                  className="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                />
                <label htmlFor="isAnonymous" className="text-sm text-muted-foreground">
                  Submit prayer request anonymously
                </label>
              </div>

              {/* Name */}
              {!form.isAnonymous && (
                <div>
                  <label htmlFor="name" className="block text-sm font-medium text-foreground mb-2">
                    <User className="inline w-4 h-4 mr-1" />
                    Your Name *
                  </label>
                  <input
                    type="text"
                    id="name"
                    value={form.name}
                    onChange={(e) => handleInputChange("name", e.target.value)}
                    className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                    placeholder="Enter your full name"
                    required={!form.isAnonymous}
                  />
                </div>
              )}

              {/* Email */}
              <div>
                <label htmlFor="email" className="block text-sm font-medium text-foreground mb-2">
                  <Mail className="inline w-4 h-4 mr-1" />
                  Email Address
                </label>
                <input
                  type="email"
                  id="email"
                  value={form.email}
                  onChange={(e) => handleInputChange("email", e.target.value)}
                  className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                  placeholder="your.email@example.com (optional)"
                />
              </div>

              {/* Phone */}
              <div>
                <label htmlFor="phone" className="block text-sm font-medium text-foreground mb-2">
                  <Phone className="inline w-4 h-4 mr-1" />
                  Phone Number
                </label>
                <input
                  type="tel"
                  id="phone"
                  value={form.phone}
                  onChange={(e) => handleInputChange("phone", e.target.value)}
                  className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                  placeholder="+256 XXX XXX XXX (optional)"
                />
              </div>

              {/* Prayer Request */}
              <div>
                <label htmlFor="prayerRequest" className="block text-sm font-medium text-foreground mb-2">
                  <MessageSquare className="inline w-4 h-4 mr-1" />
                  Prayer Request *
                </label>
                <textarea
                  id="prayerRequest"
                  value={form.prayerRequest}
                  onChange={(e) => handleInputChange("prayerRequest", e.target.value)}
                  rows={6}
                  className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background resize-none"
                  placeholder="Share your prayer request here. Our team will pray for your needs..."
                  required
                />
                <p className="text-xs text-muted-foreground mt-1">
                  {form.prayerRequest.length}/1000 characters
                </p>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={loading}
                className="w-full py-4 px-6 bg-gradient-gold text-white font-bold rounded-xl hover:shadow-divine transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 text-lg button-glow group"
              >
                {loading ? (
                  <>
                    <Loader2 className="w-5 h-5 animate-spin" />
                    Submitting...
                  </>
                ) : (
                  <>
                    <Send className="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" />
                    Submit Prayer Request
                  </>
                )}
              </button>
            </form>
          </div>

          {/* Prayer Promise */}
          <div className="mt-16 bg-gradient-divine rounded-2xl p-8 border border-border shadow-premium card-hover-3d">
            <div className="flex items-start gap-6">
              <div className="w-16 h-16 bg-gradient-gold rounded-full flex items-center justify-center shrink-0 shadow-gold-intense animate-float-medium">
                <Church className="w-8 h-8 text-white" />
              </div>
              <div>
                <h3 className="font-heading text-2xl font-bold text-gradient-heavenly mb-3">
                  We Are Here to Pray With You
                </h3>
                <p className="text-gradient-gold text-lg mb-6 font-medium italic">
                  "Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God." - Philippians 4:6
                </p>
                <div className="grid md:grid-cols-2 gap-4">
                  <div className="space-y-3">
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow"></div>
                      <p className="text-muted-foreground">Our prayer team reviews every request personally</p>
                    </div>
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow" style={{animationDelay: '0.5s'}}></div>
                      <p className="text-muted-foreground">Your requests are kept confidential</p>
                    </div>
                  </div>
                  <div className="space-y-3">
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow" style={{animationDelay: '1s'}}></div>
                      <p className="text-muted-foreground">We believe in the power of united prayer</p>
                    </div>
                    <div className="flex items-center gap-3">
                      <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow" style={{animationDelay: '1.5s'}}></div>
                      <p className="text-muted-foreground">Join us for midweek prayer on Wednesdays at 5:30 PM</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default PrayerRequest;
