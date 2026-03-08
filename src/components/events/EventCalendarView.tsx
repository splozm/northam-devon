import { useState, useMemo } from "react";
import { ChevronLeft, ChevronRight, Calendar, Clock, MapPin } from "lucide-react";
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

const typeStyles: Record<CalendarEvent["type"], { bg: string; border: string; dot: string }> = {
  kids: { bg: "bg-amber-500/10", border: "border-amber-500/30", dot: "bg-amber-500" },
  active: { bg: "bg-green-500/10", border: "border-green-500/30", dot: "bg-green-500" },
  food: { bg: "bg-orange-500/10", border: "border-orange-500/30", dot: "bg-orange-500" },
  arts: { bg: "bg-purple-500/10", border: "border-purple-500/30", dot: "bg-purple-500" },
  community: { bg: "bg-blue-500/10", border: "border-blue-500/30", dot: "bg-blue-500" },
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

  return (
    <div className="space-y-6">
      {/* Week Navigation */}
      <div className="flex items-center justify-between">
        <Button
          variant="outline"
          size="sm"
          onClick={() => setCurrentDate(subWeeks(currentDate, 1))}
          className="gap-1"
        >
          <ChevronLeft className="w-4 h-4" />
          Previous
        </Button>
        <div className="text-center">
          <h3 className="font-heading text-lg font-semibold text-foreground">
            {format(weekStart, "d MMM")} — {format(weekEnd, "d MMM yyyy")}
          </h3>
          <button
            onClick={() => setCurrentDate(new Date())}
            className="text-xs text-primary hover:underline mt-1"
          >
            Jump to today
          </button>
        </div>
        <Button
          variant="outline"
          size="sm"
          onClick={() => setCurrentDate(addWeeks(currentDate, 1))}
          className="gap-1"
        >
          Next
          <ChevronRight className="w-4 h-4" />
        </Button>
      </div>

      {/* Calendar Grid */}
      <div className="grid grid-cols-7 gap-px rounded-lg overflow-hidden border border-border bg-border">
        {/* Day Headers */}
        {weekDays.map((day) => (
          <div
            key={`header-${day.toISOString()}`}
            className={`px-2 py-3 text-center text-sm font-medium bg-muted ${
              isToday(day) ? "text-primary" : "text-muted-foreground"
            }`}
          >
            <span className="hidden sm:inline">{format(day, "EEEE")}</span>
            <span className="sm:hidden">{format(day, "EEE")}</span>
          </div>
        ))}

        {/* Day Cells */}
        {weekDays.map((day) => {
          const key = format(day, "yyyy-MM-dd");
          const dayEvents = eventsByDay.get(key) || [];
          const today = isToday(day);

          return (
            <div
              key={key}
              className={`min-h-[140px] md:min-h-[180px] p-2 bg-card flex flex-col ${
                today ? "ring-2 ring-inset ring-primary/30" : ""
              }`}
            >
              {/* Date Number */}
              <div className="flex items-center justify-between mb-2">
                <span
                  className={`inline-flex items-center justify-center w-7 h-7 rounded-full text-sm font-medium ${
                    today
                      ? "bg-primary text-primary-foreground"
                      : "text-foreground"
                  }`}
                >
                  {format(day, "d")}
                </span>
              </div>

              {/* Events */}
              <div className="flex-1 space-y-1.5 overflow-y-auto">
                {dayEvents.map((event) => (
                  <div
                    key={event.id}
                    className={`group relative rounded-md border p-1.5 md:p-2 cursor-pointer transition-all hover:shadow-md ${typeStyles[event.type].bg} ${typeStyles[event.type].border}`}
                  >
                    <div className="flex items-start gap-1.5">
                      <span
                        className={`mt-1 w-2 h-2 rounded-full flex-shrink-0 ${typeStyles[event.type].dot}`}
                      />
                      <div className="min-w-0">
                        <p className="text-xs font-semibold text-card-foreground leading-tight truncate">
                          {event.title}
                        </p>
                        <p className="text-[10px] text-muted-foreground mt-0.5 hidden md:block">
                          {event.time}
                        </p>
                      </div>
                    </div>

                    {/* Tooltip on hover */}
                    <div className="absolute left-0 top-full mt-1 z-50 hidden group-hover:block w-56 p-3 rounded-lg bg-card border border-border shadow-lg">
                      <p className="font-heading text-sm font-semibold text-card-foreground mb-1">
                        {event.title}
                      </p>
                      <p className="text-xs text-muted-foreground mb-2 line-clamp-2">
                        {event.description}
                      </p>
                      <div className="space-y-1 text-xs text-muted-foreground">
                        <div className="flex items-center gap-1.5">
                          <Clock className="w-3 h-3" />
                          {event.time}
                        </div>
                        <div className="flex items-center gap-1.5">
                          <MapPin className="w-3 h-3" />
                          {event.venue}
                        </div>
                      </div>
                      <span className={`inline-block text-[10px] font-medium mt-2 px-1.5 py-0.5 rounded-full ${typeStyles[event.type].bg} text-card-foreground`}>
                        {typeLabels[event.type]}
                      </span>
                    </div>
                  </div>
                ))}

                {dayEvents.length === 0 && (
                  <p className="text-[10px] text-muted-foreground/50 text-center mt-4 hidden md:block">
                    No events
                  </p>
                )}
              </div>
            </div>
          );
        })}
      </div>

      {/* Legend */}
      <div className="flex flex-wrap gap-4 justify-center text-xs text-muted-foreground">
        {Object.entries(typeLabels).map(([type, label]) => (
          <div key={type} className="flex items-center gap-1.5">
            <span className={`w-2.5 h-2.5 rounded-full ${typeStyles[type as CalendarEvent["type"]].dot}`} />
            {label}
          </div>
        ))}
      </div>
    </div>
  );
};

export default EventCalendarView;
