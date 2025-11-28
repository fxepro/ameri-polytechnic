import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Program {
  id: number;
  name: string;
  description: string;
  category: string;
  duration_months: number;
  format: string;
  tuition_cost: number | null;
  tuition: number | null;
  requirements: string | null;
  status: string;
  overview: string | null;
  program_code: string | null;
  skills: string[] | null;
  career_paths: string[] | null;
  certifications: string[] | null;
  program_length: string | null;
  delivery_mode: string | null;
  salary_range: string | null;
  industry_outlook: string | null;
  created_at: string;
  updated_at: string;
}

@Injectable({ providedIn: 'root' })
export class ProgramService {
  private http = inject(HttpClient);
  private apiUrl = environment.apiUrl;

  getProgramById(id: number): Observable<Program> {
    return this.http.get<Program>(`${this.apiUrl}/programs/${id}`);
  }

  searchProgramsByName(name: string): Observable<Array<{id: number, name: string, program_code: string | null}>> {
    return this.http.get<Array<{id: number, name: string, program_code: string | null}>>(
      `${this.apiUrl}/programs/search?name=${encodeURIComponent(name)}`
    );
  }
}

