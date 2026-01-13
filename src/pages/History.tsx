import { History as HistoryIcon, BookOpen, Landmark, Anchor, Church } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import Layout from "@/components/layout/Layout";

const timelineEvents = [
  { year: "Pre-1066", title: "Saxon Origins", description: "Northam's name derives from 'North Hamm', meaning northern meadow. The area was settled long before the Norman conquest." },
  { year: "1069", title: "First Church", description: "St Margaret's Church founded, becoming the spiritual heart of the village for nearly a millennium." },
  { year: "1600s", title: "Maritime Heritage", description: "Northam thrived as a shipbuilding centre. The nearby port of Appledore built vessels for trade and exploration." },
  { year: "1860s", title: "Royal North Devon Golf Club", description: "England's oldest links course established on Northam Burrows, attracting visitors from across the country." },
  { year: "1874", title: "Westward Ho! Founded", description: "The neighbouring resort town developed, named after Charles Kingsley's novel set in the area." },
  { year: "Today", title: "Modern Community", description: "A vibrant village balancing tourism, community spirit, and its precious natural environment." },
];

const History = () => (
  <Layout>
    <section className="relative py-16 md:py-24 bg-coastal-deep">
      <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
      <div className="container mx-auto px-4 relative z-10 text-center animate-fade-in">
        <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
          <HistoryIcon className="w-4 h-4" />Our Story
        </span>
        <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">History & Heritage</h1>
        <p className="text-xl text-primary-foreground/80 max-w-2xl mx-auto">From Saxon settlement to coastal gem — a thousand years of Northam's story</p>
      </div>
    </section>
    
    <section className="py-12 md:py-16">
      <div className="container mx-auto px-4 max-w-4xl">
        <div className="prose prose-lg max-w-none mb-12">
          <p className="text-lg text-muted-foreground leading-relaxed">
            Nestled on the North Devon coast, Northam has been a place of community for over a thousand years. 
            Its story intertwines with the sea, the land, and the remarkable people who have called it home.
          </p>
        </div>
        
        <div className="relative">
          <div className="absolute left-4 md:left-1/2 top-0 bottom-0 w-0.5 bg-border" />
          <div className="space-y-12">
            {timelineEvents.map((event, index) => (
              <div key={index} className={`relative flex items-start gap-8 ${index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse'}`}>
                <div className="hidden md:block flex-1" />
                <div className="absolute left-4 md:left-1/2 w-4 h-4 rounded-full bg-primary border-4 border-background -translate-x-1/2" />
                <Card className="flex-1 ml-12 md:ml-0 hover-lift">
                  <CardContent className="p-5">
                    <span className="text-sm font-bold text-accent">{event.year}</span>
                    <h3 className="font-heading text-xl font-semibold text-card-foreground mt-1 mb-2">{event.title}</h3>
                    <p className="text-sm text-muted-foreground">{event.description}</p>
                  </CardContent>
                </Card>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
    
    <section className="py-12 bg-sand-warm/50">
      <div className="container mx-auto px-4">
        <h2 className="font-heading text-2xl font-bold text-foreground text-center mb-8">Notable Landmarks</h2>
        <div className="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
          {[
            { icon: Church, name: "St Margaret's Church", desc: "12th century parish church with stunning views" },
            { icon: Anchor, name: "The Burrows", desc: "595 acres of protected coastal common" },
            { icon: Landmark, name: "Bone Hill", desc: "Historic viewpoint overlooking Bideford Bay" },
          ].map((landmark, i) => (
            <Card key={i} className="text-center hover-lift">
              <CardContent className="p-6">
                <landmark.icon className="w-8 h-8 mx-auto text-primary mb-3" />
                <h3 className="font-heading font-semibold text-foreground mb-1">{landmark.name}</h3>
                <p className="text-sm text-muted-foreground">{landmark.desc}</p>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
  </Layout>
);

export default History;
