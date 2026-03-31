import { useState, type FormEvent } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { 
  Calendar, 
  Clock, 
  Mail, 
  Phone, 
  User, 
  MessageSquare, 
  Send, 
  Loader2, 
  CheckCircle, 
  AlertCircle,
  Video,
  Globe,
  Users,
  Heart,
  BookOpen
} from "lucide-react";
import { toast } from "@/hooks/use-toast";

type BookingForm = {
  name: string;
  email: string;
  phone: string;
  bookingType: string;
  date: string;
  time: string;
  subject: string;
  description: string;
  prayerPoints: string;
};

const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const PHONE_REGEX = /^\+?[\d\s\-\(\)]+$/;

const BookPastorCall = () => {
  const [form, setForm] = useState<BookingForm>({
    name: "",
    email: "",
    phone: "",
    bookingType: "counseling",
    date: "",
    time: "",
    subject: "",
    description: "",
    prayerPoints: "",
  });

  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState("");
  const [error, setError] = useState("");
  const [selectedDate, setSelectedDate] = useState<Date | null>(null);
  const [availableSlots, setAvailableSlots] = useState<string[]>([]);
  const [bookingReference, setBookingReference] = useState("");

  const bookingTypes = [
    { value: "counseling", label: "Pastoral Counseling", description: "Spiritual guidance and counseling", icon: "🙏" },
    { value: "prayer", label: "Prayer Session", description: "Dedicated prayer time with pastor", icon: "📿" },
    { value: "guidance", label: "Spiritual Guidance", description: "Seeking divine direction for life decisions", icon: "🕊️" },
    { value: "deliverance", label: "Deliverance Ministry", description: "Spiritual deliverance and freedom", icon: "✝️" },
    { value: "thanksgiving", label: "Thanksgiving Session", description: "Share testimonies and give thanks", icon: "🎉" },
    { value: "general", label: "General Consultation", description: "Any other pastoral matters", icon: "💬" },
  ];

  const timeSlots = [
    "09:00 AM", "09:30 AM", "10:00 AM", "10:30 AM",
    "11:00 AM", "11:30 AM",
    "02:00 PM", "02:30 PM", "03:00 PM", "03:30 PM",
    "04:00 PM", "04:30 PM", "05:00 PM"
  ];

  // Generate available dates (next 30 days, excluding weekends and pastor's off days)
  const generateAvailableDates = () => {
    const dates = [];
    const today = new Date();
    
    for (let i = 1; i <= 30; i++) {
      const date = new Date(today);
      date.setDate(today.getDate() + i);
      
      const dayOfWeek = date.getDay();
      // Skip weekends (Saturday = 6, Sunday = 0) and Monday afternoons (pastor's day off)
      if (dayOfWeek !== 0 && dayOfWeek !== 6) {
        // Skip Monday afternoons (pastor's administrative day)
        if (dayOfWeek === 1 && date.getHours() >= 12) continue;
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
    
    // Check if it's a Monday (pastor's limited availability)
    const dayOfWeek = date.getDay();
    let slots = timeSlots;
    
    if (dayOfWeek === 1) {
      // Monday - morning only
      slots = timeSlots.filter(t => t.includes("AM"));
    } else if (dayOfWeek === 3) {
      // Wednesday - limited due to midweek service
      slots = timeSlots.filter(t => !t.includes("05:00 PM") && !t.includes("05:30 PM"));
    }
    
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
    if (!form.subject.trim()) {
      return "Please provide a subject for your booking.";
    }
    if (!form.date) {
      return "Please select a date for your appointment.";
    }
    if (!form.time) {
      return "Please select a time slot.";
    }
    if (form.description.trim().length < 20) {
      return "Please provide more details about your booking (minimum 20 characters).";
    }
    if (form.description.trim().length > 2000) {
      return "Description is too long (maximum 2000 characters).";
    }
    return "";
  };

  const generateBookingReference = () => {
    const timestamp = Date.now().toString(36).toUpperCase();
    const random = Math.random().toString(36).substring(2, 6).toUpperCase();
    return `SDM-${timestamp}-${random}`;
  };

  const generateGoogleMeetLink = (reference: string) => {
    // Generate a Google Meet link (in production, this would use Google Calendar API)
    const meetId = `sdm-${reference.toLowerCase().replace(/[^a-z0-9]/g, '-')}`;
    return `https://meet.google.com/${meetId}`;
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
      const reference = generateBookingReference();
      const googleMeetLink = generateGoogleMeetLink(reference);

      const bookingData = {
        ...form,
        bookingReference: reference,
        googleMeetLink: googleMeetLink,
        submittedAt: new Date().toISOString(),
      };

      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 1500));

      // In production, send to backend:
      // const response = await fetch("/api/pastor-bookings", {
      //   method: "POST",
      //   headers: { "Content-Type": "application/json" },
      //   body: JSON.stringify(bookingData),
      // });

      setBookingReference(reference);
      setSuccess("Your appointment has been booked successfully! You will receive a confirmation email with the Google Meet link shortly.");
      
      setForm({
        name: "",
        email: "",
        phone: "",
        bookingType: "counseling",
        date: "",
        time: "",
        subject: "",
        description: "",
        prayerPoints: "",
      });
      setSelectedDate(null);
      setAvailableSlots([]);

      toast({
        title: "Appointment Booked!",
        description: `Reference: ${reference}. Check your email for Google Meet link.`,
      });

    } catch (err) {
      setError("Failed to book appointment. Please try again or contact us directly.");
      
      // Fallback to email
      const subject = encodeURIComponent(`[Pastor Booking] ${form.name} - ${form.date} ${form.time}`);
      const body = encodeURIComponent(
        `Booking Request Details:\n` +
        `Name: ${form.name}\n` +
        `Email: ${form.email}\n` +
        `Phone: ${form.phone}\n` +
        `Type: ${bookingTypes.find(t => t.value === form.bookingType)?.label}\n` +
        `Date: ${form.date}\n` +
        `Time: ${form.time}\n` +
        `Subject: ${form.subject}\n\n` +
        `Description:\n${form.description}\n\n` +
        `Prayer Points:\n${form.prayerPoints || "None specified"}`
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
        title="Book a Call with Pastor" 
        subtitle="Schedule a personal Google Meet session with our Senior Pastor" 
      />

      <section className="py-20 bg-gradient-to-br from-background via-muted to-background relative overflow-hidden">
        {/* Subtle animated background */}
        <div className="absolute inset-0 bg-gradient-to-tr from-gold/3 via-transparent to-navy/3"></div>
        <div className="absolute top-20 left-20 w-32 h-32 bg-gradient-gold rounded-full opacity-3 blur-2xl animate-pulse"></div>
        <div className="absolute bottom-20 right-20 w-40 h-40 bg-gradient-navy rounded-full opacity-3 blur-2xl animate-pulse" style={{ animationDelay: '1s' }}></div>
        
        <div className="container mx-auto px-4 max-w-6xl relative z-10">
          {/* Google Meet Info Banner */}
          <div className="mb-10 bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-2xl p-6 flex items-center gap-4 animate-fade-in">
            <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shrink-0">
              <Video className="w-8 h-8 text-white" />
            </div>
            <div>
              <h3 className="font-semibold text-blue-900 text-lg">Google Meet Integration</h3>
              <p className="text-blue-700 text-sm">
                All appointments are conducted via Google Meet. Upon confirmation, you'll receive an email with the meeting link, date, and time.
              </p>
            </div>
          </div>

          <div className="grid lg:grid-cols-3 gap-8">
            {/* Main Booking Form */}
            <div className="lg:col-span-2">
              <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-8 border border-gold/20 shadow-lg">
                <div className="text-center mb-8">
                  <div className="w-20 h-20 bg-gradient-to-br from-gold-500 to-gold-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <Calendar className="w-10 h-10 text-white" />
                  </div>
                  <h2 className="font-heading text-3xl font-bold text-foreground mb-2">
                    Schedule Your Appointment
                  </h2>
                  <p className="text-muted-foreground">
                    Fill out the form below to book a session with our Senior Pastor
                  </p>
                </div>

                {error && (
                  <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
                    <AlertCircle className="w-5 h-5 text-red-600 shrink-0" />
                    <p className="text-red-800 text-sm">{error}</p>
                  </div>
                )}

                {success && (
                  <div className="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-green-600 shrink-0 mt-0.5" />
                      <div>
                        <p className="text-green-800 text-sm mb-2">{success}</p>
                        {bookingReference && (
                          <div className="mt-3 p-3 bg-white rounded-lg border border-green-200">
                            <p className="text-xs text-green-700 font-medium">Your Booking Reference:</p>
                            <p className="text-lg font-bold text-green-900">{bookingReference}</p>
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                  {/* Personal Information */}
                  <div className="space-y-4">
                    <h3 className="font-semibold text-lg text-foreground flex items-center gap-2">
                      <User className="w-5 h-5 text-gold" />
                      Personal Information
                    </h3>
                    
                    <div className="grid md:grid-cols-2 gap-4">
                      <div>
                        <label className="block text-sm font-medium text-foreground mb-2">
                          Full Name *
                        </label>
                        <input
                          type="text"
                          value={form.name}
                          onChange={(e) => handleInputChange("name", e.target.value)}
                          className="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent transition-all"
                          placeholder="Enter your full name"
                          required
                        />
                      </div>

                      <div>
                        <label className="block text-sm font-medium text-foreground mb-2">
                          Email Address *
                        </label>
                        <input
                          type="email"
                          value={form.email}
                          onChange={(e) => handleInputChange("email", e.target.value)}
                          className="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent transition-all"
                          placeholder="your.email@example.com"
                          required
                        />
                      </div>
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-foreground mb-2">
                        Phone Number
                      </label>
                      <input
                        type="tel"
                        value={form.phone}
                        onChange={(e) => handleInputChange("phone", e.target.value)}
                        className="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent transition-all"
                        placeholder="+256 XXX XXX XXX"
                      />
                    </div>
                  </div>

                  {/* Booking Details */}
                  <div className="space-y-4">
                    <h3 className="font-semibold text-lg text-foreground flex items-center gap-2">
                      <MessageSquare className="w-5 h-5 text-gold" />
                      Booking Details
                    </h3>

                    <div>
                      <label className="block text-sm font-medium text-foreground mb-3">
                        Type of Session *
                      </label>
                      <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
                        {bookingTypes.map((type) => (
                          <button
                            key={type.value}
                            type="button"
                            onClick={() => handleInputChange("bookingType", type.value)}
                            className={`p-4 rounded-xl border-2 transition-all duration-300 text-left ${
                              form.bookingType === type.value
                                ? 'border-gold bg-gold/10 shadow-md'
                                : 'border-gray-200 hover:border-gold/50 hover:bg-gold/5'
                            }`}
                          >
                            <span className="text-2xl mb-2 block">{type.icon}</span>
                            <p className="font-medium text-sm text-foreground">{type.label}</p>
                            <p className="text-xs text-muted-foreground mt-1">{type.description}</p>
                          </button>
                        ))}
                      </div>
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-foreground mb-2">
                        Subject *
                      </label>
                      <input
                        type="text"
                        value={form.subject}
                        onChange={(e) => handleInputChange("subject", e.target.value)}
                        className="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent transition-all"
                        placeholder="Brief description of what you'd like to discuss"
                        required
                      />
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-foreground mb-2">
                        Detailed Description *
                      </label>
                      <textarea
                        value={form.description}
                        onChange={(e) => handleInputChange("description", e.target.value)}
                        rows={5}
                        className="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent transition-all resize-none"
                        placeholder="Please share more details about your booking request. This will help the pastor prepare for your session..."
                        required
                      />
                      <p className="text-xs text-muted-foreground mt-1">
                        {form.description.length}/2000 characters
                      </p>
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-foreground mb-2">
                        Prayer Points (Optional)
                      </label>
                      <textarea
                        value={form.prayerPoints}
                        onChange={(e) => handleInputChange("prayerPoints", e.target.value)}
                        rows={3}
                        className="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent transition-all resize-none"
                        placeholder="Any specific prayer points you'd like to share..."
                      />
                    </div>
                  </div>

                  {/* Date and Time Selection */}
                  <div className="space-y-4">
                    <h3 className="font-semibold text-lg text-foreground flex items-center gap-2">
                      <Calendar className="w-5 h-5 text-gold" />
                      Select Date & Time *
                    </h3>

                    {/* Calendar */}
                    <div className="bg-muted rounded-xl p-4 border border-gray-200">
                      <h4 className="font-medium text-foreground mb-3">Available Dates</h4>
                      <div className="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-7 gap-2 max-h-48 overflow-y-auto">
                        {availableDates.map((date, index) => {
                          const dateStr = date.toISOString().split('T')[0];
                          const isSelected = form.date === dateStr;
                          const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                          const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                          
                          return (
                            <button
                              key={index}
                              type="button"
                              onClick={() => handleDateSelect(date)}
                              className={`p-2 rounded-lg border-2 transition-all duration-300 text-sm ${
                                isSelected 
                                  ? 'border-gold bg-gold text-white shadow-md' 
                                  : 'border-gray-200 hover:border-gold/50 hover:bg-gold/10'
                              }`}
                            >
                              <div className="text-xs text-muted-foreground">{dayNames[date.getDay()]}</div>
                              <div className="text-lg font-bold">{date.getDate()}</div>
                              <div className="text-xs text-muted-foreground">{monthNames[date.getMonth()]}</div>
                            </button>
                          );
                        })}
                      </div>
                    </div>

                    {/* Time Slots */}
                    {form.date && availableSlots.length > 0 && (
                      <div className="bg-muted rounded-xl p-4 border border-gray-200 animate-fade-in">
                        <h4 className="font-medium text-foreground mb-3 flex items-center gap-2">
                          <Clock className="w-4 h-4" />
                          Available Time Slots
                        </h4>
                        <div className="grid grid-cols-3 md:grid-cols-5 gap-2">
                          {availableSlots.map((slot, index) => (
                            <button
                              key={index}
                              type="button"
                              onClick={() => handleInputChange("time", slot)}
                              className={`p-3 rounded-lg border-2 transition-all duration-300 font-medium text-sm ${
                                form.time === slot 
                                  ? 'border-gold bg-gold text-white shadow-md' 
                                  : 'border-gray-200 hover:border-gold/50 hover:bg-gold/10'
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
                    disabled={loading}
                    className="w-full py-4 px-6 bg-gradient-to-r from-gold-500 to-gold-600 text-white font-bold rounded-xl hover:shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3 text-lg"
                  >
                    {loading ? (
                      <>
                        <Loader2 className="w-5 h-5 animate-spin" />
                        Processing Booking...
                      </>
                    ) : (
                      <>
                        <Send className="w-5 h-5" />
                        Book Appointment
                      </>
                    )}
                  </button>
                </form>
              </div>
            </div>

            {/* Sidebar Information */}
            <div className="space-y-6">
              {/* What to Expect */}
              <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gold/20 shadow-lg">
                <h3 className="font-semibold text-lg text-foreground mb-4 flex items-center gap-2">
                  <CheckCircle className="w-5 h-5 text-gold" />
                  What to Expect
                </h3>
                <div className="space-y-4">
                  <div className="flex items-start gap-3">
                    <div className="w-8 h-8 bg-gold/10 rounded-full flex items-center justify-center shrink-0">
                      <span className="text-gold font-bold">1</span>
                    </div>
                    <div>
                      <p className="font-medium text-foreground">Submit Your Request</p>
                      <p className="text-sm text-muted-foreground">Fill out the booking form with your details</p>
                    </div>
                  </div>
                  <div className="flex items-start gap-3">
                    <div className="w-8 h-8 bg-gold/10 rounded-full flex items-center justify-center shrink-0">
                      <span className="text-gold font-bold">2</span>
                    </div>
                    <div>
                      <p className="font-medium text-foreground">Receive Confirmation</p>
                      <p className="text-sm text-muted-foreground">Get an email with your booking reference</p>
                    </div>
                  </div>
                  <div className="flex items-start gap-3">
                    <div className="w-8 h-8 bg-gold/10 rounded-full flex items-center justify-center shrink-0">
                      <span className="text-gold font-bold">3</span>
                    </div>
                    <div>
                      <p className="font-medium text-foreground">Google Meet Link</p>
                      <p className="text-sm text-muted-foreground">Receive the meeting link before your appointment</p>
                    </div>
                  </div>
                  <div className="flex items-start gap-3">
                    <div className="w-8 h-8 bg-gold/10 rounded-full flex items-center justify-center shrink-0">
                      <span className="text-gold font-bold">4</span>
                    </div>
                    <div>
                      <p className="font-medium text-foreground">Join Your Session</p>
                      <p className="text-sm text-muted-foreground">Click the link at your scheduled time</p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Pastor Availability */}
              <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gold/20 shadow-lg">
                <h3 className="font-semibold text-lg text-foreground mb-4 flex items-center gap-2">
                  <Clock className="w-5 h-5 text-gold" />
                  Pastor's Availability
                </h3>
                <div className="space-y-3 text-sm">
                  <div className="flex justify-between items-center py-2 border-b border-gray-100">
                    <span className="text-foreground font-medium">Monday</span>
                    <span className="text-muted-foreground">9:00 AM - 12:00 PM</span>
                  </div>
                  <div className="flex justify-between items-center py-2 border-b border-gray-100">
                    <span className="text-foreground font-medium">Tuesday</span>
                    <span className="text-muted-foreground">9:00 AM - 5:00 PM</span>
                  </div>
                  <div className="flex justify-between items-center py-2 border-b border-gray-100">
                    <span className="text-foreground font-medium">Wednesday</span>
                    <span className="text-muted-foreground">9:00 AM - 4:30 PM</span>
                  </div>
                  <div className="flex justify-between items-center py-2 border-b border-gray-100">
                    <span className="text-foreground font-medium">Thursday</span>
                    <span className="text-muted-foreground">9:00 AM - 5:00 PM</span>
                  </div>
                  <div className="flex justify-between items-center py-2 border-b border-gray-100">
                    <span className="text-foreground font-medium">Friday</span>
                    <span className="text-muted-foreground">9:00 AM - 12:00 PM</span>
                  </div>
                  <div className="flex justify-between items-center py-2">
                    <span className="text-muted-foreground">Weekends</span>
                    <span className="text-red-500">Not Available</span>
                  </div>
                </div>
              </div>

              {/* Session Duration */}
              <div className="bg-gradient-to-br from-gold-50 to-gold-100 rounded-2xl p-6 border border-gold/30 shadow-lg">
                <div className="flex items-center gap-3 mb-3">
                  <Video className="w-6 h-6 text-gold" />
                  <h3 className="font-semibold text-lg text-foreground">Session Duration</h3>
                </div>
                <p className="text-muted-foreground text-sm">
                  Each session typically lasts <strong className="text-foreground">30 minutes</strong>. If you need more time, please mention it in your booking description.
                </p>
              </div>

              {/* Contact Info */}
              <div className="bg-white/80 backdrop-blur-sm rounded-2xl p-6 border border-gold/20 shadow-lg">
                <h3 className="font-semibold text-lg text-foreground mb-4 flex items-center gap-2">
                  <Heart className="w-5 h-5 text-gold" />
                  Need Help?
                </h3>
                <div className="space-y-3">
                  <div className="flex items-center gap-3">
                    <Mail className="w-5 h-5 text-gold" />
                    <a href="mailto:visit@salemdominionministries.com" className="text-sm text-gold hover:underline">
                      visit@salemdominionministries.com
                    </a>
                  </div>
                  <div className="flex items-center gap-3">
                    <Phone className="w-5 h-5 text-gold" />
                    <a href="tel:+256753244480" className="text-sm text-gold hover:underline">
                      +256 753 244480
                    </a>
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

export default BookPastorCall;