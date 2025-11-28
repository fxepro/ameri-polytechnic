import { inject } from '@angular/core';
import { Router, CanActivateFn } from '@angular/router';
import { AuthService } from '../services/auth.service';

export const authGuard: CanActivateFn = (route, state) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  if (!authService.isAuthenticated()) {
    router.navigate(['/login'], { queryParams: { returnUrl: state.url } });
    return false;
  }

  if (!authService.isEmailVerified()) {
    router.navigate(['/login'], { 
      queryParams: { 
        returnUrl: state.url,
        message: 'Please verify your email address before applying'
      } 
    });
    return false;
  }

  return true;
};

