import { ApplicationConfig } from '@angular/core';
import { provideRouter, withViewTransitions } from '@angular/router';
import { provideHttpClient } from '@angular/common/http';
import { provideImgixLoader } from '@angular/common';

import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideRouter(
      routes,
      withViewTransitions()
    ),
    provideHttpClient(),
    provideImgixLoader('https://images.pexels.com/'),
  ]
};