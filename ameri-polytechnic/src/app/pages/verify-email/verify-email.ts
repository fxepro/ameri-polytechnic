import { ChangeDetectionStrategy, Component, OnInit, signal, inject } from '@angular/core';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
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
    }
  }

  verifyEmail(token: string): void {
    this.http.post(`${this.apiUrl}/verify-email`, { token }).subscribe({
      next: () => {
        this.success.set(true);
        this.loading.set(false);
        setTimeout(() => {
          this.router.navigate(['/login']);
        }, 3000);
      },
      error: (err) => {
        this.error.set(err.error?.error || 'Verification failed. The link may have expired.');
        this.loading.set(false);
      }
    });
  }
}

