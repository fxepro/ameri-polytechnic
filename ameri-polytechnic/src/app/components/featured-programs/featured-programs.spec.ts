import { ComponentFixture, TestBed } from '@angular/core/testing';

import { FeaturedPrograms } from './featured-programs';

describe('FeaturedPrograms', () => {
  let component: FeaturedPrograms;
  let fixture: ComponentFixture<FeaturedPrograms>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [FeaturedPrograms]
    })
    .compileComponents();

    fixture = TestBed.createComponent(FeaturedPrograms);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
