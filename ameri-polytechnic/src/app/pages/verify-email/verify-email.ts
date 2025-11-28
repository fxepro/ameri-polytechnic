import { ChangeDetectionStrategy, Component, OnInit, signal, inject, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-verify-email',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './verify-email.html',
  styleUrls: ['./verify-email.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class VerifyEmailComponent implements OnInit {
  private route = inject(ActivatedRoute);
  private router = inject(Router);
  private http = inject(HttpClient);
  private cdr = inject(ChangeDetectorRef);

  loading = signal(true);
  success = signal(false);
  error = signal<string | null>(null);
  private apiUrl = environment.apiUrl;

  ngOnInit(): void {
    const token = this.route.snapshot.queryParamMap.get('token');
    if (token) {
      this.verifyEmail(token);
    } else {
      this.error.set('No verification token provided');
      this.loading.set(false);
      this.cdr.markForCheck();
    }
  }

  verifyEmail(token: string): void {
    console.log('Verifying email with token:', token.substring(0, 10) + '...');
    console.log('API URL:', this.apiUrl);
    
    this.http.post(`${this.apiUrl}/verify-email`, { token }).subscribe({
      next: (response) => {
        console.log('Verification successful:', response);
        this.success.set(true);
        this.loading.set(false);
        this.cdr.markForCheck();
        setTimeout(() => {
          this.router.navigate(['/login']);
        }, 3000);
      },
      error: (err: HttpErrorResponse) => {
        console.error('Verification error:', err);
        console.error('Error status:', err.status);
        console.error('Error message:', err.message);
        console.error('Error details:', err.error);
        
        let errorMessage = 'Verification failed. The link may have expired.';
        
        if (err.status === 0) {
          errorMessage = 'Cannot connect to server. Please check your internet connection or try again later.';
        } else if (err.error?.error) {
          errorMessage = err.error.error;
        } else if (err.error?.message) {
          errorMessage = err.error.message;
        } else if (err.message) {
          errorMessage = err.message;
        }
        
        this.error.set(errorMessage);
        this.loading.set(false);
        this.cdr.markForCheck();
      }
    });
  }
}

