import { Link } from "react-router-dom";
import { Calendar, MapPin, Clock, ChevronRight, ArrowRight, Users, Store, Compass, Waves } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import Layout from "@/components/layout/Layout";

// Sample data for the homepage
const upcomingEvents = [
  {
    id: 1,
    title: "Northam Hall Quiz Night",
    date: "Friday, 17th January",
    time: "7:30 PM",
    venue: "Northam Hall",
    type: "venue" as const,
  },
  {
    id: 2,
    title: "Live Music at The Kingsley",
    date: "Saturday, 18th January",
    time: "8:00 PM",
    venue: "The Kingsley Pub",
    type: "business" as const,
  },
  {
    id: 3,
    title: "WI Monthly Meeting",
    date: "Tuesday, 21st January",
    time: "2:00 PM",
    venue: "Community Hall",
    type: "community" as const,
  },
];

const featuredBusinesses = [
  {
    id: 1,
    name: "The Kingsley",
    category: "Traditional Pub",
    description: "Historic village pub serving real ales and home-cooked food.",
  },
  {
    id: 2,
    name: "Northam Burrows",
    category: "Nature Reserve",
    description: "UNESCO Biosphere Reserve with stunning coastal walks.",
  },
  {
    id: 3,
    name: "Sea Horse Fish & Chips",
    category: "Takeaway",
    description: "Fresh, locally-caught fish and hand-cut chips since 1985.",
  },
];

const Index = () => {
  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0 bg-coastal-deep" />
        <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep/80 via-coastal-mid/40 to-coastal-deep/90" />
        
        {/* Decorative waves */}
        <div className="absolute bottom-0 left-0 right-0 h-24">
          <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-full h-full">
            <path d="M0 60L48 55C96 50 192 40 288 45C384 50 480 70 576 75C672 80 768 70 864 60C960 50 1056 40 1152 45C1248 50 1344 70 1392 80L1440 90V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0V60Z" 
              fill="hsl(var(--background))" fillOpacity="0.5"/>
            <path d="M0 80L48 75C96 70 192 60 288 65C384 70 480 90 576 95C672 100 768 90 864 80C960 70 1056 60 1152 65C1248 70 1344 90 1392 100L1440 110V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0V80Z" 
              fill="hsl(var(--background))"/>
          </svg>
        </div>

        <div className="relative z-10 container mx-auto px-4 text-center">
          <div className="animate-fade-in">
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-6">
              <Waves className="w-4 h-4" />
              Welcome to
            </span>
            <h1 className="font-heading text-5xl md:text-7xl lg:text-8xl font-bold text-primary-foreground mb-6">
              Northam, Devon
            </h1>
            <p className="text-xl md:text-2xl text-primary-foreground/90 max-w-3xl mx-auto mb-8 font-light">
              A charming coastal village where community spirit meets Devon's stunning shores
            </p>
            <div className="flex flex-wrap justify-center gap-4">
              <Button size="lg" asChild className="bg-accent hover:bg-accent/90 text-accent-foreground">
                <Link to="/events">
                  <Calendar className="w-5 h-5 mr-2" />
                  Explore Events
                </Link>
              </Button>
              <Button size="lg" variant="outline" asChild className="border-primary-foreground/50 text-primary-foreground hover:bg-primary-foreground/10">
                <Link to="/things-to-do">
                  <Compass className="w-5 h-5 mr-2" />
                  Discover Northam
                </Link>
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Quick Stats */}
      <section className="py-12 bg-sand-warm/50">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {[
              { icon: Calendar, label: "Upcoming Events", value: "12+" },
              { icon: Store, label: "Local Businesses", value: "45+" },
              { icon: Users, label: "Community Groups", value: "8" },
              { icon: Compass, label: "Things to Do", value: "20+" },
            ].map((stat, index) => (
              <div key={index} className="text-center">
                <div className="w-12 h-12 mx-auto mb-3 rounded-full bg-primary/10 flex items-center justify-center">
                  <stat.icon className="w-6 h-6 text-primary" />
                </div>
                <div className="font-heading text-3xl font-bold text-foreground">{stat.value}</div>
                <div className="text-sm text-muted-foreground">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Upcoming Events Section */}
      <section className="py-16 md:py-24">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
            <div>
              <span className="text-sm font-medium text-accent uppercase tracking-wide">What's On</span>
              <h2 className="font-heading text-3xl md:text-4xl font-bold text-foreground mt-1">
                Upcoming Events
              </h2>
            </div>
            <Button variant="outline" asChild>
              <Link to="/events" className="gap-2">
                View All Events <ArrowRight className="w-4 h-4" />
              </Link>
            </Button>
          </div>

          <div className="grid md:grid-cols-3 gap-6">
            {upcomingEvents.map((event) => (
              <EventCard key={event.id} event={event} />
            ))}
          </div>
        </div>
      </section>

      {/* Featured Businesses Section */}
      <section className="py-16 md:py-24 bg-muted/30">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
            <div>
              <span className="text-sm font-medium text-accent uppercase tracking-wide">Local Directory</span>
              <h2 className="font-heading text-3xl md:text-4xl font-bold text-foreground mt-1">
                Featured Businesses
              </h2>
            </div>
            <Button variant="outline" asChild>
              <Link to="/directory" className="gap-2">
                Browse Directory <ArrowRight className="w-4 h-4" />
              </Link>
            </Button>
          </div>

          <div className="grid md:grid-cols-3 gap-6">
            {featuredBusinesses.map((business) => (
              <BusinessCard key={business.id} business={business} />
            ))}
          </div>
        </div>
      </section>

      {/* Community CTA Section */}
      <section className="py-16 md:py-24">
        <div className="container mx-auto px-4">
          <div className="relative rounded-3xl overflow-hidden bg-coastal-mid p-8 md:p-16">
            <div className="absolute inset-0 bg-gradient-to-r from-coastal-deep/80 to-transparent" />
            <div className="relative z-10 max-w-2xl">
              <span className="inline-block px-3 py-1 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
                Get Involved
              </span>
              <h2 className="font-heading text-3xl md:text-4xl font-bold text-primary-foreground mb-4">
                Join Our Community
              </h2>
              <p className="text-primary-foreground/90 text-lg mb-8">
                From the WI to sports clubs, Northam has a vibrant community waiting to welcome you. 
                Discover local groups and get involved in village life.
              </p>
              <div className="flex flex-wrap gap-4">
                <Button size="lg" asChild className="bg-accent hover:bg-accent/90">
                  <Link to="/community">
                    <Users className="w-5 h-5 mr-2" />
                    Explore Groups
                  </Link>
                </Button>
                <Button size="lg" variant="outline" asChild className="border-primary-foreground/50 text-primary-foreground hover:bg-primary-foreground/10">
                  <Link to="/history">
                    Our History
                  </Link>
                </Button>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Newsletter Section */}
      <section className="py-16 bg-sand-warm/50">
        <div className="container mx-auto px-4 text-center max-w-2xl">
          <h2 className="font-heading text-2xl md:text-3xl font-bold text-foreground mb-4">
            Stay in the Loop
          </h2>
          <p className="text-muted-foreground mb-6">
            Get weekly updates on events, news, and community happenings in Northam.
          </p>
          <div className="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <input 
              type="email" 
              placeholder="Your email address" 
              className="flex-1 px-4 py-3 rounded-lg border border-border bg-card text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
            />
            <Button className="bg-primary hover:bg-primary/90">
              Subscribe
            </Button>
          </div>
        </div>
      </section>
    </Layout>
  );
};

// Event Card Component
interface EventCardProps {
  event: {
    title: string;
    date: string;
    time: string;
    venue: string;
    type: "venue" | "business" | "community";
  };
}

const EventCard = ({ event }: EventCardProps) => {
  const typeStyles = {
    venue: { border: "border-l-event-venue", badge: "bg-event-venue/10 text-event-venue" },
    business: { border: "border-l-event-business", badge: "bg-event-business/10 text-event-business" },
    community: { border: "border-l-event-community", badge: "bg-event-community/10 text-event-community" },
  };

  const typeLabels = {
    venue: "Venue Event",
    business: "Business Event",
    community: "Community Event",
  };

  return (
    <Card className={`border-l-4 ${typeStyles[event.type].border} hover-lift cursor-pointer group`}>
      <CardContent className="p-5">
        <span className={`inline-block text-xs font-medium ${typeStyles[event.type].badge} px-2 py-1 rounded-full mb-3`}>
          {typeLabels[event.type]}
        </span>
        <h3 className="font-heading text-xl font-semibold text-card-foreground mb-3 group-hover:text-primary transition-colors">
          {event.title}
        </h3>
        <div className="space-y-2 text-sm text-muted-foreground">
          <div className="flex items-center gap-2">
            <Calendar className="w-4 h-4" />
            {event.date}
          </div>
          <div className="flex items-center gap-2">
            <Clock className="w-4 h-4" />
            {event.time}
          </div>
          <div className="flex items-center gap-2">
            <MapPin className="w-4 h-4" />
            {event.venue}
          </div>
        </div>
      </CardContent>
    </Card>
  );
};

// Business Card Component
interface BusinessCardProps {
  business: {
    name: string;
    category: string;
    description: string;
  };
}

const BusinessCard = ({ business }: BusinessCardProps) => (
  <Card className="overflow-hidden hover-lift cursor-pointer group">
    <div className="h-40 bg-gradient-to-br from-coastal-mid to-coastal-light flex items-center justify-center">
      <Store className="w-12 h-12 text-primary-foreground/30" />
    </div>
    <CardContent className="p-5">
      <span className="text-xs font-medium text-muted-foreground uppercase tracking-wide">
        {business.category}
      </span>
      <h3 className="font-heading text-xl font-semibold text-card-foreground mt-1 mb-2 group-hover:text-primary transition-colors">
        {business.name}
      </h3>
      <p className="text-sm text-muted-foreground line-clamp-2 mb-4">
        {business.description}
      </p>
      <span className="inline-flex items-center text-sm font-medium text-primary group-hover:gap-2 transition-all">
        View Details <ChevronRight className="w-4 h-4 ml-1" />
      </span>
    </CardContent>
  </Card>
);

export default Index;
