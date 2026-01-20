import { useState } from "react";
import { MapPin, Phone, Globe, Clock, Store, Briefcase, ShoppingBag } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import Layout from "@/components/layout/Layout";

// Simple directory business data
const directoryBusinesses = [
  {
    id: 1,
    name: "Cost Cutter",
    category: "retail",
    type: "Convenience Store",
    address: "56 High Street, Northam, EX39 1AB",
    phone: "01237 456123",
    hours: "Mon-Sun 7am-10pm",
    website: "costcutter.co.uk",
  },
  {
    id: 2,
    name: "Northam Newsagent",
    category: "retail",
    type: "Newsagent & Convenience",
    address: "23 The Square, Northam, EX39 1AC",
    phone: "01237 456234",
    hours: "Mon-Sat 6am-7pm, Sun 7am-1pm",
  },
  {
    id: 3,
    name: "A D J Williams & Son",
    category: "professional",
    type: "Funeral Directors",
    address: "45 Fore Street, Northam, EX39 1AD",
    phone: "01237 456345",
    hours: "24/7 Service Available",
    website: "adjwilliams.co.uk",
  },
  {
    id: 4,
    name: "Richard Williams Funeral Services",
    category: "professional",
    type: "Funeral Directors",
    address: "12 Church Lane, Northam, EX39 1AE",
    phone: "01237 456456",
    hours: "24/7 Service Available",
    website: "richardwilliamsfunerals.co.uk",
  },
  {
    id: 5,
    name: "Nova Surveyors",
    category: "professional",
    type: "Property Surveyors",
    address: "Suite 3, Business Centre, Northam, EX39 1AF",
    phone: "01237 456567",
    hours: "Mon-Fri 9am-5pm",
    website: "novasurveyors.co.uk",
  },
];

const categories = [
  { id: "all", label: "All Services", icon: Store },
  { id: "retail", label: "Retail", icon: ShoppingBag },
  { id: "professional", label: "Professional Services", icon: Briefcase },
];

const LocalServices = () => {
  const [selectedCategory, setSelectedCategory] = useState("all");

  const filteredBusinesses = directoryBusinesses.filter((business) => {
    return selectedCategory === "all" || business.category === selectedCategory;
  });

  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative py-12 md:py-20 bg-coastal-deep">
        <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center animate-fade-in">
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
              <Briefcase className="w-4 h-4" />
              Essential Services
            </span>
            <h1 className="font-heading text-4xl md:text-5xl font-bold text-primary-foreground mb-4">
              Local Services
            </h1>
            <p className="text-lg text-primary-foreground/80 max-w-2xl mx-auto">
              Essential retail and professional services serving the Northam community
            </p>
          </div>
        </div>
      </section>

      {/* Category Filters */}
      <section className="sticky top-16 md:top-20 z-40 bg-card border-b border-border shadow-sm">
        <div className="container mx-auto px-4 py-4">
          <div className="flex flex-wrap items-center gap-2 justify-center md:justify-start">
            {categories.map((category) => (
              <Button
                key={category.id}
                size="sm"
                variant={selectedCategory === category.id ? "default" : "outline"}
                onClick={() => setSelectedCategory(category.id)}
                className="text-sm gap-1.5"
              >
                <category.icon className="w-3.5 h-3.5" />
                {category.label}
              </Button>
            ))}
          </div>
        </div>
      </section>

      {/* Directory Grid */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <p className="text-muted-foreground">
              Showing <span className="font-medium text-foreground">{filteredBusinesses.length}</span> services
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredBusinesses.map((business) => (
              <DirectoryCard key={business.id} business={business} />
            ))}
          </div>

          {filteredBusinesses.length === 0 && (
            <div className="text-center py-16">
              <Store className="w-16 h-16 mx-auto text-muted-foreground/50 mb-4" />
              <h3 className="font-heading text-xl font-semibold text-foreground mb-2">
                No services found
              </h3>
              <p className="text-muted-foreground">
                Try selecting a different category.
              </p>
            </div>
          )}
        </div>
      </section>

      {/* Add Business CTA */}
      <section className="py-12 bg-sand-warm/50">
        <div className="container mx-auto px-4 text-center">
          <h2 className="font-heading text-2xl font-bold text-foreground mb-3">
            Provide a local service?
          </h2>
          <p className="text-muted-foreground mb-6 max-w-lg mx-auto">
            Get your business listed in our local services directory. Free for all Northam businesses.
          </p>
          <Button className="bg-accent hover:bg-accent/90">
            Add Your Business
          </Button>
        </div>
      </section>
    </Layout>
  );
};

// Directory Card Component - Simple, info-focused design
interface DirectoryCardProps {
  business: {
    name: string;
    type: string;
    address: string;
    phone: string;
    hours: string;
    website?: string;
    category: string;
  };
}

const DirectoryCard = ({ business }: DirectoryCardProps) => {
  const categoryColors: Record<string, string> = {
    retail: "bg-[hsl(var(--event-business))]",
    professional: "bg-[hsl(var(--event-venue))]",
  };

  return (
    <Card className="h-full border-2 border-border hover:border-primary/30 transition-colors">
      <CardContent className="p-6">
        {/* Header */}
        <div className="mb-4">
          <span 
            className={`inline-block px-2.5 py-1 rounded-full text-xs font-medium text-primary-foreground mb-3 ${categoryColors[business.category] || "bg-muted"}`}
          >
            {business.type}
          </span>
          <h3 className="font-heading text-xl font-bold text-foreground">
            {business.name}
          </h3>
        </div>

        {/* Info Grid */}
        <div className="space-y-3 text-sm">
          {/* Address */}
          <div className="flex items-start gap-3">
            <div className="w-8 h-8 rounded-lg bg-muted flex items-center justify-center flex-shrink-0">
              <MapPin className="w-4 h-4 text-muted-foreground" />
            </div>
            <span className="text-muted-foreground leading-relaxed pt-1">{business.address}</span>
          </div>

          {/* Phone */}
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
              <Phone className="w-4 h-4 text-primary" />
            </div>
            <a 
              href={`tel:${business.phone}`}
              className="font-medium text-foreground hover:text-primary transition-colors"
            >
              {business.phone}
            </a>
          </div>

          {/* Hours */}
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-lg bg-muted flex items-center justify-center flex-shrink-0">
              <Clock className="w-4 h-4 text-muted-foreground" />
            </div>
            <span className="text-muted-foreground">{business.hours}</span>
          </div>

          {/* Website */}
          {business.website && (
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                <Globe className="w-4 h-4 text-primary" />
              </div>
              <a 
                href={`https://${business.website}`}
                target="_blank"
                rel="noopener noreferrer"
                className="text-primary hover:underline truncate"
              >
                {business.website}
              </a>
            </div>
          )}
        </div>

        {/* Quick Action */}
        <div className="mt-6 pt-4 border-t border-border">
          <Button 
            variant="outline" 
            className="w-full" 
            asChild
          >
            <a href={`tel:${business.phone}`}>
              <Phone className="w-4 h-4 mr-2" />
              Call Now
            </a>
          </Button>
        </div>
      </CardContent>
    </Card>
  );
};

export default LocalServices;
