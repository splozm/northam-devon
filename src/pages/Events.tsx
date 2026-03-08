import { useState } from "react";
import { Calendar, Filter } from "lucide-react";
import EventCalendarView from "@/components/events/EventCalendarView";
import { Button } from "@/components/ui/button";
import { Button } from "@/components/ui/button";
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
  { id: 1, title: "Northam Hall Quiz Night", date: "2026-03-06", displayDate: "Friday, 6th March", time: "7:30 PM", venue: "Northam Hall", type: "arts" as const, description: "Test your knowledge at our popular monthly quiz. Teams of up to 6 people, £2 per person." },
  { id: 2, title: "Live Music at The Kingsley", date: "2026-03-07", displayDate: "Saturday, 7th March", time: "8:00 PM", venue: "The Kingsley Pub", type: "arts" as const, description: "Local band 'The Coastliners' perform classic rock and folk favourites." },
  { id: 3, title: "Family Craft Morning", date: "2026-03-08", displayDate: "Sunday, 8th March", time: "10:00 AM", venue: "Community Hall", type: "kids" as const, description: "Creative crafts for children aged 3-10. Materials provided. £3 per child." },
  { id: 4, title: "Farmers Market", date: "2026-03-08", displayDate: "Sunday, 8th March", time: "9:00 AM - 1:00 PM", venue: "Northam Square", type: "food" as const, description: "Fresh local produce, artisan breads, cheeses, and seasonal vegetables." },
  { id: 5, title: "Beach Clean-up Walk", date: "2026-03-09", displayDate: "Monday, 9th March", time: "10:00 AM", venue: "Westward Ho! Beach", type: "active" as const, description: "Help keep our beautiful coastline clean. Equipment provided, all ages welcome." },
  { id: 6, title: "Italian Night at Trattoria", date: "2026-03-10", displayDate: "Tuesday, 10th March", time: "6:00 PM", venue: "Trattoria Bella", type: "food" as const, description: "Special tasting menu with wine pairings. Booking essential." },
  { id: 7, title: "Yoga on the Burrows", date: "2026-03-11", displayDate: "Wednesday, 11th March", time: "9:30 AM", venue: "Northam Burrows", type: "active" as const, description: "Outdoor yoga session (weather permitting). Bring your own mat. £5 donation." },
  { id: 8, title: "Storytime & Singing", date: "2026-03-11", displayDate: "Wednesday, 11th March", time: "10:30 AM", venue: "Northam Library", type: "kids" as const, description: "Fun stories and songs for under-5s. Free, no booking required." },
  { id: 9, title: "Coffee Morning & Chat", date: "2026-03-12", displayDate: "Thursday, 12th March", time: "10:00 AM", venue: "Northam Hall", type: "community" as const, description: "Drop in for a cuppa and a natter. Meet neighbours and make new friends." },
  { id: 10, title: "Parish Council Meeting", date: "2026-03-12", displayDate: "Thursday, 12th March", time: "7:00 PM", venue: "Community Hall", type: "community" as const, description: "Open public meeting to discuss local planning and community matters." },
  { id: 11, title: "Toddler Splash Session", date: "2026-03-13", displayDate: "Friday, 13th March", time: "11:00 AM", venue: "Northam Pool", type: "kids" as const, description: "Supervised splash session for toddlers aged 1-4. Floats provided." },
  { id: 12, title: "Sunset Coastal Run", date: "2026-03-13", displayDate: "Friday, 13th March", time: "5:30 PM", venue: "Pebble Ridge", type: "active" as const, description: "5K social run along the coast. All abilities welcome." },
  { id: 13, title: "Open Mic Night", date: "2026-03-14", displayDate: "Saturday, 14th March", time: "7:00 PM", venue: "The Union Inn", type: "arts" as const, description: "Singers, poets, comedians — all welcome to take the stage." },
  { id: 14, title: "Vegan Street Food Pop-up", date: "2026-03-14", displayDate: "Saturday, 14th March", time: "12:00 PM", venue: "Northam Square", type: "food" as const, description: "Local vendors serving plant-based street food. Live acoustic music." },
  { id: 15, title: "Community Litter Pick", date: "2026-03-15", displayDate: "Sunday, 15th March", time: "9:00 AM", venue: "Bone Hill Road", type: "community" as const, description: "Volunteer litter pick around the village. Bags and pickers provided." },
  { id: 16, title: "Pilates in the Park", date: "2026-03-16", displayDate: "Monday, 16th March", time: "10:00 AM", venue: "Northam Burrows", type: "active" as const, description: "Gentle Pilates class in the fresh air. Bring a mat. £4." },
  { id: 17, title: "Book Club Meeting", date: "2026-03-17", displayDate: "Tuesday, 17th March", time: "7:30 PM", venue: "Northam Library", type: "community" as const, description: "This month: 'The Salt Path' by Raynor Winn. New members welcome." },
  { id: 18, title: "Kids Nature Trail", date: "2026-03-18", displayDate: "Wednesday, 18th March", time: "2:00 PM", venue: "Northam Burrows", type: "kids" as const, description: "Guided nature walk for families. Spot birds, bugs, and wildflowers." },
  { id: 19, title: "Fish & Chip Friday", date: "2026-03-20", displayDate: "Friday, 20th March", time: "5:00 PM", venue: "Northam Hall", type: "food" as const, description: "Community fish supper with raffle. £6 adults, £3 children." },
  { id: 20, title: "Art Exhibition Opening", date: "2026-03-21", displayDate: "Saturday, 21st March", time: "11:00 AM", venue: "The Burton Gallery", type: "arts" as const, description: "New exhibition showcasing local Devon artists. Free entry, donations welcome." },
];
const Events = () => {
  const [selectedType, setSelectedType] = useState("all");

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
          </div>
        </div>
      </section>

      {/* Events Calendar */}
      <section className="py-12 md:py-16">
        <div className="container mx-auto px-4">
          <EventCalendarView events={filteredEvents} />

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

export default Events;
