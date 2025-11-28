import { ChangeDetectionStrategy, Component, OnInit, signal, inject } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CommonModule, TitleCasePipe } from '@angular/common';
import { ProgramService, Program } from '../../services/program.service';

@Component({
  selector: 'app-program-detail',
  standalone: true,
  imports: [CommonModule, RouterLink, TitleCasePipe],
  templateUrl: './program-detail.html',
  styleUrls: ['./program-detail.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class ProgramDetailComponent implements OnInit {
  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private programService = inject(ProgramService);

  program = signal<Program | null>(null);
  loading = signal(true);
  error = signal<string | null>(null);

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.loadProgram(parseInt(id, 10));
    } else {
      this.error.set('Invalid program ID');
      this.loading.set(false);
    }
  }

  loadProgram(id: number): void {
    this.loading.set(true);
    this.error.set(null);

    this.programService.getProgramById(id).subscribe({
      next: (data) => {
        this.program.set(data);
        this.loading.set(false);
      },
      error: (err) => {
        const errorMessage = err?.error?.error || err?.message || 'Failed to load program details. Please try again later.';
        this.error.set(errorMessage);
        this.loading.set(false);
        console.error('Error loading program:', err);
        console.error('Error details:', JSON.stringify(err, null, 2));
      }
    });
  }

  formatCurrency(amount: number | null): string {
    if (!amount) return 'Contact for pricing';
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(amount);
  }

  formatList(items: string[] | null): string[] {
    if (!items || !Array.isArray(items)) return [];
    return items;
  }

  formatOverview(text: string | null): string {
    if (!text) return '';
    
    // Section headers to detect (in order of appearance)
    const sectionHeaders = [
      'Program Overview',
      'Learning Outcomes',
      'Industry Certifications',
      'Career Opportunities',
      'Career Paths',
      'Key Courses',
      'Program Highlights',
      'Admission Requirements',
      'What You\'ll Learn'
    ];
    
    // Replace multiple spaces (2+) with a special marker
    let formatted = text.replace(/\s{2,}/g, ' ||SPLIT|| ');
    
    // Split by the marker
    const parts = formatted.split(' ||SPLIT|| ').map(p => p.trim()).filter(p => p);
    
    let html = '';
    let currentSection = '';
    let currentContent: string[] = [];
    
    for (const part of parts) {
      // Check if this part is a section header
      let foundHeader = '';
      for (const header of sectionHeaders) {
        if (part === header || part.startsWith(header + ' ')) {
          foundHeader = header;
          break;
        }
      }
      
      if (foundHeader) {
        // Process previous section
        if (currentSection) {
          html += this.formatSectionContent(currentSection, currentContent);
        }
        
        // Start new section
        currentSection = foundHeader;
        currentContent = [];
        
        // Remove header from content if it's part of the text
        const remaining = part.substring(foundHeader.length).trim();
        if (remaining) {
          currentContent.push(remaining);
        }
      } else {
        // Add to current content
        currentContent.push(part);
      }
    }
    
    // Process last section
    if (currentSection) {
      html += this.formatSectionContent(currentSection, currentContent);
    } else if (currentContent.length > 0) {
      // No section header found, treat as paragraph
      html += '<p>' + this.escapeHtml(currentContent.join(' ')) + '</p>';
    }
    
    return html || text.replace(/\n/g, '<br>');
  }
  
  private formatSectionContent(sectionTitle: string, content: string[]): string {
    if (!content.length) return '';
    
    let html = `<h3 class="overview-section-title">${sectionTitle}</h3>`;
    
    const fullText = content.join(' ').trim();
    
    // Program Overview should be paragraphs, all other sections should be bulleted lists
    if (sectionTitle === 'Program Overview') {
      // Format as paragraph(s)
      const paragraphs = fullText.split(/\n\n+/).filter(p => p.trim());
      if (paragraphs.length > 0) {
        for (const para of paragraphs) {
          html += `<p>${this.escapeHtml(para.trim())}</p>`;
        }
      } else {
        html += `<p>${this.escapeHtml(fullText)}</p>`;
      }
    } else {
      // All other sections: format as bulleted list
      // Split by sentence endings or by patterns that indicate list items
      // Try splitting by periods followed by space and capital letter, or by common list patterns
      let items = fullText.split(/(?<=[.!?])\s+(?=[A-Z])/).filter(item => item.trim());
      
      // If splitting by sentences didn't work well, try splitting by double spaces or other patterns
      if (items.length <= 1) {
        // Try splitting by common patterns like "  " (double space) or by line breaks
        items = fullText.split(/\s{2,}/).filter(item => item.trim());
      }
      
      // If still only one item, try to split by capital letters at start of sentences
      if (items.length <= 1) {
        items = fullText.split(/(?<=[.!?])\s+(?=[A-Z][a-z])/).filter(item => item.trim());
      }
      
      // Ensure we have items to display
      if (items.length === 0) {
        items = [fullText];
      }
      
      html += '<ul class="overview-list">';
      for (const item of items) {
        const cleanItem = item.trim().replace(/^[•\-\*]\s*/, ''); // Remove any existing bullet markers
        if (cleanItem) {
          html += `<li>${this.escapeHtml(cleanItem)}</li>`;
        }
      }
      html += '</ul>';
    }
    
    return html;
  }
  
  private escapeHtml(text: string): string {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

