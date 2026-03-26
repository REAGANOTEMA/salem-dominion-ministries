import { useEffect } from "react";
import { useLocation } from "react-router-dom";

const SITE_NAME = "Salem Dominion Ministries";

const routeMeta: Record<string, { title: string; description: string }> = {
  "/": {
    title: "Home",
    description:
      "Welcome to Salem Dominion Ministries in Iganga, Uganda. Join us for worship, prayer, discipleship, and community outreach.",
  },
  "/about": {
    title: "About",
    description:
      "Learn our mission, vision, and faith foundation at Salem Dominion Ministries.",
  },
  "/leadership": {
    title: "Leadership",
    description:
      "Meet the leadership team serving Salem Dominion Ministries.",
  },
  "/ministries": {
    title: "Ministries",
    description:
      "Explore church ministries for children, youth, women, men, worship, and outreach.",
  },
  "/sermons": {
    title: "Sermons",
    description:
      "Watch and listen to recent sermons and live services from Salem Dominion Ministries.",
  },
  "/events": {
    title: "Events",
    description:
      "Stay up to date with upcoming church events, conferences, and community programs.",
  },
  "/donate": {
    title: "Donate",
    description:
      "Support the ministry through online giving, mobile money, and bank transfer options.",
  },
  "/gallery": {
    title: "Gallery",
    description:
      "View photos and highlights from services, events, and church life.",
  },
  "/blog": {
    title: "Blog & News",
    description:
      "Read devotionals, announcements, and ministry updates from Salem Dominion Ministries.",
  },
  "/contact": {
    title: "Contact",
    description:
      "Get in touch with Salem Dominion Ministries in Iganga. We would love to pray and connect with you.",
  },
};

const SeoManager = () => {
  const { pathname } = useLocation();

  useEffect(() => {
    const current = routeMeta[pathname] ?? {
      title: "Page Not Found",
      description: "The page you are looking for could not be found.",
    };

    document.title = `${current.title} | ${SITE_NAME}`;

    const descriptionTag = document.querySelector('meta[name="description"]');
    if (descriptionTag) {
      descriptionTag.setAttribute("content", current.description);
    }
  }, [pathname]);

  return null;
};

export default SeoManager;
