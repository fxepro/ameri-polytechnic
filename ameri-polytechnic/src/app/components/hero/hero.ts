import { ChangeDetectionStrategy, Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-hero',
  templateUrl: './hero.html',
  styleUrls: ['./hero.css'],
  imports: [RouterLink],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class HeroComponent {
  showPlaceholder = true;
  imageLoaded = false;

  onImageError(event: Event) {
    this.showPlaceholder = true;
    this.imageLoaded = false;
  }

  onImageLoad(event: Event) {
    this.imageLoaded = true;
    this.showPlaceholder = false;
  }
}