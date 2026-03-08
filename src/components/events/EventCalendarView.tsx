import { useState, useMemo } from "react";
import { ChevronLeft, ChevronRight, Clock, MapPin, Sparkles } from "lucide-react";
import { Button } from "@/components/ui/button";
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

interface CalendarEvent {
  id: number;
  title: string;
  date: string;
  displayDate: string;
  time: string;
  venue: string;
  type: "kids" | "active" | "food" | "arts" | "community";
  description: string;
}

interface EventCalendarViewProps {
  events: CalendarEvent[];
}

const typeStyles: Record<CalendarEvent["type"], { gradient: string; text: string; dot: string; glow: string }> = {
  kids: { 
    gradient: "bg-gradient-to-br from-amber-400/20 to-orange-400/10", 
    text: "text-amber-700",
    dot: "bg-gradient-to-br from-amber-400 to-orange-500",
    glow: "shadow-amber-500/20"
  },
  active: { 
    gradient: "bg-gradient-to-br from-emerald-400/20 to-teal-400/10", 
    text: "text-emerald-700",
    dot: "bg-gradient-to-br from-emerald-400 to-teal-500",
    glow: "shadow-emerald-500/20"
  },
  food: { 
    gradient: "bg-gradient-to-br from-orange-400/20 to-red-400/10", 
    text: "text-orange-700",
    dot: "bg-gradient-to-br from-orange-400 to-red-500",
    glow: "shadow-orange-500/20"
  },
  arts: { 
    gradient: "bg-gradient-to-br from-violet-400/20 to-purple-400/10", 
    text: "text-violet-700",
    dot: "bg-gradient-to-br from-violet-400 to-purple-500",
    glow: "shadow-violet-500/20"
  },
  community: { 
    gradient: "bg-gradient-to-br from-sky-400/20 to-blue-400/10", 
    text: "text-sky-700",
    dot: "bg-gradient-to-br from-sky-400 to-blue-500",
    glow: "shadow-sky-500/20"
  },
};

const typeLabels: Record<CalendarEvent["type"], string> = {
  kids: "Kids & Family",
  active: "Active & Outdoors",
  food: "Food & Drink",
  arts: "Arts & Culture",
  community: "Community & Social",
};

const EventCalendarView = ({ events }: EventCalendarViewProps) => {
  const [currentDate, setCurrentDate] = useState(new Date());
  const [hoveredEvent, setHoveredEvent] = useState<CalendarEvent | null>(null);

  const weekStart = startOfWeek(currentDate, { weekStartsOn: 1 });
  const weekEnd = endOfWeek(currentDate, { weekStartsOn: 1 });
  const weekDays = eachDayOfInterval({ start: weekStart, end: weekEnd });

  const eventsByDay = useMemo(() => {
    const map = new Map<string, CalendarEvent[]>();
    weekDays.forEach((day) => {
      const key = format(day, "yyyy-MM-dd");
      map.set(
        key,
        events.filter((e) => isSameDay(parseISO(e.date), day))
      );
    });
    return map;
  }, [events, weekStart.toISOString()]);

  const totalEventsThisWeek = useMemo(() => {
    let count = 0;
    eventsByDay.forEach((dayEvents) => {
      count += dayEvents.length;
    });
    return count;
  }, [eventsByDay]);

  return (
    <div className="space-y-8">
      {/* Header with Navigation */}
      <div className="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 rounded-2xl bg-gradient-to-r from-coastal-light/30 via-sea-foam/20 to-sand-warm/30 border border-border/50">
        <div className="flex items-center gap-3">
          <div className="p-2.5 rounded-xl bg-primary/10">
            <Sparkles className="w-5 h-5 text-primary" />
          </div>
          <div>
            <h3 className="font-heading text-xl font-bold text-foreground">
              {format(weekStart, "d")} — {format(weekEnd, "d MMMM yyyy")}
            </h3>
            <p className="text-sm text-muted-foreground">
              {totalEventsThisWeek} event{totalEventsThisWeek !== 1 ? "s" : ""} this week
            </p>
          </div>
        </div>
        
        <div className="flex items-center gap-2">
          <Button
            variant="outline"
            size="sm"
            onClick={() => setCurrentDate(subWeeks(currentDate, 1))}
            className="rounded-xl hover:bg-primary/5 hover:border-primary/30 transition-all"
          >
            <ChevronLeft className="w-4 h-4" />
          </Button>
          <Button
            variant="ghost"
            size="sm"
            onClick={() => setCurrentDate(new Date())}
            className="rounded-xl text-xs font-medium hover:bg-primary/10"
          >
            Today
          </Button>
          <Button
            variant="outline"
            size="sm"
            onClick={() => setCurrentDate(addWeeks(currentDate, 1))}
            className="rounded-xl hover:bg-primary/5 hover:border-primary/30 transition-all"
          >
            <ChevronRight className="w-4 h-4" />
          </Button>
        </div>
      </div>

      {/* Desktop: Calendar Grid */}
      <div className="hidden md:block rounded-2xl overflow-hidden border border-border/50 bg-card shadow-sm">
        {/* Day Headers */}
        <div className="grid grid-cols-7 border-b border-border/50">
          {weekDays.map((day) => (
            <div
              key={`header-${day.toISOString()}`}
              className={`px-2 py-4 text-center ${
                isWeekend(day) ? "bg-muted/50" : "bg-muted/30"
              }`}
            >
              <span className={`text-xs uppercase tracking-wider font-semibold ${
                isToday(day) ? "text-primary" : "text-muted-foreground"
              }`}>
                {format(day, "EEE")}
              </span>
              <div className={`mt-1 text-lg font-heading font-bold ${
                isToday(day) 
                  ? "text-primary" 
                  : isWeekend(day) 
                    ? "text-muted-foreground" 
                    : "text-foreground"
              }`}>
                {format(day, "d")}
              </div>
            </div>
          ))}
        </div>

        {/* Day Cells */}
        <div className="grid grid-cols-7">
          {weekDays.map((day, index) => {
            const key = format(day, "yyyy-MM-dd");
            const dayEvents = eventsByDay.get(key) || [];
            const today = isToday(day);
            const weekend = isWeekend(day);

            return (
              <div
                key={key}
                className={`min-h-[200px] p-3 flex flex-col transition-colors ${
                  today 
                    ? "bg-primary/5" 
                    : weekend 
                      ? "bg-muted/20" 
                      : "bg-card"
                } ${index < 6 ? "border-r border-border/30" : ""}`}
              >
                <div className="flex-1 space-y-2">
                  {dayEvents.map((event) => (
                    <div
                      key={event.id}
                      className={`relative rounded-xl p-2.5 cursor-pointer transition-all duration-200 hover:scale-[1.02] hover:shadow-lg ${typeStyles[event.type].gradient} ${typeStyles[event.type].glow}`}
                      onMouseEnter={() => setHoveredEvent(event)}
                      onMouseLeave={() => setHoveredEvent(null)}
                    >
                      <div className="flex items-start gap-2">
                        <span className={`mt-0.5 w-2.5 h-2.5 rounded-full flex-shrink-0 shadow-sm ${typeStyles[event.type].dot}`} />
                        <div className="min-w-0 flex-1">
                          <p className={`text-xs font-semibold leading-tight line-clamp-2 ${typeStyles[event.type].text}`}>
                            {event.title}
                          </p>
                          <p className="text-[10px] text-muted-foreground mt-1 flex items-center gap-1">
                            <Clock className="w-2.5 h-2.5" />
                            {event.time}
                          </p>
                        </div>
                      </div>

                      {hoveredEvent?.id === event.id && (
                        <div className="absolute left-1/2 -translate-x-1/2 top-full mt-2 z-50 w-64 p-4 rounded-xl bg-card border border-border shadow-xl animate-fade-in">
                          <div className="absolute -top-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-card border-l border-t border-border rotate-45" />
                          <div className="relative">
                            <p className="font-heading text-sm font-bold text-foreground mb-1.5">{event.title}</p>
                            <p className="text-xs text-muted-foreground mb-3 line-clamp-2">{event.description}</p>
                            <div className="space-y-1.5 text-xs">
                              <div className="flex items-center gap-2 text-muted-foreground">
                                <Clock className="w-3.5 h-3.5 text-primary/70" />
                                {event.time}
                              </div>
                              <div className="flex items-center gap-2 text-muted-foreground">
                                <MapPin className="w-3.5 h-3.5 text-primary/70" />
                                {event.venue}
                              </div>
                            </div>
                            <div className={`inline-flex items-center gap-1.5 text-[10px] font-semibold mt-3 px-2 py-1 rounded-full ${typeStyles[event.type].gradient} ${typeStyles[event.type].text}`}>
                              <span className={`w-1.5 h-1.5 rounded-full ${typeStyles[event.type].dot}`} />
                              {typeLabels[event.type]}
                            </div>
                          </div>
                        </div>
                      )}
                    </div>
                  ))}

                  {dayEvents.length === 0 && (
                    <div className="flex items-center justify-center h-full">
                      <p className="text-[10px] text-muted-foreground/40">—</p>
                    </div>
                  )}
                </div>
              </div>
            );
          })}
        </div>
      </div>

      {/* Mobile: Vertical Day List */}
      <div className="md:hidden space-y-3">
        {weekDays.map((day) => {
          const key = format(day, "yyyy-MM-dd");
          const dayEvents = eventsByDay.get(key) || [];
          const today = isToday(day);

          return (
            <div
              key={key}
              className={`rounded-xl border overflow-hidden ${
                today ? "border-primary/40 bg-primary/5" : "border-border/50 bg-card"
              }`}
            >
              {/* Day Header */}
              <div className={`flex items-center justify-between px-4 py-3 ${
                today ? "bg-primary/10" : "bg-muted/30"
              }`}>
                <div className="flex items-center gap-2">
                  <span className={`font-heading text-lg font-bold ${
                    today ? "text-primary" : "text-foreground"
                  }`}>
                    {format(day, "d")}
                  </span>
                  <span className={`text-sm font-medium ${
                    today ? "text-primary" : "text-muted-foreground"
                  }`}>
                    {format(day, "EEEE")}
                  </span>
                  {today && (
                    <span className="text-[10px] font-semibold uppercase tracking-wider bg-primary text-primary-foreground px-2 py-0.5 rounded-full">
                      Today
                    </span>
                  )}
                </div>
                {dayEvents.length > 0 && (
                  <span className="text-xs text-muted-foreground">
                    {dayEvents.length} event{dayEvents.length !== 1 ? "s" : ""}
                  </span>
                )}
              </div>

              {/* Events */}
              {dayEvents.length > 0 ? (
                <div className="p-3 space-y-2">
                  {dayEvents.map((event) => (
                    <div
                      key={event.id}
                      className={`rounded-xl p-3 ${typeStyles[event.type].gradient}`}
                    >
                      <div className="flex items-start gap-2.5">
                        <span className={`mt-1 w-2.5 h-2.5 rounded-full flex-shrink-0 ${typeStyles[event.type].dot}`} />
                        <div className="min-w-0 flex-1">
                          <p className={`text-sm font-semibold leading-tight ${typeStyles[event.type].text}`}>
                            {event.title}
                          </p>
                          <p className="text-xs text-muted-foreground mt-1 line-clamp-2">
                            {event.description}
                          </p>
                          <div className="flex items-center gap-4 mt-2 text-xs text-muted-foreground">
                            <span className="flex items-center gap-1">
                              <Clock className="w-3 h-3" />
                              {event.time}
                            </span>
                            <span className="flex items-center gap-1">
                              <MapPin className="w-3 h-3" />
                              {event.venue}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="px-4 py-4 text-center">
                  <p className="text-xs text-muted-foreground/50">No events</p>
                </div>
              )}
            </div>
          );
        })}
      </div>

      {/* Legend */}
      <div className="flex flex-wrap gap-3 justify-center">
        {Object.entries(typeLabels).map(([type, label]) => (
          <div 
            key={type} 
            className={`flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium ${typeStyles[type as CalendarEvent["type"]].gradient} ${typeStyles[type as CalendarEvent["type"]].text}`}
          >
            <span className={`w-2 h-2 rounded-full ${typeStyles[type as CalendarEvent["type"]].dot}`} />
            {label}
          </div>
        ))}
      </div>
    </div>
  );
};

export default EventCalendarView;
