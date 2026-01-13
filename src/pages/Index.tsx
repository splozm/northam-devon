import { useState } from "react";
import { Check, Waves, Cloud, Sun } from "lucide-react";
import { Button } from "@/components/ui/button";
import PaletteShowcase from "@/components/PaletteShowcase";

const Index = () => {
  const [selectedPalette, setSelectedPalette] = useState<string | null>(null);

  const palettes = [
    {
      id: "ocean",
      themeName: "theme-ocean",
      title: "Ocean Blues & Sandy Warmth",
      description: "Deep sea blues, turquoise accents, and warm sandy beige tones. Classic coastal elegance.",
      icon: Waves,
      gradient: "from-blue-600 via-cyan-500 to-amber-200",
    },
    {
      id: "mist",
      themeName: "theme-mist",
      title: "Soft Coastal Mist",
      description: "Muted sage greens, soft greys, and weathered whites. Subtle and sophisticated.",
      icon: Cloud,
      gradient: "from-slate-400 via-emerald-300 to-stone-200",
    },
    {
      id: "vibrant",
      themeName: "theme-vibrant",
      title: "Vibrant Devon",
      description: "Brighter coastal palette with coral, teal, and sunshine yellows. Energetic and welcoming.",
      icon: Sun,
      gradient: "from-teal-500 via-amber-400 to-orange-400",
    },
  ];

  return (
    <div className="min-h-screen bg-background">
      {/* Page Header */}
      <header className="bg-gradient-to-r from-slate-900 to-slate-800 text-white py-20 px-4">
        <div className="container mx-auto text-center animate-fade-in">
          <span className="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white/90 text-sm font-medium mb-6">
            Design Exploration
          </span>
          <h1 className="font-heading text-5xl md:text-6xl lg:text-7xl font-bold mb-6">
            Northam, Devon
          </h1>
          <p className="text-xl md:text-2xl text-white/80 max-w-3xl mx-auto mb-8 font-light">
            Explore three unique colour palette directions for your community & tourism website
          </p>
          <p className="text-white/60 max-w-2xl mx-auto">
            Each palette is designed to feel coastal, welcoming, and professional — 
            reflecting Northam's beautiful Devon location and vibrant community spirit.
          </p>
        </div>
      </header>

      {/* Quick Selection Bar */}
      <div className="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-border shadow-sm">
        <div className="container mx-auto px-4 py-4">
          <div className="flex flex-wrap items-center justify-center gap-4">
            <span className="text-sm font-medium text-muted-foreground hidden md:inline">
              Jump to palette:
            </span>
            {palettes.map((palette) => (
              <a
                key={palette.id}
                href={`#${palette.id}`}
                className={`inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all
                  ${selectedPalette === palette.id 
                    ? 'bg-primary text-primary-foreground shadow-md' 
                    : 'bg-secondary text-secondary-foreground hover:bg-secondary/80'
                  }`}
                onClick={() => setSelectedPalette(palette.id)}
              >
                <palette.icon className="w-4 h-4" />
                {palette.title.split(' ').slice(0, 2).join(' ')}
                {selectedPalette === palette.id && <Check className="w-4 h-4" />}
              </a>
            ))}
          </div>
        </div>
      </div>

      {/* Palette Showcases */}
      {palettes.map((palette, index) => (
        <div key={palette.id} id={palette.id} className={index % 2 === 1 ? 'bg-muted/30' : ''}>
          <PaletteShowcase
            themeName={palette.themeName}
            title={palette.title}
            description={palette.description}
          />
        </div>
      ))}

      {/* Decision Section */}
      <section className="py-20 px-4 bg-gradient-to-b from-background to-muted/50">
        <div className="container mx-auto text-center max-w-3xl">
          <h2 className="font-heading text-4xl font-bold text-foreground mb-6">
            Which palette speaks to you?
          </h2>
          <p className="text-lg text-muted-foreground mb-10">
            Once you've chosen your preferred colour direction, I'll build out the complete 
            website with all pages, components, and responsive layouts using that palette.
          </p>
          <div className="flex flex-col md:flex-row justify-center gap-4">
            <Button
              size="lg"
              variant="outline"
              className="gap-2"
              onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
            >
              <Waves className="w-5 h-5" />
              I prefer Ocean Blues
            </Button>
            <Button
              size="lg"
              variant="outline"
              className="gap-2"
              onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
            >
              <Cloud className="w-5 h-5" />
              I prefer Coastal Mist
            </Button>
            <Button
              size="lg"
              variant="outline"
              className="gap-2"
              onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
            >
              <Sun className="w-5 h-5" />
              I prefer Vibrant Devon
            </Button>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-slate-900 text-white py-8 px-4">
        <div className="container mx-auto text-center">
          <p className="text-slate-400 text-sm">
            Northam Community & Tourism Website — Design Exploration
          </p>
        </div>
      </footer>
    </div>
  );
};

export default Index;
