import { useState } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PageHero from "@/components/PageHero";
import { Heart, Smartphone, Building, Shield, Users, BookOpen } from "lucide-react";
import { toast } from "@/hooks/use-toast";

const Donate = () => {
  const [copied, setCopied] = useState<string>("");

  const copy = async (value: string, label: string) => {
    try {
      await navigator.clipboard.writeText(value);
      setCopied(label);
      toast({
        title: "Copied",
        description: `${label} copied to clipboard.`,
      });
      setTimeout(() => setCopied(""), 2000);
    } catch {
      toast({
        title: "Copy failed",
        description: "Please copy manually from the text shown.",
        variant: "destructive",
      });
    }
  };

  return (
    <div className="min-h-screen">
      <Navbar />
      <PageHero
        title="Donate"
        subtitle="Help strengthen a young and growing ministry that is shining Christ's light with courage, love, and truth"
      />

      <section className="py-20 bg-background">
        <div className="container mx-auto px-4 max-w-5xl">
          {/* Mission Appeal */}
          <div className="bg-gradient-navy rounded-xl p-8 md:p-10 mb-10 faith-glow">
            <div className="flex items-center justify-center gap-3 text-gold mb-4">
              <span className="faith-cross faith-cross-hero animate-float-slow">✝</span>
              <h2 className="font-heading text-2xl md:text-3xl font-bold text-card text-center">A Light in the Darkness</h2>
              <span className="faith-cross faith-cross-hero animate-float-slow">✝</span>
            </div>
            <p className="text-card/85 leading-relaxed text-center max-w-4xl mx-auto mb-5">
              Salem Dominion Ministries is still a young church, but God is already doing mighty things through this ministry.
              We serve in a predominantly Islamic environment and continue to share the Gospel with compassion, consistency,
              respect, and bold faith.
              Your giving helps us remain a steady light of hope, prayer, discipleship, and practical love in our community.
            </p>
            <p className="text-gold-light text-center font-medium max-w-3xl mx-auto">
              Every gift — small or large — directly strengthens this mission and helps us reach more lives for Christ.
            </p>
          </div>

          {/* Where Support Goes */}
          <div className="grid md:grid-cols-3 gap-6 mb-12">
            <div className="bg-card rounded-lg p-6 border border-border shadow-sm">
              <Shield className="text-secondary mb-3" size={24} />
              <h3 className="font-heading text-lg font-bold text-foreground mb-2">Sustaining Ministry</h3>
              <p className="text-muted-foreground text-sm leading-relaxed">
                Support helps us maintain weekly worship gatherings, prayer meetings, discipleship, and church operations.
              </p>
            </div>
            <div className="bg-card rounded-lg p-6 border border-border shadow-sm">
              <Users className="text-secondary mb-3" size={24} />
              <h3 className="font-heading text-lg font-bold text-foreground mb-2">Community Outreach</h3>
              <p className="text-muted-foreground text-sm leading-relaxed">
                Your generosity enables outreach, pastoral care, and practical support to families and individuals in need.
              </p>
            </div>
            <div className="bg-card rounded-lg p-6 border border-border shadow-sm">
              <BookOpen className="text-secondary mb-3" size={24} />
              <h3 className="font-heading text-lg font-bold text-foreground mb-2">Next Generation</h3>
              <p className="text-muted-foreground text-sm leading-relaxed">
                We invest in children and youth through biblical teaching, mentorship, and a Christ-centered spiritual foundation.
              </p>
            </div>
          </div>

          <div className="grid md:grid-cols-3 gap-8">
            {/* Online Giving */}
            <div className="bg-card rounded-lg p-8 border border-border shadow-sm text-center">
              <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-6">
                <Heart className="text-primary" size={28} />
              </div>
              <h3 className="font-heading text-xl font-bold text-foreground mb-4">Online Giving</h3>
              <p className="text-muted-foreground mb-6">
                You can support the ministry securely through our online giving platform.
              </p>
              <a
                href="https://wa.me/256753244480"
                target="_blank"
                rel="noopener noreferrer"
                className="block w-full px-6 py-3 bg-gradient-gold text-primary font-semibold rounded-md shadow-gold hover:opacity-90 transition-opacity"
              >
                Give via WhatsApp
              </a>
            </div>

            {/* Mobile Money */}
            <div className="bg-card rounded-lg p-8 border border-border shadow-sm text-center">
              <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-6">
                <Smartphone className="text-primary" size={28} />
              </div>
              <h3 className="font-heading text-xl font-bold text-foreground mb-4">Mobile Money</h3>
              <div className="text-left space-y-4">
                <div className="bg-muted rounded-md p-4">
                  <p className="text-sm font-medium text-foreground">MTN Mobile Money</p>
                  <p className="text-secondary font-semibold">+256 753 244480</p>
                  <p className="text-muted-foreground text-xs mt-1">Name: Salem Dominion Ministries</p>
                  <button
                    onClick={() => copy("+256753244480", "MTN number")}
                    className="mt-2 text-xs text-secondary underline"
                  >
                    {copied === "MTN number" ? "Copied" : "Copy number"}
                  </button>
                </div>
                <div className="bg-muted rounded-md p-4">
                  <p className="text-sm font-medium text-foreground">Airtel Money</p>
                  <p className="text-secondary font-semibold">+256 753 244480</p>
                  <p className="text-muted-foreground text-xs mt-1">Name: Salem Dominion Ministries</p>
                  <button
                    onClick={() => copy("+256753244480", "Airtel number")}
                    className="mt-2 text-xs text-secondary underline"
                  >
                    {copied === "Airtel number" ? "Copied" : "Copy number"}
                  </button>
                </div>
              </div>
            </div>

            {/* Bank Details */}
            <div className="bg-card rounded-lg p-8 border border-border shadow-sm text-center">
              <div className="w-16 h-16 rounded-full bg-gradient-gold flex items-center justify-center mx-auto mb-6">
                <Building className="text-primary" size={28} />
              </div>
              <h3 className="font-heading text-xl font-bold text-foreground mb-4">Bank Transfer</h3>
              <div className="text-left">
                <div className="bg-muted rounded-md p-4 space-y-2">
                  <div>
                    <p className="text-xs text-muted-foreground">Bank Name</p>
                    <p className="text-sm font-medium text-foreground">Equity Bank</p>
                  </div>
                  <div>
                    <p className="text-xs text-muted-foreground">Account Name</p>
                    <p className="text-sm font-medium text-foreground">Salem Dominion Ministries</p>
                  </div>
                  <div>
                    <p className="text-xs text-muted-foreground">Account Number</p>
                    <p className="text-secondary font-semibold">1017203227662</p>
                    <button
                      onClick={() => copy("1017203227662", "Bank account number")}
                      className="mt-2 text-xs text-secondary underline"
                    >
                      {copied === "Bank account number" ? "Copied" : "Copy account number"}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Scripture */}
          <div className="mt-16 text-center bg-gradient-navy rounded-lg p-10">
            <p className="font-heading text-2xl text-card italic mb-4">
              "Each of you should give what you have decided in your heart to give, not reluctantly or under compulsion, for God loves a cheerful giver."
            </p>
            <p className="text-gold font-medium">— 2 Corinthians 9:7</p>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default Donate;
