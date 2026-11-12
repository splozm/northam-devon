import { cn } from "@/lib/utils";

type LogoProps = {
  /** "onLight" for light surfaces (header), "onDark" for dark surfaces (footer) */
  variant?: "onLight" | "onDark";
  /** Extra classes for the wordmark, e.g. to hide it on small screens */
  textClassName?: string;
  className?: string;
};

/**
 * Northam brand lockup: a deep-teal rounded tile holding a serif "N",
 * beside the "Northam / Devon" wordmark.
 *
 * Built from the brand's own type and colour tokens rather than a raster image,
 * so it stays sharp at any size and can flip for dark surfaces.
 */
const Logo = ({ variant = "onLight", textClassName, className }: LogoProps) => {
  const onDark = variant === "onDark";

  return (
    <span className={cn("flex items-center gap-3 select-none", className)}>
      <span
        aria-hidden="true"
        className={cn(
          "grid h-10 w-10 shrink-0 place-items-center rounded-xl",
          onDark ? "bg-sand-light text-coastal-deep" : "bg-coastal-deep text-sand-light"
        )}
      >
        <span className="font-heading text-[26px] font-semibold leading-none">N</span>
      </span>

      <span className={cn("leading-none", textClassName)}>
        <span
          className={cn(
            "block font-heading text-xl font-bold tracking-tight",
            onDark ? "text-sand-light" : "text-foreground"
          )}
        >
          Northam
        </span>
        <span
          className={cn(
            "mt-1 block text-[11px] uppercase tracking-[0.28em]",
            onDark ? "text-sand-light/70" : "text-muted-foreground"
          )}
        >
          Devon
        </span>
      </span>
    </span>
  );
};

export default Logo;
