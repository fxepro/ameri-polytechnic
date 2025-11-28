
import { ChangeDetectionStrategy, Component, OnInit, inject } from '@angular/core';
import { RouterOutlet, Router, NavigationEnd } from '@angular/router';
import { HeaderComponent } from './components/header/header';
import { FooterComponent } from './components/footer/footer';
import { filter } from 'rxjs/operators';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, HeaderComponent, FooterComponent],
  template: `
    <app-header />
    <main>
      <router-outlet />
    </main>
    <app-footer />
  `,
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class AppComponent implements OnInit {
  private router = inject(Router);

  ngOnInit(): void {
    // Scroll to top on route change
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        window.scrollTo(0, 0);
      });
  }
}
