import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Index from "./pages/Index";
import Events from "./pages/Events";
import EventsV2 from "./pages/EventsV2";
import Directory from "./pages/Directory";
import ThingsToDo from "./pages/ThingsToDo";
import Community from "./pages/Community";
import History from "./pages/History";
import BusinessPage from "./pages/BusinessPage";
import LocalServices from "./pages/LocalServices";
import NotFound from "./pages/NotFound";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Index />} />
          <Route path="/events" element={<Events />} />
          <Route path="/events-v2" element={<EventsV2 />} />
          <Route path="/directory" element={<Directory />} />
          <Route path="/business/:slug" element={<BusinessPage />} />
          <Route path="/local-services" element={<LocalServices />} />
          <Route path="/things-to-do" element={<ThingsToDo />} />
          <Route path="/community" element={<Community />} />
          <Route path="/history" element={<History />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
