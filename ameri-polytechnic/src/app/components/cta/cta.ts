
import { ChangeDetectionStrategy, Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-cta',
  templateUrl: './cta.html',
  styleUrls: ['./cta.css'],
  imports: [RouterLink],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class CtaComponent {}
