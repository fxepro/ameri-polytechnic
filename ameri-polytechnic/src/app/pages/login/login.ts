import { ChangeDetectionStrategy, Component, signal, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink, ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, RouterLink, ReactiveFormsModule],
  templateUrl: './login.html',
  styleUrls: ['./login.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class LoginComponent {
  private fb = inject(FormBuilder);
  private authService = inject(AuthService);
  private router = inject(Router);
  private route = inject(ActivatedRoute);

  loginForm: FormGroup;
  loading = signal(false);
  error = signal<string | null>(null);
  message = signal<string | null>(null);

  constructor() {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required]]
    });

    // Check for message from query params (e.g., after registration)
    this.route.queryParams.subscribe(params => {
      if (params['message']) {
        this.message.set(params['message']);
      }
    });
  }

  onSubmit(): void {
    if (this.loginForm.invalid) {
      this.markFormGroupTouched(this.loginForm);
      return;
    }

    this.loading.set(true);
    this.error.set(null);
    this.message.set(null);

    const { email, password } = this.loginForm.value;
    
    this.authService.login(email, password).subscribe({
      next: (response) => {
        this.loading.set(false);
        if (response.user.email_verified) {
          // Redirect to apply page or home
          this.router.navigate(['/apply']);
        } else {
          // Email not verified
          this.error.set('Please verify your email address before logging in. Check your inbox for the verification link.');
        }
      },
      error: (err) => {
        this.loading.set(false);
        if (err.error?.messages) {
          const messages = err.error.messages;
          const firstError = Object.values(messages)[0];
          this.error.set(Array.isArray(firstError) ? firstError[0] : firstError);
        } else {
          this.error.set(err.error?.error || 'Invalid email or password. Please try again.');
        }
      }
    });
  }

  private markFormGroupTouched(formGroup: FormGroup): void {
    Object.keys(formGroup.controls).forEach(key => {
      const control = formGroup.get(key);
      control?.markAsTouched();
    });
  }

  getFieldError(fieldName: string): string | null {
    const field = this.loginForm.get(fieldName);
    if (field && field.touched && field.errors) {
      if (field.errors['required']) {
        return `${fieldName} is required`;
      }
      if (field.errors['email']) {
        return 'Please enter a valid email address';
      }
    }
    return null;
  }
}

