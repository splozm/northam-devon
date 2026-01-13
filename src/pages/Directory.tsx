import { useState } from "react";
import { Search, Filter, MapPin, Phone, Globe, ChevronRight, Store, UtensilsCrossed, Bed, ShoppingBag, Wrench, Heart } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import Layout from "@/components/layout/Layout";

// Sample business data
const allBusinesses = [
  {
    id: 1,
    name: "The Kingsley",
    category: "pubs",
    type: "Traditional Pub",
    description: "Historic village pub serving real ales and home-cooked food in a welcoming atmosphere. Live music every Saturday.",
    phone: "01237 123456",
    website: "thekingsley.co.uk",
    address: "Cross Street, Northam",
  },
  {
    id: 2,
    name: "Sea Horse Fish & Chips",
    category: "food",
    type: "Takeaway",
    description: "Fresh, locally-caught fish and hand-cut chips since 1985. Award-winning battered cod.",
    phone: "01237 234567",
    address: "The Square, Northam",
  },
  {
    id: 3,
    name: "Trattoria Bella",
    category: "food",
    type: "Italian Restaurant",
    description: "Authentic Italian cuisine in a cosy setting. Fresh pasta made daily, extensive wine list.",
    phone: "01237 345678",
    website: "trattoriabella.co.uk",
    address: "High Street, Northam",
  },
  {
    id: 4,
    name: "Northam B&B",
    category: "accommodation",
    type: "Bed & Breakfast",
    description: "Charming B&B with sea views. Full English breakfast included. Dog-friendly rooms available.",
    phone: "01237 456789",
    website: "northambandb.co.uk",
    address: "Fore Street, Northam",
  },
  {
    id: 5,
    name: "Coastal Crafts",
    category: "shops",
    type: "Gift Shop",
    description: "Handmade gifts from local artisans. Pottery, jewellery, artwork, and Devon souvenirs.",
    phone: "01237 567890",
    address: "Market Street, Northam",
  },
  {
    id: 6,
    name: "Green Thumb Garden Centre",
    category: "shops",
    type: "Garden Centre",
    description: "Plants, seeds, and gardening supplies. Expert advice for Devon's coastal gardens.",
    phone: "01237 678901",
    address: "Bideford Road, Northam",
  },
  {
    id: 7,
    name: "Northam Motors",
    category: "services",
    type: "Garage",
    description: "Full MOT and servicing. Reliable repairs from a family-run business for over 30 years.",
    phone: "01237 789012",
    address: "Industrial Estate, Northam",
  },
  {
    id: 8,
    name: "The Wellness Room",
    category: "health",
    type: "Therapy Centre",
    description: "Massage, reflexology, and holistic therapies. Helping you relax and recharge by the coast.",
    phone: "01237 890123",
    website: "wellnessroom.co.uk",
    address: "Church Lane, Northam",
  },
  {
    id: 9,
    name: "Royal George Inn",
    category: "pubs",
    type: "Country Pub",
    description: "17th century coaching inn with beer garden. Sunday roasts a speciality.",
    phone: "01237 901234",
    address: "Fore Street, Northam",
  },
];

const categories = [
  { id: "all", label: "All", icon: Store },
  { id: "food", label: "Food & Drink", icon: UtensilsCrossed },
  { id: "pubs", label: "Pubs", icon: UtensilsCrossed },
  { id: "accommodation", label: "Stays", icon: Bed },
  { id: "shops", label: "Shops", icon: ShoppingBag },
  { id: "services", label: "Services", icon: Wrench },
  { id: "health", label: "Health", icon: Heart },
];

const Directory = () => {
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [searchQuery, setSearchQuery] = useState("");

  const filteredBusinesses = allBusinesses.filter((business) => {
    const matchesCategory = selectedCategory === "all" || business.category === selectedCategory;
    const matchesSearch = business.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                          business.description.toLowerCase().includes(searchQuery.toLowerCase());
    return matchesCategory && matchesSearch;
  });

  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative py-16 md:py-24 bg-coastal-deep">
        <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center animate-fade-in">
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
              <Store className="w-4 h-4" />
              Local Businesses
            </span>
            <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">
              Business Directory
            </h1>
            <p className="text-xl text-primary-foreground/80 max-w-2xl mx-auto">
              Support local — discover the shops, services, and eateries that make Northam special
            </p>
          </div>
        </div>
      </section>

      {/* Search & Filters */}
      <section className="sticky top-16 md:top-20 z-40 bg-card border-b border-border shadow-sm">
        <div className="container mx-auto px-4 py-4">
          <div className="flex flex-col md:flex-row gap-4">
            {/* Search */}
            <div className="relative flex-grow max-w-md">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <Input
                placeholder="Search businesses..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-10"
              />
            </div>

            {/* Category Filters */}
            <div className="flex flex-wrap items-center gap-2">
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
        </div>
      </section>

      {/* Business Grid */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <p className="text-muted-foreground">
              Showing <span className="font-medium text-foreground">{filteredBusinesses.length}</span> businesses
            </p>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredBusinesses.map((business) => (
              <BusinessCard key={business.id} business={business} />
            ))}
          </div>

          {filteredBusinesses.length === 0 && (
            <div className="text-center py-16">
              <Store className="w-16 h-16 mx-auto text-muted-foreground/50 mb-4" />
              <h3 className="font-heading text-xl font-semibold text-foreground mb-2">
                No businesses found
              </h3>
              <p className="text-muted-foreground">
                Try adjusting your search or category filters.
              </p>
            </div>
          )}
        </div>
      </section>

      {/* Add Business CTA */}
      <section className="py-12 bg-sand-warm/50">
        <div className="container mx-auto px-4 text-center">
          <h2 className="font-heading text-2xl font-bold text-foreground mb-3">
            Own a local business?
          </h2>
          <p className="text-muted-foreground mb-6 max-w-lg mx-auto">
            Get your business listed in our directory. It's free for all Northam businesses.
          </p>
          <Button className="bg-accent hover:bg-accent/90">
            Add Your Business
          </Button>
        </div>
      </section>
    </Layout>
  );
};

// Business Card Component
interface BusinessCardProps {
  business: {
    name: string;
    type: string;
    description: string;
    phone: string;
    website?: string;
    address: string;
  };
}

const BusinessCard = ({ business }: BusinessCardProps) => (
  <Card className="overflow-hidden hover-lift cursor-pointer group h-full flex flex-col">
    <div className="h-36 bg-gradient-to-br from-coastal-mid to-coastal-light flex items-center justify-center">
      <Store className="w-10 h-10 text-primary-foreground/30" />
    </div>
    <CardContent className="p-5 flex flex-col flex-grow">
      <span className="text-xs font-medium text-muted-foreground uppercase tracking-wide">
        {business.type}
      </span>
      <h3 className="font-heading text-xl font-semibold text-card-foreground mt-1 mb-2 group-hover:text-primary transition-colors">
        {business.name}
      </h3>
      <p className="text-sm text-muted-foreground line-clamp-2 mb-4 flex-grow">
        {business.description}
      </p>
      <div className="space-y-2 text-sm text-muted-foreground border-t border-border pt-4">
        <div className="flex items-center gap-2">
          <MapPin className="w-4 h-4 flex-shrink-0" />
          <span className="truncate">{business.address}</span>
        </div>
        <div className="flex items-center gap-2">
          <Phone className="w-4 h-4 flex-shrink-0" />
          {business.phone}
        </div>
        {business.website && (
          <div className="flex items-center gap-2">
            <Globe className="w-4 h-4 flex-shrink-0" />
            <span className="truncate text-primary">{business.website}</span>
          </div>
        )}
      </div>
    </CardContent>
  </Card>
);

export default Directory;
