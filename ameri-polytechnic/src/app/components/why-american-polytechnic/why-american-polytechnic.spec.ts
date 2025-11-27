import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WhyAmericanPolytechnic } from './why-american-polytechnic';

describe('WhyAmericanPolytechnic', () => {
  let component: WhyAmericanPolytechnic;
  let fixture: ComponentFixture<WhyAmericanPolytechnic>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [WhyAmericanPolytechnic]
    })
    .compileComponents();

    fixture = TestBed.createComponent(WhyAmericanPolytechnic);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
