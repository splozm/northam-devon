import { Calendar, MapPin, Clock, ChevronRight, UtensilsCrossed, Building2, Users } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

interface PaletteShowcaseProps {
  themeName: string;
  title: string;
  description: string;
}

const PaletteShowcase = ({ themeName, title, description }: PaletteShowcaseProps) => {
  return (
    <section className={`${themeName} bg-background py-16`}>
      <div className="container mx-auto px-4">
        {/* Header */}
        <div className="text-center mb-12 animate-fade-in">
          <span className="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-sm font-medium mb-4">
            Colour Palette Option
          </span>
          <h2 className="font-heading text-4xl md:text-5xl font-bold text-foreground mb-4">
            {title}
          </h2>
          <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
            {description}
          </p>
        </div>

        {/* Colour Swatches */}
        <div className="flex flex-wrap justify-center gap-4 mb-12">
          <ColourSwatch label="Primary" className="bg-primary" />
          <ColourSwatch label="Accent" className="bg-accent" />
          <ColourSwatch label="Coastal Deep" className="bg-coastal-deep" />
          <ColourSwatch label="Coastal Mid" className="bg-coastal-mid" />
          <ColourSwatch label="Coastal Light" className="bg-coastal-light" />
          <ColourSwatch label="Sand Warm" className="bg-sand-warm" textDark />
          <ColourSwatch label="Sea Foam" className="bg-sea-foam" textDark />
        </div>

        {/* Hero Preview */}
        <div className="relative rounded-2xl overflow-hidden mb-12 bg-coastal-deep h-64 md:h-80 flex items-center justify-center">
          <div className="absolute inset-0 gradient-hero-overlay" />
          <div className="relative z-10 text-center px-6">
            <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">
              Welcome to Northam, Devon
            </h1>
            <p className="text-primary-foreground/90 text-lg md:text-xl max-w-2xl mx-auto mb-6">
              A charming coastal village where community spirit meets Devon's stunning shores
            </p>
            <div className="flex flex-wrap justify-center gap-4">
              <Button size="lg" className="bg-primary-foreground text-primary hover:bg-primary-foreground/90">
                Explore Events
              </Button>
              <Button size="lg" variant="outline" className="border-primary-foreground/50 text-primary-foreground hover:bg-primary-foreground/10">
                Discover Northam
              </Button>
            </div>
          </div>
        </div>

        {/* Event Cards - Three Types */}
        <div className="mb-12">
          <h3 className="font-heading text-2xl font-semibold text-foreground mb-6 text-center">
            Event Card Variants
          </h3>
          <div className="grid md:grid-cols-3 gap-6">
            <EventCard
              type="venue"
              title="Northam Hall Quiz Night"
              date="Friday, 17th January"
              time="7:30 PM"
              venue="Northam Hall"
              icon={Building2}
            />
            <EventCard
              type="business"
              title="Live Music at The Kingsley"
              date="Saturday, 18th January"
              time="8:00 PM"
              venue="The Kingsley Pub"
              icon={UtensilsCrossed}
            />
            <EventCard
              type="community"
              title="WI Monthly Meeting"
              date="Tuesday, 21st January"
              time="2:00 PM"
              venue="Community Hall"
              icon={Users}
            />
          </div>
        </div>

        {/* Business/Venue Card Preview */}
        <div className="mb-12">
          <h3 className="font-heading text-2xl font-semibold text-foreground mb-6 text-center">
            Business & Venue Cards
          </h3>
          <div className="grid md:grid-cols-3 gap-6">
            <BusinessCard
              name="The Kingsley"
              category="Traditional Pub"
              description="Historic village pub serving real ales and home-cooked food in a welcoming atmosphere."
            />
            <BusinessCard
              name="Northam Hall"
              category="Community Venue"
              description="The heart of community events with flexible spaces for hire."
            />
            <BusinessCard
              name="Sea Horse Fish & Chips"
              category="Takeaway"
              description="Fresh, locally-caught fish and hand-cut chips since 1985."
            />
          </div>
        </div>

        {/* Buttons Preview */}
        <div className="flex flex-wrap justify-center gap-4">
          <Button size="lg">Primary Button</Button>
          <Button size="lg" variant="secondary">Secondary</Button>
          <Button size="lg" variant="outline">Outline</Button>
          <Button size="lg" className="bg-accent hover:bg-accent/90">Accent</Button>
        </div>
      </div>
    </section>
  );
};

const ColourSwatch = ({ label, className, textDark = false }: { label: string; className: string; textDark?: boolean }) => (
  <div className={`${className} rounded-lg p-4 min-w-[100px] text-center shadow-md`}>
    <span className={`text-sm font-medium ${textDark ? 'text-foreground' : 'text-primary-foreground'}`}>
      {label}
    </span>
  </div>
);

interface EventCardProps {
  type: 'venue' | 'business' | 'community';
  title: string;
  date: string;
  time: string;
  venue: string;
  icon: React.ElementType;
}

const EventCard = ({ type, title, date, time, venue, icon: Icon }: EventCardProps) => {
  const borderColors = {
    venue: 'border-l-event-venue',
    business: 'border-l-event-business',
    community: 'border-l-event-community',
  };

  const badgeColors = {
    venue: 'bg-event-venue/10 text-event-venue',
    business: 'bg-event-business/10 text-event-business',
    community: 'bg-event-community/10 text-event-community',
  };

  const labels = {
    venue: 'Venue Event',
    business: 'Business Event',
    community: 'Community Event',
  };

  return (
    <Card className={`border-l-4 ${borderColors[type]} hover-lift cursor-pointer`}>
      <CardContent className="p-5">
        <div className="flex items-start gap-4">
          <div className={`p-2 rounded-lg ${badgeColors[type]}`}>
            <Icon className="w-5 h-5" />
          </div>
          <div className="flex-1">
            <span className={`text-xs font-medium ${badgeColors[type]} px-2 py-0.5 rounded-full`}>
              {labels[type]}
            </span>
            <h4 className="font-heading text-lg font-semibold text-card-foreground mt-2 mb-2">
              {title}
            </h4>
            <div className="space-y-1 text-sm text-muted-foreground">
              <div className="flex items-center gap-2">
                <Calendar className="w-4 h-4" />
                {date}
              </div>
              <div className="flex items-center gap-2">
                <Clock className="w-4 h-4" />
                {time}
              </div>
              <div className="flex items-center gap-2">
                <MapPin className="w-4 h-4" />
                {venue}
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>
  );
};

interface BusinessCardProps {
  name: string;
  category: string;
  description: string;
}

const BusinessCard = ({ name, category, description }: BusinessCardProps) => (
  <Card className="overflow-hidden hover-lift cursor-pointer group">
    <div className="h-40 bg-gradient-to-br from-coastal-mid to-coastal-light flex items-center justify-center">
      <span className="text-6xl opacity-30">🏠</span>
    </div>
    <CardContent className="p-5">
      <span className="text-xs font-medium text-muted-foreground uppercase tracking-wide">
        {category}
      </span>
      <h4 className="font-heading text-xl font-semibold text-card-foreground mt-1 mb-2">
        {name}
      </h4>
      <p className="text-sm text-muted-foreground line-clamp-2 mb-4">
        {description}
      </p>
      <span className="inline-flex items-center text-sm font-medium text-primary group-hover:gap-2 transition-all">
        View Details <ChevronRight className="w-4 h-4 ml-1" />
      </span>
    </CardContent>
  </Card>
);

export default PaletteShowcase;
