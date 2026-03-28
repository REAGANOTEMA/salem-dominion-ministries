interface SectionHeadingProps {
  title: string;
  subtitle?: string;
  light?: boolean;
  className?: string;
}

const SectionHeading = ({ title, subtitle, light, className = "" }: SectionHeadingProps) => (
  <div className={`text-center mb-12 ${className}`}>
    <h2 className={`font-heading text-3xl md:text-4xl font-bold mb-4 ${light ? "text-card" : "text-foreground"}`}>
      {title}
    </h2>
    <div className="w-20 h-1 bg-gradient-gold mx-auto mb-4 rounded-full" />
    {subtitle && (
      <p className={`max-w-2xl mx-auto text-lg ${light ? "text-card/70" : "text-muted-foreground"}`}>
        {subtitle}
      </p>
    )}
  </div>
);

export default SectionHeading;
