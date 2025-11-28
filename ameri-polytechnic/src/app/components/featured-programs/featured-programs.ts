import { ChangeDetectionStrategy, Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-featured-programs',
  templateUrl: './featured-programs.html',
  styleUrls: ['./featured-programs.css'],
  imports: [RouterLink],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class FeaturedProgramsComponent {
  // Program name to ID mapping based on actual database IDs
  private readonly programIdMap: Record<string, number | null> = {
    'Cloud Computing & DevOps': 26, // Cloud Computing & DevOps Technician
    'Naval Architecture & Marine Engineering': 28, // Naval Architecture & Marine Engineering
    'Cybersecurity & Digital Defense': 24, // Cybersecurity & Digital Defense
    'Smart Home & IoT Systems Technician': 21, // Smart Home & IoT Systems Technician
  };

  getProgramLink(programName: string): string {
    const programId = this.programIdMap[programName];
    return programId ? `/programs/${programId}` : '/programs';
  }
} 
