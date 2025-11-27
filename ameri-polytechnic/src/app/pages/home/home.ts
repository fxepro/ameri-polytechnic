
import { ChangeDetectionStrategy, Component } from '@angular/core';
import { CtaComponent } from '../../components/cta/cta';
import { FeaturedProgramsComponent } from '../../components/featured-programs/featured-programs';
import { HeroComponent } from '../../components/hero/hero';
import { WhyAmericanPolytechnicComponent } from '../../components/why-american-polytechnic/why-american-polytechnic';

@Component({
  selector: 'app-home',
  template: `
    <app-hero />
    <app-why-american-polytechnic />
    <app-featured-programs />
    <app-cta />
  `,
  imports: [
    HeroComponent,
    WhyAmericanPolytechnicComponent,
    FeaturedProgramsComponent,
    CtaComponent,
  ],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class HomeComponent {}
