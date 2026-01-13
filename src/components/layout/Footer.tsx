import { Link } from "react-router-dom";
import { MapPin, Mail, Phone, Facebook, Instagram, Twitter } from "lucide-react";

const Footer = () => {
  return (
    <footer className="bg-coastal-deep text-primary-foreground">
      {/* Main Footer */}
      <div className="container mx-auto px-4 py-12">
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* About */}
          <div>
            <div className="flex items-center gap-2 mb-4">
              <div className="w-10 h-10 rounded-full bg-primary-foreground/20 flex items-center justify-center">
                <MapPin className="w-5 h-5" />
              </div>
              <div>
                <span className="font-heading text-xl font-bold">Northam</span>
                <span className="block text-xs text-primary-foreground/70 -mt-1">Devon</span>
              </div>
            </div>
            <p className="text-primary-foreground/80 text-sm leading-relaxed">
              A charming coastal village where community spirit meets Devon's stunning shores. 
              Discover local events, businesses, and the rich heritage of Northam.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-heading text-lg font-semibold mb-4">Quick Links</h4>
            <ul className="space-y-2">
              <li><Link to="/events" className="text-primary-foreground/80 hover:text-primary-foreground text-sm transition-colors">Events Calendar</Link></li>
              <li><Link to="/directory" className="text-primary-foreground/80 hover:text-primary-foreground text-sm transition-colors">Business Directory</Link></li>
              <li><Link to="/things-to-do" className="text-primary-foreground/80 hover:text-primary-foreground text-sm transition-colors">Things to Do</Link></li>
              <li><Link to="/community" className="text-primary-foreground/80 hover:text-primary-foreground text-sm transition-colors">Community Groups</Link></li>
              <li><Link to="/history" className="text-primary-foreground/80 hover:text-primary-foreground text-sm transition-colors">History & Heritage</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="font-heading text-lg font-semibold mb-4">Contact</h4>
            <ul className="space-y-3">
              <li className="flex items-center gap-2 text-sm text-primary-foreground/80">
                <MapPin className="w-4 h-4 flex-shrink-0" />
                Northam, Nr Bideford, Devon
              </li>
              <li className="flex items-center gap-2 text-sm text-primary-foreground/80">
                <Mail className="w-4 h-4 flex-shrink-0" />
                hello@northamdevon.co.uk
              </li>
              <li className="flex items-center gap-2 text-sm text-primary-foreground/80">
                <Phone className="w-4 h-4 flex-shrink-0" />
                Parish Council: 01onal-237
              </li>
            </ul>
          </div>

          {/* Social */}
          <div>
            <h4 className="font-heading text-lg font-semibold mb-4">Follow Us</h4>
            <p className="text-sm text-primary-foreground/80 mb-4">
              Stay connected with Northam community news and events.
            </p>
            <div className="flex gap-3">
              <a href="#" className="w-10 h-10 rounded-full bg-primary-foreground/20 flex items-center justify-center hover:bg-primary-foreground/30 transition-colors">
                <Facebook className="w-5 h-5" />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-primary-foreground/20 flex items-center justify-center hover:bg-primary-foreground/30 transition-colors">
                <Instagram className="w-5 h-5" />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-primary-foreground/20 flex items-center justify-center hover:bg-primary-foreground/30 transition-colors">
                <Twitter className="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>
      </div>

      {/* Bottom Bar */}
      <div className="border-t border-primary-foreground/20">
        <div className="container mx-auto px-4 py-4">
          <div className="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-primary-foreground/60">
            <p>© 2025 Northam Community Website. Made with ♥ for our village.</p>
            <div className="flex gap-4">
              <Link to="#" className="hover:text-primary-foreground transition-colors">Privacy</Link>
              <Link to="#" className="hover:text-primary-foreground transition-colors">Accessibility</Link>
              <Link to="#" className="hover:text-primary-foreground transition-colors">Contact</Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
