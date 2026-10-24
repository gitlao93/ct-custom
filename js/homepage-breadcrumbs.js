document.addEventListener("DOMContentLoaded", () => {
  const navigation = document.querySelector("#section-breadcrumbs");
  const content = document.querySelector("#homepage-editor-content");

  if (!navigation || !content) return;

  const list = navigation.querySelector("ol");
  const headings = [...content.querySelectorAll("h2, h3")];

  if (!list || !headings.length) return;

  const homeUrl = navigation.dataset.homeUrl || "/";

  const usedIds = new Set(
    [...document.querySelectorAll("[id]")].map((element) => element.id),
  );

  const slugify = (value) =>
    value
      .toLowerCase()
      .normalize("NFKD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[^a-z0-9]+/g, "-")
      .replace(/^-|-$/g, "");

  // Give headings unique anchors if the editor has not assigned them.
  headings.forEach((heading, index) => {
    if (heading.id) return;

    const base = slugify(heading.textContent.trim()) || `section-${index + 1}`;
    let candidate = base;
    let counter = 2;

    while (usedIds.has(candidate)) {
      candidate = `${base}-${counter++}`;
    }

    heading.id = candidate;
    usedIds.add(candidate);
  });

  // Each H3 belongs to the most recent H2.
  const sections = [];
  let currentParent = null;

  headings.forEach((heading) => {
    const section = {
      id: heading.id,
      title: heading.textContent.trim(),
      heading,
      level: Number(heading.tagName.substring(1)),
      parent: null,
    };

    if (section.level === 2) {
      currentParent = section;
    } else {
      section.parent = currentParent;
    }

    sections.push(section);
  });

  const createItem = (title, url, current = false) => {
    const item = document.createElement("li");

    if (current) {
      item.textContent = title;
      item.setAttribute("aria-current", "location");
    } else {
      const link = document.createElement("a");
      link.href = url;
      link.textContent = title;
      item.appendChild(link);
    }

    return item;
  };

  let activeSection = null;

  const render = (section) => {
    if (activeSection === section) return;

    activeSection = section;

    const items = [];
    let current = section;

    while (current) {
      items.unshift(current);
      current = current.parent;
    }

    const fragment = document.createDocumentFragment();

    fragment.appendChild(createItem("Home", homeUrl, items.length === 0));

    items.forEach((item, index) => {
      const isCurrent = index === items.length - 1;

      fragment.appendChild(createItem(item.title, `#${item.id}`, isCurrent));
    });

    list.replaceChildren(fragment);
  };

  // Use heading positions to determine the active section.

  const updateActiveSection = () => {
    const readingPosition = window.innerHeight * 0.45;
    const maxScroll =
      document.documentElement.scrollHeight - window.innerHeight;
    const atBottom = window.scrollY >= maxScroll - 5;

    let active = null;

    for (const section of sections) {
      const headingTop = section.heading.getBoundingClientRect().top;

      if (headingTop <= readingPosition) {
        active = section;
      } else {
        break;
      }
    }

    if (atBottom && sections.length) {
      active = sections[sections.length - 1];
    }

    render(active);
  };

  let scheduled = false;

  const scheduleUpdate = () => {
    if (scheduled) return;

    scheduled = true;

    requestAnimationFrame(() => {
      updateActiveSection();
      scheduled = false;
    });
  };

  window.addEventListener("scroll", scheduleUpdate, { passive: true });
  window.addEventListener("resize", scheduleUpdate);
  window.addEventListener("hashchange", scheduleUpdate);

  updateActiveSection();
});
