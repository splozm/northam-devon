import { Users, Mail, MapPin, Clock, ChevronRight, Heart, Music, Book, Leaf, Baby, Dog } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import Layout from "@/components/layout/Layout";

const communityGroups = [
  { id: 1, name: "Northam WI", category: "Social", icon: Heart, members: "45+", meeting: "3rd Tuesday, 2pm", venue: "Community Hall", description: "Women's Institute branch with monthly talks, outings, and social events." },
  { id: 2, name: "Northam Choir", category: "Music", icon: Music, members: "30+", meeting: "Thursdays, 7pm", venue: "St Margaret's Church", description: "Mixed voice choir performing locally. New members always welcome." },
  { id: 3, name: "Book Club", category: "Literature", icon: Book, members: "12", meeting: "1st Monday, 7:30pm", venue: "The Kingsley", description: "Friendly group reading a mix of fiction and non-fiction." },
  { id: 4, name: "Northam in Bloom", category: "Gardening", icon: Leaf, members: "20+", meeting: "Monthly", venue: "Various", description: "Volunteers keeping Northam beautiful with seasonal planting." },
  { id: 5, name: "Parent & Toddler Group", category: "Family", icon: Baby, members: "Varies", meeting: "Fridays, 10am", venue: "Community Hall", description: "Playgroup for under-5s with tea and coffee for parents." },
  { id: 6, name: "Northam Dog Walkers", category: "Social", icon: Dog, members: "25+", meeting: "Saturdays, 9am", venue: "Burrows car park", description: "Weekly group walks around the Burrows. All dogs welcome!" },
];

const Community = () => (
  <Layout>
    <section className="relative py-16 md:py-24 bg-coastal-deep">
      <div className="absolute inset-0 bg-gradient-to-b from-coastal-deep to-coastal-mid/50" />
      <div className="container mx-auto px-4 relative z-10 text-center animate-fade-in">
        <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-foreground/20 text-primary-foreground text-sm font-medium mb-4">
          <Users className="w-4 h-4" />Get Involved
        </span>
        <h1 className="font-heading text-4xl md:text-6xl font-bold text-primary-foreground mb-4">Community Groups</h1>
        <p className="text-xl text-primary-foreground/80 max-w-2xl mx-auto">Join a club, meet neighbours, and become part of village life</p>
      </div>
    </section>
    <section className="py-12 md:py-16">
      <div className="container mx-auto px-4">
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {communityGroups.map((group) => (
            <Card key={group.id} className="hover-lift cursor-pointer group h-full">
              <CardContent className="p-5">
                <div className="flex items-start gap-4">
                  <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <group.icon className="w-6 h-6 text-primary" />
                  </div>
                  <div className="flex-grow">
                    <span className="text-xs font-medium text-muted-foreground uppercase">{group.category}</span>
                    <h3 className="font-heading text-lg font-semibold text-card-foreground mt-1 group-hover:text-primary transition-colors">{group.name}</h3>
                  </div>
                </div>
                <p className="text-sm text-muted-foreground mt-4 mb-4">{group.description}</p>
                <div className="space-y-2 text-sm text-muted-foreground border-t border-border pt-4">
                  <div className="flex items-center gap-2"><Users className="w-4 h-4" />{group.members} members</div>
                  <div className="flex items-center gap-2"><Clock className="w-4 h-4" />{group.meeting}</div>
                  <div className="flex items-center gap-2"><MapPin className="w-4 h-4" />{group.venue}</div>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>
    </section>
    <section className="py-12 bg-sand-warm/50">
      <div className="container mx-auto px-4 text-center">
        <h2 className="font-heading text-2xl font-bold text-foreground mb-3">Run a community group?</h2>
        <p className="text-muted-foreground mb-6 max-w-lg mx-auto">Get your group listed here to reach more villagers.</p>
        <Button className="bg-accent hover:bg-accent/90">Add Your Group</Button>
      </div>
    </section>
  </Layout>
);

export default Community;
