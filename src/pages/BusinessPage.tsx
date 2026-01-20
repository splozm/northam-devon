import { useParams, Link } from "react-router-dom";
import { 
  MapPin, Phone, Globe, Clock, Instagram, Facebook, 
  ChevronRight, Accessibility, ParkingCircle, Dog, 
  Calendar, ArrowLeft, ExternalLink
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import Layout from "@/components/layout/Layout";

// Sample business data - this would come from WordPress/database
const businesses: Record<string, Business> = {
  "the-kingsley": {
    id: "the-kingsley",
    name: "The Kingsley",
    category: "food-drink",
    type: "Traditional British Pub & Restaurant",
    heroImage: "/placeholder.svg",
    description: "A welcoming local pub in the heart of Northam serving traditional British fare and a great selection of local ales. Our cozy interior features a real fireplace, and our sunny beer garden is perfect for summer evenings. Dog-friendly throughout, we're a favourite gathering spot for locals and visitors alike.",
    specialties: ["Real Ales & Local Ciders", "Sunday Roasts", "Dog-Friendly Garden", "Live Music Saturdays", "Traditional Pub Grub"],
    accessibility: { wheelchair: true, parking: true, dogFriendly: true },
    hours: [
      { day: "Monday", open: "12:00", close: "23:00" },
      { day: "Tuesday", open: "12:00", close: "23:00" },
      { day: "Wednesday", open: "12:00", close: "23:00" },
      { day: "Thursday", open: "12:00", close: "23:00" },
      { day: "Friday", open: "12:00", close: "00:00" },
      { day: "Saturday", open: "12:00", close: "00:00" },
      { day: "Sunday", open: "12:00", close: "22:00" },
    ],
    phone: "01237 123456",
    address: "123 High Street, Northam, Devon EX39 1AB",
    website: "https://thekingsley.co.uk",
    instagram: "thekingsleynortham",
    facebook: "TheKingsleyNortham",
    instagramPosts: [
      { id: 1, image: "/placeholder.svg", caption: "Sunday roast special", likes: 42 },
      { id: 2, image: "/placeholder.svg", caption: "Live music tonight", likes: 28 },
      { id: 3, image: "/placeholder.svg", caption: "Beer garden vibes", likes: 65 },
      { id: 4, image: "/placeholder.svg", caption: "Fresh local ales", likes: 33 },
      { id: 5, image: "/placeholder.svg", caption: "Cosy fireplace", likes: 51 },
      { id: 6, image: "/placeholder.svg", caption: "Quiz night champions", likes: 24 },
      { id: 7, image: "/placeholder.svg", caption: "Chef's special", likes: 47 },
      { id: 8, image: "/placeholder.svg", caption: "Happy customers", likes: 39 },
    ],
    events: [
      { id: 1, title: "Open Mic Night", date: "2024-01-26", time: "19:00", image: "/placeholder.svg" },
      { id: 2, title: "Sunday Roast Service", date: "2024-01-28", time: "12:00", image: "/placeholder.svg" },
      { id: 3, title: "Quiz Night", date: "2024-01-25", time: "20:00", image: "/placeholder.svg" },
    ],
  },
  "coastal-cuts": {
    id: "coastal-cuts",
    name: "Coastal Cuts",
    category: "beauty-grooming",
    type: "Modern Hairdressing Salon",
    heroImage: "/placeholder.svg",
    description: "Contemporary hair styling in a relaxed, beachy atmosphere. Our talented team stays on top of the latest trends while offering classic cuts too. We use only high-quality, sustainable products and offer complimentary consultations for new clients.",
    specialties: ["Colour Specialists", "Wedding Hair", "Men's Cuts", "Sustainable Products", "Free Consultations"],
    accessibility: { wheelchair: true, parking: false, dogFriendly: false },
    hours: [
      { day: "Monday", open: "Closed", close: "" },
      { day: "Tuesday", open: "09:00", close: "17:30" },
      { day: "Wednesday", open: "09:00", close: "17:30" },
      { day: "Thursday", open: "09:00", close: "19:00" },
      { day: "Friday", open: "09:00", close: "18:00" },
      { day: "Saturday", open: "08:30", close: "16:00" },
      { day: "Sunday", open: "Closed", close: "" },
    ],
    phone: "01237 234567",
    address: "45 Fore Street, Northam, Devon EX39 1AW",
    website: "https://coastalcuts.co.uk",
    instagram: "coastalcutsnortham",
    facebook: "CoastalCutsNortham",
    instagramPosts: [
      { id: 1, image: "/placeholder.svg", caption: "Balayage transformation", likes: 89 },
      { id: 2, image: "/placeholder.svg", caption: "Fresh fade", likes: 45 },
      { id: 3, image: "/placeholder.svg", caption: "Wedding hair prep", likes: 112 },
      { id: 4, image: "/placeholder.svg", caption: "Before & after", likes: 76 },
      { id: 5, image: "/placeholder.svg", caption: "New colour range", likes: 58 },
      { id: 6, image: "/placeholder.svg", caption: "Summer styles", likes: 94 },
    ],
    events: [],
  },
  "pawfect-paws": {
    id: "pawfect-paws",
    name: "Pawfect Paws",
    category: "beauty-grooming",
    type: "Dog Grooming Specialist",
    heroImage: "/placeholder.svg",
    description: "Professional dog grooming with a gentle touch. We treat every pup like our own, offering breed-specific cuts, hand stripping, and spa treatments. Nervous dogs welcome — we have plenty of experience with anxious pups.",
    specialties: ["Breed-Specific Cuts", "Hand Stripping", "Puppy First Groom", "Nail Trimming", "Nervous Dogs Welcome"],
    accessibility: { wheelchair: false, parking: true, dogFriendly: true },
    hours: [
      { day: "Monday", open: "08:00", close: "17:00" },
      { day: "Tuesday", open: "08:00", close: "17:00" },
      { day: "Wednesday", open: "08:00", close: "17:00" },
      { day: "Thursday", open: "08:00", close: "17:00" },
      { day: "Friday", open: "08:00", close: "17:00" },
      { day: "Saturday", open: "09:00", close: "14:00" },
      { day: "Sunday", open: "Closed", close: "" },
    ],
    phone: "01237 345678",
    address: "12 Market Lane, Northam, Devon EX39 1BD",
    instagram: "pawfectpawsnortham",
    facebook: "PawfectPawsNortham",
    instagramPosts: [
      { id: 1, image: "/placeholder.svg", caption: "Fluffy boi transformation", likes: 134 },
      { id: 2, image: "/placeholder.svg", caption: "Happy customer", likes: 98 },
      { id: 3, image: "/placeholder.svg", caption: "Spa day!", likes: 145 },
      { id: 4, image: "/placeholder.svg", caption: "Before & after", likes: 167 },
      { id: 5, image: "/placeholder.svg", caption: "First groom success", likes: 189 },
    ],
    events: [
      { id: 1, title: "Puppy Grooming Workshop", date: "2024-02-10", time: "10:00", image: "/placeholder.svg" },
    ],
  },
};

interface Business {
  id: string;
  name: string;
  category: string;
  type: string;
  heroImage: string;
  description: string;
  specialties: string[];
  accessibility: { wheelchair: boolean; parking: boolean; dogFriendly: boolean };
  hours: { day: string; open: string; close: string }[];
  phone: string;
  address: string;
  website?: string;
  instagram?: string;
  facebook?: string;
  instagramPosts: { id: number; image: string; caption: string; likes: number }[];
  events: { id: number; title: string; date: string; time: string; image: string }[];
}

const BusinessPage = () => {
  const { slug } = useParams<{ slug: string }>();
  const business = slug ? businesses[slug] : null;

  if (!business) {
    return (
      <Layout>
        <div className="container mx-auto px-4 py-24 text-center">
          <h1 className="font-heading text-3xl font-bold text-foreground mb-4">Business Not Found</h1>
          <p className="text-muted-foreground mb-6">Sorry, we couldn't find that business.</p>
          <Button asChild>
            <Link to="/directory">Back to Directory</Link>
          </Button>
        </div>
      </Layout>
    );
  }

  const today = new Date().toLocaleDateString('en-GB', { weekday: 'long' });
  const todayHours = business.hours.find(h => h.day === today);

  const categoryColors: Record<string, string> = {
    "food-drink": "bg-[hsl(var(--event-venue))]",
    "beauty-grooming": "bg-[hsl(var(--event-business))]",
  };

  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative h-[50vh] md:h-[60vh] min-h-[400px]">
        <div 
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: `url(${business.heroImage})` }}
        />
        <div className="absolute inset-0 gradient-hero-overlay" />
        
        {/* Back Button */}
        <div className="absolute top-6 left-4 md:left-8 z-10">
          <Button 
            variant="ghost" 
            asChild 
            className="text-primary-foreground hover:bg-primary-foreground/20"
          >
            <Link to="/directory">
              <ArrowLeft className="w-4 h-4 mr-2" />
              Back to Directory
            </Link>
          </Button>
        </div>

        {/* Hero Content */}
        <div className="absolute bottom-0 left-0 right-0 p-6 md:p-12">
          <div className="container mx-auto">
            <Badge className={`${categoryColors[business.category] || "bg-primary"} text-primary-foreground mb-4`}>
              {business.type}
            </Badge>
            <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">
              {business.name}
            </h1>
            {/* Accessibility Indicators */}
            <div className="flex flex-wrap gap-3">
              {business.accessibility.wheelchair && (
                <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm">
                  <Accessibility className="w-4 h-4" /> Wheelchair Accessible
                </span>
              )}
              {business.accessibility.parking && (
                <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm">
                  <ParkingCircle className="w-4 h-4" /> Parking Available
                </span>
              )}
              {business.accessibility.dogFriendly && (
                <span className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm">
                  <Dog className="w-4 h-4" /> Dog Friendly
                </span>
              )}
            </div>
          </div>
        </div>
      </section>

      {/* Main Content */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <div className="grid lg:grid-cols-3 gap-8 lg:gap-12">
            {/* Left Column - Main Content */}
            <div className="lg:col-span-2 space-y-12">
              {/* About Section */}
              <div>
                <h2 className="font-heading text-2xl md:text-3xl font-bold text-foreground mb-4">
                  About {business.name}
                </h2>
                <p className="text-muted-foreground text-lg leading-relaxed mb-6">
                  {business.description}
                </p>
                
                {/* Specialties */}
                <div className="flex flex-wrap gap-2">
                  {business.specialties.map((specialty, i) => (
                    <Badge key={i} variant="secondary" className="text-sm py-1.5 px-3">
                      {specialty}
                    </Badge>
                  ))}
                </div>
              </div>

              {/* Instagram Feed Section */}
              {business.instagramPosts.length > 0 && (
                <div>
                  <div className="flex items-center justify-between mb-6">
                    <h2 className="font-heading text-2xl md:text-3xl font-bold text-foreground flex items-center gap-3">
                      <Instagram className="w-7 h-7 text-accent" />
                      Latest from Instagram
                    </h2>
                    {business.instagram && (
                      <Button variant="outline" asChild className="hidden sm:inline-flex">
                        <a 
                          href={`https://instagram.com/${business.instagram}`} 
                          target="_blank" 
                          rel="noopener noreferrer"
                        >
                          @{business.instagram}
                          <ExternalLink className="w-4 h-4 ml-2" />
                        </a>
                      </Button>
                    )}
                  </div>

                  {/* Masonry Grid */}
                  <div className="columns-2 md:columns-3 gap-4 space-y-4">
                    {business.instagramPosts.map((post, index) => (
                      <div 
                        key={post.id}
                        className="break-inside-avoid group cursor-pointer"
                      >
                        <div 
                          className="relative overflow-hidden rounded-xl bg-muted"
                          style={{ 
                            // Varied heights for masonry effect
                            aspectRatio: index % 3 === 0 ? '3/4' : index % 3 === 1 ? '1/1' : '4/3'
                          }}
                        >
                          <img 
                            src={post.image} 
                            alt={post.caption}
                            className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                          />
                          <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                          <div className="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <p className="text-white text-sm line-clamp-2">{post.caption}</p>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>

                  {business.instagram && (
                    <div className="mt-6 text-center sm:hidden">
                      <Button variant="outline" asChild>
                        <a 
                          href={`https://instagram.com/${business.instagram}`} 
                          target="_blank" 
                          rel="noopener noreferrer"
                        >
                          View @{business.instagram} on Instagram
                          <ExternalLink className="w-4 h-4 ml-2" />
                        </a>
                      </Button>
                    </div>
                  )}
                </div>
              )}

              {/* Upcoming Events Section */}
              <div>
                <h2 className="font-heading text-2xl md:text-3xl font-bold text-foreground mb-6 flex items-center gap-3">
                  <Calendar className="w-7 h-7 text-primary" />
                  Upcoming Events
                </h2>

                {business.events.length > 0 ? (
                  <div className="grid sm:grid-cols-2 gap-4">
                    {business.events.map((event) => (
                      <Card key={event.id} className="overflow-hidden hover-lift group cursor-pointer">
                        <div className="flex">
                          <div className="w-24 h-24 flex-shrink-0 bg-muted">
                            <img 
                              src={event.image} 
                              alt={event.title}
                              className="w-full h-full object-cover"
                            />
                          </div>
                          <CardContent className="p-4 flex flex-col justify-center">
                            <h3 className="font-heading font-semibold text-foreground group-hover:text-primary transition-colors">
                              {event.title}
                            </h3>
                            <p className="text-sm text-muted-foreground mt-1">
                              {new Date(event.date).toLocaleDateString('en-GB', { 
                                weekday: 'short', 
                                day: 'numeric', 
                                month: 'short' 
                              })} at {event.time}
                            </p>
                          </CardContent>
                        </div>
                      </Card>
                    ))}
                  </div>
                ) : (
                  <Card className="bg-muted/50 border-dashed">
                    <CardContent className="p-8 text-center">
                      <Calendar className="w-12 h-12 mx-auto text-muted-foreground/50 mb-3" />
                      <p className="text-muted-foreground">
                        No upcoming events scheduled. Check back soon!
                      </p>
                    </CardContent>
                  </Card>
                )}
              </div>
            </div>

            {/* Right Column - Contact Info Sidebar */}
            <div className="lg:col-span-1">
              <div className="sticky top-24">
                <Card className="overflow-hidden">
                  <CardContent className="p-0">
                    {/* Today's Hours Highlight */}
                    <div className="bg-primary p-5 text-primary-foreground">
                      <div className="flex items-center gap-2 mb-2">
                        <Clock className="w-5 h-5" />
                        <span className="font-medium">Today's Hours</span>
                      </div>
                      <p className="text-2xl font-heading font-bold">
                        {todayHours?.open === "Closed" 
                          ? "Closed" 
                          : `${todayHours?.open} – ${todayHours?.close}`
                        }
                      </p>
                    </div>

                    {/* Full Hours */}
                    <div className="p-5 border-b border-border">
                      <h3 className="font-heading font-semibold text-foreground mb-3">Opening Hours</h3>
                      <ul className="space-y-2 text-sm">
                        {business.hours.map((h) => (
                          <li 
                            key={h.day}
                            className={`flex justify-between ${h.day === today ? 'font-semibold text-primary' : 'text-muted-foreground'}`}
                          >
                            <span>{h.day}</span>
                            <span>
                              {h.open === "Closed" ? "Closed" : `${h.open} – ${h.close}`}
                            </span>
                          </li>
                        ))}
                      </ul>
                    </div>

                    {/* Contact Details */}
                    <div className="p-5 space-y-4">
                      {/* Phone */}
                      <a 
                        href={`tel:${business.phone}`}
                        className="flex items-center gap-3 text-foreground hover:text-primary transition-colors group"
                      >
                        <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                          <Phone className="w-5 h-5 text-primary" />
                        </div>
                        <span className="font-medium">{business.phone}</span>
                      </a>

                      {/* Address */}
                      <div className="flex items-start gap-3 text-muted-foreground">
                        <div className="w-10 h-10 rounded-full bg-muted flex items-center justify-center flex-shrink-0">
                          <MapPin className="w-5 h-5" />
                        </div>
                        <span className="text-sm leading-relaxed">{business.address}</span>
                      </div>

                      {/* Website */}
                      {business.website && (
                        <a 
                          href={business.website}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="flex items-center gap-3 text-foreground hover:text-primary transition-colors group"
                        >
                          <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                            <Globe className="w-5 h-5 text-primary" />
                          </div>
                          <span className="font-medium truncate">
                            {business.website.replace(/^https?:\/\//, '')}
                          </span>
                        </a>
                      )}

                      {/* Social Links */}
                      <div className="flex gap-3 pt-2">
                        {business.instagram && (
                          <a
                            href={`https://instagram.com/${business.instagram}`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400 flex items-center justify-center text-white hover:opacity-90 transition-opacity"
                          >
                            <Instagram className="w-5 h-5" />
                          </a>
                        )}
                        {business.facebook && (
                          <a
                            href={`https://facebook.com/${business.facebook}`}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-primary-foreground hover:opacity-90 transition-opacity"
                          >
                            <Facebook className="w-5 h-5" />
                          </a>
                        )}
                      </div>
                    </div>

                    {/* Map Placeholder */}
                    <div className="h-48 bg-muted relative">
                      <div className="absolute inset-0 flex items-center justify-center">
                        <div className="text-center">
                          <MapPin className="w-8 h-8 mx-auto text-muted-foreground/50 mb-2" />
                          <p className="text-sm text-muted-foreground">Google Map</p>
                        </div>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              </div>
            </div>
          </div>
        </div>
      </section>
    </Layout>
  );
};

export default BusinessPage;
