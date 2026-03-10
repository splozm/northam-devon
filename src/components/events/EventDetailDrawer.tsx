import { Clock, MapPin, Calendar, X } from "lucide-react";
import {
  Drawer,
  DrawerContent,
  DrawerHeader,
  DrawerTitle,
  DrawerClose,
} from "@/components/ui/drawer";
import { Button } from "@/components/ui/button";

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

const typeStyles: Record<CalendarEvent["type"], { gradient: string; text: string; dot: string }> = {
  kids: { gradient: "bg-gradient-to-br from-amber-400/20 to-orange-400/10", text: "text-amber-700", dot: "bg-gradient-to-br from-amber-400 to-orange-500" },
  active: { gradient: "bg-gradient-to-br from-emerald-400/20 to-teal-400/10", text: "text-emerald-700", dot: "bg-gradient-to-br from-emerald-400 to-teal-500" },
  food: { gradient: "bg-gradient-to-br from-orange-400/20 to-red-400/10", text: "text-orange-700", dot: "bg-gradient-to-br from-orange-400 to-red-500" },
  arts: { gradient: "bg-gradient-to-br from-violet-400/20 to-purple-400/10", text: "text-violet-700", dot: "bg-gradient-to-br from-violet-400 to-purple-500" },
  community: { gradient: "bg-gradient-to-br from-sky-400/20 to-blue-400/10", text: "text-sky-700", dot: "bg-gradient-to-br from-sky-400 to-blue-500" },
};

const typeLabels: Record<CalendarEvent["type"], string> = {
  kids: "Kids & Family",
  active: "Active & Outdoors",
  food: "Food & Drink",
  arts: "Arts & Culture",
  community: "Community & Social",
};

interface EventDetailDrawerProps {
  event: CalendarEvent | null;
  open: boolean;
  onOpenChange: (open: boolean) => void;
}

const EventDetailDrawer = ({ event, open, onOpenChange }: EventDetailDrawerProps) => {
  if (!event) return null;

  const style = typeStyles[event.type];

  return (
    <Drawer open={open} onOpenChange={onOpenChange}>
      <DrawerContent className="max-h-[85vh]">
        <div className="mx-auto w-full max-w-lg">
          <DrawerHeader className="relative pb-2">
            <DrawerClose asChild>
              <Button variant="ghost" size="icon" className="absolute right-2 top-2 rounded-full">
                <X className="w-4 h-4" />
              </Button>
            </DrawerClose>
            <div className={`inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full w-fit ${style.gradient} ${style.text}`}>
              <span className={`w-2 h-2 rounded-full ${style.dot}`} />
              {typeLabels[event.type]}
            </div>
            <DrawerTitle className="font-heading text-xl font-bold text-foreground text-left mt-2">
              {event.title}
            </DrawerTitle>
          </DrawerHeader>

          <div className="px-4 pb-8 space-y-5">
            {/* Key details */}
            <div className="grid grid-cols-1 gap-3">
              <div className="flex items-center gap-3 p-3 rounded-xl bg-muted/40">
                <div className="p-2 rounded-lg bg-primary/10">
                  <Calendar className="w-4 h-4 text-primary" />
                </div>
                <div>
                  <p className="text-xs text-muted-foreground">Date</p>
                  <p className="text-sm font-medium text-foreground">{event.displayDate}</p>
                </div>
              </div>
              <div className="flex items-center gap-3 p-3 rounded-xl bg-muted/40">
                <div className="p-2 rounded-lg bg-primary/10">
                  <Clock className="w-4 h-4 text-primary" />
                </div>
                <div>
                  <p className="text-xs text-muted-foreground">Time</p>
                  <p className="text-sm font-medium text-foreground">{event.time}</p>
                </div>
              </div>
              <a
                href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(event.venue + ", Northam, Devon")}`}
                target="_blank"
                rel="noopener noreferrer"
                className="flex items-center gap-3 p-3 rounded-xl bg-muted/40 hover:bg-muted/60 transition-colors group"
              >
                <div className="p-2 rounded-lg bg-primary/10">
                  <MapPin className="w-4 h-4 text-primary" />
                </div>
                <div className="flex-1">
                  <p className="text-xs text-muted-foreground">Venue</p>
                  <p className="text-sm font-medium text-foreground">{event.venue}</p>
                </div>
                <span className="text-xs text-primary font-medium opacity-0 group-hover:opacity-100 transition-opacity">Map →</span>
              </a>
            </div>

            {/* Description */}
            <div>
              <h4 className="text-sm font-semibold text-foreground mb-2">About this event</h4>
              <p className="text-sm text-muted-foreground leading-relaxed">{event.description}</p>
            </div>
          </div>
        </div>
      </DrawerContent>
    </Drawer>
  );
};

export default EventDetailDrawer;
