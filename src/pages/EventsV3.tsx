import { useMemo, useState } from "react";
import {
  ArrowRight,
  CalendarDays,
  ChevronLeft,
  ChevronRight,
  Clock,
  MapPin,
  Repeat2,
  Sparkles,
  X,
} from "lucide-react";
import {
  addDays,
  addWeeks,
  eachDayOfInterval,
  endOfWeek,
  format,
  isSameDay,
  isToday,
  isWeekend,
  parseISO,
  startOfWeek,
  subWeeks,
} from "date-fns";
import Layout from "@/components/layout/Layout";
import EventDetailDrawer from "@/components/events/EventDetailDrawer";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

type EventType = "kids" | "active" | "food" | "arts" | "community";
type EventKind = "event" | "class";
type View = "events" | "classes";

interface CalendarEvent {
  id: number;
  title: string;
  date: string;
  displayDate: string;
  time: string;
  venue: string;
  type: EventType;
  description: string;
  kind: EventKind;
  seasonal?: boolean;
}

const typeConfig: Record<
  EventType,
  { label: string; shortLabel: string; dot: string; tile: string; pill: string }
> = {
  kids: {
    label: "Kids & Family",
    shortLabel: "Kids",
    dot: "bg-event-kids",
    tile: "bg-event-kids-soft border-event-kids/20 hover:border-event-kids/50",
    pill: "bg-event-kids-soft text-event-kids-foreground",
  },
  active: {
    label: "Active & Outdoors",
    shortLabel: "Active",
    dot: "bg-event-active",
    tile: "bg-event-active-soft border-event-active/20 hover:border-event-active/50",
    pill: "bg-event-active-soft text-event-active-foreground",
  },
  food: {
    label: "Food & Drink",
    shortLabel: "Food",
    dot: "bg-event-food",
    tile: "bg-event-food-soft border-event-food/20 hover:border-event-food/50",
    pill: "bg-event-food-soft text-event-food-foreground",
  },
  arts: {
    label: "Arts & Culture",
    shortLabel: "Arts",
    dot: "bg-event-arts",
    tile: "bg-event-arts-soft border-event-arts/20 hover:border-event-arts/50",
    pill: "bg-event-arts-soft text-event-arts-foreground",
  },
  community: {
    label: "Community & Social",
    shortLabel: "Community",
    dot: "bg-event-community",
    tile: "bg-event-community-soft border-event-community/20 hover:border-event-community/50",
    pill: "bg-event-community-soft text-event-community-foreground",
  },
};

const thisMonday = startOfWeek(new Date(), { weekStartsOn: 1 });
const onDay = (dayOffset: number, weekOffset = 0) =>
  format(addDays(addWeeks(thisMonday, weekOffset), dayOffset), "yyyy-MM-dd");
const longDate = (dayOffset: number, weekOffset = 0) =>
  format(addDays(addWeeks(thisMonday, weekOffset), dayOffset), "EEEE, d MMMM");

const oneOffEvents: CalendarEvent[] = [
  { id: 1, title: "Coffee Morning & Chat", date: onDay(0), displayDate: longDate(0), time: "10:00 AM", venue: "Northam Hall", type: "community", kind: "event", description: "Drop in for a cuppa and a natter. Meet neighbours and make new friends." },
  { id: 2, title: "Northam Hall Quiz Night", date: onDay(1), displayDate: longDate(1), time: "7:30 PM", venue: "Northam Hall", type: "arts", kind: "event", description: "Test your knowledge at our popular quiz. Teams of up to six people." },
  { id: 3, title: "Christmas Craft Workshop", date: onDay(2), displayDate: longDate(2), time: "10:00 AM", venue: "Community Hall", type: "kids", kind: "event", seasonal: true, description: "Make festive decorations and cards. All materials provided; booking recommended." },
  { id: 4, title: "Italian Night at Trattoria", date: onDay(2), displayDate: longDate(2), time: "6:00 PM", venue: "Trattoria Bella", type: "food", kind: "event", description: "A special tasting menu with optional wine pairings. Booking essential." },
  { id: 5, title: "Christmas Lights Switch-On", date: onDay(3), displayDate: longDate(3), time: "5:30 PM", venue: "Northam Square", type: "community", kind: "event", seasonal: true, description: "Join the community for the lights switch-on, festive music, and seasonal treats." },
  { id: 6, title: "Beach Clean-up Walk", date: onDay(3), displayDate: longDate(3), time: "10:00 AM", venue: "Westward Ho! Beach", type: "active", kind: "event", description: "Help keep our coastline clean. Equipment provided and all ages welcome." },
  { id: 7, title: "Live Music at The Kingsley", date: onDay(4), displayDate: longDate(4), time: "8:00 PM", venue: "The Kingsley Pub", type: "arts", kind: "event", description: "Local band The Coastliners perform classic rock and folk favourites." },
  { id: 8, title: "Fish & Chip Friday", date: onDay(4), displayDate: longDate(4), time: "5:00 PM", venue: "Northam Hall", type: "food", kind: "event", description: "Community fish supper with raffle. Advance booking is helpful but not essential." },
  { id: 9, title: "Christmas Makers Market", date: onDay(5), displayDate: longDate(5), time: "9:00 AM - 2:00 PM", venue: "Northam Square", type: "food", kind: "event", seasonal: true, description: "Local makers, artisan food, handmade gifts, and festive produce." },
  { id: 10, title: "Festive Family Trail", date: onDay(5), displayDate: longDate(5), time: "11:00 AM", venue: "Northam Burrows", type: "kids", kind: "event", seasonal: true, description: "A self-guided festive trail for families with a small prize at the finish." },
  { id: 11, title: "Sunset Coastal Run", date: onDay(5), displayDate: longDate(5), time: "4:30 PM", venue: "Pebble Ridge", type: "active", kind: "event", description: "A relaxed 5K social run along the coast. All abilities welcome." },
  { id: 12, title: "Carols by Candlelight", date: onDay(6), displayDate: longDate(6), time: "6:30 PM", venue: "St Margaret's Church", type: "arts", kind: "event", seasonal: true, description: "An evening of favourite carols and seasonal readings with the community choir." },
  { id: 13, title: "Farmers Market", date: onDay(6), displayDate: longDate(6), time: "9:00 AM - 1:00 PM", venue: "Northam Square", type: "food", kind: "event", description: "Fresh local produce, artisan breads, cheeses, and seasonal vegetables." },
  { id: 14, title: "Family Pantomime", date: onDay(5, 1), displayDate: longDate(5, 1), time: "2:00 PM", venue: "Northam Hall", type: "kids", kind: "event", seasonal: true, description: "A festive family pantomime with plenty of audience participation." },
  { id: 15, title: "Winter Wildlife Walk", date: onDay(6, 1), displayDate: longDate(6, 1), time: "10:00 AM", venue: "Northam Burrows", type: "active", kind: "event", description: "A guided walk discovering winter birds and wildlife on the Burrows." },
];

const regularClasses: CalendarEvent[] = [
  { id: 101, title: "Yoga for All", date: onDay(0), displayDate: "Every Monday", time: "9:30 AM", venue: "Northam Hall", type: "active", kind: "class", description: "A friendly weekly yoga session suitable for all experience levels. Bring your own mat." },
  { id: 102, title: "Art Club", date: onDay(0), displayDate: "Every Monday", time: "1:30 PM", venue: "Community Hall", type: "arts", kind: "class", description: "A relaxed weekly art group. Bring your current project or start something new." },
  { id: 103, title: "Toddler Stay & Play", date: onDay(1), displayDate: "Every Tuesday", time: "10:00 AM", venue: "Northam Hall", type: "kids", kind: "class", description: "Toys, songs, and informal play for under-fives and their carers." },
  { id: 104, title: "Pilates", date: onDay(1), displayDate: "Every Tuesday", time: "6:00 PM", venue: "Community Hall", type: "active", kind: "class", description: "Weekly mat-based Pilates. Beginners welcome; booking required." },
  { id: 105, title: "Community Choir", date: onDay(2), displayDate: "Every Wednesday", time: "7:00 PM", venue: "St Margaret's Church", type: "arts", kind: "class", description: "A welcoming community choir with no auditions and no need to read music." },
  { id: 106, title: "Book Club", date: onDay(2), displayDate: "Every Wednesday", time: "2:00 PM", venue: "Northam Library", type: "community", kind: "class", description: "An informal weekly reading and conversation group. New members welcome." },
  { id: 107, title: "Kids Dance", date: onDay(3), displayDate: "Every Thursday", time: "4:00 PM", venue: "Northam Hall", type: "kids", kind: "class", description: "Fun weekly dance sessions for primary-school-age children." },
  { id: 108, title: "Walking Football", date: onDay(3), displayDate: "Every Thursday", time: "6:30 PM", venue: "Northam Sports Field", type: "active", kind: "class", description: "A sociable weekly walking football session for adults of all abilities." },
  { id: 109, title: "Craft & Conversation", date: onDay(4), displayDate: "Every Friday", time: "10:30 AM", venue: "Community Hall", type: "community", kind: "class", description: "Bring a craft project and enjoy a relaxed morning with other local makers." },
  { id: 110, title: "Parkrun", date: onDay(5), displayDate: "Every Saturday", time: "9:00 AM", venue: "Northam Burrows", type: "active", kind: "class", description: "Free weekly timed 5K. Walk, jog, run, or volunteer." },
];

const EventsV3 = () => {
  const [view, setView] = useState<View>("events");
  const [currentDate, setCurrentDate] = useState(new Date());
  const [selectedType, setSelectedType] = useState("all");
  const [selectedVenue, setSelectedVenue] = useState("all");
  const [seasonalOnly, setSeasonalOnly] = useState(false);
  const [selectedEvent, setSelectedEvent] = useState<CalendarEvent | null>(null);
  const [drawerOpen, setDrawerOpen] = useState(false);

  const venues = useMemo(
    () => Array.from(new Set([...oneOffEvents, ...regularClasses].map((event) => event.venue))).sort(),
    []
  );

  const filteredEvents = useMemo(() => {
    const source = view === "classes" ? regularClasses : oneOffEvents;

    return source.filter((event) => {
      if (selectedType !== "all" && event.type !== selectedType) return false;
      if (selectedVenue !== "all" && event.venue !== selectedVenue) return false;
      if (view === "events" && seasonalOnly && !event.seasonal) return false;
      return true;
    });
  }, [view, selectedType, selectedVenue, seasonalOnly]);

  const weekStart = startOfWeek(currentDate, { weekStartsOn: 1 });
  const weekEnd = endOfWeek(currentDate, { weekStartsOn: 1 });
  const weekDays = eachDayOfInterval({ start: weekStart, end: weekEnd });

  const eventsByDay = useMemo(() => {
    const map = new Map<string, CalendarEvent[]>();
    weekDays.forEach((day) => {
      map.set(
        format(day, "yyyy-MM-dd"),
        filteredEvents
          .filter((event) =>
            event.kind === "class"
              ? format(parseISO(event.date), "EEEE") === format(day, "EEEE")
              : isSameDay(parseISO(event.date), day)
          )
          .sort((a, b) => a.time.localeCompare(b.time))
      );
    });
    return map;
  }, [filteredEvents, weekStart.toISOString()]);

  const totalThisWeek = Array.from(eventsByDay.values()).reduce((total, events) => total + events.length, 0);

  const openEvent = (event: CalendarEvent) => {
    setSelectedEvent(event);
    setDrawerOpen(true);
  };

  const changeView = (nextView: View) => {
    setView(nextView);
    setSeasonalOnly(false);
    setSelectedType("all");
  };

  const eventTile = (event: CalendarEvent, compact = false) => {
    const config = typeConfig[event.type];
    const isClass = event.kind === "class";

    return (
      <button
        key={event.id}
        onClick={() => openEvent(event)}
        className={`w-full text-left rounded-xl border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm ${
          isClass ? "border-dashed border-primary/35 bg-primary/5 hover:bg-primary/10" : config.tile
        } ${compact ? "p-2.5" : "p-3"}`}
      >
        <div className="flex items-start justify-between gap-2">
          <p className={`font-semibold leading-snug text-foreground ${compact ? "text-xs" : "text-sm"}`}>
            {event.title}
          </p>
          {isClass && <Repeat2 className="w-3.5 h-3.5 text-primary shrink-0" aria-label="Regular class" />}
        </div>
        <div className={`flex items-center gap-2 text-muted-foreground ${compact ? "mt-1.5 text-[10px]" : "mt-2 text-xs"}`}>
          <Clock className="w-3 h-3 shrink-0" />
          <span>{event.time}</span>
        </div>
        {!compact && (
          <div className="flex items-center gap-2 mt-1 text-xs text-muted-foreground">
            <MapPin className="w-3 h-3 shrink-0" />
            <span className="truncate">{event.venue}</span>
          </div>
        )}
        {(event.seasonal || isClass) && (
          <span className={`inline-flex mt-2 text-[9px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-full ${
            isClass ? "bg-primary/10 text-primary" : "bg-accent/10 text-accent"
          }`}>
            {isClass ? "Weekly class" : "Christmas"}
          </span>
        )}
      </button>
    );
  };

  return (
    <Layout>
      <section className="gradient-coastal relative overflow-hidden py-10 md:py-14">
        <div className="absolute -top-20 -right-16 w-64 h-64 rounded-full bg-primary-foreground/5" />
        <div className="absolute -bottom-24 -left-20 w-72 h-72 rounded-full bg-primary-foreground/5" />
        <div className="container mx-auto px-4 relative text-center">
          <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-foreground/15 text-primary-foreground text-xs font-medium mb-3">
            <CalendarDays className="w-3.5 h-3.5" />
            Layout preview
          </span>
          <h1 className="font-heading text-4xl md:text-5xl font-bold text-primary-foreground mb-3">
            What&apos;s On in Northam
          </h1>
          <p className="text-primary-foreground/80 max-w-xl mx-auto">
            Special events, local happenings, and regular activities — without the clutter.
          </p>
        </div>
      </section>

      <div className="container mx-auto px-4 relative z-10 -mt-6">
        <div className="max-w-2xl mx-auto grid grid-cols-2 gap-2 p-2 rounded-2xl bg-card border border-border shadow-lg">
          <button
            onClick={() => changeView("events")}
            className={`rounded-xl px-3 py-3 text-left transition-colors ${view === "events" ? "bg-primary text-primary-foreground" : "text-muted-foreground hover:bg-muted/60 hover:text-foreground"}`}
            aria-pressed={view === "events"}
          >
            <span className="flex items-center gap-2 font-semibold text-sm md:text-base">
              <CalendarDays className="w-4 h-4" />
              What&apos;s On
            </span>
            <span className={`hidden sm:block text-xs mt-1 ${view === "events" ? "text-primary-foreground/75" : "text-muted-foreground"}`}>
              One-off events & special occasions
            </span>
          </button>
          <button
            onClick={() => changeView("classes")}
            className={`rounded-xl px-3 py-3 text-left transition-colors ${view === "classes" ? "bg-primary text-primary-foreground" : "text-muted-foreground hover:bg-muted/60 hover:text-foreground"}`}
            aria-pressed={view === "classes"}
          >
            <span className="flex items-center gap-2 font-semibold text-sm md:text-base">
              <Repeat2 className="w-4 h-4" />
              <span className="sm:hidden">Classes</span>
              <span className="hidden sm:inline">Classes & Activities</span>
            </span>
            <span className={`hidden sm:block text-xs mt-1 ${view === "classes" ? "text-primary-foreground/75" : "text-muted-foreground"}`}>
              Weekly sessions & regular meet-ups
            </span>
          </button>
        </div>
      </div>

      <main className="container mx-auto px-4 py-6 md:py-8">
        {view === "events" && (
          <section className={`mb-6 rounded-2xl border overflow-hidden ${seasonalOnly ? "border-accent/30 bg-accent/5" : "border-sand-warm bg-sand-light"}`}>
            <div className="p-5 md:p-6 flex flex-col md:flex-row md:items-center gap-4 md:gap-6">
              <div className="w-11 h-11 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                <Sparkles className="w-5 h-5" />
              </div>
              <div className="flex-1">
                <p className="text-xs uppercase tracking-[0.16em] font-semibold text-accent mb-1">Seasonal collection</p>
                <h2 className="font-heading text-2xl font-bold text-foreground">Christmas in Northam</h2>
                <p className="text-sm text-muted-foreground mt-1">Festive markets, carols, crafts, and family events — gathered in one place.</p>
              </div>
              <Button
                variant={seasonalOnly ? "outline" : "default"}
                onClick={() => setSeasonalOnly(!seasonalOnly)}
                className="shrink-0"
              >
                {seasonalOnly ? <X className="w-4 h-4 mr-2" /> : null}
                {seasonalOnly ? "Show all events" : "Explore Christmas events"}
                {!seasonalOnly && <ArrowRight className="w-4 h-4 ml-2" />}
              </Button>
            </div>
          </section>
        )}

        <section className="mb-5 p-4 md:p-5 rounded-2xl bg-card border border-border/70 shadow-sm">
          <div className="flex items-center justify-between gap-2 pb-4 border-b border-border/60">
            <Button variant="outline" size="icon" onClick={() => setCurrentDate(subWeeks(currentDate, 1))} aria-label="Previous week">
              <ChevronLeft className="w-4 h-4" />
            </Button>
            <div className="text-center min-w-0">
              <h2 className="font-heading text-xl md:text-2xl font-bold whitespace-nowrap">
                <span className="sm:hidden">{format(weekStart, "d")} – {format(weekEnd, "d MMM yyyy")}</span>
                <span className="hidden sm:inline">{format(weekStart, "d")} – {format(weekEnd, "d MMMM yyyy")}</span>
              </h2>
              <p className="text-xs text-muted-foreground mt-0.5">
                {totalThisWeek} {view === "classes" ? `class${totalThisWeek !== 1 ? "es" : ""}` : `${seasonalOnly ? "Christmas " : ""}event${totalThisWeek !== 1 ? "s" : ""}`} this week
              </p>
            </div>
            <div className="flex items-center gap-1">
              <Button variant="ghost" size="sm" onClick={() => setCurrentDate(new Date())}>Today</Button>
              <Button variant="outline" size="icon" onClick={() => setCurrentDate(addWeeks(currentDate, 1))} aria-label="Next week">
                <ChevronRight className="w-4 h-4" />
              </Button>
            </div>
          </div>

          <div className="pt-4 flex flex-col lg:flex-row lg:items-center gap-3">
            <div className="hidden md:flex flex-wrap gap-2 flex-1">
              <button
                onClick={() => setSelectedType("all")}
                className={`px-3 py-1.5 rounded-full border text-xs font-medium transition-colors ${selectedType === "all" ? "bg-foreground text-background border-foreground" : "bg-card text-muted-foreground border-border hover:text-foreground"}`}
              >
                All categories
              </button>
              {(Object.keys(typeConfig) as EventType[]).map((type) => (
                <button
                  key={type}
                  onClick={() => setSelectedType(type)}
                  className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-medium transition-colors ${selectedType === type ? typeConfig[type].pill : "bg-card text-muted-foreground border-border hover:text-foreground"}`}
                >
                  <span className={`w-1.5 h-1.5 rounded-full ${typeConfig[type].dot}`} />
                  {typeConfig[type].label}
                </button>
              ))}
            </div>

            <div className="grid grid-cols-2 gap-2 md:hidden">
              <Select value={selectedType} onValueChange={setSelectedType}>
                <SelectTrigger aria-label="Filter by category"><SelectValue placeholder="All categories" /></SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All categories</SelectItem>
                  {(Object.keys(typeConfig) as EventType[]).map((type) => (
                    <SelectItem key={type} value={type}>{typeConfig[type].label}</SelectItem>
                  ))}
                </SelectContent>
              </Select>
              <Select value={selectedVenue} onValueChange={setSelectedVenue}>
                <SelectTrigger aria-label="Filter by venue"><SelectValue placeholder="All venues" /></SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All venues</SelectItem>
                  {venues.map((venue) => <SelectItem key={venue} value={venue}>{venue}</SelectItem>)}
                </SelectContent>
              </Select>
            </div>

            <div className="hidden md:block w-56 shrink-0">
              <Select value={selectedVenue} onValueChange={setSelectedVenue}>
                <SelectTrigger aria-label="Filter by venue"><SelectValue placeholder="All venues" /></SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All venues</SelectItem>
                  {venues.map((venue) => <SelectItem key={venue} value={venue}>{venue}</SelectItem>)}
                </SelectContent>
              </Select>
            </div>
          </div>

        </section>

        <>
            <section className="md:hidden space-y-5">
              {weekDays.filter((day) => (eventsByDay.get(format(day, "yyyy-MM-dd")) || []).length > 0).map((day) => {
                const dayEvents = eventsByDay.get(format(day, "yyyy-MM-dd")) || [];
                return (
                  <div key={day.toISOString()}>
                    <div className="flex items-baseline gap-2 mb-2 pb-2 border-b border-border/60">
                      <h3 className={`font-heading text-xl font-bold ${isToday(day) ? "text-primary" : "text-foreground"}`}>{format(day, "EEEE")}</h3>
                      <span className="text-xs text-muted-foreground">{format(day, "d MMM")}</span>
                      {isToday(day) && <span className="text-[9px] uppercase tracking-wider bg-primary text-primary-foreground px-1.5 py-0.5 rounded">Today</span>}
                    </div>
                    <div className="space-y-2">{dayEvents.map((event) => eventTile(event))}</div>
                  </div>
                );
              })}
              {totalThisWeek === 0 && <p className="text-center text-sm text-muted-foreground py-12">No {view === "classes" ? "classes" : "events"} match these filters this week.</p>}
            </section>

            <section className="hidden md:block rounded-2xl overflow-hidden border border-border bg-card shadow-sm">
              <div className="grid grid-cols-7 border-b border-border">
                {weekDays.map((day) => (
                  <div key={day.toISOString()} className={`px-2 py-3 text-center ${isWeekend(day) ? "bg-muted/40" : "bg-muted/20"}`}>
                    <div className={`text-[10px] uppercase tracking-wider font-semibold ${isToday(day) ? "text-primary" : "text-muted-foreground"}`}>{format(day, "EEE")}</div>
                    <div className={`text-lg font-heading font-bold ${isToday(day) ? "text-primary" : "text-foreground"}`}>{format(day, "d")}</div>
                  </div>
                ))}
              </div>
              <div className="grid grid-cols-7">
                {weekDays.map((day, index) => {
                  const dayEvents = eventsByDay.get(format(day, "yyyy-MM-dd")) || [];
                  return (
                    <div key={day.toISOString()} className={`min-h-[280px] p-2 space-y-2 ${index < 6 ? "border-r border-border/50" : ""} ${isToday(day) ? "bg-primary/5" : isWeekend(day) ? "bg-muted/10" : ""}`}>
                      {dayEvents.map((event) => eventTile(event, true))}
                      {dayEvents.length === 0 && <div className="h-full flex items-center justify-center text-muted-foreground/40">—</div>}
                    </div>
                  );
                })}
              </div>
            </section>
          </>
      </main>

      <EventDetailDrawer event={selectedEvent} open={drawerOpen} onOpenChange={setDrawerOpen} />
    </Layout>
  );
};

export default EventsV3;
