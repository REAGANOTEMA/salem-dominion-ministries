import { useState, type FormEvent } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { Calendar, Clock, Mail, Phone, User, MessageSquare, Send, Loader2, CheckCircle, AlertCircle } from "lucide-react";
import { toast } from "@/hooks/use-toast";

type BookingForm = {
  name: string;
  email: string;
  phone: string;
  prayerType: string;
  date: string;
  time: string;
  message: string;
  urgency: string;
};

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_REGEX = /^\+?[\d\s\-\(\)]+$/;

const BookPrayerMeeting = () => {
  const [form, setForm] = useState<BookingForm>({
    name: "",
    email: "",
    phone: "",
    prayerType: "personal",
    date: "",
    time: "",
    message: "",
    urgency: "normal",
  });

  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState("");
  const [error, setError] = useState("");
  const [selectedDate, setSelectedDate] = useState<Date | null>(null);
  const [availableSlots, setAvailableSlots] = useState<string[]>([]);

  const prayerTypes = [
    { value: "personal", label: "Personal Prayer", description: "One-on-one prayer session" },
    { value: "family", label: "Family Prayer", description: "Prayer for family matters" },
    { value: "healing", label: "Healing Prayer", description: "Prayer for physical/emotional healing" },
    { value: "guidance", label: "Spiritual Guidance", description: "Seeking divine direction" },
    { value: "deliverance", label: "Deliverance", description: "Spiritual deliverance prayer" },
    { value: "thanksgiving", label: "Thanksgiving", description: "Gratitude and testimony sharing" },
  ];

  const urgencyLevels = [
    { value: "normal", label: "Normal", color: "text-blue-600", description: "Standard booking" },
    { value: "urgent", label: "Urgent", color: "text-orange-600", description: "Requires urgent attention" },
    { value: "emergency", label: "Emergency", color: "text-red-600", description: "Critical situation" },
  ];

  const timeSlots = [
    "08:00 AM", "09:00 AM", "10:00 AM", "11:00 AM",
    "02:00 PM", "03:00 PM", "04:00 PM", "05:00 PM",
    "06:00 PM", "07:00 PM"
  ];

  // Generate available dates (next 30 days, excluding weekends)
  const generateAvailableDates = () => {
    const dates = [];
    const today = new Date();
    
    for (let i = 1; i <= 30; i++) {
      const date = new Date(today);
      date.setDate(today.getDate() + i);
      
      // Skip weekends (Saturday = 6, Sunday = 0)
      if (date.getDay() !== 0 && date.getDay() !== 6) {
        dates.push(date);
      }
    }
    
    return dates;
  };

  const availableDates = generateAvailableDates();

  const handleDateSelect = (date: Date) => {
    setSelectedDate(date);
    const dateStr = date.toISOString().split('T')[0];
    setForm(prev => ({ ...prev, date: dateStr, time: "" }));
    
    // Simulate available slots for selected date
    const dayOfWeek = date.getDay();
    const slots = dayOfWeek >= 1 && dayOfWeek <= 5 
      ? timeSlots // Weekdays have all slots
      : timeSlots.slice(0, 6); // Limited slots on other days
    
    setAvailableSlots(slots);
  };

  const validate = () => {
    if (form.name.trim().length < 2) {
      return "Please enter your full name.";
    }
    if (!EMAIL_REGEX.test(form.email)) {
      return "Please enter a valid email address.";
    }
    if (form.phone && !PHONE_REGEX.test(form.phone)) {
      return "Please enter a valid phone number.";
    }
    if (!form.date) {
      return "Please select a date for your prayer meeting.";
    }
    if (!form.time) {
      return "Please select a time slot.";
    }
    if (form.message.trim().length < 10) {
      return "Please provide more details about your prayer needs (minimum 10 characters).";
    }
    if (form.message.trim().length > 1000) {
      return "Message is too long (maximum 1000 characters).";
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
      // Prepare booking data
      const bookingData = {
        ...form,
        submittedAt: new Date().toISOString(),
        bookingId: `PRAYER-${Date.now()}`,
      };

      // Send to backend API
      const response = await fetch("/api/prayer-booking", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(bookingData),
      });

      if (!response.ok) {
        throw new Error("Failed to submit booking request");
      }

      setSuccess("Your prayer meeting has been booked successfully! You will receive a confirmation email shortly.");
      setForm({
        name: "",
        email: "",
        phone: "",
        prayerType: "personal",
        date: "",
        time: "",
        message: "",
        urgency: "normal",
      });
      setSelectedDate(null);
      setAvailableSlots([]);

      toast({
        title: "Prayer Meeting Booked!",
        description: "Your appointment has been scheduled successfully.",
      });

    } catch (err) {
      setError("Failed to book prayer meeting. Please try again or contact us directly.");
      
      // Fallback to email
      const subject = encodeURIComponent(`[Prayer Meeting Booking] ${form.name} - ${form.date} ${form.time}`);
      const body = encodeURIComponent(
        `Booking Details:\n` +
        `Name: ${form.name}\n` +
        `Email: ${form.email}\n` +
        `Phone: ${form.phone}\n` +
        `Prayer Type: ${prayerTypes.find(t => t.value === form.prayerType)?.label}\n` +
        `Date: ${form.date}\n` +
        `Time: ${form.time}\n` +
        `Urgency: ${urgencyLevels.find(u => u.value === form.urgency)?.label}\n\n` +
        `Prayer Request:\n${form.message}\n\n` +
        `Booking ID: PRAYER-${Date.now()}`
      );
      window.open(`mailto:visit@salemdominionministries.com?subject=${subject}&body=${body}`, "_blank");
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (field: keyof BookingForm, value: string) => {
    setForm(prev => ({ ...prev, [field]: value }));
    setError("");
    setSuccess("");
  };

  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero 
        title="Book Prayer Meeting" 
        subtitle="Schedule a personal prayer session with our pastoral team" 
      />

      <section className="py-20 bg-gradient-heavenly relative overflow-hidden">
        <div className="absolute inset-0 faith-pattern opacity-30"></div>
        <div className="container mx-auto px-4 max-w-6xl relative z-10">
          {/* Booking Form */}
          <div className="bg-gradient-premium rounded-2xl p-8 md:p-12 border border-border shadow-premium card-hover-3d">
            <div className="text-center mb-10">
              <div className="w-24 h-24 bg-gradient-divine rounded-full flex items-center justify-center mx-auto mb-6 shadow-divine animate-float-slow">
                <Calendar className="w-12 h-12 text-white" />
              </div>
              <h2 className="font-heading text-4xl font-bold text-gradient-gold mb-4 animate-fade-in">
                Schedule Your Prayer Meeting
              </h2>
              <p className="text-muted-foreground text-lg animate-slide-up">
                Book a confidential one-on-one prayer session with our experienced pastoral team
              </p>
            </div>

            {error && (
              <div className="mb-6 p-4 bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg animate-fade-in">
                <div className="flex items-center gap-3">
                  <AlertCircle className="w-5 h-5 text-red-600" />
                  <p className="text-red-800 text-sm font-medium">{error}</p>
                </div>
              </div>
            )}

            {success && (
              <div className="mb-6 p-4 bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg animate-fade-in">
                <div className="flex items-center gap-3">
                  <CheckCircle className="w-5 h-5 text-green-600" />
                  <p className="text-green-800 text-sm font-medium">{success}</p>
                </div>
              </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-8">
              {/* Personal Information */}
              <div className="space-y-6">
                <h3 className="font-heading text-xl font-semibold text-gradient-divine flex items-center gap-2">
                  <User className="w-5 h-5" />
                  Personal Information
                </h3>
                
                <div className="grid md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="name" className="block text-sm font-medium text-foreground mb-2">
                      Full Name *
                    </label>
                    <input
                      type="text"
                      id="name"
                      value={form.name}
                      onChange={(e) => handleInputChange("name", e.target.value)}
                      className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                      placeholder="Enter your full name"
                      required
                    />
                  </div>

                  <div>
                    <label htmlFor="email" className="block text-sm font-medium text-foreground mb-2">
                      Email Address *
                    </label>
                    <input
                      type="email"
                      id="email"
                      value={form.email}
                      onChange={(e) => handleInputChange("email", e.target.value)}
                      className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                      placeholder="your.email@example.com"
                      required
                    />
                  </div>
                </div>

                <div>
                  <label htmlFor="phone" className="block text-sm font-medium text-foreground mb-2">
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
              </div>

              {/* Prayer Details */}
              <div className="space-y-6">
                <h3 className="font-heading text-xl font-semibold text-gradient-divine flex items-center gap-2">
                  <MessageSquare className="w-5 h-5" />
                  Prayer Details
                </h3>

                <div className="grid md:grid-cols-2 gap-6">
                  <div>
                    <label htmlFor="prayerType" className="block text-sm font-medium text-foreground mb-2">
                      Type of Prayer *
                    </label>
                    <select
                      id="prayerType"
                      value={form.prayerType}
                      onChange={(e) => handleInputChange("prayerType", e.target.value)}
                      className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                      required
                    >
                      {prayerTypes.map(type => (
                        <option key={type.value} value={type.value}>
                          {type.label} - {type.description}
                        </option>
                      ))}
                    </select>
                  </div>

                  <div>
                    <label htmlFor="urgency" className="block text-sm font-medium text-foreground mb-2">
                      Urgency Level *
                    </label>
                    <select
                      id="urgency"
                      value={form.urgency}
                      onChange={(e) => handleInputChange("urgency", e.target.value)}
                      className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background"
                      required
                    >
                      {urgencyLevels.map(level => (
                        <option key={level.value} value={level.value}>
                          {level.label} - {level.description}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                <div>
                  <label htmlFor="message" className="block text-sm font-medium text-foreground mb-2">
                    Prayer Request Details *
                  </label>
                  <textarea
                    id="message"
                    value={form.message}
                    onChange={(e) => handleInputChange("message", e.target.value)}
                    rows={6}
                    className="w-full px-4 py-3 border border-border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-background resize-none"
                    placeholder="Please share your prayer needs and any specific areas you'd like the pastoral team to pray about with you..."
                    required
                  />
                  <p className="text-xs text-muted-foreground mt-1">
                    {form.message.length}/1000 characters
                  </p>
                </div>
              </div>

              {/* Date and Time Selection */}
              <div className="space-y-6">
                <h3 className="font-heading text-xl font-semibold text-gradient-divine flex items-center gap-2">
                  <Calendar className="w-5 h-5" />
                  Select Date & Time *
                </h3>

                {/* Calendar */}
                <div className="bg-white/50 rounded-xl p-6 border border-border">
                  <h4 className="font-semibold text-foreground mb-4">Available Dates (Weekdays Only)</h4>
                  <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 max-h-64 overflow-y-auto">
                    {availableDates.map((date, index) => {
                      const dateStr = date.toISOString().split('T')[0];
                      const isSelected = form.date === dateStr;
                      const isToday = date.toDateString() === new Date().toDateString();
                      
                      return (
                        <button
                          key={index}
                          type="button"
                          onClick={() => handleDateSelect(date)}
                          className={`p-3 rounded-lg border-2 transition-all duration-300 text-sm font-medium ${
                            isSelected 
                              ? 'border-gold bg-gradient-gold text-white shadow-divine' 
                              : 'border-border hover:border-gold/50 hover:bg-gold/10'
                          } ${isToday ? 'ring-2 ring-gold/30' : ''}`}
                        >
                          <div className="text-xs">{date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                          <div className="text-lg font-bold">{date.getDate()}</div>
                          <div className="text-xs">{date.toLocaleDateString('en-US', { month: 'short' })}</div>
                        </button>
                      );
                    })}
                  </div>
                </div>

                {/* Time Slots */}
                {form.date && (
                  <div className="bg-white/50 rounded-xl p-6 border border-border animate-fade-in">
                    <h4 className="font-semibold text-foreground mb-4 flex items-center gap-2">
                      <Clock className="w-4 h-4" />
                      Available Time Slots
                    </h4>
                    <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
                      {availableSlots.map((slot, index) => (
                        <button
                          key={index}
                          type="button"
                          onClick={() => handleInputChange("time", slot)}
                          className={`p-3 rounded-lg border-2 transition-all duration-300 font-medium ${
                            form.time === slot 
                              ? 'border-gold bg-gradient-gold text-white shadow-divine' 
                              : 'border-border hover:border-gold/50 hover:bg-gold/10'
                          }`}
                        >
                          {slot}
                        </button>
                      ))}
                    </div>
                  </div>
                )}
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={loading || !form.date || !form.time}
                className="w-full py-4 px-6 bg-gradient-gold text-white font-bold rounded-xl hover:shadow-divine transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 text-lg button-glow group"
              >
                {loading ? (
                  <>
                    <Loader2 className="w-6 h-6 animate-spin" />
                    Booking Prayer Meeting...
                  </>
                ) : (
                  <>
                    <Send className="w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" />
                    Book Prayer Meeting
                  </>
                )}
              </button>
            </form>
          </div>

          {/* Additional Information */}
          <div className="mt-16 bg-gradient-divine rounded-2xl p-8 border border-border shadow-premium card-hover-3d">
            <div className="grid md:grid-cols-2 gap-8">
              <div>
                <h3 className="font-heading text-2xl font-bold text-gradient-heavenly mb-4">
                  What to Expect
                </h3>
                <div className="space-y-3">
                  <div className="flex items-start gap-3">
                    <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow mt-2"></div>
                    <p className="text-muted-foreground">Confidential one-on-one prayer session with experienced pastors</p>
                  </div>
                  <div className="flex items-start gap-3">
                    <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow mt-2"></div>
                    <p className="text-muted-foreground">Sessions typically last 30-45 minutes</p>
                  </div>
                  <div className="flex items-start gap-3">
                    <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow mt-2"></div>
                    <p className="text-muted-foreground">Biblical guidance and personalized prayer</p>
                  </div>
                  <div className="flex items-start gap-3">
                    <div className="w-2 h-2 bg-gradient-gold rounded-full animate-pulse-slow mt-2"></div>
                    <p className="text-muted-foreground">Follow-up support and resources available</p>
                  </div>
                </div>
              </div>

              <div>
                <h3 className="font-heading text-2xl font-bold text-gradient-heavenly mb-4">
                  Contact Information
                </h3>
                <div className="space-y-4">
                  <div className="flex items-center gap-3">
                    <Mail className="w-5 h-5 text-gold" />
                    <div>
                      <p className="text-sm text-muted-foreground">Email</p>
                      <p className="font-medium">visit@salemdominionministries.com</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <Phone className="w-5 h-5 text-gold" />
                    <div>
                      <p className="text-sm text-muted-foreground">Phone</p>
                      <p className="font-medium">+256 753 244480</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <Clock className="w-5 h-5 text-gold" />
                    <div>
                      <p className="text-sm text-muted-foreground">Office Hours</p>
                      <p className="font-medium">Mon-Fri: 8:00 AM - 7:00 PM</p>
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

export default BookPrayerMeeting;
