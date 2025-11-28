import { Injectable, inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, tap } from 'rxjs';
import { environment } from '../../environments/environment';

export interface User {
  id: number;
  email: string;
  first_name?: string;
  last_name?: string;
  email_verified: boolean;
}

export interface AuthResponse {
  message: string;
  user: User;
  token?: string;
  verification_url?: string;
  email_sent?: boolean;
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private http = inject(HttpClient);
  private router = inject(Router);
  private apiUrl = environment.apiUrl;

  // Auth state
  currentUser = signal<User | null>(null);
  token = signal<string | null>(null);

  constructor() {
    // Load token from localStorage on init
    const savedToken = localStorage.getItem('auth_token');
    const savedUser = localStorage.getItem('auth_user');
    if (savedToken && savedUser) {
      this.token.set(savedToken);
      this.currentUser.set(JSON.parse(savedUser));
    }
  }

  register(data: {
    first_name: string;
    last_name: string;
    email: string;
    phone?: string;
    password: string;
    password_confirmation: string;
  }): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/register`, data).pipe(
      tap(response => {
        // Don't set token on registration - user needs to verify email first
        if (response.user) {
          this.currentUser.set(response.user);
        }
      })
    );
  }

  login(email: string, password: string): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.apiUrl}/login`, { email, password }).pipe(
      tap(response => {
        if (response.token && response.user) {
          this.setAuth(response.token, response.user);
        }
      })
    );
  }

  logout(): void {
    const token = this.token();
    if (token) {
      this.http.post(`${this.apiUrl}/logout`, {}).subscribe();
    }
    this.clearAuth();
    this.router.navigate(['/login']);
  }

  verifyEmail(token: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/verify-email`, { token });
  }

  resendVerification(email: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/resend-verification`, { email });
  }

  isAuthenticated(): boolean {
    return !!this.token() && !!this.currentUser();
  }

  isEmailVerified(): boolean {
    return this.currentUser()?.email_verified ?? false;
  }

  canApply(): boolean {
    return this.isAuthenticated() && this.isEmailVerified();
  }

  private setAuth(token: string, user: User): void {
    this.token.set(token);
    this.currentUser.set(user);
    localStorage.setItem('auth_token', token);
    localStorage.setItem('auth_user', JSON.stringify(user));
  }

  private clearAuth(): void {
    this.token.set(null);
    this.currentUser.set(null);
    localStorage.removeItem('auth_token');
    localStorage.removeItem('auth_user');
  }

  getAuthHeaders(): { [key: string]: string } {
    const token = this.token();
    return token ? { Authorization: `Bearer ${token}` } : {};
  }
}

