# American Polytechnic Website

## Overview

This project is a modern, single-page application for a fictional technical college, "American Polytechnic." It is built with the latest version of Angular, showcasing standalone components, signal-based state management, and modern CSS practices. The application is designed to be visually appealing, responsive, and easy to maintain.

## Style and Design

*   **Modern & Clean:** The design is clean and modern, with a focus on readability and user experience.
*   **Responsive:** The layout is fully responsive and adapts to different screen sizes, from mobile to desktop.
*   **Color Palette:** The color palette is professional and inviting, with a mix of blues, grays, and whites.
*   **Typography:** The typography is clean and easy to read, with a clear hierarchy of headings and body text.

## Features

*   **Homepage:** A welcoming homepage with a hero section, a "Why Us" section, and a list of featured programs.
*   **Programs Page:** A page that lists all available programs, with a tabbed interface to filter by category.
*   **Admissions Page:** A page with information on how to apply, including a form to request more information.
*   **Contact Page:** A page with contact information and a form to send a message.
*   **Standalone Components:** All components are standalone, promoting a modular and maintainable architecture.
*   **Signal-Based State Management:** The application uses signals for all state management, ensuring a reactive and performant user interface.
*   **Modern Control Flow:** The templates use the new `@if`, `@for`, and `@switch` syntax for control flow.
*   **Image Optimization:** The application uses `NgOptimizedImage` for all static images.

## Current Task: Phase 1 Bug Bash

### Task 1.1: Fix Tab Switching on Programs Page

*   **Issue:** The tab-switching logic on the Programs page is implemented with direct DOM manipulation, which is not an ideal Angular practice.
*   **Plan:** Refactor the `ProgramsComponent` to use a signal to manage the active tab.

### Task 1.2: Connect "Learn More" Links

*   **Issue:** The "Learn More" links on the homepage's "Featured Programs" section are placeholder links.
*   **Plan:** Update the links to navigate to the Programs page using `routerLink`.

### Task 1.3: Connect Placeholder Links on Contact Page

*   **Issue:** The social media and appointment booking links on the Contact page are placeholders.
*   **Plan:** Update the links with actual URLs.

### Task 1.4: Add Images to Featured Programs

*   **Issue:** The "Featured Programs" section on the homepage is missing images.
*   **Plan:** Add relevant, free-to-use images to the `featured-programs.html` file and use `NgOptimizedImage` for optimization.
