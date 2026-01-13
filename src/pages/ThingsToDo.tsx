import { Link } from "react-router-dom";
import { Compass, MapPin, Clock, ChevronRight, Waves, TreeDeciduous, Camera, Footprints, Bike, Fish } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import Layout from "@/components/layout/Layout";

// Things to do data
const attractions = [
  {
    id: 1,
    title: "Northam Burrows Country Park",
    category: "Nature Reserve",
    icon: TreeDeciduous,
    description: "595 acres of unspoilt coastline. Part of the UNESCO North Devon Biosphere Reserve with rare flora and fauna.",
    duration: "2-4 hours",
    distance: "0.5 miles from village",
    highlights: ["Bird watching", "Coastal walks", "Pebble Ridge", "Golf course views"],
  },
  {
    id: 2,
    title: "Westward Ho! Beach",
    category: "Beach",
    icon: Waves,
    description: "Blue Flag beach with 2 miles of golden sand. Perfect for surfing, swimming, and family days out.",
    duration: "Half day",
    distance: "1 mile from village",
    highlights: ["Swimming", "Surfing", "Rock pools", "Beach cafes"],
  },
  {
    id: 3,
    title: "South West Coast Path",
    category: "Walking",
    icon: Footprints,
    description: "Access stunning sections of England's longest waymarked trail right from Northam.",
    duration: "1-6 hours",
    distance: "Various start points",
    highlights: ["Dramatic cliffs", "Clovelly walk", "Wildlife spotting", "Coastal views"],
  },
  {
    id: 4,
    title: "The Pebble Ridge",
    category: "Natural Landmark",
    icon: Camera,
    description: "Iconic 2-mile natural barrier of Atlantic pebbles. A geological wonder protecting the Burrows.",
    duration: "1-2 hours",
    distance: "0.5 miles from village",
    highlights: ["Unique landscape", "Photography", "Storm watching", "Nature trails"],
  },
  {
    id: 5,
    title: "Cycling Routes",
    category: "Cycling",
    icon: Bike,
    description: "Explore the Tarka Trail and quiet country lanes. Bike hire available in nearby Bideford.",
    duration: "2-4 hours",
    distance: "Various routes",
    highlights: ["Tarka Trail", "Family-friendly", "Scenic views", "Flat routes available"],
  },
  {
    id: 6,
    title: "Fishing & Watersports",
    category: "Activities",
    icon: Fish,
    description: "Sea fishing trips from Appledore, kayaking, and paddleboarding along the Torridge estuary.",
    duration: "Half day",
    distance: "2 miles to Appledore",
    highlights: ["Boat trips", "Kayak hire", "SUP lessons", "Fishing charters"],
  },
];

const nearbyAttractions = [
  { name: "Clovelly", distance: "8 miles", description: "Famous cobbled fishing village" },
  { name: "RHS Rosemoor", distance: "10 miles", description: "Beautiful 65-acre garden" },
  { name: "Lundy Island", distance: "Ferry from Bideford", description: "Wildlife haven in the Bristol Channel" },
  { name: "Dartington Crystal", distance: "5 miles", description: "Watch glass-blowing demonstrations" },
];

const ThingsToDo = () => {
  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative py-16 md:py-24 bg-coastal-deep">
        <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center animate-fade-in">
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
              <Compass className="w-4 h-4" />
              Explore
            </span>
            <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">
              Things to Do
            </h1>
            <p className="text-xl text-primary-foreground/80 max-w-2xl mx-auto">
              From stunning coastal walks to watersports adventures — discover the best of North Devon
            </p>
          </div>
        </div>
      </section>

      {/* Main Attractions */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <span className="text-sm font-medium text-accent uppercase tracking-wide">In & Around Northam</span>
            <h2 className="font-heading text-3xl md:text-4xl font-bold text-foreground mt-2">
              Local Attractions
            </h2>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {attractions.map((attraction) => (
              <AttractionCard key={attraction.id} attraction={attraction} />
            ))}
          </div>
        </div>
      </section>

      {/* Feature Section - Burrows */}
      <section className="py-16 md:py-24 bg-muted/30">
        <div className="container mx-auto px-4">
          <div className="grid lg:grid-cols-2 gap-12 items-center">
            <div className="order-2 lg:order-1">
              <span className="text-sm font-medium text-accent uppercase tracking-wide">Must Visit</span>
              <h2 className="font-heading text-3xl md:text-4xl font-bold text-foreground mt-2 mb-4">
                Northam Burrows Country Park
              </h2>
              <p className="text-lg text-muted-foreground mb-6">
                A spectacular 595-acre coastal common and country park, Northam Burrows is a jewel of North Devon. 
                Part of the UNESCO North Devon Biosphere Reserve, it offers unrivalled walking, wildlife watching, 
                and breathtaking views across Bideford Bay.
              </p>
              <ul className="space-y-3 mb-8">
                {["UNESCO Biosphere Reserve", "Rare coastal flora & fauna", "2-mile Pebble Ridge", "Royal North Devon Golf Club", "Free parking (seasonal charges apply)"].map((item, index) => (
                  <li key={index} className="flex items-center gap-3 text-foreground">
                    <span className="w-2 h-2 rounded-full bg-accent" />
                    {item}
                  </li>
                ))}
              </ul>
              <Button className="bg-primary hover:bg-primary/90">
                Plan Your Visit
              </Button>
            </div>
            <div className="order-1 lg:order-2">
              <div className="rounded-2xl overflow-hidden bg-gradient-to-br from-coastal-mid to-coastal-light h-80 lg:h-96 flex items-center justify-center">
                <TreeDeciduous className="w-24 h-24 text-primary-foreground/20" />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Nearby Attractions */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <span className="text-sm font-medium text-accent uppercase tracking-wide">Day Trips</span>
            <h2 className="font-heading text-3xl md:text-4xl font-bold text-foreground mt-2">
              Nearby Attractions
            </h2>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {nearbyAttractions.map((attraction, index) => (
              <Card key={index} className="hover-lift cursor-pointer group">
                <CardContent className="p-5">
                  <span className="text-xs font-medium text-muted-foreground">{attraction.distance}</span>
                  <h3 className="font-heading text-lg font-semibold text-card-foreground mt-1 mb-2 group-hover:text-primary transition-colors">
                    {attraction.name}
                  </h3>
                  <p className="text-sm text-muted-foreground">
                    {attraction.description}
                  </p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Visitor Tips */}
      <section className="py-12 bg-sand-warm/50">
        <div className="container mx-auto px-4">
          <div className="max-w-3xl mx-auto text-center">
            <h2 className="font-heading text-2xl font-bold text-foreground mb-6">
              Visitor Tips
            </h2>
            <div className="grid sm:grid-cols-3 gap-6 text-sm">
              <div className="bg-card rounded-xl p-5">
                <span className="text-2xl mb-3 block">🌦️</span>
                <h4 className="font-medium text-foreground mb-1">Weather</h4>
                <p className="text-muted-foreground">North Devon weather can change quickly. Bring layers and waterproofs!</p>
              </div>
              <div className="bg-card rounded-xl p-5">
                <span className="text-2xl mb-3 block">🚗</span>
                <h4 className="font-medium text-foreground mb-1">Parking</h4>
                <p className="text-muted-foreground">Free parking at Northam Burrows. Pay & display in Westward Ho!</p>
              </div>
              <div className="bg-card rounded-xl p-5">
                <span className="text-2xl mb-3 block">🐕</span>
                <h4 className="font-medium text-foreground mb-1">Dogs</h4>
                <p className="text-muted-foreground">Dogs welcome on leads. Seasonal restrictions on beaches May-Sept.</p>
              </div>
            </div>
          </div>
        </div>
      </section>
    </Layout>
  );
};

// Attraction Card Component
interface AttractionCardProps {
  attraction: {
    title: string;
    category: string;
    icon: React.ElementType;
    description: string;
    duration: string;
    distance: string;
    highlights: string[];
  };
}

const AttractionCard = ({ attraction }: AttractionCardProps) => {
  const Icon = attraction.icon;

  return (
    <Card className="overflow-hidden hover-lift cursor-pointer group h-full flex flex-col">
      <div className="h-36 bg-gradient-to-br from-coastal-mid to-coastal-light flex items-center justify-center relative">
        <Icon className="w-12 h-12 text-primary-foreground/30" />
        <span className="absolute top-4 left-4 px-2 py-1 rounded-full bg-card/90 text-xs font-medium text-foreground">
          {attraction.category}
        </span>
      </div>
      <CardContent className="p-5 flex flex-col flex-grow">
        <h3 className="font-heading text-xl font-semibold text-card-foreground mb-2 group-hover:text-primary transition-colors">
          {attraction.title}
        </h3>
        <p className="text-sm text-muted-foreground mb-4 flex-grow">
          {attraction.description}
        </p>
        <div className="flex flex-wrap gap-2 mb-4">
          {attraction.highlights.slice(0, 3).map((highlight, index) => (
            <span key={index} className="px-2 py-1 bg-muted rounded-full text-xs text-muted-foreground">
              {highlight}
            </span>
          ))}
        </div>
        <div className="flex items-center justify-between text-sm text-muted-foreground border-t border-border pt-4">
          <div className="flex items-center gap-1">
            <Clock className="w-4 h-4" />
            {attraction.duration}
          </div>
          <div className="flex items-center gap-1">
            <MapPin className="w-4 h-4" />
            {attraction.distance}
          </div>
        </div>
      </CardContent>
    </Card>
  );
};

export default ThingsToDo;
