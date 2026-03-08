import { useState } from "react";
import { Calendar, CalendarDays, MapPin, Clock, Filter, ChevronLeft, ChevronRight, Grid, List } from "lucide-react";
import EventCalendarView from "@/components/events/EventCalendarView";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import Layout from "@/components/layout/Layout";

// Sample events data
// Event categories
const eventTypes = [
  { id: "all", label: "All Events" },
  { id: "kids", label: "Kids & Family" },
  { id: "active", label: "Active & Outdoors" },
  { id: "food", label: "Food & Drink" },
  { id: "arts", label: "Arts & Culture" },
  { id: "community", label: "Community & Social" },
];

// Sample events data
const allEvents = [
  {
    id: 1,
    title: "Northam Hall Quiz Night",
    date: "2025-01-17",
    displayDate: "Friday, 17th January",
    time: "7:30 PM",
    venue: "Northam Hall",
    type: "arts" as const,
    description: "Test your knowledge at our popular monthly quiz. Teams of up to 6 people, £2 per person.",
  },
  {
    id: 2,
    title: "Live Music at The Kingsley",
    date: "2025-01-18",
    displayDate: "Saturday, 18th January",
    time: "8:00 PM",
    venue: "The Kingsley Pub",
    type: "arts" as const,
    description: "Local band 'The Coastliners' perform classic rock and folk favourites.",
  },
  {
    id: 3,
    title: "Family Craft Morning",
    date: "2025-01-21",
    displayDate: "Tuesday, 21st January",
    time: "10:00 AM",
    venue: "Community Hall",
    type: "kids" as const,
    description: "Creative crafts for children aged 3-10. Materials provided. £3 per child.",
  },
  {
    id: 4,
    title: "Farmers Market",
    date: "2025-01-25",
    displayDate: "Saturday, 25th January",
    time: "9:00 AM - 1:00 PM",
    venue: "Northam Square",
    type: "food" as const,
    description: "Fresh local produce, artisan breads, cheeses, and seasonal vegetables.",
  },
  {
    id: 5,
    title: "Beach Clean-up Walk",
    date: "2025-01-26",
    displayDate: "Sunday, 26th January",
    time: "10:00 AM",
    venue: "Westward Ho! Beach",
    type: "active" as const,
    description: "Help keep our beautiful coastline clean. Equipment provided, all ages welcome.",
  },
  {
    id: 6,
    title: "Italian Night at Trattoria",
    date: "2025-01-31",
    displayDate: "Friday, 31st January",
    time: "6:00 PM",
    venue: "Trattoria Bella",
    type: "food" as const,
    description: "Special tasting menu with wine pairings. Booking essential.",
  },
  {
    id: 7,
    title: "Yoga on the Burrows",
    date: "2025-02-03",
    displayDate: "Monday, 3rd February",
    time: "9:30 AM",
    venue: "Northam Burrows",
    type: "active" as const,
    description: "Outdoor yoga session (weather permitting). Bring your own mat. £5 donation.",
  },
  {
    id: 8,
    title: "Storytime & Singing",
    date: "2025-02-08",
    displayDate: "Saturday, 8th February",
    time: "10:30 AM",
    venue: "Northam Library",
    type: "kids" as const,
    description: "Fun stories and songs for under-5s. Free, no booking required.",
  },
  {
    id: 9,
    title: "Coffee Morning & Chat",
    date: "2025-02-05",
    displayDate: "Wednesday, 5th February",
    time: "10:00 AM",
    venue: "Northam Hall",
    type: "community" as const,
    description: "Drop in for a cuppa and a natter. Meet neighbours and make new friends. All welcome.",
  },
  {
    id: 10,
    title: "Parish Council Meeting",
    date: "2025-02-12",
    displayDate: "Wednesday, 12th February",
    time: "7:00 PM",
    venue: "Community Hall",
    type: "community" as const,
    description: "Open public meeting to discuss local planning and community matters.",
  },
];

const Events = () => {
  const [selectedType, setSelectedType] = useState("all");
  const [viewMode, setViewMode] = useState<"grid" | "list" | "calendar">("grid");

  const filteredEvents = selectedType === "all" 
    ? allEvents 
    : allEvents.filter(event => event.type === selectedType);

  return (
    <Layout>
      {/* Hero Section */}
      <section className="relative py-16 md:py-24 bg-coastal-deep">
        <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center animate-fade-in">
            <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
              <Calendar className="w-4 h-4" />
              What's On
            </span>
            <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">
              Events Calendar
            </h1>
            <p className="text-xl text-primary-foreground/80 max-w-2xl mx-auto">
              Discover what's happening in Northam — from community gatherings to live entertainment
            </p>
          </div>
        </div>
      </section>

      {/* Filters & Controls */}
      <section className="sticky top-16 md:top-20 z-40 bg-card border-b border-border shadow-sm">
        <div className="container mx-auto px-4 py-4">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            {/* Event Type Filters */}
            <div className="flex flex-wrap items-center gap-2">
              <Filter className="w-4 h-4 text-muted-foreground" />
              {eventTypes.map((type) => (
                <Button
                  key={type.id}
                  size="sm"
                  variant={selectedType === type.id ? "default" : "outline"}
                  onClick={() => setSelectedType(type.id)}
                  className="text-sm"
                >
                  {type.label}
                </Button>
              ))}
            </div>

            {/* View Toggle */}
            <div className="flex items-center gap-2">
              <Button
                size="sm"
                variant={viewMode === "grid" ? "secondary" : "ghost"}
                onClick={() => setViewMode("grid")}
              >
                <Grid className="w-4 h-4" />
              </Button>
              <Button
                size="sm"
                variant={viewMode === "list" ? "secondary" : "ghost"}
                onClick={() => setViewMode("list")}
              >
                <List className="w-4 h-4" />
              </Button>
              <Button
                size="sm"
                variant={viewMode === "calendar" ? "secondary" : "ghost"}
                onClick={() => setViewMode("calendar")}
              >
                <CalendarDays className="w-4 h-4" />
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Events Grid/List */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between mb-8">
            <p className="text-muted-foreground">
              Showing <span className="font-medium text-foreground">{filteredEvents.length}</span> events
            </p>
          </div>

          {viewMode === "calendar" ? (
            <EventCalendarView events={filteredEvents} />
          ) : viewMode === "grid" ? (
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {filteredEvents.map((event) => (
                <EventCard key={event.id} event={event} />
              ))}
            </div>
          ) : (
            <div className="space-y-4">
              {filteredEvents.map((event) => (
                <EventListItem key={event.id} event={event} />
              ))}
            </div>
          )}

          {filteredEvents.length === 0 && (
            <div className="text-center py-16">
              <Calendar className="w-16 h-16 mx-auto text-muted-foreground/50 mb-4" />
              <h3 className="font-heading text-xl font-semibold text-foreground mb-2">
                No events found
              </h3>
              <p className="text-muted-foreground">
                Try selecting a different category or check back later.
              </p>
            </div>
          )}
        </div>
      </section>

      {/* Submit Event CTA */}
      <section className="py-12 bg-sand-warm/50">
        <div className="container mx-auto px-4 text-center">
          <h2 className="font-heading text-2xl font-bold text-foreground mb-3">
            Have an event to share?
          </h2>
          <p className="text-muted-foreground mb-6 max-w-lg mx-auto">
            If you're hosting an event in Northam, let us know and we'll add it to the calendar.
          </p>
          <Button className="bg-accent hover:bg-accent/90">
            Submit an Event
          </Button>
        </div>
      </section>
    </Layout>
  );
};

// Event Card Component
interface EventCardProps {
  event: {
    title: string;
    displayDate: string;
    time: string;
    venue: string;
    type: "kids" | "active" | "food" | "arts" | "community";
    description: string;
  };
}

const EventCard = ({ event }: EventCardProps) => {
  const typeStyles = {
    kids: { border: "border-l-amber-500", badge: "bg-amber-500/10 text-amber-700" },
    active: { border: "border-l-green-500", badge: "bg-green-500/10 text-green-700" },
    food: { border: "border-l-orange-500", badge: "bg-orange-500/10 text-orange-700" },
    arts: { border: "border-l-purple-500", badge: "bg-purple-500/10 text-purple-700" },
    community: { border: "border-l-blue-500", badge: "bg-blue-500/10 text-blue-700" },
  };

  const typeLabels = {
    kids: "Kids & Family",
    active: "Active & Outdoors",
    food: "Food & Drink",
    arts: "Arts & Culture",
    community: "Community & Social",
  };

  return (
    <Card className={`border-l-4 ${typeStyles[event.type].border} hover-lift cursor-pointer group h-full`}>
      <CardContent className="p-5 flex flex-col h-full">
        <span className={`inline-block text-xs font-medium ${typeStyles[event.type].badge} px-2 py-1 rounded-full mb-3 self-start`}>
          {typeLabels[event.type]}
        </span>
        <h3 className="font-heading text-xl font-semibold text-card-foreground mb-2 group-hover:text-primary transition-colors">
          {event.title}
        </h3>
        <p className="text-sm text-muted-foreground mb-4 line-clamp-2 flex-grow">
          {event.description}
        </p>
        <div className="space-y-2 text-sm text-muted-foreground border-t border-border pt-4 mt-auto">
          <div className="flex items-center gap-2">
            <Calendar className="w-4 h-4 flex-shrink-0" />
            {event.displayDate}
          </div>
          <div className="flex items-center gap-2">
            <Clock className="w-4 h-4 flex-shrink-0" />
            {event.time}
          </div>
          <div className="flex items-center gap-2">
            <MapPin className="w-4 h-4 flex-shrink-0" />
            {event.venue}
          </div>
        </div>
      </CardContent>
    </Card>
  );
};

// Event List Item Component
const EventListItem = ({ event }: EventCardProps) => {
  const typeStyles = {
    kids: { border: "border-l-amber-500", badge: "bg-amber-500/10 text-amber-700" },
    active: { border: "border-l-green-500", badge: "bg-green-500/10 text-green-700" },
    food: { border: "border-l-orange-500", badge: "bg-orange-500/10 text-orange-700" },
    arts: { border: "border-l-purple-500", badge: "bg-purple-500/10 text-purple-700" },
    community: { border: "border-l-blue-500", badge: "bg-blue-500/10 text-blue-700" },
  };

  const typeLabels = {
    kids: "Kids & Family",
    active: "Active & Outdoors",
    food: "Food & Drink",
    arts: "Arts & Culture",
    community: "Community & Social",
  };

  return (
    <Card className={`border-l-4 ${typeStyles[event.type].border} hover-lift cursor-pointer group`}>
      <CardContent className="p-5">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div className="flex-grow">
            <div className="flex items-center gap-3 mb-2">
              <span className={`text-xs font-medium ${typeStyles[event.type].badge} px-2 py-1 rounded-full`}>
                {typeLabels[event.type]}
              </span>
              <h3 className="font-heading text-lg font-semibold text-card-foreground group-hover:text-primary transition-colors">
                {event.title}
              </h3>
            </div>
            <p className="text-sm text-muted-foreground line-clamp-1">
              {event.description}
            </p>
          </div>
          <div className="flex items-center gap-6 text-sm text-muted-foreground">
            <div className="flex items-center gap-2">
              <Calendar className="w-4 h-4" />
              {event.displayDate}
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
        </div>
      </CardContent>
    </Card>
  );
};

export default Events;
