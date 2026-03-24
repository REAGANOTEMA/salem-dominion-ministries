import { useEffect } from "react";
import logo from "@/assets/logo.jpeg";

const upsertIcon = (rel: string, href: string, type = "image/jpeg") => {
  let link = document.querySelector(`link[rel='${rel}']`) as HTMLLinkElement | null;

  if (!link) {
    link = document.createElement("link");
    link.rel = rel;
    document.head.appendChild(link);
  }

  link.href = href;
  link.type = type;
};

const FaviconManager = () => {
  useEffect(() => {
    upsertIcon("icon", logo);
    upsertIcon("shortcut icon", logo);
    upsertIcon("apple-touch-icon", logo);
  }, []);

  return null;
};

export default FaviconManager;
