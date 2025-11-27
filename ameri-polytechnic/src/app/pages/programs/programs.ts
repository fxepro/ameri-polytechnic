
import { ChangeDetectionStrategy, Component, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';

type TabId = 'trades' | 'it-cyber' | 'engineering' | 'aviation' | 'health' | 'green-energy' | 'business';

interface Program {
  id: number;
  title: string;
  description: string;
  skills: string;
  certifications: string;
  careerPaths: string;
}

@Component({
  selector: 'app-programs',
  standalone: true,
  imports: [RouterLink, CommonModule],
  templateUrl: './programs.html',
  styleUrls: ['./programs.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class ProgramsComponent {
  public activeTab = signal<TabId>('trades');
  public selectedProgram = signal<Program | null>(null);

  programs: Record<TabId, Program[]> = {
    'trades': [
      { id: 1, title: 'Electrical Technology', description: 'Learn residential, commercial, and industrial electrical systems.', skills: 'Wiring, circuits, panels, troubleshooting, code compliance', certifications: 'NEC, OSHA 30', careerPaths: 'Electrician apprentice, electrical tech, power systems specialist' },
      { id: 2, title: 'HVAC & Refrigeration Technology', description: 'Master heating, cooling, ventilation, and refrigeration systems in homes and businesses.', skills: 'Diagnostics, installation, refrigerants, smart thermostats', certifications: 'EPA 608, HVAC Excellence', careerPaths: 'HVAC technician, climate control specialist' },
      { id: 3, title: 'Plumbing Technology', description: 'Become a trained plumbing professional for residential and commercial systems.', skills: 'Piping, water systems, drainage, fixtures, welding', certifications: 'OSHA, plumbing code fundamentals', careerPaths: 'Plumber apprentice, pipefitter, maintenance tech' },
      { id: 4, title: 'Welding & Metal Fabrication', description: 'Train in modern welding processes and fabrication techniques.', skills: 'MIG, TIG, stick welding, blueprints, cutting systems', certifications: 'AWS', careerPaths: 'Welder, fabricator, industrial metalworker' },
      { id: 5, title: 'Carpentry & Building Technology', description: 'Prepare for careers in residential and commercial construction.', skills: 'Framing, materials, construction math, finishing', certifications: '', careerPaths: 'Carpenter apprentice, builder, construction technician' },
      { id: 6, title: 'Diesel & Heavy Equipment Technology', description: 'Service and repair industrial engines and machinery.', skills: 'Hydraulics, powertrains, diesel systems, diagnostics', certifications: '', careerPaths: 'Diesel mechanic, fleet technician, heavy equipment tech' },
      { id: 7, title: 'Automotive Service Technology', description: 'Become a certified automotive technician.', skills: 'Engines, transmissions, electronics, diagnostics', certifications: 'ASE', careerPaths: 'Auto tech, service advisor, performance tech' }
    ],
    'it-cyber': [
      { id: 8, title: 'Cybersecurity & Digital Defense', description: 'A complete pathway to protecting digital assets from modern threats.', skills: 'Ethical hacking, network security, SIEM tools, forensics', certifications: 'CompTIA Security+, CySA+', careerPaths: 'Cybersecurity analyst, SOC technician' },
      { id: 9, title: 'Information Technology Support & Systems Administration', description: 'Train to manage modern IT infrastructure.', skills: 'Networking, cloud systems, scripting, troubleshooting', certifications: 'A+, Net+, Linux+, Azure Fundamentals', careerPaths: 'IT support tech, systems admin, help desk engineer' },
      { id: 10, title: 'Cloud Computing & DevOps Technician', description: 'Prepare for cloud-driven automation careers.', skills: 'AWS, CI/CD, Linux, containerization', certifications: 'AWS Cloud Practitioner, Docker Essentials', careerPaths: 'Cloud technician, DevOps assistant' },
      { id: 11, title: 'Web & Application Development', description: 'Build digital experiences and software solutions.', skills: 'HTML/CSS/JS, Angular, Laravel, databases, APIs', certifications: '', careerPaths: 'Web developer, front-end developer, application technician' }
    ],
    'engineering': [
      { id: 12, title: 'Robotics & Automation Technology', description: 'Learn how to design, operate, and maintain automated systems.', skills: 'Programmable logic controllers (PLCs), sensors, industrial robots', certifications: '', careerPaths: 'Robotics technician, automation specialist' },
      { id: 13, title: 'Mechatronics & Industrial Maintenance', description: 'A multidisciplinary program for modern factories.', skills: 'Electronics, mechanics, pneumatics, controls', certifications: '', careerPaths: 'Mechatronics tech, maintenance engineer' },
      { id: 14, title: 'CNC Machining & Advanced Manufacturing', description: 'Work with precision machining and automated production.', skills: 'CNC programming, CAD/CAM, tooling, quality control', certifications: '', careerPaths: 'Machinist, manufacturing technician' }
    ],
    'aviation': [
      { id: 15, title: 'Unmanned Aerial Systems (UAS) – Drone Operations', description: 'Become certified in drone piloting and aerial systems.', skills: 'Flight ops, mapping, photography, regulations', certifications: 'FAA Part 107', careerPaths: 'Drone operator, aerial survey specialist' },
      { id: 16, title: 'Aviation Maintenance Technician (AMT)', description: 'Prepare for aircraft maintenance and inspection roles.', skills: 'Airframe, engines, avionics, FAA regulations', certifications: 'FAA A&P prep', careerPaths: 'Aviation maintenance tech' }
    ],
    'health': [
      { id: 17, title: 'Medical Assisting Technology', description: 'Hands-on medical and clinical training.', skills: 'Patient care, lab work, vitals, admin tasks', certifications: '', careerPaths: 'Medical assistant, clinic tech' },
      { id: 18, title: 'Emergency Medical Technician (EMT)', description: 'Respond to real-life emergencies and save lives.', skills: 'Trauma care, CPR, first response, emergency protocols', certifications: 'EMT-B', careerPaths: 'EMT, paramedic pathway' }
    ],
    'green-energy': [
      { id: 19, title: 'Solar Energy Installation & Maintenance', description: 'Prepare for the growing renewable energy market.', skills: 'PV installation, electrical fundamentals, safety', certifications: '', careerPaths: 'Solar installer, renewable energy tech' },
      { id: 20, title: 'Smart Home & IoT Systems Technician', description: 'Support the next generation of connected houses and buildings.', skills: 'Sensors, automation, networking, integration', certifications: '', careerPaths: 'IoT installer, smart home specialist' }
    ],
    'business': [
      { id: 21, title: 'Construction Project Administration', description: 'Learn the management side of the construction industry.', skills: 'Scheduling, estimating, procurement, safety', certifications: '', careerPaths: 'Project coordinator, site administrator' },
      { id: 22, title: 'Supply Chain & Logistics Operations', description: 'Train for roles in global logistics and warehousing.', skills: 'Inventory, shipping, planning, warehouse software', certifications: '', careerPaths: 'Logistics tech, supply chain assistant' }
    ]
  };

  selectTab(tabId: TabId): void {
    this.activeTab.set(tabId);
    this.selectedProgram.set(null);
  }

  selectProgram(program: Program): void {
    this.selectedProgram.set(program);
  }

  getCurrentPrograms(): Program[] {
    return this.programs[this.activeTab()] || [];
  }
}
