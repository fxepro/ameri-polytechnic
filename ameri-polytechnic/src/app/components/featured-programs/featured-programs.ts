import { ChangeDetectionStrategy, Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-featured-programs',
  templateUrl: './featured-programs.html',
  styleUrls: ['./featured-programs.css'],
  imports: [RouterLink],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class FeaturedProgramsComponent {} 
