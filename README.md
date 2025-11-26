# AmeriPolytechnic

Educational institution management system with multi-portal architecture.

## Project Structure

This repository contains the documentation and architecture for the AmeriPolytechnic system.

### Excluded Directories

The following directories are excluded from this repository:

- `/ameri-polytechnic-api/` - Laravel backend API (separate repository)
- `/Colorado/` - State compliance documents (private)
- `/DOCS/` - Internal documentation (private)

## Architecture Overview

### Frontend Applications (6 Portals)

1. **Admissions Portal** - `admissions.americanpolytechnic.edu`
2. **Learning Portal** - `lms.americanpolytechnic.edu`
3. **Instructor Portal** - `instructors.americanpolytechnic.edu`
4. **Admin Portal** - `admin.americanpolytechnic.edu`
5. **Academics Portal** - `academics.americanpolytechnic.edu`
6. **Finance Portal** - `finance.americanpolytechnic.edu`

### Database Architecture

- **9 Schemas** with **92 Tables**
- Multi-schema PostgreSQL architecture
- Domain-driven design with clear boundaries

## Technology Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Single Laravel application with subdomain routing
- **Database:** PostgreSQL with multi-schema architecture
- **Styling:** Tailwind CSS v4

## Key Features

- Role-based access control (RBAC)
- Single Sign-On (SSO) across portals
- Learning Management System (LMS)
- Document Management System (DMS)
- Financial management
- Student information system
- Admissions workflow
- Academic program management

## Documentation

For detailed documentation, see the internal documentation repository (excluded from public deployment).

## License

Proprietary - All rights reserved

