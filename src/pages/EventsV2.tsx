import { useState, useMemo } from "react";
import { Calendar, ChevronLeft, ChevronRight, Clock, MapPin } from "lucide-react";
import Layout from "@/components/layout/Layout";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import EventDetailDrawer from "@/components/events/EventDetailDrawer";
import {
  startOfWeek,
  endOfWeek,
  addWeeks,
  subWeeks,
  format,
  isSameDay,
  parseISO,
  eachDayOfInterval,
  isToday,
  isWeekend,
} from "date-fns";

type EventType = "kids" | "active" | "food" | "arts" | "community";

interface CalendarEvent {
  id: number;
  title: string;
  date: string;
  displayDate: string;
  time: string;
  venue: string;
  type: EventType;
  description: string;
}

const allEvents: CalendarEvent[] = [
  { id: 1, title: "Northam Hall Quiz Night", date: "2026-03-06", displayDate: "Friday, 6th March", time: "7:30 PM", venue: "Northam Hall", type: "arts", description: "Test your knowledge at our popular monthly quiz. Teams of up to 6 people, £2 per person." },
  { id: 2, title: "Live Music at The Kingsley", date: "2026-03-07", displayDate: "Saturday, 7th March", time: "8:00 PM", venue: "The Kingsley Pub", type: "arts", description: "Local band 'The Coastliners' perform classic rock and folk favourites." },
  { id: 3, title: "Family Craft Morning", date: "2026-03-08", displayDate: "Sunday, 8th March", time: "10:00 AM", venue: "Community Hall", type: "kids", description: "Creative crafts for children aged 3-10. Materials provided. £3 per child." },
  { id: 4, title: "Farmers Market", date: "2026-03-08", displayDate: "Sunday, 8th March", time: "9:00 AM - 1:00 PM", venue: "Northam Square", type: "food", description: "Fresh local produce, artisan breads, cheeses, and seasonal vegetables." },
  { id: 5, title: "Beach Clean-up Walk", date: "2026-03-09", displayDate: "Monday, 9th March", time: "10:00 AM", venue: "Westward Ho! Beach", type: "active", description: "Help keep our beautiful coastline clean. Equipment provided, all ages welcome." },
  { id: 6, title: "Italian Night at Trattoria", date: "2026-03-10", displayDate: "Tuesday, 10th March", time: "6:00 PM", venue: "Trattoria Bella", type: "food", description: "Special tasting menu with wine pairings. Booking essential." },
  { id: 7, title: "Yoga on the Burrows", date: "2026-03-11", displayDate: "Wednesday, 11th March", time: "9:30 AM", venue: "Northam Burrows", type: "active", description: "Outdoor yoga session (weather permitting). Bring your own mat. £5 donation." },
  { id: 8, title: "Storytime & Singing", date: "2026-03-11", displayDate: "Wednesday, 11th March", time: "10:30 AM", venue: "Northam Library", type: "kids", description: "Fun stories and songs for under-5s. Free, no booking required." },
  { id: 9, title: "Coffee Morning & Chat", date: "2026-03-12", displayDate: "Thursday, 12th March", time: "10:00 AM", venue: "Northam Hall", type: "community", description: "Drop in for a cuppa and a natter. Meet neighbours and make new friends." },
  { id: 10, title: "Parish Council Meeting", date: "2026-03-12", displayDate: "Thursday, 12th March", time: "7:00 PM", venue: "Community Hall", type: "community", description: "Open public meeting to discuss local planning and community matters." },
  { id: 11, title: "Toddler Splash Session", date: "2026-03-13", displayDate: "Friday, 13th March", time: "11:00 AM", venue: "Northam Pool", type: "kids", description: "Supervised splash session for toddlers aged 1-4. Floats provided." },
  { id: 12, title: "Sunset Coastal Run", date: "2026-03-13", displayDate: "Friday, 13th March", time: "5:30 PM", venue: "Pebble Ridge", type: "active", description: "5K social run along the coast. All abilities welcome." },
  { id: 13, title: "Open Mic Night", date: "2026-03-14", displayDate: "Saturday, 14th March", time: "7:00 PM", venue: "The Union Inn", type: "arts", description: "Singers, poets, comedians — all welcome to take the stage." },
  { id: 14, title: "Vegan Street Food Pop-up", date: "2026-03-14", displayDate: "Saturday, 14th March", time: "12:00 PM", venue: "Northam Square", type: "food", description: "Local vendors serving plant-based street food. Live acoustic music." },
  { id: 15, title: "Community Litter Pick", date: "2026-03-15", displayDate: "Sunday, 15th March", time: "9:00 AM", venue: "Bone Hill Road", type: "community", description: "Volunteer litter pick around the village. Bags and pickers provided." },
  { id: 16, title: "Pilates in the Park", date: "2026-03-16", displayDate: "Monday, 16th March", time: "10:00 AM", venue: "Northam Burrows", type: "active", description: "Gentle Pilates class in the fresh air. Bring a mat. £4." },
  { id: 17, title: "Book Club Meeting", date: "2026-03-17", displayDate: "Tuesday, 17th March", time: "7:30 PM", venue: "Northam Library", type: "community", description: "This month: 'The Salt Path' by Raynor Winn. New members welcome." },
  { id: 18, title: "Kids Nature Trail", date: "2026-03-18", displayDate: "Wednesday, 18th March", time: "2:00 PM", venue: "Northam Burrows", type: "kids", description: "Guided nature walk for families. Spot birds, bugs, and wildflowers." },
  { id: 19, title: "Fish & Chip Friday", date: "2026-03-20", displayDate: "Friday, 20th March", time: "5:00 PM", venue: "Northam Hall", type: "food", description: "Community fish supper with raffle. £6 adults, £3 children." },
  { id: 20, title: "Art Exhibition Opening", date: "2026-03-21", displayDate: "Saturday, 21st March", time: "11:00 AM", venue: "The Burton Gallery", type: "arts", description: "New exhibition showcasing local Devon artists. Free entry, donations welcome." },
];

const typeConfig: Record<EventType, { label: string; dot: string; pill: string; border: string }> = {
  kids:      { label: "Kids",      dot: "bg-amber-500",   pill: "bg-amber-100 text-amber-800",     border: "border-l-amber-500" },
  active:    { label: "Active",    dot: "bg-emerald-500", pill: "bg-emerald-100 text-emerald-800", border: "border-l-emerald-500" },
  food:      { label: "Food",      dot: "bg-orange-500",  pill: "bg-orange-100 text-orange-800",   border: "border-l-orange-500" },
  arts:      { label: "Arts",      dot: "bg-violet-500",  pill: "bg-violet-100 text-violet-800",   border: "border-l-violet-500" },
  community: { label: "Community", dot: "bg-sky-500",     pill: "bg-sky-100 text-sky-800",         border: "border-l-sky-500" },
};

const EventsV2 = () => {
  const [currentDate, setCurrentDate] = useState(new Date("2026-03-09"));
  const [selectedType, setSelectedType] = useState<string>("all");
  const [selectedVenue, setSelectedVenue] = useState<string>("all");
  const [selectedEvent, setSelectedEvent] = useState<CalendarEvent | null>(null);
  const [drawerOpen, setDrawerOpen] = useState(false);

  const venues = useMemo(
    () => Array.from(new Set(allEvents.map((e) => e.venue))).sort(),
    []
  );

  const filteredEvents = useMemo(() => {
    return allEvents.filter((e) => {
      if (selectedType !== "all" && e.type !== selectedType) return false;
      if (selectedVenue !== "all" && e.venue !== selectedVenue) return false;
      return true;
    });
  }, [selectedType, selectedVenue]);

  const weekStart = startOfWeek(currentDate, { weekStartsOn: 1 });
  const weekEnd = endOfWeek(currentDate, { weekStartsOn: 1 });
  const weekDays = eachDayOfInterval({ start: weekStart, end: weekEnd });

  const eventsByDay = useMemo(() => {
    const map = new Map<string, CalendarEvent[]>();
    weekDays.forEach((day) => {
      const key = format(day, "yyyy-MM-dd");
      map.set(
        key,
        filteredEvents
          .filter((e) => isSameDay(parseISO(e.date), day))
          .sort((a, b) => a.time.localeCompare(b.time))
      );
    });
    return map;
  }, [filteredEvents, weekStart.toISOString()]);

  const totalThisWeek = Array.from(eventsByDay.values()).reduce((n, d) => n + d.length, 0);

  const handleEventClick = (event: CalendarEvent) => {
    setSelectedEvent(event);
    setDrawerOpen(true);
  };

  return (
    <Layout>
      {/* Hero */}
      <section className="relative py-10 md:py-16 bg-coastal-deep">
        <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
        <div className="container mx-auto px-4 relative z-10 text-center">
          <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-foreground/20 text-primary-foreground text-xs font-medium mb-3">
            <Calendar className="w-3.5 h-3.5" />
            v2 Preview
          </span>
          <h1 className="font-heading text-3xl md:text-5xl font-bold text-primary-foreground mb-2">
            Events Calendar
          </h1>
          <p className="text-base md:text-lg text-primary-foreground/80 max-w-2xl mx-auto">
            What's on in Northam this week
          </p>
        </div>
      </section>

      {/* Sticky Controls */}
      <section className="sticky top-16 md:top-20 z-40 bg-card border-b border-border shadow-sm">
        <div className="container mx-auto px-4 py-3 space-y-3">
          {/* Week navigation */}
          <div className="flex items-center justify-between gap-2">
            <Button
              variant="outline"
              size="sm"
              onClick={() => setCurrentDate(subWeeks(currentDate, 1))}
              className="h-9 w-9 p-0 shrink-0"
            >
              <ChevronLeft className="w-4 h-4" />
            </Button>
            <div className="flex-1 text-center min-w-0">
              <div className="font-heading text-sm md:text-base font-bold text-foreground truncate">
                {format(weekStart, "d")} – {format(weekEnd, "d MMM yyyy")}
              </div>
              <div className="text-[11px] text-muted-foreground">
                {totalThisWeek} event{totalThisWeek !== 1 ? "s" : ""} this week
              </div>
            </div>
            <Button
              variant="ghost"
              size="sm"
              onClick={() => setCurrentDate(new Date())}
              className="h-9 px-2 text-xs shrink-0"
            >
              Today
            </Button>
            <Button
              variant="outline"
              size="sm"
              onClick={() => setCurrentDate(addWeeks(currentDate, 1))}
              className="h-9 w-9 p-0 shrink-0"
            >
              <ChevronRight className="w-4 h-4" />
            </Button>
          </div>

          {/* Filters */}
          <div className="grid grid-cols-2 gap-2">
            <Select value={selectedVenue} onValueChange={setSelectedVenue}>
              <SelectTrigger className="h-9 text-sm">
                <SelectValue placeholder="All venues" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All venues</SelectItem>
                {venues.map((v) => (
                  <SelectItem key={v} value={v}>{v}</SelectItem>
                ))}
              </SelectContent>
            </Select>
            <Select value={selectedType} onValueChange={setSelectedType}>
              <SelectTrigger className="h-9 text-sm">
                <SelectValue placeholder="All categories" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">All categories</SelectItem>
                {(Object.keys(typeConfig) as EventType[]).map((t) => (
                  <SelectItem key={t} value={t}>{typeConfig[t].label}</SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
        </div>
      </section>

      {/* Mobile: tight day list */}
      <section className="md:hidden py-4">
        <div className="container mx-auto px-4 space-y-5">
          {weekDays.map((day) => {
            const key = format(day, "yyyy-MM-dd");
            const dayEvents = eventsByDay.get(key) || [];
            const today = isToday(day);
            return (
              <div key={key}>
                <div className="flex items-baseline justify-between mb-2 pb-1.5 border-b border-border/60">
                  <div className="flex items-baseline gap-2">
                    <h3 className={`font-heading text-lg font-bold ${today ? "text-primary" : "text-foreground"}`}>
                      {format(day, "EEEE")}
                    </h3>
                    <span className="text-xs text-muted-foreground">
                      {format(day, "d MMM")}
                    </span>
                    {today && (
                      <span className="text-[9px] font-bold uppercase tracking-wider bg-primary text-primary-foreground px-1.5 py-0.5 rounded">
                        Today
                      </span>
                    )}
                  </div>
                  <span className="text-[11px] text-muted-foreground">
                    {dayEvents.length === 0 ? "—" : `${dayEvents.length}`}
                  </span>
                </div>

                {dayEvents.length === 0 ? (
                  <p className="text-xs text-muted-foreground/60 italic py-1">Nothing on</p>
                ) : (
                  <ul className="divide-y divide-border/40">
                    {dayEvents.map((event) => {
                      const cfg = typeConfig[event.type];
                      return (
                        <li key={event.id}>
                          <button
                            onClick={() => handleEventClick(event)}
                            className={`w-full text-left py-2.5 pl-3 pr-1 border-l-2 ${cfg.border} hover:bg-muted/40 active:bg-muted/60 transition-colors`}
                          >
                            <div className="flex items-start justify-between gap-2">
                              <p className="text-sm font-semibold text-foreground leading-snug flex-1">
                                {event.title}
                              </p>
                              <span className={`text-[10px] font-medium px-1.5 py-0.5 rounded shrink-0 ${cfg.pill}`}>
                                {cfg.label}
                              </span>
                            </div>
                            <div className="flex items-center gap-3 mt-1 text-[11px] text-muted-foreground">
                              <span className="flex items-center gap-1">
                                <Clock className="w-3 h-3" />
                                {event.time}
                              </span>
                              <span className="flex items-center gap-1 truncate">
                                <MapPin className="w-3 h-3 shrink-0" />
                                <span className="truncate">{event.venue}</span>
                              </span>
                            </div>
                          </button>
                        </li>
                      );
                    })}
                  </ul>
                )}
              </div>
            );
          })}
        </div>
      </section>

      {/* Desktop: 7-col week grid */}
      <section className="hidden md:block py-8">
        <div className="container mx-auto px-4">
          <div className="rounded-2xl overflow-hidden border border-border bg-card shadow-sm">
            <div className="grid grid-cols-7 border-b border-border">
              {weekDays.map((day) => (
                <div
                  key={`h-${day.toISOString()}`}
                  className={`px-2 py-3 text-center ${isWeekend(day) ? "bg-muted/40" : "bg-muted/20"}`}
                >
                  <div className={`text-[10px] uppercase tracking-wider font-semibold ${isToday(day) ? "text-primary" : "text-muted-foreground"}`}>
                    {format(day, "EEE")}
                  </div>
                  <div className={`text-lg font-heading font-bold ${isToday(day) ? "text-primary" : "text-foreground"}`}>
                    {format(day, "d")}
                  </div>
                </div>
              ))}
            </div>
            <div className="grid grid-cols-7">
              {weekDays.map((day, i) => {
                const key = format(day, "yyyy-MM-dd");
                const dayEvents = eventsByDay.get(key) || [];
                const today = isToday(day);
                return (
                  <div
                    key={key}
                    className={`min-h-[260px] p-2 space-y-1.5 ${i < 6 ? "border-r border-border/50" : ""} ${today ? "bg-primary/5" : isWeekend(day) ? "bg-muted/10" : ""}`}
                  >
                    {dayEvents.map((event) => {
                      const cfg = typeConfig[event.type];
                      return (
                        <button
                          key={event.id}
                          onClick={() => handleEventClick(event)}
                          className={`w-full text-left rounded-md p-2 bg-background hover:bg-muted/60 border-l-2 ${cfg.border} transition-colors`}
                        >
                          <div className="text-[10px] font-semibold text-muted-foreground">
                            {event.time}
                          </div>
                          <div className="text-xs font-semibold text-foreground leading-tight line-clamp-2 mt-0.5">
                            {event.title}
                          </div>
                          <div className="text-[10px] text-muted-foreground truncate mt-1">
                            {event.venue}
                          </div>
                        </button>
                      );
                    })}
                    {dayEvents.length === 0 && (
                      <div className="h-full flex items-center justify-center">
                        <span className="text-[10px] text-muted-foreground/40">—</span>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          </div>

          {/* Legend */}
          <div className="flex flex-wrap gap-2 justify-center mt-5">
            {(Object.keys(typeConfig) as EventType[]).map((t) => (
              <div key={t} className={`flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium ${typeConfig[t].pill}`}>
                <span className={`w-1.5 h-1.5 rounded-full ${typeConfig[t].dot}`} />
                {typeConfig[t].label}
              </div>
            ))}
          </div>
        </div>
      </section>

      <EventDetailDrawer
        event={selectedEvent}
        open={drawerOpen}
        onOpenChange={setDrawerOpen}
      />
    </Layout>
  );
};

export default EventsV2;
