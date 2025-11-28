import { ChangeDetectionStrategy, Component, signal, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, RouterLink, ReactiveFormsModule],
  templateUrl: './register.html',
  styleUrls: ['./register.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class RegisterComponent {
  private fb = inject(FormBuilder);
  private authService = inject(AuthService);
  private router = inject(Router);

  registerForm: FormGroup;
  loading = signal(false);
  error = signal<string | null>(null);
  success = signal(false);
  verificationUrl = signal<string | null>(null);

  constructor() {
    this.registerForm = this.fb.group({
      first_name: ['', [Validators.required, Validators.minLength(2)]],
      last_name: ['', [Validators.required, Validators.minLength(2)]],
      email: ['', [Validators.required, Validators.email]],
      phone: [''],
      password: ['', [Validators.required, Validators.minLength(8)]],
      password_confirmation: ['', [Validators.required]],
      agree_terms: [false, [Validators.requiredTrue]]
    }, { validators: this.passwordMatchValidator });
  }

  passwordMatchValidator(form: FormGroup) {
    const password = form.get('password');
    const passwordConfirmation = form.get('password_confirmation');
    
    if (password && passwordConfirmation && password.value !== passwordConfirmation.value) {
      passwordConfirmation.setErrors({ passwordMismatch: true });
      return { passwordMismatch: true };
    }
    return null;
  }

  onSubmit(): void {
    if (this.registerForm.invalid) {
      this.markFormGroupTouched(this.registerForm);
      return;
    }

    this.loading.set(true);
    this.error.set(null);

    const formData = this.registerForm.value;
    
    this.authService.register({
      first_name: formData.first_name,
      last_name: formData.last_name,
      email: formData.email,
      phone: formData.phone || undefined,
      password: formData.password,
      password_confirmation: formData.password_confirmation
    }).subscribe({
      next: (response) => {
        this.success.set(true);
        this.loading.set(false);
        
        // Store verification URL if provided (for local testing)
        if (response.verification_url) {
          this.verificationUrl.set(response.verification_url);
        }
        
        // Redirect to verify email page or show success message
        // Don't redirect if we have a verification URL (for local testing)
        if (!response.verification_url) {
          setTimeout(() => {
            this.router.navigate(['/login'], { 
              queryParams: { 
                message: 'Registration successful! Please check your email to verify your account.' 
              } 
            });
          }, 2000);
        }
      },
      error: (err) => {
        this.loading.set(false);
        if (err.error?.messages) {
          const messages = err.error.messages;
          const firstError = Object.values(messages)[0];
          this.error.set(Array.isArray(firstError) ? firstError[0] : firstError);
        } else {
          this.error.set(err.error?.error || 'Registration failed. Please try again.');
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
    const field = this.registerForm.get(fieldName);
    if (field && field.touched && field.errors) {
      if (field.errors['required']) {
        return `${fieldName.replace('_', ' ')} is required`;
      }
      if (field.errors['email']) {
        return 'Please enter a valid email address';
      }
      if (field.errors['minlength']) {
        return `${fieldName.replace('_', ' ')} must be at least ${field.errors['minlength'].requiredLength} characters`;
      }
      if (field.errors['passwordMismatch']) {
        return 'Passwords do not match';
      }
    }
    return null;
  }
}

